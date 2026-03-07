@extends('web.layout.main')

@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<style>
    body {
        background-color: #F5F5F5;
    }
</style>
@endpush

@section('main-section')

<div class="container mt-5 pt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">Payment Details</h3>
                </div>
                <div class="card-body">

                    @if (Session::has('success'))
                    <div class="alert alert-success text-center">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <p>{{ Session::get('success') }}</p>
                    </div>
                    @endif

                    <form id="payment-form" action="{{ route('stripe.postreg') }}" method="POST" class="require-validation" data-cc-on-file="false" data-stripe-publishable-key="{{ env('STRIPE_KEY') }}" onsubmit="document.getElementById('pay_btn').style.display = 'none';">
                        @csrf

                        <div class="mb-3">
                            <label for="name-on-card" class="form-label">Name on Card</label>
                            <input type="text" class="form-control" id="name-on-card" maxlength="100" required>
                        </div>

                        <div class="mb-3">
                            <label for="card-number" class="form-label">Card Number</label>
                            <input type="text" class="form-control card-number" id="card-number" autocomplete="off" maxlength="16" minlength="16" required>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="cvc" class="form-label">CVC</label>
                                <input type="text" class="form-control card-cvc" id="cvc" placeholder="ex. 311" maxlength="3" minlength="3" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="expiry-month" class="form-label">Expiration Month</label>
                                <input type="text" class="form-control card-expiry-month" id="expiry-month" placeholder="MM" maxlength="2" minlength="2" pattern="\d*" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="expiry-year" class="form-label">Expiration Year</label>
                                <input type="text" class="form-control card-expiry-year" id="expiry-year" placeholder="YYYY" maxlength="4" minlength="4" pattern="\d*" required>
                            </div>
                        </div>

                        <div class="alert alert-danger d-none" id="card-error">
                            <p>Please correct the errors and try again.</p>
                        </div>

                        <div class="d-grid">
                            <button class="btn btn-primary btn-lg" type="submit" id="pay_btn">Pay Now (${{ $amount }})</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://js.stripe.com/v2/"></script>
<script>
$(function () {
    var $form = $(".require-validation");

    $form.on('submit', function (e) {
        var $inputs = $form.find('.required input'),
            $errorMessage = $('#card-error'),
            valid = true;

        $errorMessage.addClass('d-none');
        $inputs.removeClass('is-invalid');

        $inputs.each(function () {
            var $input = $(this);
            if ($input.val() === '') {
                $input.addClass('is-invalid');
                $errorMessage.removeClass('d-none');
                valid = false;
            }
        });

        if (!valid) {
            e.preventDefault();
            return;
        }

        if (!$form.data('cc-on-file')) {
            e.preventDefault();
            Stripe.setPublishableKey($form.data('stripe-publishable-key'));
            Stripe.createToken({
                number: $('.card-number').val(),
                cvc: $('.card-cvc').val(),
                exp_month: $('.card-expiry-month').val(),
                exp_year: $('.card-expiry-year').val()
            }, stripeResponseHandler);
        }
    });

    function stripeResponseHandler(status, response) {
        if (response.error) {
            $('#pay_btn').show();
            $('#card-error')
                .removeClass('d-none')
                .text(response.error.message);
        } else {
            var token = response.id;
            $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
            $form.get(0).submit();
        }
    }
});
</script>

@endsection
