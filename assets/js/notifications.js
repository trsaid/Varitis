	function load_unseen_notification(notif = '') {
		$.ajax({
			url: "notif.php",
			method: "POST",
			data: {
				notif: notif
			},
			dataType: "json",
			success: function(data) {
				// $('.dropdown-menu').html(data.notification);
				var nbr = data.notification["nbr"];
				var notif_list = '';
				var id_ann = '';
				var id_offreur = '';
				var titre_ann = '';
				var date_offre = '';
				for (var i = 1; i <= nbr; i++) {
					id_ann = data.notification["id_ann" + i];
					id_offreur = data.notification["id_offreur" + i];
					titre_ann = data.notification["titre_ann" + i];
					date_offre = data.notification["date_offre" + i];
					id_off = data.notification["id_off" + i];
					
					notif_list += '<li>';
					if (data.notification["status" + i] == 0){
						notif_list += '<a class="dropdown-item non_lu" href="buy.php?ann='+id_ann+'" onclick="load_unseen_notification('+id_off+')" >' + id_offreur;
					}else{
						notif_list += '<a class="dropdown-item" onclick="load_unseen_notification('+id_off+')" href="buy.php?ann='+id_ann+'">' + id_offreur;
					}
					notif_list += '<div class="notif_ann_titre">'+ titre_ann + '</div>';
					notif_list += '<small style="float:right">' + date_offre + '</small></br>';
					notif_list += '</a>';
					notif_list += '</li>';
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
		load_unseen_notification('non_lu_all'); // Marquer comme lu
		$("#notificationContainer").hide(); // Fermer la liste
	});
	// Actualisation toute les 20 secondes
	setInterval(function() {
		load_unseen_notification();
	}, 20000);
	// Ouverture de la liste des notif
	$(".notif").click(function() {
		load_unseen_notification();
		$("#notificationContainer").fadeToggle(300);
		$("#notification_count").fadeOut("slow");
		return false;
	});
	// Fermeture
	$(document).click(function() {
		$("#notificationContainer").hide();
	});