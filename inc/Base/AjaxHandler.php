<?php

/**
 * @package 	MrkvUAMmarketplaces
 */

namespace Inc\Base;

use \Inc\Base\BaseController;
use \Inc\Core\WCShop\WCShopCollation;
use \Inc\Core\XMLController;

class AjaxHandler extends BaseController
{

    public function register()
    {

        add_action( 'wp_ajax_mrkvuamp_collation_action', array( $this, 'mrkvuamp_collation_action' ) );

    }

    public function mrkvuamp_collation_action() {
        if ( ! check_ajax_referer( 'mrkv_uamrkpl_collation_nonce' )){
        	wp_die();
        }

        if ( isset( $_REQUEST ) ) {
            $response = $_REQUEST;
        }

        // Get categories collations from mrkv_uamrkpl_collation Form on Rozetka tab
        if ( is_array( $response )  ) {
            foreach ( $response as $key => $value ) {
                if ( strpos( $key, 'mrkv-uamp-') !== false ) {
                    $cats_collation_arr[$key] = ! empty( $value ) ? sanitize_text_field( $value ) : '';
                }
            }
        }
        update_option( 'mrkv_uamrkpl_collation', $cats_collation_arr );

        // Create WooCommerce internet-shop Object
        $mrkv_uamrkpl_shop = new WCShopCollation('shop');
        $mrkv_uamrkpl_shop_arr = (array) $mrkv_uamrkpl_shop;

        // Create XML-price for marketplace
        $converter = new \Inc\Core\XMLController( 'rozetka' );
        $xml_filename = '/uploads/mrkvuamp' . $converter->marketplace . '.xml';
        $xml = $converter->array2xml( $mrkv_uamrkpl_shop_arr );

        wp_die();

    }

}
