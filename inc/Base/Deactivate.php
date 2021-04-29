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

		// Delete all added by the plugin WordPress options with `mrkv_uamrkpl_` prefix -- ТИМЧАСОВО!!!
		function mrkv_uamrkpl_delete_wp_options_prefixed( $prefix ) {
		    global $wpdb;
		    $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '{$prefix}%'" );
		}
		mrkv_uamrkpl_delete_wp_options_prefixed( 'mrkv_uamrkpl_' );

		// Delete the main plugin option
		delete_option( 'mrkv_ua_marketplaces' );

		// Remove xml-file
		$file_pointer = WP_CONTENT_DIR . '/uploads/mrkvuamprozetka.xml';
		\unlink( $file_pointer ); // -- ТИМЧАСОВО!!!

		flush_rewrite_rules();
	}
}
