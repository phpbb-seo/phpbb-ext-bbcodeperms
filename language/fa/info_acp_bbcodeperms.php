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
	'ACP_BBCODEPERMS_TITLE'              => 'دسترسی BBCodeها',
	'ACP_BBCODEPERMS_SETTINGS'           => 'تنظیمات دسترسی BBCode',
	'ACP_BBCODEPERMS_SETTINGS_EXPLAIN'   => 'در این بخش می‌توانید تعیین کنید چه BBCodeهایی از چه گروه‌های کاربری مخفی شوند و چه پیغامی به جای آن‌ها نشان داده شود.',
	'ACP_BBCODEPERMS_GENERAL_CONFIG'     => 'تنظیمات عمومی',
	'ACP_BBCODEPERMS_ENABLE'             => 'فعال‌سازی افزونه',
	'ACP_BBCODEPERMS_ENABLE_EXPLAIN'     => 'فعال یا غیرفعال‌سازی اعمال محدودیت‌های BBCode در سراسر تالار.',
	'ACP_BBCODEPERMS_EXEMPT_ADMINS'      => 'مستثنی کردن مدیران کل و موسسان',
	'ACP_BBCODEPERMS_EXEMPT_ADMINS_EXPLAIN' => 'در صورت فعال بودن، مدیران کل و موسسان تالار همیشه تمام BBCodeها را بدون محدودیت مشاهده خواهند کرد.',
	'ACP_BBCODEPERMS_CHECK_TYPE'         => 'نحوه بررسی عضویت گروه کاربر',
	'ACP_BBCODEPERMS_CHECK_TYPE_EXPLAIN' => 'نحوه محاسبه عضویت کاربر در گروه‌های محدود شده را مشخص کنید.',
	'ACP_BBCODEPERMS_CHECK_TYPE_ANY'     => 'محدود شود اگر کاربر عضو «حداقل یکی» از گروه‌های انتخابی باشد',
	'ACP_BBCODEPERMS_CHECK_TYPE_PRIMARY' => 'تنها در صورتی محدود شود که «گروه اصلی / پیش‌فرض» کاربر انتخاب شده باشد',
	'ACP_BBCODEPERMS_SELECT_BBCODES'     => 'انتخاب BBCodeهای مشمول محدودیت',
	'ACP_BBCODEPERMS_SELECT_BBCODES_EXPLAIN' => 'BBCodeهایی که قصد دارید محتوای آن‌ها از گروه‌های انتخابی مخفی شود را علامت بزنید.',
	'ACP_BBCODEPERMS_CORE_BBCODES'       => 'BBCodeهای پیش‌فرض هسته phpBB',
	'ACP_BBCODEPERMS_CUSTOM_BBCODES'     => 'BBCodeهای سفارشی تالار',
	'ACP_BBCODEPERMS_EXTRA_TAGS'         => 'تگ‌های BBCode اضافه (دستی)',
	'ACP_BBCODEPERMS_EXTRA_TAGS_EXPLAIN' => 'اختیاری: نام تگ‌های اضافی را با کاما جدا کنید (مانند: spoiler, video).',
	'ACP_BBCODEPERMS_SELECT_GROUPS'      => 'انتخاب گروه‌های کاربری محروم',
	'ACP_BBCODEPERMS_SELECT_GROUPS_EXPLAIN' => 'گروه‌های کاربری که نباید محتوای BBCodeهای انتخابی را مشاهده کنند علامت بزنید.',
	'ACP_BBCODEPERMS_SYSTEM_GROUP'       => 'گروه سیستمی',
	'ACP_BBCODEPERMS_SYSTEM_GROUPS'      => 'گروه‌های سیستمی / پیش‌فرض',
	'ACP_BBCODEPERMS_CUSTOM_GROUPS'      => 'گروه‌های کاربری سفارشی',
	'ACP_BBCODEPERMS_FILTER_PLACEHOLDER' => 'برای فیلتر کردن سریع تایپ کنید...',
	'ACP_BBCODEPERMS_MULTI_SELECT_HINT'  => 'راهنما: کلید Ctrl (یا Cmd در مک) را برای انتخاب چندگانه نگه دارید.',
	'ACP_BBCODEPERMS_SELECTED_COUNT'     => 'انتخاب شده: %d مورد',
	'ACP_BBCODEPERMS_MESSAGE_CONFIG'     => 'تنظیمات پیام عدم دسترسی',
	'ACP_BBCODEPERMS_MESSAGE_CONFIG_EXPLAIN' => 'متنی که به جای محتوای مخفی شده به کاربران نمایش داده می‌شود را تنظیم کنید.',
	'ACP_BBCODEPERMS_MESSAGE'            => 'متن پیغام (پشتیبانی از HTML)',
	'ACP_BBCODEPERMS_MESSAGE_VARS_EXPLAIN' => 'می‌توانید از متغیرهای پویای زیر در متن پیام استفاده کنید تا هنگام نمایش به صورت خودکار جایگزین شوند.',
	'ACP_BBCODEPERMS_QUICK_INSERT'       => 'افزودن سریع',
	'ACP_BBCODEPERMS_PLACEHOLDERS'       => 'متغیرهای در دسترس',
	'ACP_BBCODEPERMS_PLACEHOLDERS_EXPLAIN' => 'با کلیک روی هر متغیر یا دکمه «+ افزودن»، آن متغیر در محل نشانگر در کادر متن پیام درج می‌شود.',
	'ACP_BBCODEPERMS_PLACEHOLDER_TAG'    => 'نام متغیر',
	'ACP_BBCODEPERMS_PLACEHOLDER_DESC'   => 'توضیحات و عملکرد',
	'ACP_BBCODEPERMS_PLACEHOLDER_EXAMPLE' => 'نمونه جایگزینی',
	'ACP_BBCODEPERMS_PLACEHOLDER_ACTION' => 'عملیات',
	'ACP_BBCODEPERMS_INSERT'             => 'افزودن',
	'ACP_BBCODEPERMS_CLICK_TO_INSERT'    => 'کلیک برای درج در متن',
	'ACP_BBCODEPERMS_VAR_LOGIN'          => 'لینک مستقیم صفحه ورود به حساب کاربری (ucp.php?mode=login)',
	'ACP_BBCODEPERMS_VAR_REGISTER'       => 'لینک مستقیم صفحه ثبت‌نام کاربر جدید (ucp.php?mode=register)',
	'ACP_BBCODEPERMS_VAR_USERNAME'       => 'نام کاربری بیننده مطلب (یا کلمه «مهمان» برای افراد وارد نشده)',
	'ACP_BBCODEPERMS_VAR_SITENAME'       => 'نام تالار گفتگو تنظیم شده در تنظیمات سراسری انجمن',
	'ACP_BBCODEPERMS_VAR_TAG'            => 'نام تگ BBCode مسدود شده (مانند: code یا quote)',
	'ACP_BBCODEPERMS_LIVE_PREVIEW'       => 'پیش‌نمایش زنده پیام',
	'ACP_BBCODEPERMS_SELECT_ALL'         => 'انتخاب همه',
	'ACP_BBCODEPERMS_DESELECT_ALL'       => 'لغو انتخاب همه',
	'ACP_BBCODEPERMS_SETTING_SAVED'      => 'تنظیمات دسترسی BBCode با موفقیت ذخیره شد.',
	'ACP_BBCODEPERMS_NO_HELPLINE'        => 'بدون توضیحات',

	'ACP_BBCODEPERMS_TAG_CODE_DESC'       => 'تگ بلوک کد [code]',
	'ACP_BBCODEPERMS_TAG_QUOTE_DESC'      => 'تگ نقل‌قول [quote]',
	'ACP_BBCODEPERMS_TAG_URL_DESC'        => 'تگ پیوند و لینک [url]',
	'ACP_BBCODEPERMS_TAG_IMG_DESC'        => 'تگ تصویر [img]',
	'ACP_BBCODEPERMS_TAG_ATTACHMENT_DESC' => 'تگ فایل پیوست [attachment]',
	'ACP_BBCODEPERMS_TAG_B_DESC'          => 'تگ متن درشت [b]',
	'ACP_BBCODEPERMS_TAG_I_DESC'          => 'تگ متن ایتالیک [i]',
	'ACP_BBCODEPERMS_TAG_U_DESC'          => 'تگ متن خط زیرین [u]',
	'ACP_BBCODEPERMS_TAG_LIST_DESC'       => 'تگ لیست [list]',
	'ACP_BBCODEPERMS_TAG_SIZE_DESC'       => 'تگ اندازه قلم [size]',
	'ACP_BBCODEPERMS_TAG_COLOR_DESC'      => 'تگ رنگ متن [color]',
	'ACP_BBCODEPERMS_TAG_EMAIL_DESC'      => 'تگ آدرس ایمیل [email]',
]);
