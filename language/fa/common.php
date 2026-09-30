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
	'BBCODEPERMS_PROTECTED_CONTENT' => 'محتوای محافظت‌شده',
	'BBCODEPERMS_GUEST'             => 'مهمان',
	'BBCODEPERMS_QUOTE_HIDDEN'      => '[محتوای مخفی: شما مجوز مشاهده این بخش را ندارید]',
]);
