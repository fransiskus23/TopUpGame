@extends($theme.'layouts.user')
@section('title',trans('Payment Log'))
@section('content')

        <div class="container login-section">
            <div class="row justify-content-center bg-gradient">
                <div class="col-md-12">
                    <div class="contact-box mb-3 mx-2">
                        <form action="{{ route('user.fund-history.search') }}" method="get">
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
                                        <select name="status" class="form-control style-two">
                                            <option value="">@lang('All Payment')</option>
                                            <option value="1"
                                                    @if(@request()->status == '1') selected @endif>@lang('Complete Payment')</option>
                                            <option value="2"
                                                    @if(@request()->status == '2') selected @endif>@lang('Pending Payment')</option>
                                            <option value="3"
                                                    @if(@request()->status == '3') selected @endif>@lang('Cancel Payment')</option>
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
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered transection__table mt-5" id="service-table">
                        <thead>
                            <tr>
                                <th scope="col">@lang('Transaction ID')</th>
                                <th scope="col">@lang('Gateway')</th>
                                <th scope="col">@lang('Amount')</th>
                                <th scope="col">@lang('Charge')</th>
                                <th scope="col">@lang('Status')</th>
                                <th scope="col">@lang('Time')</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($funds as $data)
                            <tr>

                                <td data-label="#@lang('Transaction ID')">{{$data->transaction}}</td>
                                <td data-label="@lang('Gateway')">@lang(optional($data->gateway)->name)</td>
                                <td data-label="@lang('Amount')">
                                    <strong>{{getAmount($data->amount)}} @lang($basic->currency)</strong>
                                </td>

                                <td data-label="@lang('Charge')">
                                    <strong>{{getAmount($data->charge)}} @lang($basic->currency)</strong>
                                </td>

                                <td data-label="@lang('Status')">
                                    @if($data->status == 1)
                                        <span class="font-weight-bold text-success">@lang('Complete')</span>
                                    @elseif($data->status == 2)
                                        <span class="font-weight-bold text-warning">@lang('Pending')</span>
                                    @elseif($data->status == 3)
                                        <span class="font-weight-bold text-danger">@lang('Cancel')</span>
                                    @endif
                                </td>

                                <td data-label="@lang('Time')">
                                    {{ dateTime($data->created_at, 'd M Y h:i A') }}
                                </td>
                            </tr>
                        @empty

                            <tr class="text-center">
                                <td colspan="100%">{{__('No Data Found!')}}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                </div>

                {{ $funds->appends($_GET)->links($theme.'partials.pagination') }}

            </div>
        </div>
@endsection

