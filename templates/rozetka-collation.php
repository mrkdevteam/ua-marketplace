<?php

use \Inc\Core\WCShop\WCShopCollation;
use \Inc\Core\XMLController;

?>

<div id="mrkvuamp-category-matching" class="link-pane">

    <h2>Rozetka Співставлення категорій та генерація XML</h2>
    <p>Додайте ідентифікатори категорій з сайту Rozetka для співставлення</p>

    <div class="mrkvuamp_collation_form_wrap">
        <form id="mrkv_uamrkpl_collation_form" class="mrkv_uamrkpl_collation_form" method="post" action="">
            <?php echo WCShopCollation::get_hierarchical_tree_categories(); ?>
            <input type="hidden" name="action" value="mrkvuamp_collation_action">
            <?php wp_nonce_field( 'mrkv_uamrkpl_collation_form_nonce' ); ?>
            <?php submit_button( __( 'Співставити', 'mrkv-ua-marketplaces'), 'primary', 'mrkvuamp_submit_collation', false ); ?>
            <span style="display:inline;" id="mrkvuamp_loader"> </span>
        </form>
    </div>

    <div class="mrkvuamp_progress_bar hidden">
        <form action="">
            <progress id="mrkvuamp-progress-xml-upload" max="<?php echo \round( $site_total_product_qty * $progBarCoef ); ?>" value="0" style="width:37%;height:5px;"></progress>
            <div class="hidden" id="mrkvuamp_progbar_hidden_msg" style="padding-left: 10px;"></div>
            <input type="hidden" name="mrkvuamp_site_total_product_qty" value="<?php echo sanitize_text_field( $site_total_product_qty ); ?>" />
        </form>
    </div>

</div>
