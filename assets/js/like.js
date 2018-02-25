function cwRating(id,userId){
	$.ajax({
		url		: 'rating.php',
		type	: 'POST',
		data	: 'id=' + id + '&userId=' + userId,
		dataType: "json",
		success : function(msg){
				if(msg[1]){
					$(".likeBtn"+id).addClass("active").addClass("fas").removeClass("far");
					$.notify(
						msg[0],
						{
							 position: 'bottom right',
							 className: 'success'
						}
					);
				}
				else{
					$(".likeBtn"+id).removeClass("active").addClass("far").removeClass("fas");
					$.notify(
						msg[0],
						{
							 position: 'bottom right'
						}
					);
				}
		}
	});
}