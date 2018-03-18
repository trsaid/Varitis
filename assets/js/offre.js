$(document).ready(function() {
	
	// Affichage form d'offre.
	$(".offre_button").click(function() {
			$(".offre-form").fadeToggle(300);
			$(".offre_button").prop("disabled", true);
			
			$('html, body').animate({
				scrollTop: $(".offre-form").offset().top
			}, 1000)
			return false;
	});
	
	$('.offre-form').on('submit', function(event) {
		event.preventDefault();
		if ($('.prix-offre').val() != '' && $('.message-offre').val() != '') {
			var offre_data = $(this).serialize();
			var id_ann = $(".prix-offre").attr("data-ann-id");
			var error = '';
			$.ajax({
				url: "offre-form-ajax.php",
				method: "POST",
				data: offre_data + "&id_ann=" + id_ann ,
				success: function(data) {
					if (data == 'log'){
						$(".offreErr").addClass("alert alert-danger");
						error = '<strong>Erreur : </strong> Vous devez être connecté pour faire une offre.';
					}else if(data == 'error'){
						$(".offreErr").addClass("alert alert-danger");
						error = '<strong>Erreur : </strong> Vous ne pouvez pas faire une offre à votre propre annonce.';
					}else{
						$(".offreErr").addClass("alert alert-success");
						error = 'Votre offre a été envoyée.';
					}
					$(".offreErr").html(error);
					$('.offre-form')[0].reset();
				}
			});
		}
	});
	
	
	// Nombres selement
	
	$(".prix-offre").keydown(function (e) {
		// Allow: backspace, delete, tab, escape, enter and .
		if ($.inArray(e.keyCode, [46, 8, 9, 27, 13]) !== -1 ||
			// Allow: Ctrl+A
			(e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
			// Allow: Ctrl+C
			(e.keyCode == 67 && (e.ctrlKey === true || e.metaKey === true)) ||
			// Allow: Ctrl+X
			(e.keyCode == 88 && (e.ctrlKey === true || e.metaKey === true)) ||
			// Allow: home, end, left, right
			(e.keyCode >= 35 && e.keyCode <= 39) ||
			//Allow numbers and numbers + shift key
			((e.shiftKey && (e.keyCode >= 48 && e.keyCode <= 57)) || (e.keyCode >= 96 && e.keyCode <= 105))) {
			// let it happen, don't do anything
			return;
		}
		// Ensure that it is a number and stop the keypress
		if ((!e.shiftKey && (e.keyCode < 48 || e.keyCode > 57)) || (e.keyCode < 96 || e.keyCode > 105)) {
			e.preventDefault();
		}
	});
	
});