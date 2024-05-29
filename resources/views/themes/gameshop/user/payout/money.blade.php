@extends($theme.'layouts.user')
@section('title', trans($title))

@section('content')

    <section class="login-section">
        <div class="container">
            <div class="custom-card bg-gradient">
                <div class="card-body gradient-bg">
                    <div class="row">
                        @foreach($gateways as $key => $gateway)
                            <div class="col-xl-2 col-lg-3 col-md-3 col-sm-6 mb-4">
                                <div class="card card-type-1 text-center">
                                    <div class="card-icon">
                                        <img class="w-100 gateway" src="{{ getFile(config('location.withdraw.path').$gateway->image)}}"
                                             alt="{{$gateway->name}}">
                                    </div>
                                    <button type="button"
                                            data-id="{{$gateway->id}}"
                                            data-name="{{$gateway->name}}"
                                            data-min_amount="{{getAmount($gateway->minimum_amount, $basic->fraction_number)}}"
                                            data-max_amount="{{getAmount($gateway->maximum_amount,$basic->fraction_number)}}"
                                            data-percent_charge="{{getAmount($gateway->percent_charge,$basic->fraction_number)}}"
                                            data-fix_charge="{{getAmount($gateway->fixed_charge, $basic->fraction_number)}}"
                                            class="btn btn-site btn-block  w-100 addFund"
                                            data-backdrop='static' data-keyboard='false'
                                            data-bs-toggle="modal" data-bs-target="#addFundModal">@lang('PAYOUT NOW')
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    @push('loadModal')
        <div id="addFundModal" class="modal fade addFundModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content form-block">
                    <div class="modal-header-custom modal-colored-header bg-custom">
                        <h4 class="modal-title" id="myModalLabel">@lang('Pay Out')</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>

                    <form action="{{route('user.payout.moneyRequest')}}" method="post">
                        @csrf
                    <div class="modal-body-custom">
                        <div class="payment-form withdraw-detail">
                            <p class="text-danger depositLimit"></p>
                            <p class="text-danger depositCharge"></p>
                            <input type="hidden" class="gateway" name="gateway" value="">
                            <div class="form-group mb-30">
                                <label>@lang('Amount')</label>
                                <div class="input-group">
                                    <input type="text" class="amount form-control earn" name="amount">
                                    <div class="input-group-append">
                                        <button class="btn btn-success-custom copy-btn show-currency" type="button"></button>
                                    </div>
                                </div>
                                @error('amount')
                                <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer-custom">
                        <button type="submit" class="btn btn-success-custom">@lang('Next')</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    @endpush
@endsection
@push('script')

    @if(count($errors) > 0 )
        <script>
            @foreach($errors->all() as $key => $error)
            Notiflix.Notify.Failure("@lang($error)");
            @endforeach
        </script>
    @endif

    <script>
        "use strict";

        var id, minAmount, maxAmount, baseSymbol, fixCharge, percentCharge, currency, gateway;

        $('.addFund').on('click', function () {
            id = $(this).data('id');
            gateway = $(this).data('gateway');
            minAmount = $(this).data('min_amount');
            maxAmount = $(this).data('max_amount');
            baseSymbol = "{{config('basic.currency_symbol')}}";
            fixCharge = $(this).data('fix_charge');
            percentCharge = $(this).data('percent_charge');
            currency = $(this).data('currency');
            $('.depositLimit').text(`@lang('Transaction Limit:') ${minAmount} - ${maxAmount}  ${baseSymbol}`);

            var depositCharge = `@lang('Charge:') ${fixCharge} ${baseSymbol}  ${(0 < percentCharge) ? ' + ' + percentCharge + ' % ' : ''}`;
            $('.depositCharge').text(depositCharge);
            $('.method-name').text(`@lang('Payout By') ${$(this).data('name')}`);
            $('.show-currency').text("{{config('basic.currency')}}");
            $('.gateway').val(id);
        });
        $('.close').on('click', function (e) {
            $('#loading').hide();
            $('.amount').val(``);
            $("#addFundModal").modal("hide");
        });

    </script>
@endpush

