function generateRandomId() {
	return 'id-'+Math.random().toString(36).substr(2);
}

$.featherlight.defaults.autostart = false;

$(function() {// on DOM load

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

const $inputs = $('form :input');
const getInputValue = function(el) {
	if (el.type === 'radio' || el.type === 'checkbox') {
		return el.checked;
	}
	const value = $(el).val();
	return Array.isArray(value) ? value.join('') : value;
};
$inputs.each(function() {
	$(this).data("orig-value", getInputValue(this));
});
$inputs.on('change', function() {
	const originalValue = $(this).data("orig-value");
	const marker = 'changed';
	const $richSelect = $(this).next('.form-select');
	if (getInputValue(this) === originalValue) {
		this.classList.remove(marker);
		$richSelect.removeClass(marker)
	} else {
		this.classList.add(marker);
		$richSelect.addClass(marker)
	}

}).on('submit', function() {
	if ($(this).data('submitted')) {
		return false;
	}
	$('.page-actions button').addClass('disabled');
	$(this).data('submitted', true);
	return true;
});

// We do not want that scrolling with a wheel changes the number value as this leads to unintended edits.
$inputs.filter('input[type="number"]').on('wheel', function (e) {
	e.preventDefault();
});

// disable form submission on hitting the Enter key
$('form').on('keypress', 'input', function(event){
	if (event.key === 'Enter') {
		event.preventDefault();
	}
});

const $panelHeaders = $('.form-panel-header');
const panelNav = $panelHeaders.map(function() {
	const target = $(this).next().attr('id');
	const iconClass = $(this).find('.form-panel-icon')[0].className;
	return `<li class="menu-item"><a href="#${target}" class="menu-item-contents"><i class="menu-icon ${iconClass}"></i> <span>${$(this).text().trim()}</span></a></li>`;
}).get().join('');
$(`<ul class="menu position-fixed"><li class="menu-header"><span class="menu-header-contents">Книга</span></li>${panelNav}</ul>`)
.appendTo('#main-menu').on('click', 'a', function () {
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

});// on DOM load
