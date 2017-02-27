(function () {
    $('#submit-btn').prop('disabled', true);
    Stripe.setPublishableKey("{{ getenv('STRIPE_PUBLISHABLE_KEY') }}");

    function stripeResponseHandler(status, response) {
        var $form = $('#subscriptionForm');

        if (response.error) {
            $form.find('input[type=submit]').prop('disabled', false);
            $('#paymentErrors')
                .text(response.error.message)
                .slideDown('fast');
            $('.loading').fadeOut('fast');
            $('#submit-btn').prop('disabled', false);
        } else {
            var token = response.id;
            $form.append($('<input type="hidden" name="stripe-token" />').val(token));
            $form.get(0).submit();
        }
    }

    $('#subscriptionForm').submit(function (e) {
        var $form = $(this);

        $('#submit-btn').prop('disabled', true);
        $('.loading').fadeIn('fast');

        if($form.find('input[name="stripe-customer-id"]').val()){
            // Charge existing user
            $form.get(0).submit();
        } else{
            // Otherwise create new Stripe payment
            Stripe.card.createToken($form, stripeResponseHandler);
        }

        return false;
    });

    // Allow only 1 subscription plan to be selected
    $('.checkbox-tag.plan input[type="checkbox"]').on('change', function () {
        $('.checkbox-tag.plan input[type="checkbox"]').not(this).prop('checked', false);
        $('.plan').removeClass('selected');

        var $form = $('#subscriptionForm');
        if (this.checked) {
            $(this).parent('.plan').addClass('selected');
            if (!$('input[name="plan-name"]').val() || !$('input[name="plan-type"]').val() || !$('input[name="plan-price"]').val() || !$('input[name="plan-slug"]').val()) {
                $form.append($('<input type="hidden" name="plan-name" />'));
                $form.append($('<input type="hidden" name="plan-type" />'));
                $form.append($('<input type="hidden" name="plan-price" />'));
                $form.append($('<input type="hidden" name="plan-slug" />'));
            }
            $('input[name="plan-name"]').val($(this).attr('plan-name'));
            $('input[name="plan-type"]').val($(this).attr('plan-type'));
            $('input[name="plan-price"]').val($(this).attr('plan-price'));
            $('input[name="plan-slug"]').val($(this).attr('plan-slug'));

            $('#submit-btn').show();
            $('#submit-btn').prop('disabled', false);
        } else {
            $('input[name="plan-name"], input[name="plan-type"], input[name="plan-price"], input[name="plan-slug"]').remove();
            $('#submit-btn').prop('disabled', true);
        }
    });
})();
