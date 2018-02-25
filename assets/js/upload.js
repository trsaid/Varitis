// Changement d'image quand le curseur est au dessus
$('.upload-zone').hover(function(e) {
	$(".upload-img").attr("src", "./assets/images/upload-hover.png");
}, function(e) {
	$(".upload-img").attr("src", "./assets/images/upload.png");
});
// drag and drop
$(document).on('dragenter', '.upload-zone', function() {
	$(this).css('border', '4px dashed #25a5c4');
	return false;
});
$(document).on('dragover', '.upload-zone', function(e) {
	e.preventDefault();
	e.stopPropagation();
	$(this).css('border', '4px dashed #25a5c4');
	return false;
});
$(document).on('dragleave', '.upload-zone', function(e) {
	e.preventDefault();
	e.stopPropagation();
	$(this).css('border', '4px dashed #BBBBBB');
	return false;
});
$(document).on('drop', '.upload-zone', function(e) {
	// alert(e.originalEvent.dataTransfer.files[0].name);
	if (e.originalEvent.dataTransfer) {
		if (e.originalEvent.dataTransfer.files.length) {
			var token = $(this).attr("data-up-token");
			// Stop the propagation of the event
			e.preventDefault();
			e.stopPropagation();
			$(this).css('border', '4px dashed green');
			// Main function to upload
			var image = e.originalEvent.dataTransfer.files;
			createFormData(image, token);
		}
	} else {
		$(this).css('border', '4px dashed #BBBBBB');
	}
	return false;
});
$(document).ready(function() {
	$('input[type="file"]').change(function(e) {
		var token = $('.upload-zone').attr("data-up-token");
		e.preventDefault();
		e.stopPropagation();
		var image = e.target.files;
		createFormData(image, token);
	});
});

function createFormData(image, token) {
	var formImage = new FormData();
	formImage.append('dropImage', image[0]);
	formImage.append('uptoken', token);
	uploadFormData(formImage);
}

function uploadFormData(formData) {
	$.ajax({
		url: "upload.php",
		type: "POST",
		data: formData,
		contentType: false,
		cache: false,
		processData: false,
		dataType:"json",
		success: function(reponse) {
			var error = '';
			if (reponse == 'Error') {
				error = '<div class="alert alert-danger" id="imgErr"><strong>Erreur : </strong> Seules les images au format PNG et JPEG sont acceptées</div>'
			} else if (reponse == 'Error2') {
				error = '<div class="alert alert-danger" id="imgErr"><strong>Erreur : </strong>Vous ne pouvez utilisé plus de 3 images</div>'
			} else {
				var imagePreview = '';
				imagePreview += '<div class="col-sm-2 uploaded-img" data-up-id="'+ reponse[1] +'">';
				imagePreview += '<img src="uploads/' + reponse[0] + '" class="uploaded-img-show" />';
				imagePreview += '<i class="far fa-trash-alt uploaded-del" onclick="delImg('+ reponse[1] +')"></i>';
				imagePreview += '<i class="fas fa-search-plus uploaded-zoom"></i>';
				imagePreview += '</div>';
				$('#upload-zone').append(imagePreview);
				$("#imgErr").remove();
			}
			$("#upload-zone").after($(error));
		}
	});
}

function delImg(img) {
	swal({
		title: "Êtes-vous sûr ?",
		text: "Êtes-vous sûr de supprimer cette image?",
		icon: "warning",
		buttons: true,
		dangerMode: true,
	}).then((willDelete) => {
		if (willDelete) {
			$.ajax({
				url: "upload.php",
				type: "POST",
				data: {
					'delimg': + img
				},
				success: function(reponse) {
					if(reponse == 1){
						$("div").find("[data-up-id='" + img + "']").remove();
						swal("Votre image a été supprimé.", {
							icon: "success",
						});
					}else{
						swal("Une erreur est survenue lors de la requette...", {
							icon: "error",
						});
					}
				}
			});
		}
	});
}