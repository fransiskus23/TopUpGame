@extends($theme.'layouts.user')
@section('title')
    {{ 'Pay with '.optional($order->gateway)->name ?? '' }}
@endsection

@section('content')


    <section class="choose-section ">
        <div class="container ">
            <div class="row align-items-center justify-content-center py-5">
                <div class="col-md-3">
                    <img
                        src="{{getFile(config('location.gateway.path').optional($order->gateway)->image)}}"
                        class="card-img-top gateway-img" alt="..">
                </div>

                <div class="col-md-5">
                    <h4>@lang('Please Pay') {{getAmount($order->final_amount)}} {{$order->gateway_currency}}</h4>
                    <h4 class="mt-15 mb-15">@lang('To Get') {{getAmount($order->amount)}}  {{$basic->currency}}</h4>

                    <form action="{{$data->url}}" method="{{$data->method}}">
                        <script src="{{$data->checkout_js}}"
                                @foreach($data->val as $key=>$value)
                                data-{{$key}}="{{$value}}"
                            @endforeach >
                        </script>
                        <input type="hidden" custom="{{$data->custom}}" name="hidden">
                    </form>
                </div>

            </div>
        </div>
    </section>


    @push('script')
        <script>
              "use strict";
            $(document).ready(function () {
                $('input[type="submit"]').addClass("game-btn");
            })
        </script>
    @endpush
@endsection




