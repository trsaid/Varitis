$(document).ready(function() {
	function load_unseen_notification(view = '') {
		$.ajax({
			url: "notif.php",
			method: "POST",
			data: {
				view: view
			},
			dataType: "json",
			success: function(data) {
				// $('.dropdown-menu').html(data.notification);
				var nbr = data.notification["nbr"];
				var notif_list = '';
				for (var i = 1; i <= nbr; i++) {
					notif_list += '<li>';
					notif_list += '<a class="dropdown-item" href="#">' + data.notification["id_offreur" + i] + '</br>';
					notif_list += data.notification["titre_ann" + i] + '</br>';
					notif_list += '<small><i class="far fa-clock"></i>  ' + data.notification["date_offre" + i] + '</small></br>';
					notif_list += '</a>';
					notif_list += '</li>';
					notif_list += '<div class="dropdown-divider"></div>';
				}
				$('.notifications').html(notif_list);
				if (data.unseen_notification > 0) {
					$('#notif-count').addClass('fa-layers-counter fa-lg notif-count');
					$('#notif-count').html(data.unseen_notification);
				}
			}
		});
	}
	load_unseen_notification();
	$(".notif-lu").click(function() {
		$('.notif-count').html('');
		$('#notif-count').removeClass('fa-layers-counter fa-lg notif-count');
		load_unseen_notification('lu'); // Marquer comme lu
		$("#notificationContainer").hide(); // Fermer la liste
	});
	// Actualisation toute les 5 secondes
	setInterval(function() {
		load_unseen_notification();;
	}, 20000);
	// Ouverture de la liste des notif
	$(".notif").click(function() {
		$("#notificationContainer").fadeToggle(300);
		$("#notification_count").fadeOut("slow");
		return false;
	});
	//Document Click hiding the popup 
	$(document).click(function() {
		$("#notificationContainer").hide();
	});
	//Popup on click
	$("#notificationContainer").click(function() {
		return false;
	});
});