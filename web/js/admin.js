$.featherlight.defaults.autostart = false;
$('img').each(function() {
	var src = $(this).attr('src').replace(/\.tif/, '.png');
	if ($(this).attr('src') != src) {
		$(this).attr('src', src);
	}
	var bigImage = src.replace(/\.(jpg|png)/, '.1000.$1');
	$(this).attr('data-featherlight', bigImage);
}).featherlight(null, {
	
});

$('#book_title').on('change', function() {
	var $input = $(this);
	var $form = $input.closest('form');
	var params = {
		title: $input.val(),
		author: $form.find('#book_author').val(),
		id: $form.data('entity-id')
	};
	$.get('/books/search-duplicates', params, function(foundBooks) {
		$input.next('.duplicates').remove();
		if ($.trim(foundBooks) !== '') {
			$('<div class="help-block duplicates">'+foundBooks+'</div>').insertAfter($input);
		}
	});
});

$('textarea').css({'height': '3em', 'min-height': '3em'}).on('focus', function() {
	$(this).css('height', '15em');
});
