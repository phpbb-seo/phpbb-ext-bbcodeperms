<?php
/**
 * phpBB-SEO BBCode Permissions Extension
 *
 * @copyright (c) 2026 Meis@M <info@phpbb-seo.ir>
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = [];
}

$lang = array_merge($lang, [
	'ACP_BBCODEPERMS_TITLE'              => 'BBCode Permissions',
	'ACP_BBCODEPERMS_SETTINGS'           => 'BBCode Permission Settings',
	'ACP_BBCODEPERMS_SETTINGS_EXPLAIN'   => 'Configure which BBCodes should be restricted from specific user groups and customize the access denied message.',
	'ACP_BBCODEPERMS_GENERAL_CONFIG'     => 'General Settings',
	'ACP_BBCODEPERMS_ENABLE'             => 'Enable Extension',
	'ACP_BBCODEPERMS_ENABLE_EXPLAIN'     => 'Quickly turn the BBCode permission restrictions on or off.',
	'ACP_BBCODEPERMS_EXEMPT_ADMINS'      => 'Exempt Administrators & Founders',
	'ACP_BBCODEPERMS_EXEMPT_ADMINS_EXPLAIN' => 'If enabled, administrators and board founders will always see all BBCodes regardless of group restrictions.',
	'ACP_BBCODEPERMS_CHECK_TYPE'         => 'Group Membership Check Mode',
	'ACP_BBCODEPERMS_CHECK_TYPE_EXPLAIN' => 'Choose how the user\'s group membership is evaluated.',
	'ACP_BBCODEPERMS_CHECK_TYPE_ANY'     => 'Restricted if user is in ANY selected group',
	'ACP_BBCODEPERMS_CHECK_TYPE_PRIMARY' => 'Restricted ONLY if user\'s Primary/Default group is selected',
	'ACP_BBCODEPERMS_SELECT_BBCODES'     => 'Restricted BBCodes',
	'ACP_BBCODEPERMS_SELECT_BBCODES_EXPLAIN' => 'Select the BBCodes that should be hidden from the restricted groups.',
	'ACP_BBCODEPERMS_CORE_BBCODES'       => 'Default phpBB BBCodes',
	'ACP_BBCODEPERMS_CUSTOM_BBCODES'     => 'Custom Forum BBCodes',
	'ACP_BBCODEPERMS_EXTRA_TAGS'         => 'Additional BBCode Tags',
	'ACP_BBCODEPERMS_EXTRA_TAGS_EXPLAIN' => 'Optional: Comma-separated list of any additional BBCode tags to restrict (e.g. spoiler, video).',
	'ACP_BBCODEPERMS_SELECT_GROUPS'      => 'Restricted User Groups',
	'ACP_BBCODEPERMS_SELECT_GROUPS_EXPLAIN' => 'Select which user groups cannot view the selected BBCodes.',
	'ACP_BBCODEPERMS_SYSTEM_GROUP'       => 'System Group',
	'ACP_BBCODEPERMS_SYSTEM_GROUPS'      => 'System / Built-in Groups',
	'ACP_BBCODEPERMS_CUSTOM_GROUPS'      => 'Custom User Groups',
	'ACP_BBCODEPERMS_FILTER_PLACEHOLDER' => 'Type to filter list...',
	'ACP_BBCODEPERMS_MULTI_SELECT_HINT'  => 'Tip: Hold Ctrl (or Cmd on Mac) to select multiple items.',
	'ACP_BBCODEPERMS_SELECTED_COUNT'     => 'Selected: %d item(s)',
	'ACP_BBCODEPERMS_MESSAGE_CONFIG'     => 'Access Denied Message',
	'ACP_BBCODEPERMS_MESSAGE_CONFIG_EXPLAIN' => 'Customize the notice message displayed in place of the restricted BBCode.',
	'ACP_BBCODEPERMS_MESSAGE'            => 'Message Content (HTML Allowed)',
	'ACP_BBCODEPERMS_MESSAGE_VARS_EXPLAIN' => 'You can use the dynamic placeholders below in your message. They will be substituted automatically.',
	'ACP_BBCODEPERMS_QUICK_INSERT'       => 'Quick Insert',
	'ACP_BBCODEPERMS_PLACEHOLDERS'       => 'Available Placeholders',
	'ACP_BBCODEPERMS_PLACEHOLDERS_EXPLAIN' => 'Click any tag badge or "+ Insert" button to append it into your message at the cursor position.',
	'ACP_BBCODEPERMS_PLACEHOLDER_TAG'    => 'Placeholder Tag',
	'ACP_BBCODEPERMS_PLACEHOLDER_DESC'   => 'Description',
	'ACP_BBCODEPERMS_PLACEHOLDER_EXAMPLE' => 'Example Replacement',
	'ACP_BBCODEPERMS_PLACEHOLDER_ACTION' => 'Action',
	'ACP_BBCODEPERMS_INSERT'             => 'Insert',
	'ACP_BBCODEPERMS_CLICK_TO_INSERT'    => 'Click to insert into message',
	'ACP_BBCODEPERMS_VAR_LOGIN'          => 'Direct link to login page (ucp.php?mode=login)',
	'ACP_BBCODEPERMS_VAR_REGISTER'       => 'Direct link to register page (ucp.php?mode=register)',
	'ACP_BBCODEPERMS_VAR_USERNAME'       => 'Username of current viewing user (or "Guest" for anonymous)',
	'ACP_BBCODEPERMS_VAR_SITENAME'       => 'Board site name as configured in board settings',
	'ACP_BBCODEPERMS_VAR_TAG'            => 'Name of the restricted BBCode tag (e.g. code, quote)',
	'ACP_BBCODEPERMS_LIVE_PREVIEW'       => 'Live Message Preview',
	'ACP_BBCODEPERMS_SELECT_ALL'         => 'Select All',
	'ACP_BBCODEPERMS_DESELECT_ALL'       => 'Deselect All',
	'ACP_BBCODEPERMS_SETTING_SAVED'      => 'BBCode permission settings have been successfully updated.',
	'ACP_BBCODEPERMS_NO_HELPLINE'        => 'No description available',

	'ACP_BBCODEPERMS_TAG_CODE_DESC'       => 'Code block tag [code]',
	'ACP_BBCODEPERMS_TAG_QUOTE_DESC'      => 'Quote block tag [quote]',
	'ACP_BBCODEPERMS_TAG_URL_DESC'        => 'Hyperlink tag [url]',
	'ACP_BBCODEPERMS_TAG_IMG_DESC'        => 'Image tag [img]',
	'ACP_BBCODEPERMS_TAG_ATTACHMENT_DESC' => 'Inline attachment tag [attachment]',
	'ACP_BBCODEPERMS_TAG_B_DESC'          => 'Bold text tag [b]',
	'ACP_BBCODEPERMS_TAG_I_DESC'          => 'Italic text tag [i]',
	'ACP_BBCODEPERMS_TAG_U_DESC'          => 'Underline text tag [u]',
	'ACP_BBCODEPERMS_TAG_LIST_DESC'       => 'List tag [list]',
	'ACP_BBCODEPERMS_TAG_SIZE_DESC'       => 'Font size tag [size]',
	'ACP_BBCODEPERMS_TAG_COLOR_DESC'      => 'Font color tag [color]',
	'ACP_BBCODEPERMS_TAG_EMAIL_DESC'      => 'Email link tag [email]',
]);
