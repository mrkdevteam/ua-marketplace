<?php
/**
 * @package 	MrkvUAMmarketplaces
 */

namespace Inc\Core\WCShop;

use \Inc\Core\WCShopController;
use \Inc\Core\WCShop\WCShopOffer;

class WCShopOfferSimple extends WCShopOffer {

    public static function set_simple_offer($id, $offers)
    {
        $offer = $offers->addChild( 'offer' ); // XML tag <offer>
            $offer->addAttribute('id', $id);
            $is_available = self::is_available( $id, $offer );
            $offer->addAttribute( 'available', $is_available );

            $url = $offer->addChild( 'url', \get_permalink( $id ) ); // XML tag <url>
    }

    public static function is_available($id, $offers)
    {
        $is_manage_stock = parent::$_product->get_manage_stock();
        $is_in_stock = parent::$_product->is_in_stock();
        $stock_qty = parent::$_product->get_stock_quantity();

        if ( ! $is_manage_stock ) { // If manage_stock == false
            if ( $is_in_stock ) {
                return 'true';
            }
            return 'false';
        }
        if ( $stock_qty > 0) { // If manage_stock == true
            return 'true';
        }
        return 'false';
    }

    public static function get_product_prices( $id, $product_type ) // TODO
    {
        $_product = wc_get_product( $id );

        if ( 'simple' == $product_type ) {
            $priceval = intval( $_product->get_regular_price() );
        }

        if ( 'variable' == $product_type ) {
\error_log('$_product');\error_log(print_r($_product,1));
            $variations = $_product->get_available_variations();
\error_log('$variations');\error_log(print_r($variations,1));
        }

// \error_log('$_product');\error_log(print_r($_product,1));
        // $priceval = intval( $_product->get_regular_price() );
        // $pricesale = intval( $product->get_price() );
        // $products = \wc_get_products( $args );
//         $wc_product_types = \wc_get_product_types();
// \error_log('$wc_product_types');\error_log(print_r($wc_product_types,1));
//         foreach ( $products as $product ) {
//             $offer_ids[] = $product->get_id();
//             $wc_product_type = $product->get_type( $offer_ids );
// \error_log('$wc_product_type');\error_log(print_r($wc_product_type,1));
//         }
        return $priceval;
    }

    public static function get_product_pictures() // TODO
    {

    }

}
