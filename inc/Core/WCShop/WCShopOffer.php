<?php
/**
 * @package 	MrkvUAMmarketplaces
 */

namespace Inc\Core\WCShop;

use \Inc\Core\WCShopController;

class WCShopOffer extends WCShopController {

    public static $_product;

    public static function set_offer($id, $offers)
    {
        // Get product object from collation list
        self::$_product = \wc_get_product( $id );

        $product_type = self::$_product->get_type();
        if ( 'simple' == $product_type ) {
            WCShopOfferSimple::set_simple_offer( $id, $offers );
        }

        if ( 'variable' == $product_type ) {
            WCShopOfferVariable::set_variable_offer( $id, $offers );
        }
    }

}
