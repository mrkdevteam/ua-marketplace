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
        // var $formAction = jQuery('#mrkv_uamrkpl_collation input[name=action]').val();
        var $formData = $form.serialize();
        // var today = new Date();
        // var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
        // var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
        // var $dateTime = date+' '+time;
        // console.log($form.serialize());

        jQuery.ajax({
            // url: '/wp-admin/admin-ajax.php',
            url: ajaxurl,
            // data: $formData + '&currentDateTime=' + $dateTime,
            data: $formData,

            success: function( data ) {
                console.log('mrkvuamp_collation_form - Good Request!');
            }
        });
    }); // on('submit', ...)

}); // ready()
