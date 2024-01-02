<?php

use \Inc\Core\WCShop\WCShopCollation;
use \Inc\Core\XMLController;
use \Inc\Core\WCShopController;

if ( ! $this->activated( 'mrkvuamp_rozetka_activation' ) ) return;

$xml = new XMLController( 'rozetka' ); // Get xml-file URL and plugin uploads dir path
$xml_fileurl = $xml->plugin_uploads_dir_url . $xml->plugin_uploads_rozetka_xmlname;
$plugin_uploads_dir_path = $xml->plugin_uploads_dir_path;
$site_total_product_qty = $xml->site_total_product_qty;

?>

<div class="wrap">

    <h2><?php echo $this->plugin_name['name']; ?></h2>
    <?php settings_errors(); ?>

    <?php
        $default_tab = null;
        $tab = isset($_GET['page']) ? $_GET['page'] : $default_tab;
        $option = get_option('mrkv_ua_marketplaces');
        $rozetka_activated = isset( $option['mrkvuamp_rozetka_activation'] ) ? $option['mrkvuamp_rozetka_activation'] : false;
        $promua_activated = isset( $option['mrkvuamp_promua_activation'] ) ? $option['mrkvuamp_promua_activation'] : false;
    ?>

    <?php // Tabs ?>
    <nav class="nav-tab-wrapper">
      <a href="?page=mrkv_ua_marketplaces" class="nav-tab <?php if( 'mrkv_ua_marketplaces' == $tab ):?>nav-tab-active<?php endif; ?>">Dashboard</a>
      <?php
          if ( $rozetka_activated ) { ?>
              <a href="?page=mrkv_ua_marketplaces_rozetka" class="nav-tab <?php if( 'mrkv_ua_marketplaces_rozetka' == $tab ):?>nav-tab-active<?php endif; ?>">Rozetka</a>
          <?php }
          if ( $promua_activated ) { ?>
              <a href="?page=mrkv_ua_marketplaces_promua" class="nav-tab <?php if( 'mrkv_ua_marketplaces_promua' == $tab ):?>nav-tab-active<?php endif; ?>">PromUA</a>
          <?php }
       ?>
      <a href="?page=mrkv_ua_marketplaces_support" class="nav-tab <?php if( 'mrkv_ua_marketplaces_support' == $tab ):?>nav-tab-active<?php endif; ?>">Підтримка</a>
    </nav>

    <?php // Links ?>
    <ul class="mrkvuamp-nav-links">
        <li class="active"><a href="#mrkvuamp_main-configuration">Загальні налаштування</a></li>
        <li><a href="#mrkvuamp-category-matching">Співставлення категорій</a></li>
        <li><a href="#mrkvuamp-my-orders">Мої замовлення</a></li>
    </ul>

    <div class="mrkvuamp-nav-links-content">

        <?php // Загальні налаштування link ?>
        <div id="mrkvuamp_main-configuration" class="link-pane active">

            <?php // Last xml-file link ?>
            <div class="mrkvuamp_collation_xml_link" >
                <form action="">
                    <p>Посилання на
                        <a  class="mrkvuamp_xml_link" target="_blank" href="<?php clearstatcache(); echo esc_url( $xml_fileurl ); clearstatcache(); ?>">останній згенерований xml</a>
                            <?php $xml->last_xml_file_date();
                                $xml_file_path = $plugin_uploads_dir_path . $xml->plugin_uploads_rozetka_xmlname;
                                $xml_file_size = ( file_exists( $xml_file_path ) ) ? filesize( $xml_file_path ) : '';
                                $progBarCoef = ( $site_total_product_qty < 100 ) ? 0.9 : 2;
                            ?>
                            <input type="hidden" name="mrkvuamp_xml_file_path" value="<?php echo sanitize_text_field( $xml_file_path ); ?>" />
                            <input type="hidden" name="mrkvuamp_xml_file_size" value="<?php echo sanitize_text_field( $xml_file_size ); ?>" />
                    </p>
                </form>
            </div>

            <form method="post" action="options.php">

                <?php settings_fields( 'mrkv_ua_rozetka_option_group' ); ?>
                <?php do_settings_sections( 'mrkv_ua_marketplaces_rozetka' ); ?>

                <?php submit_button(); ?>

            </form>

        </div>

        <?php // Співставлення категорій link ?>
        <?php require_once( 'rozetka-collation.php' ); ?>

        <div id="mrkvuamp-my-orders" class="link-pane">
            <h2>Rozetka Мої замовлення</h2>
            <p>Доступно лише у Про-версії. Детальніше:
                <a target="_blank" href="https://morkva.co.ua/shop-2/ua-marketplaces-woocommerce-plugin"> дивіться тут</a></p>
        </div>

    </div>

</div><!-- /.wrap -->
