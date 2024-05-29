@extends($theme.'layouts.user')
@section('title',trans($title))
@section('content')
    <section class="container login-section">
            <div class="row justify-content-center bg-gradient">
                <div class="col-md-12">
                    <div class="contact-box mb-3 mx-2">
                        <div class="card-body">
                            <form action="{{ route('user.payout.history.search') }}" method="get">
                                <div class="row justify-content-between">
                                    <div class="col-md-3">
                                        <div class="form-group mb-2">
                                            <input type="text" name="name" value="{{@request()->name}}"
                                                   class="form-control"
                                                   placeholder="@lang('Type Here')">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group mb-2">
                                            <select name="status" class="form-control">
                                                <option value="">@lang('All Payment')</option>
                                                <option value="1"
                                                        @if(@request()->status == '1') selected @endif>@lang('Pending Payment')</option>
                                                <option value="2"
                                                        @if(@request()->status == '2') selected @endif>@lang('Complete Payment')</option>
                                                <option value="3"
                                                        @if(@request()->status == '3') selected @endif>@lang('Rejected Payment')</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group mb-2">
                                            <input type="date" class="form-control" name="date_time"
                                                   id="datepicker"/>
                                        </div>
                                    </div>


                                    <div class="col-md-3">
                                        <div class="form-group mb-2 h-fill">
                                            <button type="submit" class="game-btn w-100">
                                                <i
                                                    class="fas fa-search"></i> @lang('Search')</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered transection__table mt-5">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">@lang('Transaction ID')</th>
                            <th scope="col">@lang('Gateway')</th>
                            <th scope="col">@lang('Amount')</th>
                            <th scope="col">@lang('Charge')</th>
                            <th scope="col">@lang('Status')</th>
                            <th scope="col">@lang('Time')</th>
                            <th scope="col">@lang('Details')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($payoutLog as $item)
                            <tr>
                                <td data-label="#@lang('Transaction ID')">{{$item->trx_id}}</td>
                                <td data-label="@lang('Gateway')">@lang(optional($item->method)->name)</td>
                                <td data-label="@lang('Amount')">
                                    <strong>{{getAmount($item->amount)}} @lang($basic->currency)</strong>
                                </td>
                                <td data-label="@lang('Charge')">
                                    <strong>{{getAmount($item->charge)}} @lang($basic->currency)</strong>
                                </td>

                                <td data-label="@lang('Status')">
                                    @if($item->status == 1)
                                        <span class="text-warning">@lang('Pending')</span>
                                    @elseif($item->status == 2)
                                        <span class="text-success">@lang('Complete')</span>
                                    @elseif($item->status == 3)
                                        <span class="text-danger">@lang('Cancel')</span>
                                    @endif
                                </td>

                                <td data-label="@lang('Time')">
                                    {{ dateTime($item->created_at, 'd M Y h:i A') }}
                                </td>
                                <td data-label="@lang('Details')">
                                    <button type="button" class="btn btn-custom btn-sm infoButton "
                                            data-information="{{json_encode($item->information)}}"
                                            data-feedback="{{$item->feedback}}"
                                            data-trx_id="{{ $item->trx_id }}"><i
                                            class="fa fa-info-circle"></i></button>
                                </td>

                            </tr>
                        @empty

                            <tr class="text-center">
                                <td colspan="100%">{{trans('No Data Found!')}}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                </div>

                {{ $payoutLog->appends($_GET)->links($theme.'partials.pagination') }}
            </div>
    </section>

    <div id="infoModal" class="modal fade" tabindex="-1" data-backdrop="static"  role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">

                <div class="modal-header-custom modal-colored-header bg-custom">
                    <h5 class="modal-title">@lang('Details')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body-custom">
                    <ul class="list-group ">
                        <li class="list-group-item bg-transparent">@lang('Transactions') : <span class="trx"></span>
                        </li>
                        <li class="list-group-item bg-transparent">@lang('Admin Feedback') : <span
                                class="feedback"></span></li>
                    </ul>
                    <div class="payout-detail">

                    </div>
                </div>

                <div class="modal-footer-custom">
                    <button type="button" class="btn btn-secondary closeModal" data-bs-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')

    <script>
        "use strict";

        $(document).ready(function () {
            $('.infoButton').on('click', function () {
                var infoModal = $('#infoModal');
                infoModal.find('.trx').text($(this).data('trx_id'));
                infoModal.find('.feedback').text($(this).data('feedback'));
                var list = [];
                var information = Object.entries($(this).data('information'));

                var ImgPath = "{{asset(config('location.withdrawLog.path'))}}/";
                var result = ``;
                for (var i = 0; i < information.length; i++) {
                    if (information[i][1].type == 'file') {
                        result += `<li class="list-group-item bg-transparent">
                                            <span class="font-weight-bold "> ${information[i][0].replaceAll('_', " ")} </span> : <img class="w-100"src="${ImgPath}/${information[i][1].field_name}" alt="..." class="w-100">
                                        </li>`;
                    } else {
                        result += `<li class="list-group-item bg-transparent">
                                            <span class="font-weight-bold "> ${information[i][0].replaceAll('_', " ")} </span> : <span class="font-weight-bold ml-3">${information[i][1].field_name}</span>
                                        </li>`;
                    }
                }

                if (result) {
                    infoModal.find('.payout-detail').html(`<br><strong class="my-3">@lang('Payment Information')</strong>  ${result}`);
                } else {
                    infoModal.find('.payout-detail').html(`${result}`);
                }
                infoModal.modal('show');
            });


            $('.closeModal').on('click', function (e) {
                $("#infoModal").modal("hide");
            });
        });

    </script>
@endpush
