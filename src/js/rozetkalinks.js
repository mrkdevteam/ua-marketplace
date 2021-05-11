if (location.search.indexOf('page=mrkv_ua_marketplaces_rozetka') !== -1) { // Only Rozetka tab
    window.addEventListener("load", function() {

        // Rozetka links
        // Show/hide link's content on Rozetka's tab: 'Загальні налаштування', 'Співставлення категорій', 'Мої замовлення'
        var mrkvRozetkaNavLinks = document.querySelectorAll("ul.mrkvuamp-nav-links > li");

    	for (i = 0; i < mrkvRozetkaNavLinks.length; i++) {
    		mrkvRozetkaNavLinks[i].addEventListener("click", mrkvSwitchLink);
    	}

    	function mrkvSwitchLink(event) {
    		event.preventDefault();

    		document.querySelector("ul.mrkvuamp-nav-links li.active").classList.remove("active");
    		document.querySelector(".link-pane.active").classList.remove("active");

    		var clickedLink = event.currentTarget;
    		var anchor = event.target;
    		var activePaneID = anchor.getAttribute("href");

    		clickedLink.classList.add("active");
    		document.querySelector(activePaneID).classList.add("active");
    	}

    }); // window.addEventListener()

} // Rozetka tab
