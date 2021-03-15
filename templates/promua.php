<?php require_once( 'dashboard-header.php' ); ?>

        <?php settings_fields( 'mrkv_ua_marketplaces_option_groupn' ); ?>
        <?php //settings_fields( 'mrkvuamp_activation_section' ); ?>
        <?php //settings_fields( 'mrkv_ua_marketplaces_settings' ); ?>
        <?php do_settings_sections( 'mrkv_ua_marketplaces' ); ?>

        <?php //submit_button(); ?>

<?php require_once( 'dashboard-footer.php' ); ?>
