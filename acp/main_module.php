<?php
/**
 * phpBB-SEO BBCode Permissions Extension
 *
 * @copyright (c) 2026 Meis@M <info@phpbb-seo.ir>
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

namespace phpbbseo\bbcodeperms\acp;

class main_module
{
	public $u_action;
	public $tpl_name;
	public $page_title;

	public function main($id, $mode)
	{
		global $config, $request, $template, $user, $db, $phpbb_container;

		$user->add_lang_ext('phpbbseo/bbcodeperms', 'common');
		$user->add_lang_ext('phpbbseo/bbcodeperms', 'info_acp_bbcodeperms');

		$this->tpl_name = 'acp_bbcodeperms';
		$this->page_title = $user->lang('ACP_BBCODEPERMS_TITLE');

		/** @var \phpbb\config\db_text $config_text */
		$config_text = $phpbb_container->get('config_text');

		add_form_key('acp_bbcodeperms_settings');

		if ($request->is_set_post('submit'))
		{
			if (!check_form_key('acp_bbcodeperms_settings'))
			{
				trigger_error($user->lang('FORM_INVALID'), E_USER_WARNING);
			}

			// Read selected tags from checkboxes
			$selected_tags = $request->variable('bbcodeperms_tags', [''], true);
			// Read extra manual tags from input
			$custom_tags_raw = $request->variable('bbcodeperms_custom_tags', '', true);
			if (!empty($custom_tags_raw))
			{
				$extra_tags = explode(',', $custom_tags_raw);
				foreach ($extra_tags as $t)
				{
					$clean_tag = strtolower(trim($t));
					if ($clean_tag !== '')
					{
						$selected_tags[] = $clean_tag;
					}
				}
			}

			// Clean, unique, and serialize tags
			$clean_tags = [];
			foreach ($selected_tags as $tag)
			{
				$tag = strtolower(trim($tag));
				if ($tag !== '' && preg_match('#^[a-z0-9_\-]+$#i', $tag))
				{
					$clean_tags[] = $tag;
				}
			}
			$clean_tags = array_values(array_unique($clean_tags));

			// Read selected groups
			$selected_groups = $request->variable('bbcodeperms_groups', [0]);
			$clean_groups = [];
			foreach ($selected_groups as $gid)
			{
				$gid = (int) $gid;
				if ($gid > 0)
				{
					$clean_groups[] = $gid;
				}
			}
			$clean_groups = array_values(array_unique($clean_groups));

			$enabled       = $request->variable('bbcodeperms_enabled', 0);
			$exempt_admins = $request->variable('bbcodeperms_exempt_admins', 1);
			$check_type    = $request->variable('bbcodeperms_check_type', 'any');
			$message = htmlspecialchars_decode($request->variable('bbcodeperms_message', '', true), ENT_QUOTES);

			$config->set('bbcodeperms_enabled', $enabled);
			$config->set('bbcodeperms_tags', implode(',', $clean_tags));
			$config->set('bbcodeperms_groups', implode(',', $clean_groups));
			$config->set('bbcodeperms_exempt_admins', $exempt_admins);
			$config->set('bbcodeperms_check_type', $check_type);

			$config_text->set('bbcodeperms_message', $message);

			trigger_error($user->lang('ACP_BBCODEPERMS_SETTING_SAVED') . \adm_back_link($this->u_action));
		}

		// Retrieve current settings
		$current_tags_str   = $config['bbcodeperms_tags'] ?? '';
		$current_groups_str = $config['bbcodeperms_groups'] ?? '';

		$current_tags   = array_filter(array_map('trim', explode(',', strtolower($current_tags_str))));
		$current_groups = array_filter(array_map('intval', explode(',', $current_groups_str)));

		$enabled       = (bool) ($config['bbcodeperms_enabled'] ?? 1);
		$exempt_admins = (bool) ($config['bbcodeperms_exempt_admins'] ?? 1);
		$check_type    = $config['bbcodeperms_check_type'] ?? 'any';
		$message       = htmlspecialchars_decode($config_text->get('bbcodeperms_message') ?: '', ENT_QUOTES);

		// Standard phpBB Core BBCodes
		$core_tags_def = [
			'code'       => ['name' => '[code]', 'desc' => $user->lang('ACP_BBCODEPERMS_TAG_CODE_DESC')],
			'quote'      => ['name' => '[quote]', 'desc' => $user->lang('ACP_BBCODEPERMS_TAG_QUOTE_DESC')],
			'url'        => ['name' => '[url]', 'desc' => $user->lang('ACP_BBCODEPERMS_TAG_URL_DESC')],
			'img'        => ['name' => '[img]', 'desc' => $user->lang('ACP_BBCODEPERMS_TAG_IMG_DESC')],
			'attachment' => ['name' => '[attachment]', 'desc' => $user->lang('ACP_BBCODEPERMS_TAG_ATTACHMENT_DESC')],
			'b'          => ['name' => '[b]', 'desc' => $user->lang('ACP_BBCODEPERMS_TAG_B_DESC')],
			'i'          => ['name' => '[i]', 'desc' => $user->lang('ACP_BBCODEPERMS_TAG_I_DESC')],
			'u'          => ['name' => '[u]', 'desc' => $user->lang('ACP_BBCODEPERMS_TAG_U_DESC')],
			'list'       => ['name' => '[list]', 'desc' => $user->lang('ACP_BBCODEPERMS_TAG_LIST_DESC')],
			'size'       => ['name' => '[size]', 'desc' => $user->lang('ACP_BBCODEPERMS_TAG_SIZE_DESC')],
			'color'      => ['name' => '[color]', 'desc' => $user->lang('ACP_BBCODEPERMS_TAG_COLOR_DESC')],
			'email'      => ['name' => '[email]', 'desc' => $user->lang('ACP_BBCODEPERMS_TAG_EMAIL_DESC')],
		];

		$known_tags = array_keys($core_tags_def);

		foreach ($core_tags_def as $tag => $data)
		{
			$is_sel = in_array($tag, $current_tags, true);
			$template->assign_block_vars('core_bbcodes', [
				'TAG'      => $tag,
				'NAME'     => $data['name'],
				'DESC'     => $data['desc'],
				'CHECKED'  => $is_sel,
				'SELECTED' => $is_sel,
			]);
		}

		// Query custom BBCodes from database
		$sql = 'SELECT bbcode_id, bbcode_tag, bbcode_helpline
			FROM ' . BBCODES_TABLE . '
			ORDER BY bbcode_tag ASC';
		$result = $db->sql_query($sql);
		$has_custom = false;
		while ($row = $db->sql_fetchrow($result))
		{
			$has_custom = true;
			$tag_lower = strtolower(trim($row['bbcode_tag']));
			$known_tags[] = $tag_lower;
			$is_sel = in_array($tag_lower, $current_tags, true);
			$template->assign_block_vars('custom_bbcodes', [
				'ID'       => (int) $row['bbcode_id'],
				'TAG'      => $tag_lower,
				'NAME'     => '[' . $row['bbcode_tag'] . ']',
				'HELPLINE' => $row['bbcode_helpline'] ?: $user->lang('ACP_BBCODEPERMS_NO_HELPLINE'),
				'CHECKED'  => $is_sel,
				'SELECTED' => $is_sel,
			]);
		}
		$db->sql_freeresult($result);

		// Determine any extra custom tags that are in $current_tags but not in core or DB
		$extra_tags = array_diff($current_tags, $known_tags);
		$custom_tags_val = implode(', ', $extra_tags);

		// Query User Groups
		$sql = 'SELECT group_id, group_name, group_type, group_colour
			FROM ' . GROUPS_TABLE . '
			ORDER BY group_type DESC, group_name ASC';
		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result))
		{
			$group_name = ($row['group_type'] == GROUP_SPECIAL) ? ($user->lang['G_' . $row['group_name']] ?? $row['group_name']) : $row['group_name'];
			$is_sel = in_array((int) $row['group_id'], $current_groups, true);
			$template->assign_block_vars('user_groups', [
				'ID'         => (int) $row['group_id'],
				'NAME'       => $group_name,
				'COLOUR'     => $row['group_colour'] ? '#' . $row['group_colour'] : '',
				'IS_SPECIAL' => ($row['group_type'] == GROUP_SPECIAL),
				'CHECKED'    => $is_sel,
				'SELECTED'   => $is_sel,
			]);
		}
		$db->sql_freeresult($result);

		$sitename = !empty($config['sitename']) ? $config['sitename'] : 'Forum Community';

		$placeholders = [
			[
				'TAG'     => '{REGISTER_URL}',
				'DESC'    => $user->lang('ACP_BBCODEPERMS_VAR_REGISTER'),
				'EXAMPLE' => './ucp.php?mode=register',
			],
			[
				'TAG'     => '{LOGIN_URL}',
				'DESC'    => $user->lang('ACP_BBCODEPERMS_VAR_LOGIN'),
				'EXAMPLE' => './ucp.php?mode=login',
			],
			[
				'TAG'     => '{USERNAME}',
				'DESC'    => $user->lang('ACP_BBCODEPERMS_VAR_USERNAME'),
				'EXAMPLE' => 'JohnDoe',
			],
			[
				'TAG'     => '{SITENAME}',
				'DESC'    => $user->lang('ACP_BBCODEPERMS_VAR_SITENAME'),
				'EXAMPLE' => $sitename,
			],
			[
				'TAG'     => '{TAG}',
				'DESC'    => $user->lang('ACP_BBCODEPERMS_VAR_TAG'),
				'EXAMPLE' => 'code',
			],
		];

		foreach ($placeholders as $ph)
		{
			$template->assign_block_vars('placeholders', $ph);
		}

		$preview_text = $message;
		$preview_text = str_replace(
			['{LOGIN_URL}', '{REGISTER_URL}', '{USERNAME}', '{SITENAME}', '{TAG}'],
			['#login', '#register', 'JohnDoe', $sitename, 'code'],
			$preview_text
		);

		$template->assign_vars([
			'U_ACTION'                  => $this->u_action,
			'BBCODEPERMS_ENABLED'       => $enabled,
			'BBCODEPERMS_EXEMPT_ADMINS' => $exempt_admins,
			'BBCODEPERMS_CHECK_TYPE'    => $check_type,
			'BBCODEPERMS_MESSAGE'       => $message,
			'BBCODEPERMS_PREVIEW_HTML'  => $preview_text,
			'BBCODEPERMS_PREVIEW_SITENAME' => addslashes($sitename),
			'BBCODEPERMS_CUSTOM_TAGS'   => $custom_tags_val,
			'BBCODE_SELECTED_COUNT'     => count($current_tags),
			'GROUP_SELECTED_COUNT'      => count($current_groups),
			'BBCODE_SELECTED_STR'       => sprintf($user->lang('ACP_BBCODEPERMS_SELECTED_COUNT'), count($current_tags)),
			'GROUP_SELECTED_STR'        => sprintf($user->lang('ACP_BBCODEPERMS_SELECTED_COUNT'), count($current_groups)),
			'S_HAS_CUSTOM_BBCODES'      => $has_custom,
		]);
	}
}
