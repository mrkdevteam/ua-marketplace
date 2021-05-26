<?php
/**
 * @package 	MrkvUAMmarketplaces
 */

namespace Inc\Core\WCShop;

use \Inc\Core\WCShopController;
use \Inc\Base\BaseController;

class WCShopOffer extends WCShopController {

    public $_product;

    public $activations = array();

    public function __construct()
    {
        $baseController = new BaseController();
        $this->activations = $baseController->activations;
    }

    // Set <offer> xml-tag
    public function set_offer($id, $offers)
    {
        $this->_product = \wc_get_product( $id ); // Get product object from collation list
        $wcShopOfferSimple = new WCShopOfferSimple();
        $wcShopOfferVariable = new WCShopOfferVariable();

        $product_type = $this->_product->get_type();
        if ( 'simple' == $product_type ) {
            $wcShopOfferSimple->set_simple_offer( $id, $offers );
        }

        if ( 'variable' == $product_type ) {
            $wcShopOfferVariable->set_variable_offer( $id, $offers );
        }
    }

    // Get product params (attributes) for <param> xml-tag
    public function get_product_attributes($id)
    {
        $param_labels = array();
        $param_values = array();
        $params = $this->_product->get_attributes();

        foreach ( $params as $key => $value ) {
            // Get only product (not variation) attributes
            if ( ! $value->get_variation() ) {
                $param_labels[] = wc_attribute_label( $key );
                $param_values[] = $this->_product->get_attribute( $key );
            }
        }
        return [ $param_labels, $param_values ];
    }

    // Get product Title for <name> xml-tag
    public function get_product_title($id)
    {
        $this->_product = \wc_get_product( $id ); // Get product object from collation list
        $product_title = $this->_product->get_title();

        // Title from '{Marketplace} Title' custom field
        foreach ( $this->activations as $activation  ) {
            $slug =  \strtolower( $activation );
            $mrktplc_title = get_post_meta( $id , "mrkvuamp_{$slug}_title", true);

            if ( isset( $mrktplc_title ) && ! empty( $mrktplc_title ) ) return $mrktplc_title;
        }
        return $product_title;
    }

    // Get product description for <description> xml-tag
    public function get_product_description()
    {
        $description = $this->_product->get_description();
        if ( ! empty ($description ) ) {
            return $description;
        }
        return $this->get_product_short_description();
    }

    // Get product short description for self::get_product_description()
    public function get_product_short_description()
    {
        $short_description = $this->_product->get_short_description();
        return $short_description;
    }

    // Get product brand for <vendor> xml-tag
    public function get_product_vendor($id)
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
                $vendor_name = $this->_product->get_attribute( 'pa_' . $vendor_taxonomy );

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
    public function get_product_image_urls($id)
    {
        $image_urls = array();
        $image_id  = $this->_product->get_image_id(); // Get main product image id
        $image_urls[0] = wp_get_attachment_image_url( $image_id, 'full' );

        // Image from '{Marketplace} Image URL' custom field
        foreach ( $this->activations as $activation  ) {
            $slug =  \strtolower( $activation );
            if (  ! empty( get_post_meta( $id , "mrkvuamp_{$slug}_image", true) ) ) {
                $image_urls[0] = get_post_meta( $id , "mrkvuamp_{$slug}_image", true);
            }
        }

        // If not exists product image gallary
        if ( empty( $this->_product->get_gallery_image_ids() ) && ! empty( $image_urls ) ) {
            return $image_urls;
        }

        // If exists product image gallery
        $gallery_image_ids = $this->_product->get_gallery_image_ids(); // Get product gallery ids
        $gallery_image_urls = array();

        foreach ( $gallery_image_ids as $gallery_image_id ) {
            $gallery_image_urls[] = wp_get_attachment_image_url( $gallery_image_id, 'full' );
        }
        return \array_merge( $image_urls, $gallery_image_urls );
    }

    // Get marketplace category id for <categoryId> xml-tag
    public function get_marketplace_category_id()
    {
        if ( empty( get_option( 'mrkv_uamrkpl_collation_option' ) ) ) {
            return;
        }
        // Get all product categories
        $product_category_ids = $this->_product->get_category_ids();
        $id = $this->_product->get_id();

        // Get wc-categories and marketplace-categories collation array
        $collation_option_ids = get_option( 'mrkv_uamrkpl_collation_option' );
        foreach ( $collation_option_ids as $key => $value ) {

            // Get first wc-category id
            $wc_cat_id = substr( $key , strpos( $key, 'mrkv-uamp-' ) + strlen( 'mrkv-uamp-' ) );
            if ( $value ) { // Is set marketplace-category?
                if ( \in_array( $wc_cat_id, $product_category_ids ) ) {

                    // Category id from '{Marketplace} ID Category' custom field
                    foreach ( $this->activations as $activation  ) {
                        $slug =  \strtolower( $activation );
                        $cat_id = get_post_meta( $id , "mrkvuamp_{$slug}_cat_id", true);
                        if ( isset( $cat_id ) && ! empty( $cat_id ) )
                        {
                            $value = $cat_id;
                        }
                    }

                    return $value;
                } else {
                    continue;
                }
            }
            return false;
        } // foreach $collation_option_ids
    }

    // Get currency value (UAH, USD, EUR, RUR) attribute for <currencyId> xml-tag
    public function get_wc_currency_id()
    {
        $wc_shop = new WCShopController();
        return $wc_shop->currencies[0];
    }

    // Get stock quantity for <stock_quantity> xml-tag
    // Uses for getting 'available' attribute for <offer> xml-tag
    public function get_product_stock_quantity($id, $offers)
    {
        $this->_product = \wc_get_product( $id ); // Get product object from collation list

        $is_manage_stock = $this->_product->get_manage_stock();
        $stock_status = $this->_product->get_stock_status();
        $stock_qty = $this->_product->get_stock_quantity();

        if ( ! $is_manage_stock ) { // If manage_stock == false

            if ( 'instock' == $stock_status ) {
                return 1;
            }
            return 0;
        }
        if ( $stock_qty > 0) { // If manage_stock == true
            return $stock_qty;
        }
        return 0;
    }

}
