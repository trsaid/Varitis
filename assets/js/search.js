$(document).ready(function(){

	$('.search-bar input[type="text"]').on("keyup input", function(){


		var txt = $(this).val();

		var resultDropdown = $(".result-search");

		if(txt.length){

			$.get("search-ajax.php", {reponse: txt}).done(function(data){
				resultDropdown.html(data); // On affiche le résultat.
			});

		} else{

			resultDropdown.empty();

		}

	});

	$(document).on("click", ".result-search p", function(){

		$(this).parents(".search-bar").find('input[type="text"]').val($(this).text());

		$(".result-search").empty();

	});

});