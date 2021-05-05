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

    public static function get_marketplace_category_id()
    {
        if ( empty( get_option( 'mrkv_uamrkpl_collation_option' ) ) ) {
            return;
        }

        // Get all product categories
        $product_category_ids = self::$_product->get_category_ids();
        // Get wc-categories and marketplace-categories collation array
        $collation_option_ids = get_option( 'mrkv_uamrkpl_collation_option' );

        foreach ( $collation_option_ids as $key => $value ) {

            // Get first wc-category id
            $wc_cat_id = substr( $key , strpos( $key, 'mrkv-uamp-' ) + strlen( 'mrkv-uamp-' ) );

            if ( $value ) { // Is set marketplace-category?
                if ( \in_array( $wc_cat_id, $product_category_ids ) ) {
                    return $value;
                }
            }
        }
    }

    public static function get_wc_currency_id()
    {
        $wc_shop = new WCShopController();
        return $wc_shop->currencies[0];
    }

    public static function is_available($id, $offers, $_product)
    {
        $is_manage_stock = $_product->get_manage_stock();
        $stock_status = $_product->get_stock_status();
        $stock_qty = $_product->get_stock_quantity();

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

}
