<?php
/**
 * @package 	MrkvUAMmarketplaces
 */

namespace Inc\Core\WCShop;

use \Inc\Core\WCShopPromuaController;
use \Inc\Core\WCShop\WCShopPromuaOffer;
use \Inc\Core\WCShop\WCShopOfferVariable;

class WCShopPromuaOfferVariable extends WCShopOfferVariable {

    public $wcShopPromuaOffer;

    public function __construct()
    {
        $this->wcShopPromuaOffer = new WCShopPromuaOffer();
    }

    public function set_category_id($id, $offer) // XML tag <categoryId>
    {
        return $offer->addChild( 'categoryId', $this->wcShopPromuaOffer->get_wc_promua_category_id( $id ) );
    }

    public function set_stock_quantity($id, $offer, $offers) // XML tag <available>
    {
        $stock_status = $this->variation->get_stock_status();
        if ( 'instock' == $stock_status ) {
            $availability = 'true';
        }
        if ( 'outofstock' == $stock_status ) {
            $availability = ' ';
        }
        if ( 'onbackorder' == $stock_status ) {
            $availability = 'false';
        }

        return $offer->addChild( 'available', $availability );
    }

    public function set_vendor($id, $offer) // XML tag <vendor>
    {
        $vendor_name = $this->wcShopPromuaOffer->get_product_vendor( $id );
        return $offer->addChild( 'vendor', $vendor_name );
    }

}
