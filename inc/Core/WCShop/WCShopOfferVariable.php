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

                $is_available = parent::is_available( $id, $offer, self::$variation );
                $offer->addAttribute( 'available', $is_available );

                $url = $offer->addChild( 'url',
                    esc_html( $variation_permalink . '?' .
                        self::get_variation_params( self::$variation, $variation_attrs ) ) ); // XML tag <url>

                $price = self::$variation->get_regular_price();
                $price = $offer->addChild( 'price', $price ); // XML tag <price>

                $currencyId = $offer->addChild( 'currencyId', parent::get_wc_currency_id() ); // XML tag <currencyId>

                $currencyId = $offer->addChild( 'categoryId', parent::get_marketplace_category_id() ); // XML tag <categoryId>
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

    public static function get_product_prices( $id, $product_type ) // TODO
    {
        // \error_log('self::$variation');\error_log(print_r(self::$variation,1));
    }

    public static function get_product_pictures() // TODO
    {

    }

}
