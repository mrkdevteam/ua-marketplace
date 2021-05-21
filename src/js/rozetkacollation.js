jQuery(document).ready(function(){

    // Rozetka tab
    // AJAX  handler of '#mrkv_uamrkpl_collation_form' Form in Rozetka tab
    var protocol = jQuery(location).attr('protocol'); // http or https
    var host = jQuery(location).attr('host'); // example.com
    if (location.search.indexOf('page=mrkv_ua_marketplaces_rozetka') !== -1) { // Only Rozetka tab
        jQuery( '#mrkv_uamrkpl_collation_form' ).on('submit', async function(event) {
            collateAndCreateXml(); // Collate and create XML

            async function collateAndCreateXml() {
                var $form = jQuery( '#mrkv_uamrkpl_collation_form' );
                var $formData = $form.serialize();
                try {
                    let a = await collateCategories($form, $formData);
                    let b = await SweetAlert2Resolve();
                    let c = await showSpinner();
                    let cc = await removeHiddenLink();
                } catch(err) {
                    let d = await SweetAlert2Reject(err);
                    let e = await functionTimeOut();
                }
            }

            // Collate WC categories with marketplace categories
            async function collateCategories($form, $formData) {
                jQuery.ajax({
                    url: ajaxurl,
                    headers: { 'Clear-Site-Data': "cache" },
                    data: $formData,
                    cache: false,
                    ifModified: true
                });
            }

            // Sweetalert2 success modal
            async function SweetAlert2Resolve() {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'XML-прайс створюється...',
                    showConfirmButton: false,
                    timer: 2000,
                    allowOutsideClick: false
                })
            }

            // Show spinner beside 'Співставити' button
            async function showSpinner() {
                // var protocol = jQuery(location).attr('protocol'); // http or https
                // var host = jQuery(location).attr('host'); // example.com
                // Get spinner gif-file data
                var loaderUrl = protocol + '\/\/' + host + '/wp-content/plugins/ua-marketplace/assets/images/spinner.gif';
                var image = new Image();
                image.src = loaderUrl;
                // Activate spinner and make 'Співставити' button disabled
                jQuery('#mrkv_uamrkpl_collation_form #mrkvuamp_submit_collation').css({"margin-right":"10px"});
                jQuery('#mrkv_uamrkpl_collation_form #mrkvuamp_submit_collation').addClass('mrkv_uamrkpl_collation_btn_desabled');
                jQuery('#mrkvuamp_loader').append(image);
            }

            // Sweetalert2 error modal
            async function SweetAlert2Reject(err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: err,
                    timer: 2000
                })
            }

        }); // on('submit', ...)

        // Remove xml link on 'Rozetka' tab when xml-file is not exists yet
        removeHiddenLink();
        async function removeHiddenLink() {
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
        }

    } // Rozetka tab

}); // ready()
