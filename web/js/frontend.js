$(function () {
	$('.popover-trigger').popover({
		html: true,
		//placement: 'auto',
		trigger: 'hover'
	});
	$('body').lightGallery({
		selector: '.thumb-link',
		subHtmlSelectorRelative: true,
		hideBarsDelay: 2000
	});
	$(document).on('submit', 'form[data-confirmation]', function (event) {
		var confirmationMessage = $(this).data('confirmation');
		return confirm(confirmationMessage);
	});
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
			$.ajax({
				url: '/my/shelves/'+$(option).val()+'/books/'+$(option).closest('select').data('book'),
				type: (checked ? 'POST' : 'DELETE')
			});
			var $importantShelfLabel = this.$container.siblings('.important-shelf-picker').find('label.shelf-'+$(option).val());
			if ( (checked && !$importantShelfLabel.is('.btn-info')) || (!checked && $importantShelfLabel.is('.btn-info')) ) {
				var toggledClasses = 'btn-info btn-default active';
				$importantShelfLabel.toggleClass(toggledClasses);
			}
		}
	});
	$('.important-shelf-picker').on('click', 'label', function () {
		var $self = $(this);
		var shelfId = $self.find('input').val();
		var wasSelected = $self.is('.active');
		var $shelfPicker = $self.parent().siblings('select.shelf-picker');
		$self.toggleClass('btn-info btn-default');
		if (wasSelected) {
			$shelfPicker.multiselect('deselect', shelfId, true);
		} else {
			$shelfPicker.multiselect('select', shelfId, true);
		}
	})
});
