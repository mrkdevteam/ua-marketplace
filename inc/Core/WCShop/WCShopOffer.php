<?php
/**
 * @package 	MrkvUAMmarketplaces
 */

namespace Inc\Core\WCShop;

use \Inc\Core\WCShopController;
// use \Inc\Core\XMLController;

class WCShopOffer extends WCShopController {

    public static function set_variable_offer($id, $offers)
    {
        $variation_product = wc_get_product( $id ); // Get variation product object
        $variation_product_permalink = $variation_product->get_permalink();

        $variations_ids = $variation_product->get_children();

        // Variations loop
        foreach ( $variations_ids as $variation_id ) {
            $variation = wc_get_product( $variation_id ); // Get variation object

            $variation_attrs = $variation->get_attributes(); // Get variation attribute

            $offer = $offers->addChild( 'offer' ); // XML tag <offer>
                $offer->addAttribute( 'group_id', $id );
                $offer->addAttribute( 'id', $variation_id );
                $offer->addAttribute('available', 'true'); // TODO
                $url = $offer->addChild( 'url',
                    esc_html( $variation_product_permalink . '?' .
                        WCShopOffer::get_variation_params( $variation, $variation_attrs ) ) ); // XML tag <url>
        }
    }

    public static function set_simple_offer($id, $offers)
    {
        $offer = $offers->addChild( 'offer' ); // XML tag <offer>
        $offer->addAttribute('id', $id);
        $offer->addAttribute('available', 'true'); // TODO
        $url = $offer->addChild( 'url', \get_permalink( $id ) );
    }

    public static function set_offer($id, $offers)
    {
        // Get product object from collation list
        $_product = \wc_get_product( $id );
        $product_type = $_product->get_type();

        if ( 'simple' == $product_type ) {
            self::set_simple_offer( $id, $offers );
        }

        if ( 'variable' == $product_type ) {
            self::set_variable_offer( $id, $offers );
        }
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

    public static function get_product_prices( $id, $product_type )
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
