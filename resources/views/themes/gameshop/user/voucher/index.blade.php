@extends($theme . 'layouts.user')
@section('title')
    @lang('Voucher Orders')
@endsection
@section('content')
    <div class="container login-section">
        <div class="row justify-content-between bg-gradient">
            <div class="col-md-12">
                <div class="contact-box mb-3 mx-2">
                    <form action="{{ route('user.voucherOrder.search') }}" method="get">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-2">
                                    <input type="text" name="transaction_id" value="{{ @request()->transaction_id }}"
                                        class="form-control" placeholder="@lang('Search for Transaction ID')">
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group mb-2">
                                    <input type="date" class="form-control" name="datetrx" id="datepicker" />
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-2 h-fill">
                                    <button type="submit" class="game-btn w-100">
                                        <i class="fas fa-search"></i> @lang('Search')</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-bordered transection__table mt-5" id="service-table">
                    <thead>
                        <tr>
                            <th scope="col">@lang('No.')</th>
                            <th scope="col">@lang('TRX')</th>
                            <th scope="col">@lang('Voucher')</th>
                            <th scope="col">@lang('Service')</th>
                            <th scope="col">@lang('Price')</th>
                            <th scope="col">@lang('Status')</th>
                            <th scope="col">@lang('Date - Time')</th>
                            <th scope="col">@lang('More')</th>
                        </tr>
                    </thead>
                    <tbody>

                        @forelse($voucherOrders as $k => $row)
                            <tr>
                                <td data-label="@lang('No.')">{{ ++$k }}</td>
                                <td data-label="@lang('TRX')">@lang($row->transaction)</td>
                                <td data-label="@lang('Voucher')">@lang($row->voucher->details->name)</td>
                                <td data-label="@lang('Service')">@lang($row->service->name)</td>
                                <td data-label="@lang('Price')"><span
                                        class="font-weight-bold">{{ config('basic.currency_symbol') }}</span>{{ getAmount($row->price) }}
                                </td>
                                <td data-label="@lang('Status')">
                                    @if ($row->status == 1)
                                        <span class="font-weight-bold text-success">@lang('Complete')</span>
                                    @elseif($row->status == 0)
                                        <span class="font-weight-bold text-warning">@lang('Pending')</span>
                                    @endif
                                </td>

                                <td data-label="@lang('Date - Time')">{{ dateTime($row->created_at, 'd M, Y h:i A') }}</td>
                                <td data-label="@lang('More')">
                                    @php
                                        $details = $row->code != null ? json_encode($row->code) : null;
                                    @endphp
                                    <button type="button" class="btn btn-custom btn-icon edit_button"
                                        data-bs-toggle="modal" data-bs-target="#codeShow" data-info="{{ $details }}"
                                        data-id="{{ $row->id }}">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td class="text-center text-danger" colspan="100%">@lang('No Data Found')</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{ $voucherOrders->appends($_GET)->links($theme . 'partials.pagination') }}

            </div>
        </div>
    </div>
@endsection
@push('loadModal')
    <!-- Modal for Code Show -->
    <div class="modal fade" id="codeShow" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content ">
                <div class="modal-header-custom modal-colored-header bg-custom">
                    <h4 class="modal-title" id="myModalLabel">@lang('Voucher Code')</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>



                <div class="modal-body-custom">

                    <div class="withdraw-detail">

                    </div>

                </div>
                <div class="modal-footer-custom">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Close')
                    </button>
                </div>

            </div>
        </div>
    </div>

@endpush
@push('script')
    <script>
        "use strict";
        $(document).ready(function() {
            $(document).on("click", '.edit_button', function(e) {
                var id = $(this).data('id');
                var details = Object.entries($(this).data('info'));
                var list = [];
                details.map(function(item, i) {

                    list[i] = `<div class="input-group mb-3 ">
                                    <div class="input-group-prepend">
                                        <button class="btn btn-success-custom visible" data-id=${i} type="button"><i class="fa fa-eye"></i></button>
                                    </div>
                                     <input type="password" id="codeVisible_${i}" class="form-control copyText" value=${item[1]} readonly />
                                    <div class="input-group-append">
                                        <button class="btn btn-success-custom copy-btn" data-id=${i} data-code=${item[1]} type="button"><i class="fa fa-copy"></i></button>
                                    </div>
                                </div>`
                });

                $('.withdraw-detail').html(list);
            });

            $(document).on("click", '.visible', function(e) {
                var k = $(this).data('id');

                var id_comp = "#codeVisible_" + k;


                if ($(id_comp).attr('type') == 'password') {
                    $(id_comp).attr('type', 'text');
                    $(this).children().removeClass('fa fa-eye');
                    $(this).children().addClass('fa fa-eye-slash');
                } else {
                    $(id_comp).attr('type', 'password');
                    $(this).children().removeClass('fa fa-eye-slash');
                    $(this).children().addClass('fa fa-eye');
                }
            })

            $(document).on('click', '.copy-btn', function () {
                var _this = $(this)[0];
                var copyText = $(this).parents('.input-group-append').siblings('input');
                $(copyText).prop('disabled', false);
                copyText.select();
                document.execCommand("copy");
                $(copyText).prop('disabled', true);
                $(this).text('Coppied');
                setTimeout(function () {
                    $(_this).text('');
                    $(_this).html('<i class="fas fa-copy"></i>');
                }, 500)
            });

        });
    </script>
@endpush
