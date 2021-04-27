<?php
/**
 * @package  	MrkvUAMmarketplaces
 */

namespace Inc\Base;

class Deactivate
{
	public static function deactivate() {

		// Unschedule CRON-task
		$timestamp = wp_next_scheduled( 'mrkvuamp_update_xml_hook' );
		wp_unschedule_event( $timestamp, 'mrkvuamp_update_xml_hook' );

		flush_rewrite_rules();
	}
}
