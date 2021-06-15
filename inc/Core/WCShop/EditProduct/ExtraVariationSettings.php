<?php
/**
 * @package 	MrkvUAMmarketplaces
 */

namespace Inc\Core\WCShop\EditProduct;

use \Inc\Base\BaseController;

class ExtraVariationSettings {

    public $activations = array();

    public function register()
    {
        if ( empty( get_option( 'mrkv_ua_marketplaces' ) ) ) {
            return;
        }

        $base_controller = new BaseController();
        $marketplaces = $base_controller->activations;
        $activated_marketplaces = get_option( 'mrkv_ua_marketplaces' );

        foreach ( $activated_marketplaces as $key => $value ) {
            if ( $value ) {
                $this->activations[] = $marketplaces[$key];
            }
        }

        add_action( 'woocommerce_variation_options_pricing', array( $this, 'add_image_field' ), 10, 3 );
        add_action( 'woocommerce_save_product_variation', array( $this, 'save_image_field' ), 10, 2 );
        // add_filter( 'woocommerce_available_variation', array( $this, 'add_image_field_data' ), 10, 3 );
    }

    public function add_image_field( $loop, $variation_data, $variation )
    {
        foreach ( $this->activations as $activation  ) {

            $slug =  \strtolower( $activation );
            woocommerce_wp_text_input(
                array(
                    'id' => "mrkvuamp_{$slug}_variation_image[" . $loop . "]",
                    'name' => "mrkvuamp_{$slug}_variation_image[" . $loop . "]",
                    'class' => 'short mrkvuamp-full-width',
                    'label' => __( "{$activation} Variation Image", 'mrkv-ua-marketplaces' ),
                    'value' => get_post_meta( $variation->ID, "mrkvuamp_{$slug}_variation_image", true ),
                    'type' => 'text',
                    'data_type' => 'url',
                    'desc_tip' => true,
                    'description' => __( 'Якщо ввести URL потрібного фото, саме це фото потрапить в xml замість того, що на сторінці товару.', 'mrkv-ua-marketplaces' )
                )
            );
        }
    }

    public function save_image_field( $variation_id, $i )
    {
        foreach ( $this->activations as $activation  ) {
            $slug =  \strtolower( $activation );
            $image_field = $_POST["mrkvuamp_{$slug}_variation_image"][$i];

            if ( isset( $image_field ) ) {
                update_post_meta( $variation_id, "mrkvuamp_{$slug}_variation_image", esc_attr( $image_field ) );
            }
        }
    }

    // public function add_image_field_data( $data, $product, $variations )
    // {
    //     foreach ( $this->activations as $activation  ) {
    //         $slug =  \strtolower( $activation );
    //         $image_field = $_POST["mrkvuamp_{$slug}_variation_image"];
    //         $variations[$image_field] = get_post_meta( $variations[ 'variation_id' ], $image_field, true );
    //
    //         return $variations;
    //     }
    // }

}
