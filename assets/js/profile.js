$(document).ready(function() {
	var navItems = $('.admin-menu li > a');
	var navListItems = $('.admin-menu li > a');
	var allWells = $('.admin-content');
	var allWellsExceptFirst = $('.admin-content:not(:first)');
	allWellsExceptFirst.hide();
	navItems.click(function(e) {
		e.preventDefault();
		navListItems.removeClass('active');
		$(this).closest('a').addClass('active');
		allWells.hide();
		var target = $(this).attr('data-target-id');
		$('#' + target).show();
	});
});