/*

function vcWaiting(id, show) {
	if (show) {
		jQuery(id).show();
	} else {
		jQuery(id).hide();
	}
}

function vcToolHide(id, field, value) {
	jQuery('#'+field+'_label_'+id).html(value).show();
	jQuery('#'+field+'_value_'+id).hide();
	jQuery('#'+field+'_button_'+id).hide();
	jQuery('#'+field+'_waiting_'+id).hide();
}

function vcInit() {
	jQuery('span[id^="price_label"]').parent().unbind('click').click(function(){
		priceLabel = jQuery(this).find('span[id^="price_label"]');
		id = priceLabel.attr('data-id');
		priceLabel.hide();
		jQuery("#price_value_" + id).show();
		jQuery("#price_button_" + id).show();
	});
	
	jQuery('span[id^="qty_label"]').parent().unbind('click').click(function(){
		qtyLabel = jQuery(this).find('span[id^="qty_label"]');
		id = qtyLabel.attr('data-id');
		qtyLabel.hide();
		jQuery("#qty_value_" + id).show();
		jQuery("#qty_button_" + id).show();
	});
	
}

jQuery(function() {
	vcInit();
});
*/