@extends($theme.'layouts.user')
@section('title')
    {{ 'Pay with '.optional($order->gateway)->name ?? '' }}
@endsection

@section('content')

<section 


<section class="choose-section ">
        <div class="container">
            <div class="row align-items-center justify-content-center py-5">
                <div class="col-md-3">
                    <img
                        src="{{getFile(config('location.gateway.path').optional($order->gateway)->image)}}"
                        class="card-img-top gateway-img br-4" alt="..">
                </div>
                <div class="col-md-5">
                    <h4>@lang('Please Pay') {{getAmount($order->final_amount)}} {{$order->gateway_currency}}</h4>
                    <h4 class="mt-15 mb-15">@lang('To Get') {{getAmount($order->amount)}}  {{$basic->currency}}</h4>

                    <button type="button" class="game-btn" id="btn-confirm"
                            onClick="payWithRave()">@lang('Pay Now')</button>
                </div>
            </div>
        </div>
    </section>



    @push('script')
        <script src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
        <script>
              "use strict";
            var btn = document.querySelector("#btn-confirm");
            btn.setAttribute("type", "button");
            const API_publicKey = "{{$data->API_publicKey ?? ''}}";

            function payWithRave() {
                var x = getpaidSetup({
                    PBFPubKey: API_publicKey,
                    customer_email: "{{$data->customer_email ?? 'example@example.com'}}",
                    amount: "{{ $data->amount ?? '0' }}",
                    customer_phone: "{{ $data->customer_phone ?? '0123' }}",
                    currency: "{{ $data->currency ?? 'USD' }}",
                    txref: "{{ $data->txref ?? '' }}",
                    onclose: function () {
                    },
                    callback: function (response) {
                        let txref = response.tx.txRef;
                        let status = response.tx.status;
                        window.location = '{{ url('payment/flutterwave') }}/' + txref + '/' + status;
                    }
                });
            }
        </script>
    @endpush
@endsection
