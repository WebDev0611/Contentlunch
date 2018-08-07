(function () {
    $('#submit-btn').prop('disabled', true);

    // Disable current subscription selection
    if (typeof subscriptionTypeSlug != 'undefined' && subscriptionTypeSlug != null) {
        var planSelector = $(".plan-selector.plan-" + subscriptionTypeSlug);
        $("<div />").addClass('disabled-overlay').appendTo(planSelector);
    }

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
        e.preventDefault();
        var $form = $(this);

        $('#submit-btn').prop('disabled', true);
        $('.loading').fadeIn('fast');

        if ($form.find('input[name="stripe-customer-id"]').val()) {
            // Charge existing user
            $form.get(0).submit();
        } else {
            // Otherwise create new Stripe payment
            Stripe.card.createToken($form, stripeResponseHandler);
        }

        return false;
    });

    // Allow only 1 subscription plan to be selected
    $('.checkbox-tag.plan input[type="checkbox"]').on('change', function () {
        $('.checkbox-tag.plan input[type="checkbox"]').not(this).prop('checked', false);
        $('.plan-selector').removeClass('selected');
        $('.plan-selector .checkbox-tag span').html('Select');

        var $form = $('#subscriptionForm');
        if (this.checked) {
            $(this).closest('.plan-selector').addClass('selected');
            $(this).next('span').html('Selected');
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

    $('#edit-payment').click(function () {
        var $subscriptionForm = $('#subscriptionForm');
        $subscriptionForm.find('input[name="stripe-customer-id"]').remove();
        $subscriptionForm.find('.stripe-container').show();
    });

    // Switch monthly/annually plans
    $('.billing-buttons .btn').click(function() {
        var monthlyPlans = $('.plan-basic-monthly, .plan-pro-monthly');
        var annualPlans =$('.plan-basic-annually, .plan-pro-annually');

        if ($(this).hasClass("monthly")) {
            $(this).addClass('selected');
            $('.billing-buttons .btn.annually').removeClass('selected');
            annualPlans.hide();
            monthlyPlans.show();
        } else if ($(this).hasClass("annually")) {
            $(this).addClass('selected');
            $('.billing-buttons .btn.monthly').removeClass('selected');
            monthlyPlans.hide();
            annualPlans.show();
        }
    });
})();
