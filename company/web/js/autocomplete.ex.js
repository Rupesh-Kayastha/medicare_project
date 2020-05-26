(function($) {

	if($.ui) {
		$.ui.autocomplete.prototype.options.autoSelect = true;
		$('body').on('blur', '.ui-autocomplete-input', function(event) {
			var autocomplete = $(this).data('ui-autocomplete');
			if (!autocomplete.options.autoSelect || autocomplete.selectedItem) { return; }

			var matcher = new RegExp("^"
									+ $.ui.autocomplete.escapeRegex($(this).val())
									+ "$", "i");
									
			
			
			autocomplete.widget().children('.ui-menu-item').each(function(index, item) {
				var item = $( this ).data('uiAutocompleteItem');
				if (matcher.test(item.label || item.value || item)) {
					autocomplete.selectedItem = item;
					return false;
				}
			});

			if (autocomplete.selectedItem) {
				autocomplete
				._trigger('select', event, {item: autocomplete.selectedItem});
			}
		});
	}
}(jQuery));