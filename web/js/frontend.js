$(function () {
	registerPopovers('.popover-trigger');
	registerLightGallery();
	registerFormConfirmations();
	registerShelfPickers();
	registerBookSearchForm();
});

function registerPopovers(selector) {
	$(selector).popover({
		html: true,
		//placement: 'auto',
		trigger: 'hover'
	});
}

function registerLightGallery() {
	$('body').lightGallery({
		selector: '.thumb-link',
		subHtmlSelectorRelative: true,
		hideBarsDelay: 2000
	});
}

function registerFormConfirmations() {
	$(document).on('submit', 'form[data-confirmation]', function () {
		var confirmationMessage = $(this).data('confirmation');
		return confirm(confirmationMessage);
	});
}

function registerShelfPickers() {
	var shelfPicker = new ShelfPicker();
	$('.shelf-picker').multiselect({
		nonSelectedText: 'Рафт...',
		nSelectedText: 'рафта избрани',
		allSelectedText: 'Всички рафтове са избрани',
		enableFiltering: true,
		enableCaseInsensitiveFiltering: true,
		filterPlaceholder: 'Търсене',
		buttonContainer: '<div class="btn-group shelf-picker"/>',
		buttonWidth: '100%',
		optionLabel: function(option) {
			return '<span class="fa fa-fw '+$(option).data('icon')+' shelf-icon"></span> ' + $(option).html();
		},
		enableHTML: true,
		templates: {
			button: '<button type="button" class="multiselect dropdown-toggle" data-toggle="dropdown"><span class="fa fa-folder-o"></span> <span class="multiselect-selected-text"></span> <b class="caret"></b></button>',
			filter: '<li class="multiselect-item multiselect-filter"><div class="input-group"><span class="input-group-addon"><i class="fa fa-search"></i></span><input class="form-control multiselect-search" type="text"></div></li>',
			filterClearBtn: '<span class="input-group-btn"><button class="btn btn-default multiselect-clear-filter" type="button"><i class="fa fa-remove"></i></button></span>'
		},
		onChange: function(option, checked) {
			shelfPicker.clickOnOption(this.$container, $(option), checked);
		}
	});
	$('.important-shelf-picker').on('click', 'label', function () {
		shelfPicker.clickOnImportantShelf($(this));
	});
}

function registerBookSearchForm() {
	$('.book-search-form').on('click', '.js-search-actions a', function() {
		$(this).closest('form').attr('action', this.href)
			.find('.js-search-action-toggle').dropdown('toggle')
			.find('.button-text').text($(this).text());
		return false;
	});
}

var ShelfPicker = function () {
	var my = this;

	my.clickOnImportantShelf = function($label) {
		var shelfId = $label.find('input').val();
		var wasSelected = $label.is('.active');
		var $shelfPicker = $label.parent().siblings('select.shelf-picker');
		$label.toggleClass('btn-info btn-default');
		if (wasSelected) {
			$shelfPicker.multiselect('deselect', shelfId, true);
		} else {
			$shelfPicker.multiselect('select', shelfId, true);
		}
	};
	my.clickOnOption = function ($container, $option, checked) {
		var shelfId = $option.val();
		var bookId = $option.closest('select').data('book');
		$.ajax({
			url: '/my/shelves/'+shelfId+'/books/'+bookId,
			type: (checked ? 'POST' : 'DELETE')
		});
		updateImportantShelfButtonOnOptionClick($container, shelfId, checked);
	};
	function updateImportantShelfButtonOnOptionClick($container, shelfId, checked) {
		var $importantShelfLabel = $container.siblings('.important-shelf-picker').find('label.shelf-'+shelfId);
		if ( (checked && !$importantShelfLabel.is('.btn-info')) || (!checked && $importantShelfLabel.is('.btn-info')) ) {
			var toggledClasses = 'btn-info btn-default active';
			$importantShelfLabel.toggleClass(toggledClasses);
		}
	}
};
