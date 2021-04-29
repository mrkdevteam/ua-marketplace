<?php
/**
 * @package 	MrkvUAMmarketplaces
 */

namespace Inc\Base;

class Activate
{
	public static function activate() {
		flush_rewrite_rules();

		if ( get_option( 'mrkv_ua_marketplaces' ) ) {
			return;
		}

		$default = array();

		update_option( 'mrkv_ua_marketplaces', $default );
		update_option( 'mrkv_uamrkpl_collation_option', $default );
	}

}
