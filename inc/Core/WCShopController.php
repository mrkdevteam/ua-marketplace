<?php
/**
 * @package 	MrkvUAMmarketplaces
 */

namespace Inc\Core;

use \Inc\Core\Marketplaces\FactoryAbstractAPI;
use \Inc\Core\Marketplaces\FactoryAPI;

class WCShopController {

    public $name;

    public $company;

    public $url;

    public $currencies = array();

    public $categories = array();

    public $offers = array();

    public function __construct()
    {

        $this->name = ( null !== \get_option( 'mrkv_uamrkpl_rozetka_shop_name' ) )
            ? \get_option( 'mrkv_uamrkpl_rozetka_shop_name' )
            : \get_bloginfo( 'name' );

        $this->company = ( null !== \get_option( 'mrkv_uamrkpl_rozetka_company' ) )
            ? \get_option( 'mrkv_uamrkpl_rozetka_company' )
            : \get_bloginfo( 'description' );

        $this->url = \get_bloginfo( 'url' );

        if ( ! \class_exists( 'WooCommerce' ) ) {
            return;
        }

        global $woocommerce, $product;

        $this->currencies[] = \get_option( 'woocommerce_currency' );

        $this->categories = $this->get_marketplace_collation_category_ids();

        $this->offers = $this->get_offers_ids();


    }

    public function get_offers_ids()
    {
        $collation_cats_ids = $this->get_wc_collation_categories_ids();
        foreach ( $collation_cats_ids as $collation_cats_id ) {
            if ( $term = get_term_by( 'id', $collation_cats_id, 'product_cat' ) ) {
                $collation_cats_slugs[] = $term->slug;
            }
        }

        $args = array(
            'status' => array( 'publish' ),
            'category' => $collation_cats_slugs,
        );
        $products = \wc_get_products( $args );
        foreach ( $products as $product ) {
            $offer_ids[] = $product->get_id();
        }

        return $offer_ids;
    }

    public function get_wc_collation_categories_ids()
    {

        if ( empty( get_option( 'mrkv_uamrkpl_collation_option' ) ) ) {
            return;
        }
        $collation_option_ids = get_option( 'mrkv_uamrkpl_collation_option' );

        foreach ( $collation_option_ids as $key => $value ) {
            if ( strpos( $key, 'mrkv-uamp-') !== false) {
                // Get WooCommerce catigories ids collations
                $wc_cat_id = substr( $key , strpos( $key, 'mrkv-uamp-' ) + strlen( 'mrkv-uamp-' ) );

                if ( $value ) {
                    $wc_cats_collation_arr[] = $wc_cat_id;
                }
            }
        }

        return $wc_cats_collation_arr;
    }

    public function get_marketplace_collation_category_ids()
    {

        if ( empty( get_option( 'mrkv_uamrkpl_collation_option' ) ) ) {
            return;
        }
        $category_collation_ids = get_option( 'mrkv_uamrkpl_collation_option' );

        foreach ( $category_collation_ids as $key => $value ) {
            if ( strpos( $key, 'mrkv-uamp-') !== false) {
                $id_number = substr( $key ,strpos( $key, 'mrkv-uamp-' ) );
                $cats_collation_arr[] = $value;
            }
        }

        return $cats_collation_arr;
    }

    public static function get_collation_category_name_by_id($id)
    {
        $factory_api = new FactoryAPI();
        $rozetka_api = $factory_api->create('rozetka');

        $rozetka_category_name = $rozetka_api->get_category_name_by_id( $id );

        return $rozetka_category_name;
    }

/////////////////////
    public function get_all_category_ids() // function is not in use
    {

        $category_ids = array();
        $args = array(
            'taxonomy'     => array('category', 'product_cat'),
            'orderby'      => 'name',
            'order'        => 'ASC',
            'hide_empty'   => 0,
            'hierarchical' => 1,
            // 'child_of'     => 1
        );
        $terms = \get_terms( 'product_cat', $args );

        foreach ( $terms as $term ) {
            $category_ids[] = isset( $term->term_id ) ? $term->term_id : null;
        }
        return $category_ids;

    }

    public static function get_category_name_by_id($id) // function is not in use
    {
        $term = get_term( $id );
        return $term->name;
    }

}
