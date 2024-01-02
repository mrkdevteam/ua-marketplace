<?php

/**
 * Trigger this file on Plugin uninstall
 *
 * @package  morkva-ua-marketplace-woo-plugin
 */

 namespace Inc\Base;

 use \Inc\Base\BaseController;
 use \Inc\Core\XMLController;

 require_once ('inc/Base/BaseController.php');
 require_once ('inc/Core/XMLController.php');

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}

// Delete all added by the plugin WordPress options with `mrkv_uamrkpl_` prefix
function mrkv_uamrkpl_delete_wp_options_prefixed( $prefix ) {
    global $wpdb;
    $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '{$prefix}%'" );
}
mrkv_uamrkpl_delete_wp_options_prefixed( 'mrkv_uamrkpl_' );

// Delete the main plugin option
delete_option( 'mrkv_ua_marketplaces' );

// Remove xml-files and plugin uploads directory
$rozetkaXMLController = new XMLController( 'rozetka' );
$rozetka_xml_file_path = $rozetkaXMLController->xml_rozetka_filepath; // path to xml file
$rozetka_plugin_uploads_dir_path = $rozetkaXMLController->plugin_uploads_dir_path; // path to plugin uploads directory

// List of filenames located inside plugin uploads directory
$files = glob( $rozetka_plugin_uploads_dir_path . '/*' );

// Delete all the files from the list
foreach( $files as $file ) {
    if ( is_file( $file ) ) {
        \chmod( $file, 0777 );
        \unlink( $file );
    }
}

// Delete plugin uploads directory if it is empty
if ( is_readable( $rozetka_plugin_uploads_dir_path ) && count( scandir( $rozetka_plugin_uploads_dir_path ) ) == 2 ) {
    rmdir( $rozetka_plugin_uploads_dir_path );
}
