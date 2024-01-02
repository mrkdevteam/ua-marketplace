<?php
/**
 * @package  	MrkvUAMmarketplaces
 */

namespace Inc\Base;

class Deactivate
{
	public static function deactivate() {

		// Unschedule CRON-task for Rozetka
		$timestamp = wp_next_scheduled( 'mrkvuamp_update_xml_hook' );
		wp_unschedule_event( $timestamp, 'mrkvuamp_update_xml_hook' );

		// Unschedule CRON-task for PromUA
		$timestamp = wp_next_scheduled( 'mrkvuamp_update_xml_hook_promua' );
		wp_unschedule_event( $timestamp, 'mrkvuamp_update_xml_hook_promua' );

		flush_rewrite_rules();
	}
}
