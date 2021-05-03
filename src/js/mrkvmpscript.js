window.addEventListener("load", function() {

    // Dashboard tab
    // Show/hide link's content on marketplace's tabs (Rozetka, PromUA, ..., Підтримка):
    // 'Загальні налаштування', 'Співставлення категорій', 'Мої замовлення'
    var mrkvRozetkaNavLinks = document.querySelectorAll("ul.mrkv-nav-links > li");

	for (i = 0; i < mrkvRozetkaNavLinks.length; i++) {
		mrkvRozetkaNavLinks[i].addEventListener("click", mrkvSwitchLink);
	}

	function mrkvSwitchLink(event) {
		event.preventDefault();

		document.querySelector("ul.mrkv-nav-links li.active").classList.remove("active");
		document.querySelector(".link-pane.active").classList.remove("active");

		var clickedLink = event.currentTarget;
		var anchor = event.target;
		var activePaneID = anchor.getAttribute("href");

		clickedLink.classList.add("active");
		document.querySelector(activePaneID).classList.add("active");
	}

}); // window.addEventListener()


jQuery(document).ready(function(){

    // Dashboard tab
    // Copy xml content as string to browser Clipboard with 'Скопіювати' button on Dashboard tab
    if (location.search.indexOf('page=mrkv_ua_marketplaces') !== -1) { // Only Dashboard tab
        jQuery( '.mrkvuamp_xml_link_copy' ).on('click', function(){

            var refFile = "/wp-content/uploads/mrkvuamprozetka.xml"; // path to my txt file
            var fileContent;

            jQuery.get(refFile, function(response) {
                fileContent = response;
                xmlString = (new XMLSerializer()).serializeToString(fileContent); // convert xml Object to String
                var promise = navigator.clipboard.writeText(xmlString); // set promise to write in Clipboard
                var selval = jQuery("input[name=mrkvuamp_clipboard]").val(xmlString).select(); // write to hidden input element

                document.execCommand("copy");
                // Sweetalert2 modal
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Скопійовано!',
                    showConfirmButton: false,
                    timer: 1500
                });
            })
        });

        // Remove checked checkbox fields after its activation on Dashboard tab
        var marketplaces_checked_count = 0; // how many marketplaces was clicked (selected)
        var checkboxes_qty = jQuery('#mrkvuamp-dashboard-form .mrkv_chk').length; // total marketplaces quantity

        jQuery('input[type=checkbox].mrkv_chk:checked').each(function () { // all checkboxes loop
            var status = (this.checked ? jQuery(this).val() : "");
            var id = jQuery(this).attr("id");
            // create css-class e.g. 'rozetka_activation_class' name from checkbox name id 'mrkvuamp_rozetka_activation'
            var prefix = 'mrkvuamp_';
            var checkboxBlock = id.slice(prefix.length) + '_class';

            if (status) { // not show clicked (selected) marketplace
                ++marketplaces_checked_count;
                jQuery('.' + checkboxBlock).css("display", "none");
            }
        });

        // remove Dashboard subtitle and 'Зберегти зміни' button when all checkboxes checked
        if (marketplaces_checked_count == checkboxes_qty) {
            jQuery('.dashboard-subtitle').css("display", "none");
            jQuery('#dashboard_submit').css("display", "none");
        }

    } // Dashboard tab

    // Rozetka tab
    // AJAX  handler of '#mrkv_uamrkpl_collation_form' Form in Rozetka tab
    var protocol = jQuery(location).attr('protocol'); // http or https
    var host = jQuery(location).attr('host'); // example.com
    if (location.search.indexOf('page=mrkv_ua_marketplaces_rozetka') !== -1) { // Only Rozetka tab
        jQuery( '#mrkv_uamrkpl_collation_form' ).on('submit', function(event){
            var $form = jQuery(this);
            var $formData = $form.serialize();

            // Sweetalert2 modal
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'XML-прайс створюється...',
                showConfirmButton: false
            });

            jQuery.ajax({
                url: ajaxurl,
                headers: { 'Clear-Site-Data': "cache" },
                data: $formData,
                cache: false,
                ifModified: true,
                context: document.body,

                success: function( data ) {
                    // Get spinner gif-file data
                    var loaderUrl = protocol + '\/\/' + host + '/wp-content/plugins/ua-marketplace/assets/images/spinner.gif';
                    var image = new Image();
                    image.src = loaderUrl;
                    // Activate spinner and make 'Співставити' button disabled
                    jQuery('#mrkv_uamrkpl_collation_form #mrkvuamp_submit_collation').css({"margin-right":"10px"});
                    jQuery('#mrkv_uamrkpl_collation_form #mrkvuamp_submit_collation').addClass('mrkv_uamrkpl_collation_btn_desabled');
                    jQuery('#mrkvuamp_loader').append(image);
                },

                error: function( data ) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!',
                        timer: 2000
                        // footer: '<a href>Why do I have this issue?</a>'
                    })
                }
                // complete: function( data ) {
                    // // Get spinner gif-file data
                    // var loaderUrl = protocol + '\/\/' + host + '/wp-content/plugins/ua-marketplace/assets/images/spinner.gif';
                    // var image = new Image();
                    // image.src = loaderUrl;
                    // // Activate spinner and make 'Співставити' button disabled
                    // jQuery('#mrkv_uamrkpl_collation_form #mrkvuamp_submit_collation').css({"margin-right":"10px"});
                    // jQuery('#mrkv_uamrkpl_collation_form #mrkvuamp_submit_collation').addClass('mrkv_uamrkpl_collation_btn_desabled');
                    // jQuery('#mrkvuamp_loader').append(image);
                    // console.log('mrkvuamp_collation_form - Good Request!');
                // }
            }); // jQuery.ajax
        }); // on('submit', ...)

        // Remove xml link on 'Rozetka' tab when xml-file is not exists yet
        setTimeout(function() {
            jQuery.ajax({
                url: protocol + '\/\/' + host + '/wp-content/uploads/mrkvuamprozetka.xml',
                headers: { 'Clear-Site-Data': "cache" },
                type:'HEAD',
                cache: false,
                error: function() { //file not exists
                    jQuery('.mrkvuamp_collation_xml_link').addClass('hidden');
                },
                success: function() { //file exists
                    jQuery('.mrkvuamp_collation_xml_link').removeClass('hidden');
                }
            });
        }, 1500);

        // 'Бренди' Settings show/hide functionality
        if ( ( jQuery( '#mrkv_uamrkpl_rozetka_vendor_names' ).val() == 'your_vendor_choice' ) ||
             ( jQuery( '#mrkv_uamrkpl_rozetka_vendor_names' ).val() == 'vendor_pwb_brand' ) ) {
            jQuery( '.mrkv_uamrkpl_rozetka_vendor_by_attributes_class' ).hide();
            jQuery( '.mrkv_uamrkpl_rozetka_vendor_all_possibilities_class' ).hide();
        }
        if ( jQuery( '#mrkv_uamrkpl_rozetka_vendor_names' ).val() == 'vendor_by_attributes' ) {
            // Add 'Атрибути в якості брендів' field
            jQuery( '.mrkv_uamrkpl_rozetka_vendor_all_possibilities_class' ).hide();
            jQuery( '.mrkv_uamrkpl_rozetka_vendor_all_possibilities_class' ).css( ' display', 'none' );
            jQuery( '.mrkv_uamrkpl_rozetka_vendor_by_attributes_class' ).show();
            jQuery( '.mrkv_uamrkpl_rozetka_vendor_by_attributes_class' ).css( ' display', 'block' );
        } else if ( jQuery( '#mrkv_uamrkpl_rozetka_vendor_names' ).val() == 'vendor_all_possibilities' ) {
            // Add 'Всі можливі варіанти' field
            jQuery( '.mrkv_uamrkpl_rozetka_vendor_by_attributes_class' ).hide();
            jQuery( '.mrkv_uamrkpl_rozetka_vendor_by_attributes_class' ).css( ' display', 'none' );
            jQuery( '.mrkv_uamrkpl_rozetka_vendor_all_possibilities_class' ).show();
            jQuery( '.mrkv_uamrkpl_rozetka_vendor_all_possibilities_class' ).css( ' display', 'block' );
        }
        jQuery( '#mrkv_uamrkpl_rozetka_vendor_names' ).on( 'change', (function() {
            if ( ( jQuery( '#mrkv_uamrkpl_rozetka_vendor_names' ).val() == 'your_vendor_choice' ) ||
                 ( jQuery( '#mrkv_uamrkpl_rozetka_vendor_names' ).val() == 'vendor_pwb_brand' ) ) {
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

    } // Rozetka tab


}); // ready()
