<?php
/**
 * @package 	MrkvUAMmarketplaces
 */

namespace Inc\Core\WCShop;

use \Inc\Core\WCShopController;
use \Inc\Core\WCShop\WCShopOffer;

class WCShopOfferVariable extends WCShopOffer {

    public static $variation;

    public static function set_variable_offer($id, $offers)
    {
        parent::$_product = \wc_get_product( $id ); // Get variation product object
        $variation_permalink = parent::$_product->get_permalink();

        $variations_ids = parent::$_product->get_children();

        // Variations loop
        foreach ( $variations_ids as $variation_id ) {
            self::$variation = wc_get_product( $variation_id ); // Get variation object

            $variation_attrs = self::$variation->get_attributes(); // Get variation attributes

            $offer = $offers->addChild( 'offer' ); // XML tag <offer>
                $offer->addAttribute( 'group_id', $id );
                $offer->addAttribute( 'id', $variation_id );

                $is_available = self::is_available( $id, $offer );
                $offer->addAttribute( 'available', $is_available ); // TODO

                $url = $offer->addChild( 'url',
                    esc_html( $variation_permalink . '?' .
                        self::get_variation_params( self::$variation, $variation_attrs ) ) ); // XML tag <url>
        }
    }

    public static function is_available($id, $offers)
    {
        $is_manage_stock = self::$variation->get_manage_stock();
        $stock_status = self::$variation->get_stock_status();
        $stock_qty = self::$variation->get_stock_quantity();

        if ( ! $is_manage_stock ) { // If manage_stock == false
            if ( 'instock' == $stock_status ) {
                return 'true';
            }
            return 'false';
        }
        if ( $stock_qty > 0) { // If manage_stock == true
            return 'true';
        }
        return 'false';
    }

    // Create GET parameters for product variation URLs
    public static function get_variation_params($variation, $variation_attrs)
    {
        $attr_keys = array_keys( $variation_attrs );

        for ( $i = 0; $i < \sizeof( $attr_keys );  $i++) {
            $key = $attr_keys[$i];
            $value = $variation_attrs[$key];
            $params[$i] = $key . '=' . $value;
        }

        return join( "&", $params );
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
