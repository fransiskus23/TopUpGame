@extends($theme.'layouts.user')
@section('title')
    {{ 'Pay with '.optional($order->gateway)->name ?? '' }}
@endsection


@section('content')

    <script src="https://js.stripe.com/v3/"></script>
    <style>
        .StripeElement {
            box-sizing: border-box;
            height: 40px;
            padding: 10px 12px;
            border: 1px solid transparent;
            border-radius: 4px;
            background-color: white;
            box-shadow: 0 1px 3px 0 #e6ebf1;
            -webkit-transition: box-shadow 150ms ease;
            transition: box-shadow 150ms ease;
        }

        .StripeElement--focus {
            box-shadow: 0 1px 3px 0 #cfd7df;
        }

        .StripeElement--invalid {
            border-color: #fa755a;
        }

        .StripeElement--webkit-autofill {
            background-color: #fefde5 !important;
        }
    </style>



    <section class="choose-section ">
        <div class="container ">
            <div class="row align-items-center justify-content-center py-5">
                <div class="col-md-3">
                    <img
                        src="{{getFile(config('location.gateway.path').optional($order->gateway)->image)}}"
                        class="w-75" alt="..">
                </div>
                <div class="col-md-5">
                    <h4>@lang('Please Pay') {{getAmount($order->final_amount)}} {{$order->gateway_currency}}</h4>
                    <h4 class="my-3">@lang('To Get') {{getAmount($order->amount)}}  {{$basic->currency}}</h4>
                    <form action="{{$data->url}}" method="{{$data->method}}">
                        <script
                            src="{{$data->src}}"
                            class="stripe-button"
                            @foreach($data->val as $key=> $value)
                            data-{{$key}}="{{$value}}"
                            @endforeach>
                        </script>
                    </form>
                </div>
            </div>

        </div>
    </section>


    @push('script')
        <script>
            $(document).ready(function () {
                $('button[type="submit"]').removeClass("stripe-button-el").addClass("game-btn").find('span').css('min-height', 'initial');
            })
        </script>
    @endpush

@endsection




