<?php

/**
 * @package 	MrkvUAMmarketplaces
 */

namespace Inc\Base;

use \Inc\Base\BaseController;
use \Inc\Core\XMLController;

class Enqueue extends BaseController
{

	public function register()
	{
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ), 99 );
		add_filter( 'script_loader_tag', array( $this, 'add_type_attribute' ), 10, 3);
	}

	public function enqueue()
	{
		wp_enqueue_style( 'morkvauamarketplacestyle', $this->plugin_url . 'assets/mrkvmpstyle.min.css', array(), $this->plugin_ver['ver'] );

		 // Deactivate logging for JQMIGRATE
		wp_add_inline_script( 'jquery-migrate', 'jQuery.migrateMute = true;', 'before' );

		wp_enqueue_script( 'morkvauamarketplacescript', $this->plugin_url . 'assets/mrkvmpscript.min.js', array('jquery'), $this->plugin_ver['ver'], true );

		$xml = new XMLController( 'rozetka' );
		$plugin_uploads_dir_url = $xml->plugin_uploads_dir_url;
		$xml_rozetka_fileurl = $xml->plugin_uploads_dir_url . $xml->plugin_uploads_rozetka_xmlname; // Get Rozetka xml-file URL
		$xml_promua_fileurl = $xml->plugin_uploads_dir_url . $xml->plugin_uploads_promua_xmlname; 	// Get PromUA xml-file URL
		$promua_xml_name = $xml->plugin_uploads_promua_xmlname; 									// Get PromUA xml-file name
		$site_total_product_qty = $xml->site_total_product_qty; 									// Get site total product quantity
		$background_xml_promua_chkbx = get_option( 'mrkv_uamrkpl_promua_background_proc_xml_chk', false ); 

		$ajaxHandler = new AjaxHandler();
		$rozetka_xml_created_event = 0;
		$rozetka_xml_created_event = $ajaxHandler->rozetka_collation_script_time; // Get PHP execution time value

		wp_localize_script( 				// Add php variables for using in js-script:
		    'morkvauamarketplacescript', 	// - the handle of the 'morkvauamarketplacescript' script we have enqueued above
		    'mrkvuamp_script_vars', 		// - object name to access our PHP variables from in js-script
		    array( 							// Register an array of variables we would like to use in js-script
		    	'plugin_uploads_dir_url' => $plugin_uploads_dir_url, // Dirname in uploads for this plugin
		        'rozetka_xml_path' => $xml_rozetka_fileurl,
		        'promua_xml_path' => $xml_promua_fileurl,
		        'promua_xml_name' => $promua_xml_name, // Filename of xml file for PromUA
		        'background_xml_promua_chkbx' => $background_xml_promua_chkbx, // 'Фоновий режим xml' checkbox status
		        'site_total_product_qty' => $site_total_product_qty,
				'rozetka_xml_created_event' => $rozetka_xml_created_event,
		        'nonce' => wp_create_nonce('mrkv_uamrkpl_collation_form_nonce')
		    )
		);

		// Load JavaScript library SweetAlert2 to create beautifull popup boxes
		wp_register_script( 'Sweetalert2', 'https://cdn.jsdelivr.net/npm/sweetalert2@10', null, null, true );
		wp_enqueue_script('Sweetalert2');

	}

	function add_type_attribute($tag, $handle, $src) {
	    // if not your script, do nothing and return original $tag
	    if ( 'morkvauamarketplacescript' !== $handle ) {
	        return $tag;
	    }
	    // change the script tag by adding type="module" and return it.
	    $tag = '<script type="module" src="' . esc_url( $src ) . '"></script>';
	    return $tag;
	}

}
