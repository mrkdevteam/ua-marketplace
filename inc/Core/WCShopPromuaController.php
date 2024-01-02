<?php
/**
 * @package 	MrkvUAMmarketplaces
 */

namespace Inc\Core;

use \Inc\Core\Marketplaces\FactoryAbstractAPI;
use \Inc\Core\Marketplaces\FactoryAPI;

class WCShopPromuaController {

    public $name;

    public $company;

    public $url;

    public $currencies = array();

    public $categories = array();

    public $offers = array();

    public function __construct()
    {

        $this->name = ( null !== \get_option( 'mrkv_uamrkpl_promua_shop_name' ) )
            ? \get_option( 'mrkv_uamrkpl_promua_shop_name' )
            : \get_bloginfo( 'name' );

        $this->company = ( null !== \get_option( 'mrkv_uamrkpl_promua_company' ) )
            ? \get_option( 'mrkv_uamrkpl_promua_company' )
            : \get_bloginfo( 'description' );

        $this->url = \get_bloginfo( 'url' );

        if ( ! \class_exists( 'WooCommerce' ) ) {
            return;
        }

        global $woocommerce, $product;

        $this->currencies[] = \get_option( 'woocommerce_currency' );

        $this->categories[] = $this->get_wc_promua_categories_ids();

        $this->offers = $this->get_wc_offers_ids();

    }

    public function get_wc_offers_ids() // Get the site all product ids by current language
    { 
        // $offer_ids = get_transient( 'promua_offer_ids_full' );
        // if ( false === $offer_ids || empty( $offer_ids ) ) {

            $cats_slugs = array();
            foreach ( $this->categories as $category ) {
                if ( $term = \get_term_by( 'id', $category, 'product_cat' ) ) {
                    $cats_slugs[] = $term->slug;
                }
            }

            // Get wc-site all products
            $lang = ( \get_locale() ?? \get_bloginfo('language') ) ?? 'uk';           
            $args = array(
                'limit' => -1,
                // 'limit' => 5000,
                'paginate' => true,
                'page' => 1,
                'status' => array( 'publish' ),
                'category' => $cats_slugs,
                'lang' => $lang,
                'return' => 'ids',
            );

            $offer_ids = \wc_get_products( $args );
            // set_transient( 'promua_offer_ids_full', $offer_ids, DAY_IN_SECONDS );
        // }
        return $offer_ids->products;
    }

    public function get_wc_promua_categories_ids() // Get the site all product categories ids by current language
    {
        // $categories_ids = get_transient( 'mrkv_promua_categories_ids' );
        // if ( false === $categories_ids || empty( $categories_ids ) ) {
            $categories_ids = array();
            $pll = false;
            if ( \function_exists('pll_get_term') ) $pll = true; // If Polylang is active
            $lang = ( \get_locale() ?? \get_bloginfo('language') ) ?? 'uk';
            $args = array(
                'taxonomy'   => "product_cat",
                'orderby'    => 'id',
                'hide_empty' => true,
            );
            $product_categories = \get_terms($args);
            foreach( $product_categories as $category ) {
                $cat_term = $category->term_id;
                $cat_ids[] = $cat_term;
            }
            foreach ( $cat_ids as $cat_id ) {
                if ( $pll ) {
                    if ( $lang == pll_get_term_language( $cat_id ) ) {
                        if ( ! empty( \pll_get_term( $cat_id, $lang ) ) ) {
                            $categories_ids[] = \pll_get_term( $cat_id, $lang );
                        }
                    }
                } else {
                    $categories_ids[] = $cat_id;
                }
            }
            // set_transient( 'mrkv_promua_categories_ids', $categories_ids, DAY_IN_SECONDS );
        // }
        return $categories_ids;
    }

    public static function get_promua_category_name_by_id($id)
    {
        return \get_the_category_by_ID( $id );
    }

    public function get_parent_category_id($id)
    {
        $args = array(
            'taxonomy'   => "product_cat",
            'orderby'    => 'id',
            'hide_empty' => false,
        );
        $product_categories = \get_terms($args);
        foreach( $product_categories as $category ) {
            if ( $id == $category->term_id ) {
                return $category->parent;
            }
        }
    }

}
