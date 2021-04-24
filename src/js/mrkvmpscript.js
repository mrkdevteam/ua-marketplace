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
        jQuery( '#mrkvuamp_xml_link_copy' ).on('click', function(){

            var refFile = "/wp-content/uploads/mrkvuamprozetka.xml"; // path to my txt file
            var fileContent;

            jQuery.get(refFile, function(response) {
                fileContent = response;
                xmlString = (new XMLSerializer()).serializeToString(fileContent); // convert xml Object to String
                var promise = navigator.clipboard.writeText(xmlString); // set promise to write in Clipboard
                var selval = jQuery("input[name=mrkvuamp_clipboard]").val(xmlString).select(); // write to hidden input element
                console.log(selval);
                document.execCommand("copy");
            })
        });

        // Remove checked checkbox fields after its activation on Dashboard tab
        var marketplaces_count = 0;
        jQuery('input[type=checkbox]:checked').each(function () { // all checkboxes loop
            var status = (this.checked ? jQuery(this).val() : "");
            var id = jQuery(this).attr("id");
            // create css-class 'rozetka_activation_class' name from checkbox id 'mrkvuamp_promua_activation' name
            var prefix = 'mrkvuamp_';
            var checkboxBlock = id.slice(prefix.length) + '_class';
            if (status) { // remove Dashboard subtitle when all checkboxes checked
                marketplaces_count++;
                jQuery('.' + checkboxBlock).css("display", "none");
                if (marketplaces_count > 1) {
                    jQuery('.dashboard-subtitle').css("display", "none");
                }
            }
        });
    } // Dashboard tab

    // Rozetka tab
    // AJAX  handler of mrkv_uamrkpl_collation Form in Rozetka tab
    if (location.search.indexOf('page=mrkv_ua_marketplaces_rozetka') !== -1) { // Only Rozetka tab
        jQuery( '#mrkv_uamrkpl_collation' ).on('submit', function(){
            var $form = jQuery(this);
            var $formData = $form.serialize();
            var protocol = jQuery(location).attr('protocol'); // http or https
            var host = jQuery(location).attr('host'); // example.com

            jQuery.ajax({
                url: ajaxurl,
                data: $formData,
                cache: false,
                ifModified: true,

                success: function( data ) {
                    var loaderUrl = protocol + '\/\/' + host + '/wp-content/plugins/ua-marketplace/assets/images/spinner.gif';
                    var image = new Image();
                    image.src = loaderUrl;
                    // Activate spinner and make 'Співставити' button disabled
                    jQuery('.mrkv_uamrkpl_collation input.button-primary').css({"margin-right":"10px"});
                    jQuery('#mrkv_uamrkpl_collation #mrkvuamp_submit_collation').addClass('mrkv_uamrkpl_collation_desabled');
                    jQuery('#mrkvuamp_loader').append(image);
                    console.log('mrkvuamp_collation_form - Good Request!');
                    alert("XML-прайс створено успішно!");
                }
            });
        }); // on('submit', ...)
    } // Rozetka tab


}); // ready()
