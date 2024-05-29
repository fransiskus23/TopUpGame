@extends($theme.'layouts.user')
@section('title')
    @lang('ID Purchases')
@endsection
@section('content')
    <div class="container login-section">
        <div class="row justify-content-between bg-gradient">
            <div class="col-md-12">
                <div class="contact-box mb-3 mx-2">
                    <form action="{{route('user.sellPostOrder.search')}}" method="get">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-2">
                                    <input type="text" name="transaction_id"
                                           value="{{@request()->transaction_id}}"
                                           class="form-control"
                                           placeholder="@lang('Search for Transaction ID')">
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group mb-2">
                                    <input type="date" class="form-control" name="datetrx" id="datepicker"/>
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
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">@lang('No.')</th>
                        <th scope="col">@lang('TRX')</th>
                        <th scope="col">@lang('Category')</th>
                        <th scope="col">@lang('Title')</th>
                        <th scope="col">@lang('Amount')</th>
                        <th scope="col">@lang('Date - Time')</th>
                        <th scope="col">@lang('More')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($sellPostOrders as $k => $row)
                        <tr>
                            <td data-label="@lang('No.')">{{++$k}}</td>
                            <td data-label="@lang('TRX')">@lang($row->transaction)</td>
                            <td data-label="@lang('Category')">@lang(@optional($row->sellPost)->category->details->name)</td>
                            <td data-label="@lang('Title')">@lang(@optional($row->sellPost)->title)</td>
                            <td data-label="@lang('Amount')"><span
                                    class="font-weight-bold">{{config('basic.currency_symbol')}}</span>{{getAmount($row->price)}}
                                @if(0 <$row->discount)
                                    <sup
                                        class="badge-light badge-pill ">  {{config('basic.currency_symbol')}}{{getAmount($row->discount)}} @lang('Off')</sup>
                                @endif
                            </td>
                            <td data-label="@lang('Date - Time')">{{dateTime($row->created_at, 'd M, Y h:i A')}}</td>
                            <td data-label="@lang('More')">
                                @php
                                    $details = ($row->sellPost->credential != null) ? json_encode($row->sellPost->credential) : null;
                                @endphp
                                <button type="button" class="btn btn-custom btn-icon edit_button"
                                        data-bs-toggle="modal" data-bs-target="#credentialShow"
                                        data-info="{{$details}}"
                                        data-id=""
                                >
                                    <i class="fa fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center text-danger" colspan="9">@lang('No Data Found')</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            {{ $sellPostOrders->appends($_GET)->links($theme.'partials.pagination') }}
        </div>
    </div>

@endsection
@push('loadModal')
    <!-- Modal for Code Show -->
    <div class="modal fade" id="credentialShow" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content ">
                <div class="modal-header-custom modal-colored-header bg-custom">
                    <h4 class="modal-title" id="myModalLabel">@lang('Credentials')</h4>
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
        $(document).ready(function () {
            $(document).on("click", '.edit_button', function (e) {

                var details = Object.entries($(this).data('info'));
                var list = [];
                details.map(function (item, i) {
                    list[i] = `<div class="form-group">
                                 <label>${item[1].field_name}</label>
                                 <div class="input-group mb-3 ">
                                     <input type="text" id="codeVisible_${i}" class="form-control copyText" value=${item[1].field_value} readonly />
                                    <div class="input-group-append">
                                        <button class="btn btn-custom text-white copy-btn" data-id=${i} data-code=${item[1]} type="button"><i class="fa fa-copy"></i></button>
                                    </div>
                                </div>
                                </div>`
                });

                $('.withdraw-detail').html(list);
            });

            $(document).on('click', '.copy-btn', function () {
                var _this = $(this)[0];
                var copyText = $(this).parents('.input-group-append').siblings('input');
                console.log(copyText);
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
