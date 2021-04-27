<?php

/**
 * Trigger this file on Plugin uninstall
 *
 * @package  morkva-ua-marketplace-woo-plugin
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}

// Delete all added by the plugin WorlPress options with `mrkv_uamrkpl_` prefix
mrkv_uamrkpl_delete_wp_options_prefixed( 'mrkv_uamrkpl_' );

function mrkv_uamrkpl_delete_wp_options_prefixed( $prefix ) {
    global $wpdb;
    $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '{$prefix}%'" );
}

// Delete the main plugin option
delete_option( 'mrkv_ua_marketplaces' );
