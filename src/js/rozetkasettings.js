if (location.search.indexOf('page=mrkv_ua_marketplaces_rozetka') !== -1) { // Only Rozetka tab
    jQuery(document).ready(function(){

        // 'Бренди' Settings show/hide functionality
        if ( ( jQuery( '#mrkv_uamrkpl_rozetka_custom_vendor' ).val() == 'your_vendor_choice' ) ||
             ( jQuery( '#mrkv_uamrkpl_rozetka_custom_vendor' ).val() == 'vendor_pwb_brand' ) ) {
            jQuery( '.mrkv_uamrkpl_rozetka_vendor_by_attributes_class' ).hide();
            jQuery( '.mrkv_uamrkpl_rozetka_vendor_all_possibilities_class' ).hide();
        }
        if ( jQuery( '#mrkv_uamrkpl_rozetka_custom_vendor' ).val() == 'vendor_by_attributes' ) {
            // Add 'Атрибути в якості брендів' field
            jQuery( '.mrkv_uamrkpl_rozetka_vendor_all_possibilities_class' ).hide();
            jQuery( '.mrkv_uamrkpl_rozetka_vendor_all_possibilities_class' ).css( ' display', 'none' );
            jQuery( '.mrkv_uamrkpl_rozetka_vendor_by_attributes_class' ).show();
            jQuery( '.mrkv_uamrkpl_rozetka_vendor_by_attributes_class' ).css( ' display', 'block' );
        } else if ( jQuery( '#mrkv_uamrkpl_rozetka_custom_vendor' ).val() == 'vendor_all_possibilities' ) {
            // Add 'Метадані в якості брендів' field
            jQuery( '.mrkv_uamrkpl_rozetka_vendor_by_attributes_class' ).hide();
            jQuery( '.mrkv_uamrkpl_rozetka_vendor_by_attributes_class' ).css( ' display', 'none' );
            jQuery( '.mrkv_uamrkpl_rozetka_vendor_all_possibilities_class' ).show();
            jQuery( '.mrkv_uamrkpl_rozetka_vendor_all_possibilities_class' ).css( ' display', 'block' );
        }
        jQuery( '#mrkv_uamrkpl_rozetka_custom_vendor' ).on( 'change', (function() {
            if ( ( jQuery( '#mrkv_uamrkpl_rozetka_custom_vendor' ).val() == 'your_vendor_choice' ) ||
                 ( jQuery( '#mrkv_uamrkpl_rozetka_custom_vendor' ).val() == 'vendor_pwb_brand' ) ) {
                jQuery( '.mrkv_uamrkpl_rozetka_vendor_by_attributes_class' ).hide();
                jQuery( '.mrkv_uamrkpl_rozetka_vendor_all_possibilities_class' ).hide();
            }
            if ( jQuery( this ).val() == 'vendor_by_attributes' ) {
                jQuery( '.mrkv_uamrkpl_rozetka_vendor_by_attributes_class' ).fadeIn(500);
                jQuery( '.mrkv_uamrkpl_rozetka_vendor_all_possibilities_class' ).fadeOut(500);
            } else if ( jQuery( this ).val() == 'vendor_all_possibilities' ) {
                jQuery( '.mrkv_uamrkpl_rozetka_vendor_by_attributes_class' ).fadeOut(500);
                jQuery( '.mrkv_uamrkpl_rozetka_vendor_all_possibilities_class' ).fadeIn(500);
            }
        }));

    }); // ready()

} // Rozetka tab
