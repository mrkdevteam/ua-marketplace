<?php
header('Clear-Site-Data: "cache"'); // Clear browser cache for read last xml file

use \Inc\Core\WCShop\WCShopCollation;
use \Inc\Core\XMLController;

$xml_filename = '/uploads/mrkvuamprozetka.xml';
?>

<div id="category-matching" class="link-pane">
    <h2>Rozetka Співставлення категорій</h2>
    <p>Додайте ідентифікатори сатегорій з сайту Rozetka для співставлення</p>

    <div class="mrkvuamp_collation_form_wrap">
        <form id="mrkv_uamrkpl_collation" class="mrkv_uamrkpl_collation" method="post" action="<?php //echo admin_url('admin-ajax.php'); ?>">
            <?php echo WCShopCollation::get_hierarchical_tree_categories(); ?>
            <input type="hidden" name="action" value="mrkvuamp_collation_action">
            <?php wp_nonce_field( 'mrkv_uamrkpl_collation_nonce' ); ?>
            <?php submit_button( __( 'Співставити', 'mrkv-ua-marketplaces'), 'primary', 'mrkvuamp_submit_collation2', true ); ?>
        </form>
    </div>
    <div class="mrkvuamp_collation_xml_link">
        <form action="">
            <p>Посилання на
                <a  id="mrkvuamp_xml_link" target="_blank"
                    href="<?php echo content_url() . $xml_filename; ?>">останній згенерований xml
                </a>
                <?php if ( isset($_POST["mrkvuamp_submit_collation2"] ) ) : ?>
                    <span>
                        ( <?php echo date( " d.m.Y H:i:s" ); ?> UTC )
                    </span>
                <?php else : ?>
                    <span>
                        ( <?php clearstatcache(); echo date( " d.m.Y H:i:s", filemtime( WP_CONTENT_DIR . $xml_filename ) ); ?> UTC )
                    </span>
                <?php endif; ?>
            </p>
        </form>
    </div>
    <div id="mrkvuamp_content"></div>
</div>
