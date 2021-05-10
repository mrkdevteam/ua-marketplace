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
            // $is_available = parent::is_available( $id, $offer, parent::$_product );
            $is_available = parent::get_product_stock_quantity( $id, $offer, parent::$_product ) ? 'true' : 'false';
            $offer->addAttribute( 'available', $is_available );

            $url = $offer->addChild( 'url', \get_permalink( $id ) ); // XML tag <url>

            $price = parent::$_product->get_regular_price();
            $price = $offer->addChild( 'price', $price ); // XML tag <price>

            $currencyId = $offer->addChild( 'currencyId', parent::get_wc_currency_id() ); // XML tag <currencyId>

            $currencyId = $offer->addChild( 'categoryId', parent::get_marketplace_category_id() ); // XML tag <categoryId>

            $image_urls = parent::get_product_image_urls();
            foreach ( $image_urls as $image_url ) {
                if ( empty( $image_url ) ) {
                    continue;
                }
                $picture = $offer->addChild( 'picture', $image_url ); // XML tag <picture>
            }

            $name = $offer->addChild( 'name', parent::get_product_title() ); // XML tag <name>

            $vendor_name = parent::get_product_vendor($id);
            $vendor = $offer->addChild( 'vendor', $vendor_name ); // XML tag <vendor>

            // XML tag <description>
            $description = $offer->addChildWithCDATA( 'description', nl2br( parent::get_product_description() ) );

            // XML tag <param>
            [ $param_labels, $param_values ] = parent::get_product_attributes( $id );
            for ( $i = 0; $i < \sizeof( $param_values ) ; $i++ ) {
                $param = $offer->addChild( 'param', $param_values[$i] );
                $param->addAttribute( 'name', $param_labels[$i] );
            }

            // XML tag <stock_quantity>
            $quantity = parent::get_product_stock_quantity( $id, $offers, parent::$_product );
            $stock_quantity = $offer->addChild( 'stock_quantity', $quantity );
    }

}
