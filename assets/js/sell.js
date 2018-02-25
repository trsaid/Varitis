"use strict";

function submitForm() {
	var data = $("#sellpost").serialize();
	var token = $(".upload-zone").attr("data-up-token");
	data = data + "&uptoken=" + token;
	$.ajax({
		type: 'POST',
		url: 'sell-data.php',
		data: data,
		dataType: "json",
		cache: false,
		success: function(data) {
			var err = '<div class="alert alert-danger"><strong>Erreur : </strong> ' + data[1] + '</div>';
			var val = '<div class="alert alert-success"><strong>Parfait ! </strong> ' + data[1] + '</div>';
			if (data[0] > 0 && data[0] < 6) {
				$("#result").fadeIn(1000, function() {
					$("#result").html(err);
					$("#btn-sell-submit").val('Valider mon annonce');
				});
			} else if (data[0] == "6") {
				$("#result").fadeIn(1000, function() {
					$("#result").html(val);
					$("#btn-sell-submit").val('Valider mon annonce');
					setTimeout(function() {
						window.location.href = "buy.php?ann=" + data[2];
					}, 1000);
				});
			} else {
				$("#result").fadeIn(1000, function() {
					$("#result").html('Une erreur est survenue...');
					$("#btn-sell-submit").val('Valider mon annonce');
				});
			}
		}
	});
	return false;
}