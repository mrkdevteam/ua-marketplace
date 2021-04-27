<?php

/**
 * @package 	MrkvUAMmarketplaces
 */

namespace Inc\Base;

use \Inc\Base\BaseController;
use \Inc\Core\WCShop\WCShopCollation;
use \Inc\Core\XMLController;

class WPCRONHandler extends BaseController
{

    public function register()
    {
        $activation_options_name = get_option( 'mrkv_ua_marketplaces');
        foreach ( $activation_options_name as $key => $value ) {
            $marketplace = $this->activations[$key];
            $xml = new XMLController( strtolower( $marketplace ) );
            $xml_fileurl = '/uploads/mrkvuamp' . strtolower( $marketplace ) . '.xml';
            if ( file_exists( $xml->xml_filepath ) ) {
                add_action( 'admin_head', array( $this, 'activate_xml_update' ) );
                add_action( 'mrkvuamp_update_xml_twicedaily', array( $this, 'do_this_twicedaily' ) );
            }
        }
    }

    public function activate_xml_update()
    {
        if( ! wp_next_scheduled( 'mrkvuamp_update_xml_twicedaily' ) ) {
            wp_schedule_event( time(), 'twicedaily', 'mrkvuamp_update_xml_twicedaily' );
        }
    }

    public function do_this_twicedaily()
    {
        // Create WooCommerce internet-shop Object
        $mrkv_uamrkpl_shop = new WCShopCollation('shop');
        $mrkv_uamrkpl_shop_arr = (array) $mrkv_uamrkpl_shop;

        // Create XML-price for marketplace
        $converter = new \Inc\Core\XMLController( 'rozetka' );
        $xml_filename = '/uploads/mrkvuamp' . $converter->marketplace . '.xml';
        $xml = $converter->array2xml( $mrkv_uamrkpl_shop_arr );
        exit;
    }

}
