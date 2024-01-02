<?php
/**
 * @package 	MrkvUAMmarketplaces
 */

namespace Inc\Core\WCShop\EditProduct;

use \Inc\Base\BaseController;

class ExtraProductSettings {

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

        // Remove '{Marketplace} ID Category' fields if they have been installed before
        add_filter( 'manage_edit-product_columns', array( $this, 'remove_product_cat_id' ), 15, 1 ); // тимчасово

        // Add marketplace tabs and fields on 'Products' and 'Edit Product' admin pages
        add_filter( 'woocommerce_product_data_tabs', array( $this, 'product_tab' ) );
        add_action( 'woocommerce_product_data_panels', array( $this, 'data_fields' ) );
        add_action( 'woocommerce_process_product_meta', array( $this, 'save_fields' ), 10, 2 );

    }

    public function remove_product_cat_id($columns) // тимчасово
    {
        unset( $columns['mrkvuamp_rozetka_cat_id'] );
        unset( $columns['mrkvuamp_promua_cat_id'] );
        return $columns;
    }

    public function product_tab( $product_data_tabs )
    {
        foreach ( $this->activations as $activation  ) {
            $tab = 'mrkvuamp_' . \strtolower( $activation ) . '_tab';
            $label = \ucfirst( $activation );
        	$product_data_tabs[$tab] = array(
        		'label' => __( $label, 'mrkv-ua-marketplaces' ),
        		'target' => $tab,
        	);
        }
    	return $product_data_tabs;
    }

    public function save_fields( $id, $post )
    {
        foreach ( $this->activations as $activation  ) {
            $slug =  \strtolower( $activation );

            if ( 'rozetka' == $slug ) {
                // '{Marketplace} xml' field
        		$mrkvuamp_not_xml = isset( $_POST["mrkvuamp_{$slug}_not_xml"] ) ? sanitize_text_field( $_POST["mrkvuamp_{$slug}_not_xml"] ) : 0;
        		update_post_meta( $id, "mrkvuamp_{$slug}_not_xml", $mrkvuamp_not_xml );

                // '{Marketplace} Variation ID' field
        		update_post_meta( $id, "mrkvuamp_{$slug}_product_id", sanitize_text_field( $_POST["mrkvuamp_{$slug}_product_id"] ) );

                // '{Marketplace} ID Category' field // Можливо буде повернуто у майбутньому
        		// update_post_meta( $id, "mrkvuamp_{$slug}_cat_id", sanitize_text_field( $_POST["mrkvuamp_{$slug}_cat_id"] ) );

                // '{Marketplace} Image URL' field
        		update_post_meta( $id, "mrkvuamp_{$slug}_image", sanitize_text_field( $_POST["mrkvuamp_{$slug}_image"] ) );

                // '{Marketplace} Description' field
        		update_post_meta( $id, "mrkvuamp_{$slug}_description", wp_filter_kses( $_POST["mrkvuamp_{$slug}_description"] )  );
            }

            if ( 'rozetka' == $slug || 'promua' == $slug ) {
                // '{Marketplace} Title' field
                update_post_meta( $id, "mrkvuamp_{$slug}_title", sanitize_text_field( $_POST["mrkvuamp_{$slug}_title"] ) );
            }
        }
    }

    public function data_fields()
    {
        foreach ( $this->activations as $activation  ) {

            $slug =  \strtolower( $activation );
        	echo '<div id="mrkvuamp_'. $slug . '_tab" class="panel woocommerce_options_panel">';
        	echo '<div class="options_group">';

            if ( 'rozetka' == $slug ) {
            	// '{Marketplace} xml' fields
            	woocommerce_wp_checkbox( array(
                    'id'            => 'mrkvuamp_' . $slug . '_not_xml',
                    'wrapper_class' => '',
                    'label'         => __( "{$activation} xml", 'mrkv-ua-marketplaces' ),
                    'description'   => __( "Якщо обрати, товар не буде доданий до xml-прайсу для сайту {$activation}", 'mrkv-ua-marketplaces' ),
                    'default'       => '0',
                    'desc_tip'      => false,
                ) );
            }

            if ( 'rozetka' == $slug || 'promua' == $slug ) {
            	// '{Marketplace} Title' fields
            	woocommerce_wp_text_input ( array(
            		'id'      => 'mrkvuamp_' . $slug . '_title',
            		'value'   => get_post_meta( get_the_ID(), 'mrkvuamp_' . $slug . '_title', true ),
            		'label'   => __( "{$activation} Title", 'mrkv-ua-marketplaces' ),
            		'desc_tip' => true,
            		'description' => __( 'Якщо ввести значення, саме воно потрапить в xml замість заголовка. Важливо при використанні додаткових SEO налаштувань.', 'mrkv-ua-marketplaces' ),
            	) );
            }

            if ( 'rozetka' == $slug ) {
            	// '{Marketplace} ID Category' fields // Можливо буде повернуто у майбутньому
             	// woocommerce_wp_text_input( array(
            	// 	'id' => 'mrkvuamp_' . $slug . '_cat_id',
            	// 	'value'   => get_post_meta( get_the_ID(), 'mrkvuamp_' . $slug . '_cat_id', true ),
            	// 	'label' => __( "{$activation} ID Category", 'mrkv-ua-marketplaces' ),
            	// 	'desc_tip' => true,
            	// 	'description' => __( "Введіть бажаний номер категорії з сайту {$activation}.", 'mrkv-ua-marketplaces' ),
            	// ) );

            	// '{Marketplace} image URL' fields
                echo '<input id="mrkvuamp_imgurl_btn" type="button" class="button" value="Image URL">';
                woocommerce_wp_text_input ( array(
                    'id'      => 'mrkvuamp_' . $slug . '_image',
                    'value'   => get_post_meta( get_the_ID(), 'mrkvuamp_' . $slug . '_image', true ),
                    'label'   => __( "{$activation} Image URL", 'mrkv-ua-marketplaces' ),
                    'type' => 'text',
                    'data_type' => 'url',
                    'desc_tip' => true,
                    'description' => __( 'Якщо ввести URL потрібного фото, саме це фото потрапить в xml замість того, що на сторінці товару.', 'mrkv-ua-marketplaces' )
                ) );

             	// '{Marketplace} Description' fields
            	echo "<p class=\"form-field mrkvuamp{$slug}description_field \">
            		<label for=\"mrkvuamp{$slug}description\" style=\"font-size: 12px;\">{$activation} Description</label></p>";
            	wp_editor(
                    get_post_meta( get_the_ID(), 'mrkvuamp_' . $slug . '_description', true ) ,
            		'mrkvuamp' . $slug . 'description',
            		array(
            			'textarea_name' => 'mrkvuamp_' . $slug . '_description',
            			'tinymce' => 0,
            			'media_buttons' => 0 ,
                        'teeny' => 1,
                        'quicktags' => array (
                            'id' => 'mrkvuamp' . $slug . 'description',
                            'buttons' => 'strong,em,ul,ol,li,close'
                        )
            		)
            	);
            }
        	echo "</div></div>";
        }
    }

}
