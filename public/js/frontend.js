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
	$('.shelf-picker').on('click', 'button', function (e) {
		$(this).toggleClass(this.getAttribute('data-state-classes'));
		const shelfId = this.getAttribute('data-value');
		const bookId = e.delegateTarget.getAttribute('data-book');
		$.ajax({
			url: '/my/shelves/'+shelfId+'/books/'+bookId,
			type: (this.classList.contains('active') ? 'POST' : 'DELETE')
		});
	}).each(function() {
		const $notCheckedSecondLevelShelves = $(this).find('button:not(.active).is-not-important');
		if ($notCheckedSecondLevelShelves.length < 2) {
			return;
		}
		$('<a class="btn btn-outline-secondary btn-sm fa fa-ellipsis-h" title="Показване на всички рафтове"></a>').on('click', function(){
			$notCheckedSecondLevelShelves.show();
			this.remove();
		}).appendTo(this);
		$notCheckedSecondLevelShelves.hide();
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
