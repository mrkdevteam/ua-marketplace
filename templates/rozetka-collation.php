<?php

use \Inc\Core\WCShop\WCShopCollation;
use \Inc\Core\XMLController;

    // Create internet-shop Object
    $mrkv_uamrkpl_shop = new WCShopCollation('shop');
    $mrkv_uamrkpl_shop_arr = (array) $mrkv_uamrkpl_shop;

    // Create XML-price
    $convert = new \Inc\Core\XMLController( 'rozetka' );
    $xml_filename = '/uploads/mrkvuamp' . $convert->marketplace . '.xml';
    $xml = $convert->array2xml( $mrkv_uamrkpl_shop_arr );

    if ( $_POST ) {
        foreach ( $_POST as $key => $value ) {
            if ( strpos( $key, 'mrkv-uamp-') !== false ) {
                $cats_collation_arr[$key] = ! empty( $value ) ? sanitize_text_field( $value ) : '';
            }
        }
        update_option( 'mrkv_uamrkpl_collation', $cats_collation_arr );
    }

?>

<div id="category-matching" class="link-pane">
    <h2>Rozetka Співставлення категорій</h2>
    <div>
        <form class="mrkv_uamrkpl_collation" action="" method="post">
            <?php // Show internet-shop categories for collation ?>
            <?php echo WCShopCollation::get_hierarchical_tree_categories(); ?>
            <?php submit_button( __( 'Співставити', 'mrkv-ua-marketplaces' ), 'primary', 'submit-collation' ); ?>
        </form>
    </div>

    <p>Посилання на
        <a  id="mrkvuamp_xml_link" target="_blank"
            href="<?php echo content_url(); echo $xml_filename; ?>">останній згенерований xml
        </a>
        ( <?php echo date( " d.m.Y H:i", filemtime( WP_CONTENT_DIR . $xml_filename ) ); clearstatcache(); ?> UTC )
    </p>

</div>
