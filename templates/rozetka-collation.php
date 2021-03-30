<?php

use \Inc\ExternalApi\WoocommerceApi;
use \Inc\WCShop\WCShop;
use \Inc\Base\XMLController;

    // Create internet-shop Object
    $mrkv_uamrkpl_shop = new WCShop('shop');
    $mrkv_uamrkpl_shop_arr = (array) $mrkv_uamrkpl_shop;

    // Create XML-price
    $convert = new XMLController();
    $xml = $convert->array2xml( $mrkv_uamrkpl_shop_arr );

?>

<div id="category-matching" class="link-pane">
    <h2>Rozetka Співставлення категорій</h2>
    <div>
        <?php // Show internet-shop categories for collation ?>
        <?php echo WCSHOP::get_hierarchical_tree_categories(); ?>
    </div>

    <p>Посилання на
        <a  id="mrkvuamp_xml_link" target="_blank" href="<?php echo content_url(); ?>/uploads/mrkvuamprozetka.xml">останній згенерований xml</a>
        (<?php echo date('m/d/Y H:i', filemtime(WP_CONTENT_DIR.'/uploads/mrkvuamprozetka.xml')); clearstatcache(); ?> UTC)
    </p>

</div>
