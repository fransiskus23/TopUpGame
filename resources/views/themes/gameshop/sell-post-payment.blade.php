@extends($theme . 'layouts.user')
@section('title', trans('Make Payment'))

@section('content')
    <!-- SELL POST DETAILS -->
    <section class="sell-post-details">
        <div class="container">
            <div class="row g-4 g-md-5">
                <div class="col-md-6">
                    <div class="game-box d-md-flex">
                        <div class="img-box image-slider owl-carousel">
                            @for($i = 0; $i<count($sellPost->image); $i++)
                                <img
                                    src="{{ getFile(config('location.sellingPost.path') . @$sellPost->image[$i]) }}"
                                    class="img-fluid"
                                    alt="..."
                                />
                            @endfor
                        </div>
                        <div class="w-100 d-block">
                            <div class="row justify-content-between  ">
                                <div class="col-md-12">
                                    <h6 class="name">{{$sellPost->title}}</h6>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-3">
                                <span class="game-level">
                                @lang('Price'):
                                     <span>{{getAmount($price)}} {{config('basic.currency')}}</span></span>
                                @if($sellPost->payment_lock == 1)
                                    @if(Auth::check() && Auth::id()==$sellPost->lock_for)
                                        <span class="badge bg-secondary">@lang('Waiting Payment')</span>
                                    @elseif(Auth::check() &&  Auth::id()==$sellPost->user_id)
                                        <span class="badge bg-warning text-dark">@lang('Payment Processing')</span>
                                    @else
                                        <span class="badge bg-warning">@lang('Going to Sell')</span>
                                    @endif
                                @endif
                            </div>

                            <div class="row g-2 mt-3 more-info">
                                @forelse($sellPost->post_specification_form as $k => $v)
                                    <div class="col-6">
                                        <span>{{$v->field_name}}: {{$v->field_value}}</span>
                                    </div>
                                @empty
                                @endforelse
                            </div>
                        </div>
                    </div>
                    <div>
                        <p class="mt-4">
                            {{$sellPost->details}}
                        </p>
                    </div>
                </div>


                @if(0 < count($gateways))
                    <div class="col-md-6">
                        <form action="{{route('user.sellPost.makePayment')}}" method="post"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="payment-box">
                                <div class="d-flex justify-content-between">
                                    <h5>@lang('SELECT PAYMENT OPTION')</h5>
                                    @error('gateway')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>

                                <input type="hidden" class="sellPostId" name="sellPostId" value="{{$sellPost->id}}">
                                <div class="payment-options">
                                    <div class="d-flex flex-wrap justify-content-start ">
                                        @foreach($gateways as $gateway)
                                            <div class="m-1 package-list gateway">
                                                <input type="radio" class="btn-check gateway-check" name="gateway"
                                                       required
                                                       id="gateway{{$gateway->id}}"
                                                       value="{{$gateway->id}}"
                                                       @if(old('gateway') == $gateway->id) checked @endif
                                                       autocomplete="off"/>
                                                <label class="btn btn-primary" for="gateway{{$gateway->id}}">
                                                    <img class="img-fluid"
                                                         src="{{ getFile(config('location.gateway.path').$gateway->image) }}"
                                                         alt="{{$gateway->name}}"/>
                                                    <img src="{{ asset($themeTrue.'/images/icon/check.png') }}" alt="..."
                                                         class="check"/></label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="payment-box estimate-box">
                                <div class="payment-info">
                                    <div id="loading" class="text-center">
                                        <img src="{{asset('assets/admin/images/loading.gif')}}" alt="..."
                                             class="w-15"/>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button class="game-btn-sm" type="submit">
                                        <img src="{{ asset($themeTrue.'/images/icon/credit-card.png') }}" alt=".."/>
                                        @lang('Buy now')
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
@push('script')
    <script>
         'use strict';
        $('#loading').hide();

        var sellPostId, gatewayId;

        $(document).on('click', '.gateway-check', function () {
            $('#loading').hide();
            $('.gateway-check').attr('checked', false);
            $(this).attr('checked', true);
            sellPostId = $('.sellPostId').val();
            gatewayId = $(this).val();
            checkCalc(sellPostId, gatewayId);
        });

        function checkCalc(sellPostId, gatewayId) {
            if (sellPostId == undefined || gatewayId == undefined) {
                return 0;
            }
            $('#loading').show();
            $.ajax({
                url: "{{route('ajaxCheckSellPostCalc')}}",
                type: 'POST',
                data: {
                    sellPostId,
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


    </script>
@endpush
