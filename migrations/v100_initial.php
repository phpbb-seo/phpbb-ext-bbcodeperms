<?php
/**
 * phpBB-SEO BBCode Permissions Extension
 *
 * @copyright (c) 2026 Meis@M <info@phpbb-seo.ir>
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

namespace phpbbseo\bbcodeperms\migrations;

use phpbb\db\migration\migration;

class v100_initial extends migration
{
	public static function depends_on()
	{
		return ['\phpbb\db\migration\data\v330\v330'];
	}
	public function update_data()
	{
		$default_message = '<div class="bbcodeperms-alert"><div class="bbcodeperms-icon"><i class="icon fa fa-lock fa-fw" aria-hidden="true"></i></div><div class="bbcodeperms-content"><strong class="bbcodeperms-title">Protected Content</strong><p class="bbcodeperms-desc">You do not have permission to view this content. Please sign in or upgrade your account to access it.</p><div class="bbcodeperms-actions"><a href="{LOGIN_URL}" class="button button-secondary">Login</a> <a href="{REGISTER_URL}" class="button button-secondary">Register</a></div></div></div>';

		return [
			['config.add', ['bbcodeperms_enabled', 1]],
			['config.add', ['bbcodeperms_tags', 'code']],
			['config.add', ['bbcodeperms_groups', '1']], // default: Guests (group_id = 1)
			['config.add', ['bbcodeperms_exempt_admins', 1]],
			['config.add', ['bbcodeperms_check_type', 'any']],
			['config_text.add', ['bbcodeperms_message', $default_message]],

			['module.add', [
				'acp',
				'ACP_CAT_DOT_MODS',
				'ACP_BBCODEPERMS_TITLE',
			]],
			['module.add', [
				'acp',
				'ACP_BBCODEPERMS_TITLE',
				[
					'module_basename' => '\phpbbseo\bbcodeperms\acp\main_module',
					'modes'           => ['settings'],
				],
			]],
		];
	}
}
