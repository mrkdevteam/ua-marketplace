<?php
/**
 * @package 	MrkvUAMmarketplaces
 */

namespace Inc\Core;

use \Inc\Core\Offer;

// If this file is called directly, abort.
defined( 'ABSPATH' ) or die();

class OfferPromua extends Offer {

    public function set_vendorCode($offer) // XML tag <vendorCode>
    {
        if ( empty( $offer->get_sku() ) ) return ' ';
        return $offer->get_sku();
    }

    public function set_available($offer) // XML tag <available> for PromUA
    {
        $stock_status = $offer->get_stock_status();
        if ( 'instock' == $stock_status || 'onbackorder' == $stock_status ) {
            $availability = 'true';
        }
        if ( 'outofstock' == $stock_status ) {
            $availability = 'false';
        }

        return $availability;
    }

    public function set_quantity_in_stock($offer) // XML tag <stock_quantity>
    {
        $is_in_stock = $offer->is_in_stock();
        $is_on_backorder = $offer->is_on_backorder();
        $stock_quantity = $offer->get_stock_quantity() ?: 0;
        $is_manage_stock = $offer->get_manage_stock();

        if ( ! $is_manage_stock && ( $is_in_stock || $is_on_backorder ) ) {
            return 1;
        }

        if ( ! $is_manage_stock && ! $stock_quantity ) { // outofstock
            return 0;
        }

        return $stock_quantity;
    }    

}
