$.featherlight.defaults.autostart = false;
$('img').each(function() {
	var bigImage = $(this).attr('src').replace(/\.jpg/, '.800.jpg');
	$(this).attr('data-featherlight', bigImage);
}).featherlight(null, {
	
});

$('#book_title').on('change', function() {
	var $input = $(this);
	var $form = $input.closest('form');
	var id = $form.data('entity-id');
	$.get('/books/search', { title: $input.val(), author: $form.find('#book_author').val() }, function(foundBooks) {
		var duplicates = [];
		$.each(foundBooks, function(i, book) {
			if (book.id != id) {
				duplicates.push('<li><a href="/books/'+book.id+'" target="_blank">от '+book.author+'</a></li>');
			}
		});
		$input.next('.duplicates').remove();
		if (duplicates.length) {
			var $info = $('<div class="help-block duplicates">В базата вече е записана книга с това заглавие:<ul>'+duplicates.join('')+'</ul></div>').insertAfter($input);
		}
	});
});
