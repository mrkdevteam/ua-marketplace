<?php

use \Inc\Core\XMLController;

require_once( 'dashboard-header.php' );

?>

        <?php settings_fields( 'mrkv_ua_marketplaces_option_group' ); ?>
        <?php do_settings_sections( 'mrkv_ua_marketplaces' ); ?>
        <?php submit_button( null, 'primary', 'dashboard_submit', false ); ?>

        <?php // Show marketplace panels ?>
        <?php $activation_options_name = get_option( 'mrkv_ua_marketplaces'); ?>
        <?php foreach ( $activation_options_name as $key => $value ): ?>
            <?php if ( $value ) : ?>
                <div class="form-table mrkv_uamrkpl_panel">
                    <?php $marketplace = $this->activations[$key]; ?>
                    <h2><?php echo $marketplace ?></h2>
                    <?php
                        $xml = new XMLController( strtolower( $marketplace ) );
                        $xml_fileurl = '/uploads/mrkvuamp' . strtolower( $marketplace ) . '.xml';
                    ?>
                    <?php if ( file_exists( $xml->xml_filepath ) ) : // Last xml-file link ?>
                        <a class="mrkvuamp_xml_link" target="_blank" href="<?php echo content_url() . $xml_fileurl; ?>">останній згенерований xml</a>
                        <div><?php $xml->last_xml_file_date(); ?></div>
                        <span class="mrkvuamp_xml_link_copy button">Скопіювати</span>
                    <?php else : ?>
                        <div class="nav">
                            <button onclick="location.href='?page=mrkv_ua_marketplaces_<?php echo strtolower($marketplace); ?>'" type="button" class="btn">
                              <span class="">Налаштувати</span>
                            </button>
                        </div>
                    <?php endif ?>

                </div>
            <?php endif; ?>
        <?php endforeach; ?>

<?php require_once( 'dashboard-footer.php' ); ?>
