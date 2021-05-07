<?php
/**
 * @package 	MrkvUAMmarketplaces
 */

namespace Inc\Core\WCShop;

use \Inc\Core\WCShopController;

class WCShopOffer extends WCShopController {

    public static $_product;

    // Set <offer> xml-tag
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

    // Get product Title for <name> xml-tag
    public static function get_product_title()
    {
        $product_title = self::$_product->get_title();
        return $product_title;
    }

    // Get product description for <description> xml-tag
    public static function get_product_description()
    {
        $description = self::$_product->get_description();
        if ( ! empty ($description ) ) {
            return $description;
        }
        return self::get_product_short_description();
    }

    // Get product short description for $this->get_product_description()
    public static function get_product_short_description()
    {
        $short_description = self::$_product->get_short_description();
        return $short_description;
    }

    // Get product brand for <vendor> xml-tag
    public static function get_product_vendor($id)
    {
        $global_vendor = ( null !== \get_option( 'mrkv_uamrkpl_rozetka_global_vendor' ) )
            ? \get_option( 'mrkv_uamrkpl_rozetka_global_vendor' ) : '';
        $custom_vendor = ( null !== \get_option( 'mrkv_uamrkpl_rozetka_custom_vendor' ) )
            ? \get_option( 'mrkv_uamrkpl_rozetka_custom_vendor' ) : '';
        if ( isset( $global_vendor ) ) { // If Global Vendor is exists
            if ( ! empty( $global_vendor ) ) {
                return $global_vendor;
            }
        }

        if ( empty( $global_vendor ) ) {  // If Global Vendor is not exists
            // If `Perfect Brands for WooCommerce` plugin is active
            if ( 'vendor_pwb_brand' == $custom_vendor ) {
                $vendor_taxonomy = 'pwb-brand';
                $pwb_brand_obj = get_the_terms( $id, $vendor_taxonomy );

                if ( ! empty( $pwb_brand_obj[0]->name ) ) {
                    return $pwb_brand_obj[0]->name;
                }
                return ' ';
            }

            // If brands set by product attributes
            if ( 'vendor_by_attributes' == $custom_vendor ) {
                $vendor_taxonomy = \get_option('mrkv_uamrkpl_rozetka_vendor_by_attributes');
                $vendor_name = self::$_product->get_attribute( 'pa_' . $vendor_taxonomy );

                if ( ! empty( $vendor_name ) ) {
                    return $vendor_name;
                }
                return ' ';
            }

            // If brands set by other product metadata
            if ( 'vendor_all_possibilities' == $custom_vendor ) {
                $vendor_taxonomy = \get_option('mrkv_uamrkpl_rozetka_vendor_all_possibilities');
                $vendor_name = ( null !== get_post_meta( $id, $vendor_taxonomy ) )
                    ? get_post_meta( $id, $vendor_taxonomy ) : ' ';

                if ( ! empty( $vendor_name[0] ) ) {
                    return $vendor_name[0];
                }
                return ' ';
            }
        }
    }

    // Get product image URLs for <picture> xml-tag
    public static function get_product_image_urls()
    {
        $image_urls = array();
        $image_id  = self::$_product->get_image_id(); // Get main product image id
        $image_urls[] = wp_get_attachment_image_url( $image_id, 'full' );

        // If exists only main product image
        if ( empty( self::$_product->get_gallery_image_ids() ) && ! empty( $image_urls ) ) {
            return $image_urls;
        }

        // If exists product image gallery
        $gallery_image_ids = self::$_product->get_gallery_image_ids(); // Get product gallery ids
        $gallery_image_urls = array();

        foreach ( $gallery_image_ids as $gallery_image_id ) {
            $gallery_image_urls[] = wp_get_attachment_image_url( $gallery_image_id, 'full' );
        }
        return \array_merge( $image_urls, $gallery_image_urls );
    }

    // Get marketplace category id for <categoryId> xml-tag
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

    // Get currency value (UAH, USD, EUR, RUR) attribute for <currencyId> xml-tag
    public static function get_wc_currency_id()
    {
        $wc_shop = new WCShopController();
        return $wc_shop->currencies[0];
    }

    // Get 'available' attribute for <offer> xml-tag
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
