$(document).ready(function() {
	$(".offre_button").click(function() {
			$(".offre-form").fadeToggle(300);
			$(".offre_button").prop("disabled", true);
			
			$('html, body').animate({
				scrollTop: $("div.offre-form").offset().top
			}, 1000)
			return false;
	});
});