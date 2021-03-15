<?php require_once( 'dashboard-header.php' ); ?>

        <?php settings_fields( 'mrkv_ua_marketplaces_option_group' ); ?>
        <?php do_settings_sections( 'mrkv_ua_marketplaces' ); ?>

        <?php // Show marketplace panels ?>
        <?php $activation_options_name = get_option( 'mrkv_ua_marketplaces'); ?>
        <?php foreach ( $activation_options_name as $key => $value ): ?>
            <?php if ( $value ) : ?>
                <div class="form-table mrkv_uamrkpl_panel">
                    <?php $marketplace = $this->activations[$key]; ?>
                    <h2><?php echo $marketplace ?></h2>
                    <div class="nav">
                        <button onclick="location.href='?page=mrkv_ua_marketplaces_<?php echo strtolower($marketplace); ?>'" type="button" class="btn">
                          <span class="">Налаштувати</span>
                        </button>
                    </div>
                </div>
                <div class="blank-block"></div>
            <?php endif; ?>
        <?php endforeach; ?>

    <?php submit_button(); ?>

<?php require_once( 'dashboard-footer.php' ); ?>
