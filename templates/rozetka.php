<?php
if ( ! $this->activated( 'mrkvuamp_rozetka_activation' ) ) return;
 ?>

<?php require_once( 'dashboard-header.php' ); ?>

        <?php settings_fields( 'mrkv_ua_rozetka_option_group' ); ?>
        <?php do_settings_sections( 'mrkv_ua_marketplaces_rozetka' ); ?>

        <?php submit_button(); ?>

<?php require_once( 'dashboard-footer.php' ); ?>
