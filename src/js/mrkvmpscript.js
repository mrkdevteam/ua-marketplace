window.addEventListener("load", function() {

    // Show/hide link's content on marketplace's tabs (Rozetka, PromUA, ...):
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

// AJAX  handler of mrkv_uamrkpl_collation Form in Rozetka tab
jQuery(document).ready(function(){

    jQuery( '#mrkv_uamrkpl_collation' ).on('submit', function(){
        var $form = jQuery(this);
        var $formData = $form.serialize();
        var protocol = jQuery(location).attr('protocol'); // http or https
        var host = jQuery(location).attr('host'); // example.com

        jQuery.ajax({
            url: ajaxurl,
            data: $formData,

            success: function( data ) {
                var loaderUrl = protocol + '\/\/' + host + '/wp-content/plugins/ua-marketplace/assets/images/spinner.gif';
                var image = new Image();
                image.src = loaderUrl;
                jQuery('.mrkv_uamrkpl_collation input.button-primary').css({"margin-right":"10px"});
                jQuery('#mrkv_uamrkpl_collation #mrkvuamp_submit_collation').addClass('mrkv_uamrkpl_collation_desabled');
                jQuery('#mrkvuamp_loader').append(image);
                console.log('mrkvuamp_collation_form - Good Request!');
            }
        });
    }); // on('submit', ...)

}); // ready()
