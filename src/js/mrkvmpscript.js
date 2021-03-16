window.addEventListener("load", function() {

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

});
