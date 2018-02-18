$.featherlight.defaults.autostart = false;
$('img').each(function() {
	var src = $(this).attr('src');
	var smallImage = src.replace(/\.([^.]+)$/, '.150.$1');
	var bigImage = src.replace(/\.([^.]+)$/, '.1000.$1');
	$(this).attr('src', smallImage);
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

var $bookForm = $('#edit-book-form');
if ($bookForm.length) {
	setInterval(function() {
		$.post('/admin/books/extend-lock?' + $.param({
			entity: $bookForm.data('entity'),
			id: $bookForm.data('entity-id')
		}));
	}, 120000);
}
