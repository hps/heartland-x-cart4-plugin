(function ($) {
  var SecureSubmit = window.SecureSubmit = window.SecureSubmit || {
    responseHandler: function (response) {
      if ( response.message ) {
        $(".securesubmit_checkout_errors")
          .append('<div class="error">' + response.message + '</div>');
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
      $('form[name="checkout_form"]').unblock();
    },
    processSubmit: function () {
      if ($("#credit_card_card_number").is(":visible") && jQuery(".securesubmit_token").val() === '') {
        $('form[name="checkout_form"]')
          .block({message: null, overlayCSS: {background: '#fff', opacity: 0.6}});

        hps.tokenize({
          data: {
            public_key: securesubmit_key,
            number: $.trim($('#credit_card_card_number').val().replace(/\D/g, '')),
            cvc: $.trim($('#credit_card_cvv').val()),
            exp_month: $.trim($('#credit_card_expiry_month').val()),
            exp_year: $.trim($('#credit_card_expiry_year').val())
          },
          success: SecureSubmit.responseHandler,
          error: SecureSubmit.responseHandler
        });

        return false;
      }  else {
        return true;
      }
    }
  };

  if (SecureSubmit.interval) {
    clearInterval(SecureSubmit.interval);
  }
  SecureSubmit.interval = setInterval(function () {
    $(".main-button").last()
      .unbind('click', SecureSubmit.processSubmit)
      .bind("click", SecureSubmit.processSubmit);
  }, 1 * 1000);
}(jQuery));
