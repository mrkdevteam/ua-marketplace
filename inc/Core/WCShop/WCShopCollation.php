<?php
/**
 * @package 	MrkvUAMmarketplaces
 */

namespace Inc\Core\WCShop;

use \Inc\Core\WCShopController;
use \Inc\Core\XMLController;

class WCShopCollation extends WCShopController {

    public static function get_hierarchical_tree_categories($category = 0)
    {
        $categories_html = '';
        $args = array(
            'parent' => $category,
            'taxonomy'     => array('category', 'product_cat'),
            'orderby'      => 'name',
            'order'        => 'ASC',
            'hide_empty'   => 0,
            'hierarchical' => 1
        );
        $next = get_terms('product_cat', $args);
        if ( $next ) {
            $categories_html .= '<ul class="mrkv-uamp-hierarchical-categories">';
            foreach ( $next as $cat ) {
                $categories_html .= '<li><label for="mrkv-uamp-' . $cat->term_id . '" >' . $cat->name . '
                    <span style="font-weight:400;">(' . $cat->count . ')</span>' . '</label>';
                $categories_html .= '<input type="text" id="mrkv-uamp-' . $cat->term_id . '" name="mrkv-uamp-' . $cat->term_id .
                    '" placeholder="' . $cat->name . '" value="' . self::get_collation_option( $cat->term_id ) . '">';
                $categories_html .= $cat->term_id !== 0 ? self::get_hierarchical_tree_categories($cat->term_id) : null;
            }
            $categories_html .= '</li></ul>';
        }
        return $categories_html;
    }

    public static function get_collation_option($id)
    {

        if ( ! is_array( get_option( 'mrkv_uamrkpl_collation_option' ) ) ) {
            return;
        }

        if ( empty( $_POST["mrkv-uamp-{$id}"] ) ) {
            return;
        }

        if ( isset( $_POST["mrkv-uamp-{$id}"] ) ) {
            return $_POST["mrkv-uamp-{$id}"];
        }
        return get_option( 'mrkv_uamrkpl_collation_option' )["mrkv-uamp-{$id}"];
    }

}
