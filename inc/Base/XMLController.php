<?php
/**
 * @package 	MrkvUAMmarketplaces
 */

namespace Inc\Base;

use \Inc\Base\WCShopController;

class XMLController {

    public $xml_price_name; // TODO замість хардкоду mrkvuamprozetka.xml

    public $xml_header;

    public $current_date;

    public $category_name;

    public function __construct()
    {
        $this->current_date = \date("Y-m-d H:i");

        $this->xml_header = '<yml_catalog date="' . $this->current_date . '"></yml_catalog>';

    }

    public function array2xml($array, $xml = null)
    {
        if ( $xml === null ) {
            $xml = new \SimpleXMLElement( "<?xml version='1.0' encoding='UTF-8'?>
                <!DOCTYPE yml_catalog SYSTEM 'shops.dtd'>" . $this->xml_header );
        }
        $shop = $xml->addChild('shop');
        foreach( $array as $key => $value ){
            if ( is_array( $value ) ) {

                if ( 'currencies' == $key ) { // XML tag <currencies>
                    $currencies = $shop->addChild( 'currencies' );
                    $currency = $currencies->addChild( 'currency' );
                    $currency->addAttribute('id', $value[0]);

                } else if ( 'categories' == $key ) { // XML tag <categories>
                    $categories = $shop->addChild( 'categories' );
                    foreach ($value as $k => $v) {
                        $category = $categories->addChild( 'category', WCShopController::get_category_name_by_id($v) );
                        $category->addAttribute('id', $v);
                    }

                } else {
                    $this->array2xml( $value, $shop->addChild( $key ) );
                }
            } else {
                if ( \is_numeric($key) ) {
                    // $key = 'id';
                    // $xml->removeChild( $key );
                    // $xml->unset( $key );
                } else {
                    $shop->addChild( $key, $value );
                }
            }
        }
        $xml->saveXML();
        return $xml->asXML(WP_CONTENT_DIR . '/uploads/mrkvuamprozetka.xml');
    }

}
