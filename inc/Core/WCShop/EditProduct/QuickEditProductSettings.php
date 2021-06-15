<?php
/**
 * QuickEditProductSettings class
 *
 * Makes Quick Edit products '{Marketplace} Title' and '{Marketplace} ID Category' fields
 * on admin 'Products' page
 *
 * @package 	MrkvUAMmarketplaces
 */

namespace Inc\Core\WCShop\EditProduct;

use \Inc\Base\BaseController;

class QuickEditProductSettings extends ExtraProductSettings {

    public function __construct()
    {
        add_filter( 'manage_product_posts_columns', array( $this, 'add_product_columns' ) );
        add_action( 'manage_product_posts_custom_column', array( $this, 'render_title_column' ), 10, 2 );
        add_action( 'manage_product_posts_custom_column', array( $this, 'render_cat_id_column' ), 10, 2 );

        add_action( 'quick_edit_custom_box', array( $this, 'add_quickedit_fields' ), 10, 2 );
        add_action( 'save_post', array( $this, 'save_quickedit_fields_data' ), 10, 2 );

        add_action( 'admin_footer', array( $this, 'quickedit_rozetka_javascript' ) );
        add_filter( 'post_row_actions', array( $this, 'set_rozetka_data' ), 10, 2);
    }

    // Sets '{Marketplace} Rozetka Title' and '{Marketplace} Rozetka ID Category' columns on 'Products' page.
    public function add_product_columns( $posts_columns ) {
        foreach ( $this->activations as $activation  ) {
            $slug =  \strtolower( $activation );
            $posts_columns["mrkvuamp_{$slug}_title"] = __( "{$activation} Title", 'mrkv-ua-marketplaces' );
            $posts_columns["mrkvuamp_{$slug}_cat_id"] = __( "{$activation} ID Category", 'mrkv-ua-marketplaces' );
        }
        return $posts_columns;
    }

    // Adds values to '{Marketplace} Title' column.
    function render_title_column( $column_name, $post_id ) {
        foreach ( $this->activations as $activation  ) {
            $slug =  \strtolower( $activation );
            if ( "mrkvuamp_{$slug}_title" == $column_name ) {
                $marketplace_title = get_post_meta( $post_id, "mrkvuamp_{$slug}_title", true );

                if ( $marketplace_title ) {
                    echo esc_html( $marketplace_title );
                } else {
                    esc_html_e( 'N/A', 'mrkv-ua-marketplaces' );
                }
            }
        }
    }

    // Adds values to '{Marketplace} ID Category' column.
    function render_cat_id_column( $column_name, $post_id ) {
        foreach ( $this->activations as $activation  ) {
            $slug =  \strtolower( $activation );
            if ( "mrkvuamp_{$slug}_cat_id" == $column_name ) {
                $marketplace_cat_id = get_post_meta( $post_id, "mrkvuamp_{$slug}_cat_id", true );

                if ( $marketplace_cat_id ) {
                    echo esc_html( $marketplace_cat_id );
                } else {
                    esc_html_e( 'N/A', 'mrkv-ua-marketplaces' );
                }
            }
        }
    }

    // Sets header 'Дані товару для {Marketplace}',
    // '{Marketplace} Title' and '{Marketplace} ID Category' custom fields to 'Quick Edit' ('Властивості') panel.
    function add_quickedit_fields( $column_name, $post_type ) {
    	global $post;
        foreach ( $this->activations as $activation  ) {
            $slug =  \strtolower( $activation );
            if ( "mrkvuamp_{$slug}_title" == $column_name ) {
    		    $marketplace_title = get_post_meta( $post->ID, "mrkvuamp_{$slug}_title", true );
                $marketplace_header = __('Дані товару для ', 'mrkv-ua-marketplaces' ) . $activation; ?>
    				<fieldset class="inline-edit-col-left" style="margin-left: 40%;">
    					<div id="mrkvuamp-woocommerce-fields" class="inline-edit-col">
                            <?php wp_nonce_field( 'mrkvuamp_quick_edit_nonce', 'mrkvuamp_qe_nonce' ); ?>
    						<h4><?php echo $marketplace_header; ?></h4>
    						<label class="inline-edit-group" style="margin-bottom: 4px;">
    							<span class="title">Title</span>
    							<span class="input-text-wrap">
    								<input type="text" name="mrkvuamp_<?php echo $slug; ?>_title"
                                            class="mrkvuamp<?php echo $slug; ?>title"
                                            value="<?php echo $marketplace_title; ?>">
    							</span>
    						</label> <?php
    		}
            if ( "mrkvuamp_{$slug}_cat_id" == $column_name ) {
    		    $marketplace_cat_id = get_post_meta( $post->ID, "mrkvuamp_{$slug}_cat_id", true ); ?>
    						<label class="inline-edit-group">
    							<span class="title">ID Category</span>
    							<span class="input-text-wrap">
    								<input type="text" name="mrkvuamp_<?php echo $slug; ?>_cat_id"
                                            class="mrkvuamp<?php echo $slug; ?>cat_id"
                                            value="<?php echo $marketplace_cat_id; ?>">
    							</span>
    						</label>
    					</div>
    				</fieldset>
    		    <?php
    		}
        }
    }

    // Saves custom fields in DB
    function save_quickedit_fields_data( $post_id, $post ) {
        // verify if this is an auto save routine.
        // If it is our form has not been submitted, so we dont want to do anything
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
            return;

        // if this "product" post type?
        if ( $post->post_type != 'product' )
            return;

        // does this user have permissions?
         if ( ! current_user_can( 'edit_post', $post_id ) )
             return;

         // check nonce
    	if ( ! wp_verify_nonce( $_POST['mrkvuamp_qe_nonce'], 'mrkvuamp_quick_edit_nonce' ) ) {
    		return;
    	}

        // update!
        foreach ( $this->activations as $activation  ) {
            $slug =  \strtolower( $activation );
            if ( isset( $_POST["mrkvuamp_{$slug}_title"] ) ) {
                update_post_meta( $post_id, "mrkvuamp_{$slug}_title", $_POST["mrkvuamp_{$slug}_title"] );
            }
            if ( isset( $_POST["mrkvuamp_{$slug}_cat_id"] ) ) {
                update_post_meta( $post_id, "mrkvuamp_{$slug}_cat_id", $_POST["mrkvuamp_{$slug}_cat_id"] );
            }
        }
    }

    // Adds JavaScript
    function quickedit_rozetka_javascript() {
        $current_screen = get_current_screen();
        if ( $current_screen->id != 'edit-product' || $current_screen->post_type != 'product' ) return;
        ?>
        <script type="text/javascript">
        	window.setTimeout(function(){
    	        jQuery( function( $ ) {
    	            jQuery( '#the-list' ).on( 'click', '.editinline', function( e ) {
    	                e.preventDefault();
    	                var editRozTitle = jQuery(this).data( 'edit-roz-title' );
    	                var editRozCatId = jQuery(this).data( 'edit-roz-cat_id' );
    	                inlineEditPost.revert();
    	                jQuery( ".mrkvuamprozetkatitle" ).val( editRozTitle ? editRozTitle : '' );
    	                jQuery( '.mrkvuamprozetkacat_id' ).val( editRozCatId ? editRozCatId : '' );
    	            });
    	        });
    		}, 0);
        </script>
        <?php
    }

    // Dynamically populates 'Rozetka Title' and 'Rozetka ID Category' custom fields from DB
    function set_rozetka_data( $actions, $post ) {
        foreach ( $this->activations as $activation  ) {
            $slug =  \strtolower( $activation );
            $title_value = get_post_meta( $post->ID, "mrkvuamp_{$slug}_title", true );
            $cat_id_value = get_post_meta( $post->ID, "mrkvuamp_{$slug}_cat_id", true );

            if ( $title_value ) {
                if ( isset( $actions['inline hide-if-no-js'] ) ) {
                    $title_attr = sprintf( 'data-edit-roz-title="%s"', esc_attr( $title_value ) );
                    $actions['inline hide-if-no-js'] = str_replace( 'class=', "$title_attr class=", $actions['inline hide-if-no-js'] );
                }
            }

            if ( $cat_id_value ) {
                if ( isset( $actions['inline hide-if-no-js'] ) ) {
                    $cat_id_attr = sprintf( 'data-edit-roz-cat_id="%s"', esc_attr( $cat_id_value ) );
                    $actions['inline hide-if-no-js'] = str_replace( 'class=', "$cat_id_attr class=", $actions['inline hide-if-no-js'] );
                }
            }
        }
        return $actions;
    }

}
