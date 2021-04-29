<?php

use \Inc\Core\WCShop\WCShopCollation;
use \Inc\Core\XMLController;

if ( ! $this->activated( 'mrkvuamp_rozetka_activation' ) ) return;

$xml = new XMLController( 'rozetka' );
$xml_fileurl = '/uploads/mrkvuamprozetka.xml';

?>

<div class="mrkvuamp_wrap">

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
    <ul class="mrkv-nav-links">
        <li class="active"><a href="#mrkvuamp_main-configuration">Загальні налаштування</a></li>
        <li><a href="#category-matching">Співставлення категорій</a></li>
        <li><a href="#my-orders">Мої замовлення</a></li>
    </ul>

    <div class="mrkv-nav-links-content">
        <div id="mrkvuamp_main-configuration" class="link-pane active">

            <?php // Last xml-file link ?>
                <div class="mrkvuamp_collation_xml_link hidden" >
                    <form action="">
                        <p>Посилання на
                            <a  class="mrkvuamp_xml_link" target="_blank" href="<?php echo content_url() . $xml_fileurl; ?>">останній згенерований xml</a>
                            <?php $xml->last_xml_file_date(); ?>
                        </p>
                    </form>
                </div>
            <?php //endif; ?>

            <form method="post" action="options.php">

                <?php settings_fields( 'mrkv_ua_rozetka_option_group' ); ?>
                <?php do_settings_sections( 'mrkv_ua_marketplaces_rozetka' ); ?>

                <?php submit_button(); ?>

            </form>

        </div>

        <?php require_once( 'rozetka-collation.php' ); ?>

        <div id="my-orders" class="link-pane">
            <h2>Rozetka Мої замовлення</h2>
        </div>

    </div>

</div><!-- /.wrap -->
