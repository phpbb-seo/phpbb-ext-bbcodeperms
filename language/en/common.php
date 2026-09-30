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
	'BBCODEPERMS_PROTECTED_CONTENT' => 'Protected Content',
	'BBCODEPERMS_GUEST'             => 'Guest',
	'BBCODEPERMS_QUOTE_HIDDEN'      => '[Hidden content: You do not have permission to view this section]',
]);
