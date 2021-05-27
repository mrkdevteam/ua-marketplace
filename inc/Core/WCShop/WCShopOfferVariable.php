<?php
/**
 * @package 	MrkvUAMmarketplaces
 */

namespace Inc\Core\WCShop;

use \Inc\Core\WCShopController;
use \Inc\Core\WCShop\WCShopOffer;

class WCShopOfferVariable extends WCShopOffer {

    public $variation;

    public function set_variable_offer($id, $offers)
    {
        // Checkbox '{Marketplace} xml' custom field
        foreach ( $this->slug_activations as $slug  ) {
            $mrktplc_not_xml = get_post_meta( $id , "mrkvuamp_{$slug}_not_xml", true);
            if ( $mrktplc_not_xml ) return;
        }

        $this->_product = \wc_get_product( $id ); // Get variation product object
        $variation_permalink = $this->_product->get_permalink();

        $variations_ids = $this->_product->get_children();

        // Variations loop
        foreach ( $variations_ids as $variation_id ) {

            $this->variation = wc_get_product( $variation_id ); // Get variation object

            $variation_attrs = $this->variation->get_attributes(); // Get variation attributes

            $offer = $this->set_offer_content( $id, $offers, $variation_id ); // XML tag <offer>

                $url = $this->set_url( $id, $offer, $variation_permalink, $variation_attrs ); // XML tag <url>

                $price = $this->set_price( $offer ); // XML tag <price>

                $currencyId = $this->set_currency_id( $offer ); // XML tag <currencyId>

                $categoryId = $this->set_category_id( $offer ); // XML tag <categoryId>

                $picture = $this->set_picture( $id, $offer, $variation_id ); // XML tag <picture>

                $name = $this->set_name( $id, $offer ); // XML tag <name>

                $vendor = $this->set_vendor( $id, $offer ); // XML tag <vendor>

                $description = $this->set_description( $id, $offer ); // XML tag <description>

                $param = $this->set_param( $id, $offer ); // XML tag <param>

                $stock_quantity = $this->set_stock_quantity($id, $offer, $offers); // XML tag <stock_quantity>
        }
    }

    public function set_offer_content($id, $offers, $variation_id) // XML tag <offer>
    {
        $offer = $offers->addChild( 'offer' );
        $offer->addAttribute( 'group_id', $id );
        $offer->addAttribute( 'id', $variation_id );
        $is_available = $this->get_product_stock_quantity( $id, $offer, $this->variation ) ? 'true' : 'false';
        $offer->addAttribute( 'available', $is_available );
        return $offer;
    }

    public function set_url($id, $offer, $variation_permalink, $variation_attrs) // XML tag <url>
    {
        return $offer->addChild( 'url',
            esc_html( $variation_permalink . '?' .
                $this->get_variation_params( $this->variation, $variation_attrs ) ) );
    }

    public function set_price($offer) // XML tag <price>
    {
        $price = $this->variation->get_regular_price();
        $price = $offer->addChild( 'price', $price );
        return $price;
    }

    public function set_currency_id( $offer ) // XML tag <currencyId>
    {
        return $offer->addChild( 'currencyId', $this->get_wc_currency_id() );
    }

    public function set_category_id($offer) // XML tag <categoryId>
    {
        return $offer->addChild( 'categoryId', $this->get_marketplace_category_id() );
    }

    public function set_picture($id, $offer, $variation_id) // XML tag <picture>
    {
        $image_urls = $this->get_variable_image_urls( $id, $variation_id );
        if ( \is_array( $image_urls ) ) {
            foreach ( $image_urls as $key => $value ) {
                if ( empty( $value ) ) continue;
                $offer->addChild( 'picture', $value );
            }
        }
    }

    public function set_name( $id, $offer) // XML tag <name>
    {
        return $offer->addChild( 'name', $this->get_product_title( $id ) );
    }

    public function set_vendor($id, $offer) // XML tag <vendor>
    {
        $vendor_name = $this->get_product_vendor( $id );
        return $offer->addChild( 'vendor', $vendor_name );
    }

    public function set_description($id, $offer) // XML tag <description>
    {
        return $offer->addChildWithCDATA( 'description', nl2br( $this->get_product_description( $id ) ) );
    }

    public function set_param($id, $offer) // XML tag <param>
    {
        [ $param_labels, $param_values ] = $this->get_product_attributes( $id );
        // Get product variation attributes
        $variation_params = $this->variation->get_attributes();
        foreach ( $variation_params as $key => $value ) {
            $variation_param_label = wc_attribute_label( $key );
            $variation_param_value = $this->variation->get_attribute( $key );
            $param = $offer->addChild( 'param', $variation_param_value );
            $param->addAttribute( 'name', $variation_param_label );
        }
        // Get product attributes
        for ( $i = 0; $i < \sizeof( $param_values ) ; $i++ ) {
            $param = $offer->addChild( 'param', $param_values[$i] );
            $param->addAttribute( 'name', $param_labels[$i] );
        }
    }

    public function set_stock_quantity($id, $offer, $offers) // XML tag <stock_quantity>
    {
        $quantity = $this->get_product_stock_quantity( $id, $offers, $this->_product );
        return $offer->addChild( 'stock_quantity', $quantity );
    }

    // Create GET parameters for product variation URLs
    public function get_variation_params($variation, $variation_attrs)
    {
        $attr_keys = array_keys( $variation_attrs );

        for ( $i = 0; $i < \sizeof( $attr_keys );  $i++) {
            $key = $attr_keys[$i];
            $value = $variation_attrs[$key];
            $params[$i] = $key . '=' . $value;
        }

        return join( "&", $params );
    }

    // Get variable image URLs for <picture> xml-tag
    public function get_variable_image_urls($id, $variation_id)
    {
        // Get product image urls
        $product_image_urls = array();
        $product_image_urls = $this->get_product_image_urls( $id );
        // Get variation image urls
        $variation_image_urls = array();
        foreach ( $this->slug_activations as $slug  ) {
            if (  ! empty( get_post_meta( $variation_id , "mrkvuamp_{$slug}_variation_image", true) ) ) {
                $variation_image_urls[0] = get_post_meta( $variation_id , "mrkvuamp_{$slug}_variation_image", true);
                $product_image_urls[0] = $variation_image_urls[0];
            }
        }
        return $product_image_urls;
    }

}
