function secureSubmitResponseHandler( response ) {
    if ( response.message ) {
        jQuery(".securesubmit_checkout_errors").append('<div class="error">' + response.message + '</div>');
    } else {
		$("<input>").attr({
			type: 'hidden',
			id: 'securesubmit_token',
			name: 'securesubmit_token',
			value: response.token_value
		}).appendTo($('form[name="checkout_form"]'));
		
		$(".main-button").last().unbind("click");
		$(".main-button").last().click();
    }
}

jQuery(document).ready(function($) {
	$(".main-button").last().bind("click", processSubmit);
	
	function processSubmit() {
		if ($("#credit_card_card_number").is(":visible") && jQuery(".securesubmit_token").val() === '')
		{
			$('form[name="checkout_form"]').block({message: null, overlayCSS: {background: '#fff', opacity: 0.6}});
		
			hps.tokenize({
				data: {
					public_key: securesubmit_key,
					number: $.trim($('#credit_card_card_number').val().replace(/\D/g, '')),
					cvc: $.trim($('#credit_card_cvv').val()),
					exp_month: $.trim($('#credit_card_expiry_month').val()),
					exp_year: $.trim($('#credit_card_expiry_year').val())
				},
				success: function(response) {
					secureSubmitResponseHandler(response);
				},
				error: function(response) {
					secureSubmitResponseHandler(response);
				}
			});
			
			return false;
		}
		else
		{
			return true;
		}
	}
});