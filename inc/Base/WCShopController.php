<?php
/**
 * @package 	MrkvUAMmarketplaces
 */

namespace Inc\Base;

class WCShopController
{

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

        $this->categories = $this->get_category_ids();

    }

    public function get_category_ids()
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
        $terms = \get_terms( 'product_cat', $args );error_log(print_r($terms,true));

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

}
