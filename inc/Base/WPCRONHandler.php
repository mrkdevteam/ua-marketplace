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
        if ( empty( get_option( 'mrkv_ua_marketplaces' ) ) ) {
            return;
        }
        $activation_options_name = get_option( 'mrkv_ua_marketplaces');

        foreach ( $activation_options_name as $key => $value ) {
            $marketplace = $this->activations[$key];
            $xml = new XMLController( strtolower( $marketplace ) );
            
            // Create xml-file name for each marketplace
            $xml_fileurl = '/uploads/mrkvuamp' . strtolower( $marketplace ) . '.xml';

            // Activate CRON-task for generation xml-прайс twice daily
            if ( file_exists( $xml->xml_filepath ) ) {
                add_action( 'admin_head', array( $this, 'activate_xml_update' ) );
                add_action( 'mrkvuamp_update_xml_hook', array( $this, 'update_xml_exec' ) );
            }
        }
    }

    public function activate_xml_update()
    {
        if( ! wp_next_scheduled( 'mrkvuamp_update_xml_hook' ) ) {
            wp_schedule_event( time(), 'twicedaily', 'mrkvuamp_update_xml_hook' );
        }
    }

    public function update_xml_exec()
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
