@extends($theme . 'layouts.app')
@section('title', trans('Voucher Details'))

@section('content')

    <!-- DETAILS SECTION -->
    <section class="details-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 pe-lg-5 mb-5 mb-md-0">
                    <div class="img-box">
                        <img class="img-fluid"
                            src="{{ getFile(config('location.voucher.path') . @$voucherDetails->image) }}" alt="..." />
                    </div>
                    <div class="text-box">
                        <h4>@lang(@$voucherDetails->details->name)</h4>
                        <p>
                            @lang(@$voucherDetails->details->details)
                        </p>
                        {{-- <a href="{{ $topUpDetails->playStoreLink }}"> --}}
                        {{-- <img class="img-fluid" src="{{ asset($themeTrue) }}/img/play-app.png" alt="..."/> --}}
                        {{-- </a> --}}
                    </div>
                </div>
                <div class="col-lg-8 col-md-6">
                    <form action="{{route('user.voucher.payment')}}" method="post" enctype="multipart/form-data">
                        @csrf
                    <!-- SELECT RECHARGE -->
                    <div class="payment-box">
                        <div class="d-flex justify-content-between">
                            <h5>@lang('SELECT RECHARGE')</h5>
                            @error('service')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <div
                        class="d-flex flex-wrap  justify-content-start ">
                            @forelse($voucherDetails->activeServices as $key => $item)
                                @if(0 < count($item->voucherActiveCodes))
                                <div class="m-1 package-list ">
                                    <input type="radio" class="btn-check recharge-check" name="service" value="{{$item->id}}"
                                           id="option{{ $item->id }}" autocomplete="off" >

                                    <label class="btn btn-primary" for="option{{ $item->id }}">
                                        {{ $item->name }}
                                        <img src="{{ asset($themeTrue) . '/images/icon/check.png' }}" alt="..."
                                             class="check" />
                                    </label>
                                </div>
                                @endif
                            @empty
                            @endforelse

                        </div>
                    </div>
                    <!-- PAYMENT OPTIONS -->
                    <div class="payment-box">
                        <div class="d-flex justify-content-between">
                            <h5>@lang('SELECT PAYMENT OPTION')</h5>
                            @error('gateway')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="payment-options">

                            <div class="d-flex flex-wrap justify-content-start ">

                                @foreach($gateways as $gateway)
                                    <div class="m-1 package-list gateway">
                                        <input type="radio" class="btn-check gateway-check" name="gateway" id="gateway{{$gateway->id}}"
                                             value="{{$gateway->id}}"  autocomplete="off" />
                                        <label class="btn btn-primary" for="gateway{{$gateway->id}}">
                                            <img class="img-fluid" src="{{ getFile(config('location.gateway.path').$gateway->image) }}"
                                                 alt="{{$gateway->name}}" />
                                            <img src="{{ asset($themeTrue.'/images/icon/check.png') }}" alt="..."
                                                 class="check" /></label>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                    <div class="payment-box estimate-box">
                        <div class="payment-info">
                            <div  id="loading" class="text-center">
                                <img  src="{{asset('assets/admin/images/loading.gif')}}" alt="..."
                                      class="w-15"/>
                            </div>


                        </div>
                        <div class="d-flex justify-content-end">
                            <button class="game-btn-sm" type="submit">
                                <img src="{{ asset($themeTrue.'/images/icon/credit-card.png') }}" alt="..." />
                                @lang('Buy now')
                            </button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    {{-- @include($theme . 'sections.package-details') --}}
@endsection
@push('script')
    <script>
         'use strict';
        $('#loading').hide();

        var serviceId, gatewayId;

        $(document).on('click', '.recharge-check', function () {

            $('#loading').hide();
            $('.recharge-check').attr('checked', false);
            $(this).attr('checked', true);
            serviceId = $(this).val();
            checkCalc(serviceId, gatewayId);
        });


        $(document).on('click', '.gateway-check', function () {

            $('#loading').hide();
            $('.gateway-check').attr('checked', false);
            $(this).attr('checked', true);
            gatewayId = $(this).val();
            checkCalc(serviceId, gatewayId);
        });

        function checkCalc(serviceId, gatewayId) {
            if (serviceId == undefined || gatewayId == undefined) {
                return 0;
            }
            $('#loading').show();
            $.ajax({
                url: "{{route('ajaxCheckVoucherCalc')}}",
                type: 'POST',
                data: {
                    serviceId,
                    gatewayId
                },
                success(data) {

                    var htmlData = `
                    <h5>@lang('PURCHASE')</h5>
                                <ul>

                                    <li>
                                        @lang('Subtotal'):
                                        <span>${data.subtotal}</span>
                                    </li>

                                    <li>
                                        @lang('Discount'):
                                        <span>${data.discount}</span>
                                    </li>

                                    <li>
                                        @lang('Payable'):
                                        <span>${data.payable}</span>
                                    </li>
                                    ${(data.isCrypto == false) ? `
                                    <li class="text-center">
                                        ${data.in}
                                    </li>
                                    ` : ``}


                                </ul>`;

                    $('.payment-info').html(htmlData)
                },
                complete: function () {
                    $('#loading').hide();
                },
                error(err) {
                    var errors = err.responseJSON;
                    for (var obj in errors) {
                        Notiflix.Notify.Failure(`${errors[obj]}`);
                    }

                }
            });
        }

        $(document).on('click', '.recharge-check', function () {
            var price = $(this).data('price');
            var discount = $(this).data('discount');
            console.log(discount);
        })


        $(document).on('click', '.gateway-check', function() {
            $('.gateway-check').attr('checked', false);
            $(this).attr('checked', true);
        })
    </script>
@endpush
