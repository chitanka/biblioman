function generateRandomId() {
	return 'id-'+Math.random().toString(36).substr(2);
}

$.featherlight.defaults.autostart = false;

$('img').each(function() {
	// TODO remove this building logic - it should come from the backend
	let src = $(this).attr('src');
	if (src.indexOf('thumb') === -1) {
		// not a thumbnail
		return;
	}
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

$('#Book_title').on('change', function() {
	var $input = $(this);
	var $form = $input.closest('form');
	var params = {
		title: $input.val(),
		author: $form.find('#Book_author').val(),
		id: $form.data('entity-id')
	};
	$.get('/books/search-duplicates', params, function(foundBooks) {
		$input.next('.duplicates').remove();
		if ($.trim(foundBooks) !== '') {
			$('<div class="help-block duplicates">'+foundBooks+'</div>').insertAfter($input);
		}
	});
});

$('textarea').css({'height': '2.2em', 'min-height': '2em'}).on('focus', function() {
	$(this).css('height', '15em');
}).on('blur', function() {
	$(this).css('height', '2.2em');
});

const $bookForm = $('#edit-Book-form');
if ($bookForm.length) {
	const bookId = new URLSearchParams(document.location.search.substring(1)).get('entityId');
	setInterval(function() {
		$.post('/admin/books/extend-lock/' + bookId);
	}, 120000);
}

const $helpBlocks = $('.form-help');
$helpBlocks.each(function () {
	var $helpBlock = $(this);
	var $helpToggler = $('<i class="fa fa-info-circle"></i>').on('click', function () {
		$helpBlock.toggle();
		return false; // this stops the default behaviour of clicking the label and focusing the field
	}).css({'margin-left': '.5em', 'opacity': '0.5', 'cursor': 'pointer'});
	$helpBlock.closest('.form-group').find('legend,label').first().append($helpToggler);
	$helpBlock.hide();
});

$('form a:not(.action-list)').attr('target', '_blank');

$('form').on('change', ':input', function() {
	const originalValue = $(this).data("ays-orig");
	const comparisonValue = function(el) {
		if (typeof originalValue === 'boolean') {
			return el.checked;
		}
		return el.value;
	}
	if (comparisonValue(this) === originalValue) {
		this.classList.remove('changed');
	} else {
		this.classList.add('changed');
	}

}).on('submit', function() {
	if ($(this).data('submitted')) {
		return false;
	}
	$(this).data('submitted', true);
	return true;
});

const $panelHeaders = $('.content-panel-header');
$panelHeaders.each(function () {
	const $body = $(this).next().addClass('collapse show');
	const target = $body.attr('id') || $body.attr('id', generateRandomId()).attr('id');
	$(this).attr({'data-toggle': 'collapse', 'data-target': '#'+target, 'aria-expanded': 'false', 'aria-controls': target});
});

const panelNav = $panelHeaders.map(function() {
	const target = $(this).next().attr('id');
	const iconClass = $(this).find('i')[0].className;
	return `<li><a href="#${target}"><i class="${iconClass}"></i> <span>${$(this).text().trim()}</span></a></li>`;
}).get().join('');
$(`<ul class="sidebar-menu position-fixed"><li class="header"><span>Книга</span></li>${panelNav}</ul>`).insertAfter('.sidebar').on('click', 'a', function () {
	$([document.documentElement, document.body]).animate({
		scrollTop: $($(this).attr('href')).offset().top - 100
	}, 0);
	return false;
});

$('.field-collection-delete-button').each(function () {
	this.onclick = function() {
		if (confirm('Потвърдете изтриването.')) {
			this.closest('.form-group').remove();
		}
		return false;
	};
});
