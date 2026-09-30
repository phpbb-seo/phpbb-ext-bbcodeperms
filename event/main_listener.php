<?php
/**
 * phpBB-SEO BBCode Permissions Extension
 *
 * @copyright (c) 2026 Meis@M <info@phpbb-seo.ir>
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

namespace phpbbseo\bbcodeperms\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use phpbb\config\config;
use phpbb\config\db_text;
use phpbb\user;
use phpbb\auth\auth;
use phpbb\db\driver\driver_interface;

class main_listener implements EventSubscriberInterface
{
	/** @var config */
	protected $config;

	/** @var db_text */
	protected $config_text;

	/** @var user */
	protected $user;

	/** @var auth */
	protected $auth;

	/** @var driver_interface */
	protected $db;

	/** @var string */
	protected $phpbb_root_path;

	/** @var string */
	protected $php_ext;

	/** @var bool|null */
	protected $is_user_restricted = null;

	/** @var array|null */
	protected $restricted_tags = null;

	/** @var string|null */
	protected $cached_message_box = null;

	/**
	 * Constructor
	 *
	 * @param config           $config
	 * @param db_text          $config_text
	 * @param user             $user
	 * @param auth             $auth
	 * @param driver_interface $db
	 * @param string           $phpbb_root_path
	 * @param string           $php_ext
	 */
	public function __construct(
		config $config,
		db_text $config_text,
		user $user,
		auth $auth,
		driver_interface $db,
		$phpbb_root_path,
		$php_ext
	) {
		$this->config          = $config;
		$this->config_text     = $config_text;
		$this->user            = $user;
		$this->auth            = $auth;
		$this->db              = $db;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext         = $php_ext;
	}

	/**
	 * Subscribed events
	 *
	 * @return array
	 */
	public static function getSubscribedEvents()
	{
		return [
			'core.text_formatter_s9e_render_before' => 'on_render_before',
			'core.text_formatter_s9e_render_after'  => 'on_render_after',
			'core.posting_modify_message_text'      => 'on_posting_modify_message_text',
		];
	}

	/**
	 * Check whether the current viewing user is restricted from viewing protected BBCodes
	 *
	 * @return bool
	 */
	protected function is_restricted()
	{
		if ($this->is_user_restricted !== null)
		{
			return $this->is_user_restricted;
		}

		// Check if extension is enabled
		if (empty($this->config['bbcodeperms_enabled']))
		{
			return $this->is_user_restricted = false;
		}

		// Exempt Administrators and Board Founders if configured
		$exempt_admins = !empty($this->config['bbcodeperms_exempt_admins']);
		if ($exempt_admins)
		{
			if ($this->auth->acl_get('a_') || (!empty($this->user->data['user_type']) && $this->user->data['user_type'] == USER_FOUNDER))
			{
				return $this->is_user_restricted = false;
			}
		}

		// Get restricted groups from config
		$restricted_groups_str = $this->config['bbcodeperms_groups'] ?? '';
		if (trim($restricted_groups_str) === '')
		{
			return $this->is_user_restricted = false;
		}
		$restricted_groups = array_filter(array_map('intval', explode(',', $restricted_groups_str)));
		if (empty($restricted_groups))
		{
			return $this->is_user_restricted = false;
		}

		$user_id = (int) ($this->user->data['user_id'] ?? ANONYMOUS);
		$check_type = $this->config['bbcodeperms_check_type'] ?? 'any';

		// Guest user check
		if ($user_id === ANONYMOUS)
		{
			$guest_group_id = (int) ($this->user->data['group_id'] ?? 1);
			return $this->is_user_restricted = (in_array($guest_group_id, $restricted_groups, true) || in_array(1, $restricted_groups, true));
		}

		// If configured to check only Primary/Default Group
		if ($check_type === 'primary')
		{
			$primary_group_id = (int) ($this->user->data['group_id'] ?? 0);
			return $this->is_user_restricted = in_array($primary_group_id, $restricted_groups, true);
		}

		// Default 'any': Check all groups user belongs to
		$user_groups = [(int) $this->user->data['group_id']];
		$sql = 'SELECT group_id FROM ' . USER_GROUP_TABLE . '
			WHERE user_id = ' . (int) $user_id . '
				AND user_pending = 0';
		$result = $this->db->sql_query($sql);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$user_groups[] = (int) $row['group_id'];
		}
		$this->db->sql_freeresult($result);
		$user_groups = array_unique($user_groups);

		$intersect = array_intersect($user_groups, $restricted_groups);
		return $this->is_user_restricted = !empty($intersect);
	}

	/**
	 * Get the list of restricted tags in uppercase
	 *
	 * @return array
	 */
	protected function get_restricted_tags()
	{
		if ($this->restricted_tags !== null)
		{
			return $this->restricted_tags;
		}

		$tags_str = $this->config['bbcodeperms_tags'] ?? '';
		if (trim($tags_str) === '')
		{
			return $this->restricted_tags = [];
		}

		$tags = array_filter(array_map('trim', explode(',', strtolower($tags_str))));
		$upper_tags = [];
		foreach ($tags as $t)
		{
			if ($t !== '')
			{
				$upper_tags[] = strtoupper($t);
			}
		}

		return $this->restricted_tags = array_unique($upper_tags);
	}

	/**
	 * Modify parsed s9e XML before rendering to strip restricted BBCodes
	 *
	 * @param \phpbb\event\data $event
	 */
	public function on_render_before($event)
	{
		if (!$this->is_restricted())
		{
			return;
		}

		$tags = $this->get_restricted_tags();
		if (empty($tags))
		{
			return;
		}

		$xml = $event['xml'];
		if (empty($xml) || strpos($xml, '<') === false)
		{
			return;
		}

		// Quick string check to avoid unnecessary DOM parsing if none of the tags exist
		$has_any = false;
		foreach ($tags as $tag)
		{
			if (stripos($xml, '<' . $tag) !== false)
			{
				$has_any = true;
				break;
			}
		}

		if (!$has_any)
		{
			return;
		}

		$dom = new \DOMDocument('1.0', 'UTF-8');
		$loaded = @$dom->loadXML($xml, LIBXML_NOERROR | LIBXML_NOWARNING);
		if (!$loaded || !$dom->documentElement)
		{
			return;
		}

		$modified = false;
		foreach ($tags as $tag)
		{
			$nodes = [];
			// Collect uppercase tags (standard s9e format)
			foreach ($dom->getElementsByTagName($tag) as $node)
			{
				$nodes[] = $node;
			}
			// Also collect lowercase if any custom tag variant
			foreach ($dom->getElementsByTagName(strtolower($tag)) as $node)
			{
				if (!in_array($node, $nodes, true))
				{
					$nodes[] = $node;
				}
			}

			if (!empty($nodes))
			{
				$modified = true;
				foreach ($nodes as $node)
				{
					$token = '___BBCODEPERMS_BLOCKED_' . $tag . '___';
					$textNode = $dom->createTextNode($token);
					if ($node->parentNode)
					{
						$node->parentNode->replaceChild($textNode, $node);
					}
				}
			}
		}

		if ($modified)
		{
			$new_xml = $dom->saveXML($dom->documentElement);
			if ($new_xml !== false)
			{
				$event['xml'] = $new_xml;
			}
		}
	}

	/**
	 * Inject formatted alert box after s9e rendering
	 *
	 * @param \phpbb\event\data $event
	 */
	public function on_render_after($event)
	{
		if (!$this->is_restricted())
		{
			return;
		}

		$html = $event['html'];
		if (empty($html) || strpos($html, '___BBCODEPERMS_BLOCKED_') === false)
		{
			return;
		}

		$message_template = $this->get_rendered_message();

		$html = preg_replace_callback('/___BBCODEPERMS_BLOCKED_([A-Z0-9_\-]+)___/', function ($matches) use ($message_template) {
			$tag = strtolower($matches[1]);
			return str_replace('{TAG}', htmlspecialchars($tag, ENT_QUOTES, 'UTF-8'), $message_template);
		}, $html);

		$event['html'] = $html;
	}

	/**
	 * Build base message box with user context and URL placeholders
	 *
	 * @return string
	 */
	protected function get_rendered_message()
	{
		if ($this->cached_message_box !== null)
		{
			return $this->cached_message_box;
		}

		$this->user->add_lang_ext('phpbbseo/bbcodeperms', 'common');

		$message = $this->config_text->get('bbcodeperms_message');
		if (empty($message))
		{
			$message = '<div class="bbcodeperms-alert"><div class="bbcodeperms-icon"><i class="icon fa fa-lock fa-fw" aria-hidden="true"></i></div><div class="bbcodeperms-content"><span class="bbcodeperms-title">' . $this->user->lang('BBCODEPERMS_PROTECTED_CONTENT') . '</span><p class="bbcodeperms-desc">' . $this->user->lang('BBCODEPERMS_QUOTE_HIDDEN') . '</p></div></div>';
		}
		$message = htmlspecialchars_decode($message, ENT_QUOTES);

		$login_url    = append_sid($this->phpbb_root_path . 'ucp.' . $this->php_ext, 'mode=login');
		$register_url = append_sid($this->phpbb_root_path . 'ucp.' . $this->php_ext, 'mode=register');
		$user_id      = (int) ($this->user->data['user_id'] ?? ANONYMOUS);
		$username     = ($user_id !== ANONYMOUS) ? ($this->user->data['username'] ?? '') : ($this->user->lang['BBCODEPERMS_GUEST'] ?? 'Guest');
		$sitename     = $this->config['sitename'] ?? '';

		$message = str_replace(
			['{LOGIN_URL}', '{REGISTER_URL}', '{USERNAME}', '{SITENAME}'],
			[$login_url, $register_url, htmlspecialchars($username, ENT_QUOTES, 'UTF-8'), htmlspecialchars($sitename, ENT_QUOTES, 'UTF-8')],
			$message
		);

		return $this->cached_message_box = $message;
	}

	/**
	 * Prevent leaking hidden BBCode content when quoting posts
	 *
	 * @param \phpbb\event\data $event
	 */
	public function on_posting_modify_message_text($event)
	{
		$mode = $event['mode'] ?? '';
		if ($mode !== 'quote')
		{
			return;
		}

		if (!$this->is_restricted())
		{
			return;
		}

		// If user is quoting their own post, allow them to view/edit their own content
		$poster_id = (int) ($event['post_data']['poster_id'] ?? 0);
		if ($poster_id > 0 && $poster_id === (int) ($this->user->data['user_id'] ?? 0))
		{
			return;
		}

		$tags = $this->get_restricted_tags();
		if (empty($tags))
		{
			return;
		}

		$message_parser = $event['message_parser'];
		if (!isset($message_parser->message) || empty($message_parser->message))
		{
			return;
		}

		$this->user->add_lang_ext('phpbbseo/bbcodeperms', 'common');
		$replacement = ' ' . ($this->user->lang['BBCODEPERMS_QUOTE_HIDDEN'] ?? '[Protected Content: Access Denied]') . ' ';

		$raw_message = $message_parser->message;
		foreach ($tags as $tag)
		{
			$tag_lower = strtolower($tag);
			$pattern = '#\[' . preg_quote($tag_lower, '#') . '(?:\=[^\]]*)?\](.*?)\[/' . preg_quote($tag_lower, '#') . '\]#is';
			while (preg_match($pattern, $raw_message))
			{
				$raw_message = preg_replace($pattern, $replacement, $raw_message);
			}
		}

		$message_parser->message = $raw_message;
	}
}
