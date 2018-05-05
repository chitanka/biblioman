$.featherlight.defaults.autostart = false;
$('img').each(function() {
	// TODO remove this building logic - it should come from the backend
	var src = $(this).attr('src');
	if (!/\d\/\d\/\d\//.test(src)) {
		src = src.replace(/(.+\/)(\d+)(-[^\/]+)/, function(match, dir, id, basename) {
			var subDirCount = 4;
			var subDir = id.padStart(subDirCount, '0').substr(-subDirCount).split('').join('/');
			return dir+subDir+'/'+id+basename;
		});
	}
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

// bigfix - remove unnecessary help blocks
$('#book_links,#book_otherCovers,#book_scans').find('.help-block').remove();

var $helpBlocks = $('.help-block');
$helpBlocks.each(function () {
	var $helpBlock = $(this);
	var $helpToggler = $('<i class="fa fa-info-circle"></i>').on('click', function () {
		if ($helpBlock.is(':hidden')) {
			$helpBlock.slideDown();
		} else {
			$helpBlock.slideUp();
		}
	}).css({'margin-left': '.5em', 'opacity': '0.5', 'cursor': 'pointer'});
	$helpBlock.closest('.form-group').find('label:first').append($helpToggler);
	$helpBlock.hide();
});

$('form a:not(.action-list)').attr('target', '_blank');

$('form').on('submit', function() {
	if ($(this).data('submitted')) {
		return false;
	}
	$(this).data('submitted', true);
	return true;
});
