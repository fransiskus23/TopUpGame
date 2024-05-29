@extends($theme.'layouts.user')
@section('title')
    @lang('Top Up Orders')
@endsection
@section('content')

    <div class="login-section">

        <div class="container ">
            <div class="row justify-content-between bg-gradient">
                <div class="col-md-12">

                    <div class="contact-box mb-3 mx-2">
                        <form action="{{route('user.topUpOrder.search')}}" method="get">
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

                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered transection__table mt-5" id="service-table">
                            <thead>
                            <tr>
                                <th scope="col">@lang('No.')</th>
                                <th scope="col">@lang('TRX')</th>
                                <th scope="col">@lang('Category')</th>
                                <th scope="col">@lang('Service')</th>
                                <th scope="col">@lang('Price')</th>
                                <th scope="col">@lang('Status')</th>
                                <th scope="col">@lang('Date - Time')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($topUpOrders as $k => $row)
                                <tr>
                                    <td data-label="@lang('No.')">{{++$k}}</td>
                                    <td data-label="@lang('TRX')">@lang($row->transaction)</td>
                                    <td data-label="@lang('Category')">@lang($row->category->details->name)</td>
                                    <td data-label="@lang('Service')">@lang($row->service->name)</td>
                                    <td data-label="@lang('Price')"><span
                                            class="font-weight-bold">{{config('basic.currency_symbol')}}</span>{{getAmount($row->price)}}
                                    </td>
                                    <td data-label="@lang('Status')">
                                        @if($row->status == 1)
                                            <span class="font-weight-bold text-success">@lang('Complete')</span>
                                        @elseif($row->status == 0)
                                            <span class="font-weight-bold text-warning">@lang('Pending')</span>
                                        @endif
                                    </td>

                                    <td data-label="@lang('Date - Time')">{{dateTime($row->created_at, 'd M, Y h:i A')}}</td>
                                </tr>

                            @empty
                                <tr>
                                    <td class="text-center text-danger" colspan="100%">@lang('No Data Found')</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>

                    </div>
                    {{ $topUpOrders->appends($_GET)->links($theme.'partials.pagination') }}

                </div>
            </div>
        </div>
    </div>
@endsection
