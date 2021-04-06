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

    public function __construct()
    {

        $this->name = ( null !== \get_option( 'mrkv_uamrkpl_rozetka_shop_name' ) )
            ? \get_option( 'mrkv_uamrkpl_rozetka_shop_name' )
            : \get_bloginfo( 'name' );

        $this->company = ( null !== \get_option( 'mrkv_uamrkpl_rozetka_company' ) )
            ? \get_option( 'mrkv_uamrkpl_rozetka_company' )
            : \get_bloginfo( 'description' );

        $this->url = \get_bloginfo( 'url' );

        $this->currencies[] = \get_option( 'woocommerce_currency' );

        // $this->categories = $this->get_all_category_ids();
        $this->categories = $this->get_collation_category_ids();
// \error_log('categories');\error_log(print_r($this->categories,true));

    }

    public function get_all_category_ids()
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

    public static function get_category_name_by_id($id)
    {
        $term = get_term( $id );
        return $term->name;
    }

    public function get_collation_category_ids()
    {
// \delete_option( 'mrkv_uamrkpl_collation' );
        if ( empty( get_option( 'mrkv_uamrkpl_collation' ) ) ) {
            return;
        }
        $category_collation_ids = get_option( 'mrkv_uamrkpl_collation' );
        // \error_log(print_r($category_collation_ids, true));
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
        // $rozetka_category_name = $rozetka_api->get_category_info_by_id( $datastring = "market-categories/search?category_id={$id}" );
        // $rozetka_category_name = $rozetka_api->get_category_info_by_id( $id );
        $rozetka_category_name = $rozetka_api->get_category_name_by_id( $id );
// error_log(print_r($rozetka_api->get_category_info_by_id( $datastring = "market-categories/search?category_id={$id}" ),true));
// error_log(print_r($rozetka_api->get_category_info_by_id( $id ),true));
// error_log(print_r($rozetka_api->get_category_name_by_id( $id ),true));
        // $cat_get_val = 'https://api-seller.rozetka.com.ua/market-categories/category-options?category_id='. $id;
        // $term = get_term( $id );
        return $rozetka_category_name;
    }

}
