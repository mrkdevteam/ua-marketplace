<?php
/**
 * @package 	MrkvUAMmarketplaces
 */

namespace Inc\Core\WCShop;

use \Inc\Core\WCShopController;
use \Inc\Core\WCShop\WCShopOffer;

class WCShopOfferSimple extends WCShopOffer {

    public static function set_simple_offer($id, $offers)
    {
        $offer = $offers->addChild( 'offer' ); // XML tag <offer>
            $offer->addAttribute('id', $id);
            $is_available = parent::is_available( $id, $offer, parent::$_product );
            $offer->addAttribute( 'available', $is_available );

            $url = $offer->addChild( 'url', \get_permalink( $id ) ); // XML tag <url>

            $price = parent::$_product->get_regular_price();
            $price = $offer->addChild( 'price', $price ); // XML tag <price>

            $currencyId = $offer->addChild( 'currencyId', parent::get_wc_currency_id() ); // XML tag <currencyId>

            $currencyId = $offer->addChild( 'categoryId', parent::get_marketplace_category_id() ); // XML tag <categoryId>
    }

    public static function get_product_prices( $id, $product_type ) // TODO
    {
        // \error_log('parent::$_product');\error_log(print_r(parent::$_product,1));
    }

    public static function get_product_pictures() // TODO
    {

    }

}
