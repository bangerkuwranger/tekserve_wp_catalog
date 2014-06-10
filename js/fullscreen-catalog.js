jQuery(document).ready( function($) {
	if ( document.fullscreenEnabled || document.webkitFullscreenEnabled || document.mozFullScreenEnabled || document.msFullscreenEnabled ) {
		var catalog = document.getElementById("zoom-viewport");
		var $fstoggle = $("#fullscreen-toggle");
		if (catalog.requestFullscreen) {
			$(fsToggle).click( function(){
				if ($(catalog).hasClass("isFullScreen")) {
					$(catalog).removeClass("isFullScreen");
					$($fstoggle).text("");
					document.exitFullscreen();
				}
				else {
					$(catalog).addClass("isFullScreen");
					$($fstoggle).text("");
					catalog.requestFullscreen();
				}
			});
		}
		else if (catalog.msRequestFullscreen) {
			$($fstoggle).click( function(){
				if ($(catalog).hasClass("isFullScreen")) {
					$(catalog).removeClass("isFullScreen");
					$($fstoggle).text("");
					document.msExitFullscreen();
				}
				else {
					$(catalog).addClass("isFullScreen");
					$($fstoggle).text("");
					catalog.msRequestFullscreen();
				}
			});
		}
		else if (catalog.mozRequestFullScreen) {
			$($fstoggle).click( function(){
				if ($(catalog).hasClass("isFullScreen")) {
					$(catalog).removeClass("isFullScreen");
					$($fstoggle).text("");
					document.mozExitFullscreen();
				}
				else {
					$(catalog).addClass("isFullScreen");
					$($fstoggle).text("");
					catalog.mozRequestFullscreen();
				}
			});
		}
		else if (catalog.webkitRequestFullscreen) {
			$($fstoggle).click( function(){
				if ($(catalog).hasClass("isFullScreen")) {
					$(catalog).removeClass("isFullScreen");
					$($fstoggle).text("");
					document.webkitExitFullscreen();
				}
				else {
					$(catalog).addClass("isFullScreen");
					$($fstoggle).text("");
					catalog.webkitRequestFullscreen();
				}
			});
		}
	}
});