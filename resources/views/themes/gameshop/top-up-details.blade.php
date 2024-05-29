@extends($theme . 'layouts.app')
@section('title', trans('Product Details'))

@section('content')

    <!-- DETAILS SECTION -->
    <section class="details-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 pe-lg-5 mb-5 mb-md-0">
                    <div class="img-box">
                        <img class="img-fluid"
                             src="{{ getFile(config('location.category.path') . @$topUpDetails->image) }}"
                             alt="..."/>
                    </div>
                    <div class="text-box">
                        <h4>@lang(@$topUpDetails->details->name)</h4>
                        <p>
                            @lang(@$topUpDetails->details->details)
                        </p>
                        @if($topUpDetails->playStoreLink)
                            <a href="{{ $topUpDetails->playStoreLink }}">
                                <img class="img-fluid" src="{{ asset($themeTrue) . '/images/google.png' }}"
                                     alt="..."/>
                            </a>
                        @endif
                        @if($topUpDetails->appStoreLink)
                            <a href="{{ $topUpDetails->appStoreLink }}">
                                <img class="img-fluid" src="{{ asset($themeTrue) . '/images/app.png' }}"
                                     alt="..."/>
                            </a>
                        @endif
                    </div>
                </div>
                <div class="col-lg-8 col-md-6">
                    <form action="{{route('user.topUp.payment')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        @if ($topUpDetails->form_field)
                            <!-- PLAYER ID -->
                            <div class="order-box">
                                <h5>@lang('Enter Information')</h5>
                                <div class="input-box d-flex">
                                    <div class="d-flex flex-wrap justify-content-between">
                                        @foreach ($topUpDetails->form_field as $k => $v)
                                            @if ($v->type == 'text')
                                                <div>
                                                    <input name="{{ $k }}" type="text" class="form-control"
                                                           value="{{old($k)}}"
                                                           placeholder="{{ trans($v->field_level) }}"
                                                           @if ($v->validation == 'required') required @endif />

                                                    @if ($errors->has($k))

                                                        <br><span
                                                            class="text-danger">{{ trans($errors->first($k)) }}</span>
                                                    @endif
                                                </div>
                                            @elseif($v->type == 'textarea')
                                                <div>
                                                    <textarea name="{{ $k }}" class="form-control"
                                                              @if ($v->validation == 'required') required @endif>{{old($k)}}</textarea>
                                                    @if ($errors->has($k))
                                                        <span class="text-danger">{{ trans($errors->first($k)) }}</span>
                                                    @endif

                                                </div>
                                            @elseif($v->type == 'file')
                                                <div>
                                                    <input name="{{ $k }}" type="file" class="form-control"
                                                           placeholder="{{ trans($v->field_level) }}"
                                                           @if ($v->validation == 'required')  required @endif />
                                                    @if ($errors->has($k))
                                                        <span class="text-danger">{{ trans($errors->first($k)) }}</span>
                                                    @endif
                                                </div>
                                            @endif
                                        @endforeach


                                    </div>
                                    @if ($topUpDetails->instruction_image)
                                        <span class="info">
                                        <img class="info-icon" src="{{ asset($themeTrue) . '/images/icon/info.png' }}"
                                             alt="..."/>
                                        <img class="hovered img-fluid"
                                             src="{{ getFile(config('location.category.path') . @$topUpDetails->instruction_image) }}"
                                             alt="..."/>
                                    </span>
                                    @endif
                                </div>
                                <p>
                                    @lang(@$topUpDetails->details->instruction)
                                </p>
                            </div>
                        @endif

                        @if($topUpDetails->categoryService)
                            <div class="payment-box">
                                <div class="d-flex justify-content-between">
                                    <h5>@lang('SELECT RECHARGE')</h5>
                                    @error('service')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                                <div
                                    class="d-flex flex-wrap justify-content-start">
                                    @forelse($topUpDetails->categoryService as $key => $item)
                                        <div class="m-1 package-list ">
                                            <input type="radio" class="btn-check recharge-check" name="service" required
                                                   id="option{{ $item->id }}" autocomplete="off"
                                                   value="{{$item->id}}" @if(old('service') == $item->id) checked @endif
                                                   data-price="{{ $item->price }}" data-discount="">

                                            <label class="btn btn-primary" for="option{{ $item->id }}">
                                                {{ $item->name }}
                                                <img src="{{ asset($themeTrue) . '/images/icon/check.png' }}" alt="..."
                                                     class="check"/>
                                            </label>
                                        </div>
                                    @empty

                                    @endforelse

                                </div>
                            </div>
                        @endif


                        @if(0 < count($gateways))
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
                        @endif

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
            </div>
        </div>
    </section>

    <section class="package-details">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 mb-5 mb-md-0">
                    <div class="description-box">
                        <div class="navigator">
                            <button class="navigate active" id="descriptionBtn">
                                @lang('Description')
                            </button>
                            <button class="navigate" id="instructionBtn">
                                @lang('Instruction')
                            </button>
                        </div>
                        <div id="description" class="mb-5">
                            <h4>@lang('Description Area')</h4>
                            <p>
                                @lang(@$topUpDetails->details->details)
                            </p>
                        </div>
                        <div id="instruction" class="mb-5">
                            <h4>@lang('Instruction')</h4>
                            <p>
                                @lang(@$topUpDetails->details->instruction)
                            </p>
                            <img class="img-fluid mb-4"
                                 src="{{ getFile(config('location.category.path') . @$topUpDetails->instruction_image) }}"
                                 alt="..."/>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </section>
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
                url: "{{route('ajaxCheckTopUpCalc')}}",
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

        $(document).on('click', '#instructionBtn', function () {
            $('#instruction').removeClass('d-none');
            $('#instruction').addClass('d-block');
            $('#description').addClass('d-none');

        })
        $(document).on('click', '#descriptionBtn', function () {
            $('#description').removeClass('d-none');
            $('#description').addClass('d-block');
            $('#instruction').addClass('d-none');
        })
    </script>
@endpush
