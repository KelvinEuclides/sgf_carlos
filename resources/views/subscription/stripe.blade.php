@extends('layouts.app')

@section('page-title')
    {{__('Subscription Detail')}}
@endsection
@php
    $dir= asset(Storage::url('uploads/subscription'));
@endphp
@push('script-page')
    <script src="https://js.stripe.com/v3/"></script>
    <script type="text/javascript">
        @if($subscription->price > 0.0 && env('ENABLE_STRIPE') == 'on' && !empty(env('STRIPE_KEY')) && !empty(env('STRIPE_SECRET')))
        var stripe = Stripe('{{ env('STRIPE_KEY') }}');
        var elements = stripe.elements();

        // Custom styling can be passed to options when creating an Element.
        var style = {
            base: {
                // Add your base input styles here. For example:
                fontSize: '14px',
                color: '#32325d',
            },
        };

        // Create an instance of the card Element.
        var card = elements.create('card', {style: style});

        // Add an instance of the card Element into the `card-element` <div>.
        card.mount('#card-element');

        // Create a token or display an error when the form is submitted.
        var form = document.getElementById('payment-form');
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            stripe.createToken(card).then(function (result) {
                if (result.error) {
                    $("#card-errors").html(result.error.message);
                    show_toastr('Error', result.error.message, 'error');
                } else {
                    // Send the token to your server.
                    stripeTokenHandler(result.token);
                }
            });
        });

        function stripeTokenHandler(token) {
            // Insert the token ID into the form so it gets submitted to the server
            var form = document.getElementById('payment-form');
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'stripeToken');
            hiddenInput.setAttribute('value', token.id);
            form.appendChild(hiddenInput);

            // Submit the form
            form.submit();
        }

        @endif

        $(document).ready(function () {
            $(document).on('click', '.apply-voucher', function () {

                var ele = $(this);
                var voucher = ele.closest('.row').find('.voucher').val();
                $.ajax({
                    url: '{{route('apply.voucher')}}',
                    datType: 'json',
                    data: {
                        subscription_id: '{{\Illuminate\Support\Facades\Crypt::encrypt($subscription->id)}}',
                        voucher: voucher
                    },
                    success: function (data) {

                        $('.final-price').text(data.final_price);
                        $('#stripe_voucher, #paypal_voucher').val(voucher);
                        if (data.is_success == true) {
                            show_toastr('Success', data.message, 'success');
                        } else if (data.is_success == false) {
                            show_toastr('Error', data.message, 'error');
                        } else {
                            show_toastr('Error', 'Voucher is required', 'error');
                        }
                    }
                })
            });
        });

    </script>
@endpush
@php
    $dir= asset(Storage::url('uploads/subscription'));
    $dir_payment= asset(Storage::url('uploads/payments'));
@endphp
@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card card-fluid">
                <div class="card-header border-0 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">{{$subscription->name}}</h6>
                        </div>
                    </div>
                </div>
                <div class="card-body text-center plan-box">
                    <a href="#" class="avatar rounded-circle avatar-lg hover-translate-y-n3">
                        <img alt="Image placeholder" src="{{$dir.'/'.$subscription->image}}" class="">
                    </a>

                    <h5 class="h6 my-4 "><span class="final-price">{{env('CURRENCY_SYMBOL').$subscription->price}}</span> {{' / '.$subscription->duration}}</h5>

                    @if(\Auth::user()->type=='company' && \Auth::user()->subscription == $subscription->id)
                        <h5 class="h6 my-4">
                            {{__('Expired : ')}} {{\Auth::user()->subscription_expire_date ? \Auth::user()->dateFormat(\Auth::user()->subscription_expire_date):__('Unlimited')}}
                        </h5>
                    @endif
                    <h5 class="h6 my-4">{{$subscription->description}}</h5>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col text-center">
                            <span class="h5 mb-0">{{$subscription->max_users}}</span>
                            <span class="d-block text-sm">{{__('Users')}}</span>
                        </div>
                        <div class="col text-center">
                            <span class="h5 mb-0">{{$subscription->max_customers}}</span>
                            <span class="d-block text-sm"> {{__('Customers')}}</span>
                        </div>
                        <div class="col text-center">
                            <span class="h5 mb-0">{{$subscription->max_vendors}}</span>
                            <span class="d-block text-sm"> {{__('Vendors')}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <ul class="nav nav-tabs nav-overflow profile-tab-list" role="tablist">
                    @if(env('ENABLE_STRIPE') == 'on')
                        <li class="nav-item ml-4">
                            <a href="#stripe" id="stripe-settings" class="nav-link active" data-toggle="tab" role="tab" aria-controls="home" aria-selected="true">
                                {{__('Stripe')}}
                            </a>
                        </li>
                    @endif
                    @if(env('ENABLE_PAYPAL') == 'on')
                        <li class="nav-item ml-4">
                            <a href="#paypal" id="paypal-settings" class="nav-link" data-toggle="tab" role="tab" aria-controls="home" aria-selected="false">
                                {{__('Paypal')}}
                            </a>
                        </li>
                    @endif
                </ul>
                <div class="tab-content">
                    @if(env('ENABLE_STRIPE') == 'on' && !empty(env('STRIPE_KEY')) && !empty(env('STRIPE_SECRET')))
                        <div class="tab-pane fade active show" id="stripe" role="tabpanel" aria-labelledby="orders-tab">
                            <div class="">
                                <div class="card-body">
                                    <form role="form" action="{{ route('stripe.post') }}" method="post" class="require-validation" id="payment-form">
                                        @csrf
                                        <div class="border p-3 mb-3 rounded stripe-payment-div">
                                            <div class="row">
                                                <div class="col-sm-8">
                                                    <div class="custom-radio">
                                                        <label class="font-16 font-weight-bold">{{__('Stripe Credit / Debit Card')}}</label>
                                                    </div>

                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="card-name-on">{{__('Name on card')}}</label>
                                                        <input type="text" name="name" id="card-name-on" class="form-control required" placeholder="{{\Auth::user()->name}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div id="card-element"></div>
                                                    <div id="card-errors" role="alert"></div>
                                                </div>
                                                <div class="col-md-10">
                                                    <br>
                                                    <div class="form-group">
                                                        <input type="text" id="stripe_voucher" name="voucher" class="form-control voucher" placeholder="{{ __('Enter Voucher Code') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-2 voucher-apply-btn my-auto">
                                                    <div class="form-group apply-stripe-btn-voucher">
                                                        <a href="#" class="btn btn-primary voucher-apply-btn apply-voucher btn-sm"><i class="fas fa-check-circle"></i></a>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="error" style="display: none;">
                                                        <div class='alert-danger alert'>{{__('Please correct the errors and try again.')}}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <div class="text-sm-right mr-2">
                                                        <input type="hidden" name="plan_id" value="{{\Illuminate\Support\Facades\Crypt::encrypt($subscription->id)}}">
                                                        <button class="btn btn-primary btn-sm" type="submit">
                                                            <i class="mdi mdi-cash-multiple mr-1"></i> {{__('Pay Now')}}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(env('ENABLE_PAYPAL') == 'on' && !empty(env('PAYPAL_CLIENT_ID')) && !empty(env('PAYPAL_SECRET_KEY')))
                        <div class="tab-pane fade {{(env('ENABLE_STRIPE') != 'on' && env('ENABLE_PAYPAL') == 'on')?'active show':''}}" id="paypal" role="tabpanel" aria-labelledby="orders-tab">
                            <div class="">
                                <div class="card-body">
                                    <form class="w3-container w3-display-middle w3-card-4" method="POST" id="payment-form" action="{{ route('subscription.pay.with.paypal') }}">
                                        @csrf
                                        <input type="hidden" name="subscription_id" value="{{\Illuminate\Support\Facades\Crypt::encrypt($subscription->id)}}">

                                        <div class="border p-3 mb-3 rounded payment-box">
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <div class="form-group">
                                                        <label for="paypal_voucher">{{__('Voucher')}}</label>
                                                        <input type="text" id="paypal_voucher" name="voucher" class="form-control voucher" placeholder="{{ __('Enter Voucher Code') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-2 voucher-apply-btn my-5">
                                                    <div class="form-group apply-paypal-btn-voucher">
                                                        <a href="#" class="btn btn-primary apply-voucher btn-sm"><i class="fas fa-check-circle"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group mt-3 mr-3">
                                            <div class="text-sm-right">
                                                <button class="btn btn-primary btn-sm" type="submit">
                                                    <i class="mdi mdi-cash-multiple mr-1"></i> {{__('Pay Now')}}
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
