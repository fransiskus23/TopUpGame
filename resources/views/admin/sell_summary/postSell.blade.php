@extends('admin.layouts.app')
@section('title',trans(@$title))
@section('content')

    <div class="page-header card card-primary m-0 m-md-4 my-4 m-md-0 p-5 shadow">
        <div class="row justify-content-between">
            <div class="col-md-12">
                <form action="{{route('admin.postSell.search')}}" method="get">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" name="transaction_id" value="{{@request()->transaction_id}}"
                                       class="form-control get-trx-id"
                                       placeholder="@lang('Search for Transaction ID')">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" name="user_name" value="{{@request()->user_name}}"
                                       class="form-control get-username"
                                       placeholder="@lang('Username')">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="date" class="form-control" name="datetrx" id="datepicker"/>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <button type="submit" class="btn waves-effect waves-light btn-primary"><i
                                        class="fas fa-search"></i> @lang('Search')</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
        <div class="card-body">
            <div class="dropdown mb-2 text-right">
                <button class="btn btn-sm  btn-dark dropdown-toggle" type="button" id="dropdownMenuButton"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span><i class="fas fa-bars pr-2"></i> @lang('Action')</span>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <button class="dropdown-item" type="button" data-toggle="modal"
                            data-target="#all_hold">@lang('Hold')</button>
                    <button class="dropdown-item" type="button" data-toggle="modal"
                            data-target="#all_release">@lang('Unhold')</button>
                </div>
            </div>

            <table class="categories-show-table table table-hover table-striped table-bordered">
                <thead class="thead-dark">
                <tr>
                    <th scope="col" class="text-center">
                        <input type="checkbox" class="form-check-input check-all tic-check" name="check-all"
                               id="check-all">
                        <label for="check-all"></label>
                    </th>

                    <th scope="col">@lang('Seller')</th>
                    <th scope="col">@lang('Buyer')</th>
                    <th scope="col">@lang('Title')</th>
                    <th scope="col">@lang('Payment')</th>
                    <th scope="col">@lang('Seller Get')</th>
                    <th scope="col">@lang('TRX')</th>
                    <th scope="col">@lang('Payment')</th>
                    <th scope="col">@lang('Payment At')</th>
                    <th scope="col">@lang('More')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($postSell as $k => $row)
                    <tr>
                        <td class="text-center">
                            <input type="checkbox" id="chk-{{ $row->id }}"
                                   class="form-check-input row-tic tic-check" name="check" value="{{$row->id}}"
                                   data-id="{{ $row->id }}">
                            <label for="chk-{{ $row->id }}"></label>
                        </td>

                        <td data-label="@lang('Seller')">
                            <a href="{{route('admin.user-edit',optional($row->sellPost)->user_id??0)}}">
                                <div class="d-flex no-block align-items-center">
                                    <div class="mr-3"><img
                                            src="{{getFile(config('location.user.path').optional(@$row->sellPost->user)->image) }}"
                                            alt="user" class="rounded-circle" width="45" height="45"></div>
                                    <div class="">
                                        <h5 class="text-dark mb-0 font-16 font-weight-medium">@lang(optional(@$row->sellPost->user)->username)</h5>
                                        <span
                                            class="text-muted font-14">{{optional(@$row->sellPost->user)->email}}</span>
                                    </div>
                                </div>
                            </a>
                        </td>


                        <td data-label="@lang('Buyer')">
                            <a href="{{route('admin.user-edit',$row->user_id)}}">
                                <div class="d-flex no-block align-items-center">
                                    <div class="mr-3"><img
                                            src="{{getFile(config('location.user.path').optional(@$row->user)->image) }}"
                                            alt="user" class="rounded-circle" width="45" height="45"></div>
                                    <div class="">
                                        <h5 class="text-dark mb-0 font-16 font-weight-medium">@lang(optional(@$row->user)->username)</h5>
                                        <span class="text-muted font-14">{{optional(@$row->user)->email}}</span>
                                    </div>
                                </div>
                            </a>
                        </td>

                        <td data-label="@lang('Title')"><a
                                href="{{route('sellPost.details',[slug(optional(@$row->sellPost)->title),optional(@$row->sellPost)->id])}}">{{optional(@$row->sellPost)->title}}</a>
                        </td>
                        <td data-label="@lang('Payment')"><span>{{config('basic.currency_symbol')}}</span><span
                                class="font-weight-bold">{{getAmount($row->price)}}</span>
                        </td>

                        <td data-label="@lang('Seller Get')"><span
                                class="font-weight-bold">{{config('basic.currency_symbol')}}</span>{{getAmount($row->seller_amount)}}
                            <sup class="badge badge-light badge-pill text-danger">
                                - {{config('basic.currency_symbol')}}{{getAmount($row->admin_amount)}} <span
                                    class="text-dark">@lang('Charge')</span></sup>
                        </td>


                        <td data-label="@lang('TRX')">@lang($row->transaction)</td>
                        <td data-label="@lang('Payment')">

                            @if($row->payment_release ==1 )
                                <span class="badge badge-light"><i
                                        class="fa fa-circle text-success success font-12"></i> @lang('Released')</span>
                            @elseif($row->payment_release ==0 )
                                <span class="badge badge-light"><i
                                        class="fa fa-circle text-warning pending font-12"></i> @lang('Upcoming')</span>
                            @else
                                <span class="badge badge-light"><i
                                        class="fa fa-circle text-danger danger font-12"></i> @lang('Hold')</span>
                            @endif

                        </td>
                        <td data-label="@lang('Payment At')">

                            @if($row->payment_release ==1 )
                                {{Carbon\Carbon::parse($row->released_at)->format('d M, Y H:i')}}
                            @elseif($row->payment_release ==0 )
                                {{Carbon\Carbon::parse($row->created_at)->addDays(config('basic.payment_released'))->format('d M, Y H:i')}}
                            @else
                                -
                            @endif
                        </td>
                        <td data-label="@lang('More')">
                            @php
                                $details = ($row->sellPost->credential != null) ? json_encode($row->sellPost->credential) : null;
                            @endphp


                            <div class="dropdown show">
                                <a class="dropdown-toggle p-3" href="#" id="dropdownMenuLink"
                                   data-toggle="dropdown"
                                   aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item edit_button cursor-pointer text-dark"
                                       data-info="{{$details}}"
                                       data-toggle="modal" data-target="#myModal">
                                        <i class="fa fa-info-circle text-primary pr-2"
                                           aria-hidden="true"></i> @lang('Credentials')
                                    </a>

                                    @if($row->payment_release == 0)
                                        <a class="dropdown-item holdBtn"
                                           href="javascript:void(0)"
                                           data-resource="{{$row->id}}"
                                           data-toggle="modal"
                                           data-target="#hold-modal">
                                            <i class="fa fa-lock text-danger pr-2"
                                               aria-hidden="true"></i> @lang('Hold Payment')
                                        </a>
                                    @elseif($row->payment_release == 2)
                                        <a class="dropdown-item unholdBtn"
                                           href="javascript:void(0)"
                                           data-resource="{{$row->id}}"
                                           data-toggle="modal"
                                           data-target="#unhold-modal">
                                            <i class="fa fa-unlock text-warning pr-2"
                                               aria-hidden="true"></i> @lang('Unhold Payment')
                                        </a>
                                    @endif
                                </div>
                            </div>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center" colspan="100%">@lang('No Data Found')</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            {{ $postSell->links('partials.pagination') }}
        </div>
    </div>

    <!-- All Hold Modal -->
    <div class="modal fade" id="all_hold" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h5 class="modal-title">@lang('Payment Hold Confirmation')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                </div>
                <div class="modal-body">
                    <p>@lang("Are you really want to hold payments")</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal"><span>@lang('No')</span></button>
                    <form action="" method="post">
                        @csrf
                        <a href="" class="btn btn-primary active-yes"><span>@lang('Yes')</span></a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- All Un Hold Modal -->
    <div class="modal fade" id="all_release" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h5 class="modal-title">@lang('Payment Un Hold Confirmation')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                </div>
                <div class="modal-body">
                    <p>@lang("Are you really want to Un Hold payments")</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal"><span>@lang('No')</span></button>
                    <form action="" method="post">
                        @csrf
                        <a href="" class="btn btn-primary inactive-yes"><span>@lang('Yes')</span></a>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Hold Modal -->
    <div id="hold-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="primary-header-modalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h4 class="modal-title" id="primary-header-modalLabel">@lang('Hold Confirmation')
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                    </button>
                </div>
                <div class="modal-body">
                    <p>@lang('Are you sure to hold this payment?')</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">@lang('Close')</button>
                    <form action="{{route('admin.paymentHold')}}" method="post">
                        @csrf
                        @method('post')
                        <input type="hidden" class="hold" value="" name="id">
                        <button type="submit" class="btn btn-primary">@lang('Yes')</button>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- Un Hold Modal -->
    <div id="unhold-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="primary-header-modalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h4 class="modal-title" id="primary-header-modalLabel">@lang('Unhold Confirmation')
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                    </button>
                </div>
                <div class="modal-body">
                    <p>@lang('Are you sure to Unhold this payment?')</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">@lang('Close')</button>
                    <form action="{{route('admin.paymentUnhold')}}" method="post">
                        @csrf
                        @method('post')
                        <input type="hidden" class="unhold" value="" name="id">
                        <button type="submit" class="btn btn-primary">@lang('Yes')</button>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    <!-- Modal for Credentials -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content ">
                <div class="modal-header modal-colored-header bg-primary">
                    <h4 class="modal-title" id="myModalLabel">@lang('Credentials')</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <ul class="list-group withdraw-detail">
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <script>
        "use strict";

        $(document).on('click', '.holdBtn', function () {
            $('.hold').val($(this).data('resource'));
        });

        $(document).on('click', '.unholdBtn', function () {
            $('.unhold').val($(this).data('resource'));
        });

        $(document).on('click', '#check-all', function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });

        $(document).on('change', ".row-tic", function () {
            let length = $(".row-tic").length;
            let checkedLength = $(".row-tic:checked").length;
            if (length == checkedLength) {
                $('#check-all').prop('checked', true);
            } else {
                $('#check-all').prop('checked', false);
            }
        });

        //dropdown menu is not working
        $(document).on('click', '.dropdown-menu', function (e) {
            e.stopPropagation();
        });

        $(document).ready(function () {
            $(document).on("click", '.edit_button', function (e) {

                var details = Object.entries($(this).data('info'));
                var list = [];
                details.map(function (item, i) {
                    var singleInfo = `<span class="font-weight-bold ml-3">${item[1].field_value}</span>  `;
                    list[i] = ` <li class="list-group-item"><span class="font-weight-bold "> ${item[0].replace('_', " ")} </span> : ${singleInfo}</li>`
                });

                $('.withdraw-detail').html(list);
            });
        });

        //multiple active
        $(document).on('click', '.active-yes', function (e) {
            e.preventDefault();
            var allVals = [];
            $(".row-tic:checked").each(function () {
                allVals.push($(this).attr('data-id'));
            });

            var strIds = allVals;

            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                url: "{{ route('admin.holdMultiple') }}",
                data: {strIds: strIds},
                datatType: 'json',
                type: "post",
                success: function (data) {
                    location.reload();

                },
            });
        });

        //multiple deactive
        $(document).on('click', '.inactive-yes', function (e) {
            e.preventDefault();
            var allVals = [];
            $(".row-tic:checked").each(function () {
                allVals.push($(this).attr('data-id'));
            });

            var strIds = allVals;
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                url: "{{ route('admin.releaseMultiple') }}",
                data: {strIds: strIds},
                datatType: 'json',
                type: "post",
                success: function (data) {
                    location.reload();

                }
            });
        });
    </script>
@endpush
