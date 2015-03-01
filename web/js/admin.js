$.featherlight.defaults.autostart = false;
$('img').each(function() {
	var bigImage = $(this).attr('src').replace(/\.jpg/, '.600.jpg');
	$(this).attr('data-featherlight', bigImage);
}).featherlight(null, {
	
});
