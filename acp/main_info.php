<?php
/**
 * phpBB-SEO BBCode Permissions Extension
 *
 * @copyright (c) 2026 Meis@M <info@phpbb-seo.ir>
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

namespace phpbbseo\bbcodeperms\acp;

class main_info
{
	public function module()
	{
		return [
			'filename' => '\phpbbseo\bbcodeperms\acp\main_module',
			'title'    => 'ACP_BBCODEPERMS_TITLE',
			'modes'    => [
				'settings' => [
					'title' => 'ACP_BBCODEPERMS_SETTINGS',
					'auth'  => 'ext_phpbbseo/bbcodeperms && acl_a_board',
					'cat'   => ['ACP_BBCODEPERMS_TITLE'],
				],
			],
		];
	}
}
