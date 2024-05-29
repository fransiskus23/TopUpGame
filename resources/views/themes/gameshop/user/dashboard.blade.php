@extends($theme.'layouts.user')
@section('title',trans('Dashboard'))
@section('content')
    <div class="dashboard-section ">
        <div class="container">
            <div class="row justify-content-center g-4">
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="dashboard__card dashboard__card-1">
                        <div class="dashboard__card-content">
                            <h2 class="price"><sup>{{config('basic.currency_symbol')}}</sup>{{auth()->user()->balance}}
                            </h2>
                            <p class="info">@lang('Wallet Balance')</p>
                        </div>
                        <div class="dashboard__card-icon dashboard__card-icon-1">
                            <img src="{{asset($themeTrue.'images/icon2/1.png')}}" alt="...">
                        </div>
                    </div>
                </div>

                @if(config('basic.top_up'))
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="dashboard__card dashboard__card-2">
                            <div class="dashboard__card-content">
                                <h2 class="price">{{$topUp}}</h2>
                                <p class="info">@lang('Top Up')</p>
                            </div>
                            <div class="dashboard__card-icon dashboard__card-icon-2">
                                <img src="{{asset($themeTrue.'images/icon2/2.png')}}" alt="...">
                            </div>
                        </div>
                    </div>
                @endif

                @if(config('basic.voucher'))
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="dashboard__card dashboard__card-3">
                            <div class="dashboard__card-content">
                                <h2 class="price">{{$voucher}}</h2>
                                <p class="info">@lang('Voucher')</p>
                            </div>
                            <div class="dashboard__card-icon dashboard__card-icon-3">
                                <img src="{{asset($themeTrue.'images/icon2/3.png')}}" alt="...">
                            </div>
                        </div>
                    </div>
                @endif

                @if(config('basic.gift_card'))
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="dashboard__card dashboard__card-4">
                            <div class="dashboard__card-content">
                                <h2 class="price">{{$giftCard}}</h2>
                                <p class="info">@lang('Gift Card')</p>
                            </div>
                            <div class="dashboard__card-icon dashboard__card-icon-4">
                                <img src="{{asset($themeTrue.'images/icon2/4.png')}}" alt="...">
                            </div>
                        </div>
                    </div>
                @endif

                @if(config('basic.sell_post'))
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="dashboard__card dashboard__card-5">
                            <div class="dashboard__card-content">
                                <h2 class="price">{{$createSellPost}}</h2>
                                <p class="info">@lang('Selling Post')</p>
                            </div>
                            <div class="dashboard__card-icon dashboard__card-icon-5">
                                <img src="{{asset($themeTrue.'images/icon2/5.png')}}" alt="...">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="dashboard__card dashboard__card-9">
                            <div class="dashboard__card-content">
                                <h2 class="price">{{$soldSellPost}}</h2>
                                <p class="info">@lang('Sold Post')</p>
                            </div>
                            <div class="dashboard__card-icon dashboard__card-icon-9">
                                <img src="{{asset($themeTrue.'images/icon2/6.png')}}" alt="...">
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="dashboard__card dashboard__card-8">
                            <div class="dashboard__card-content">
                                <h2 class="price">{{$upcoming['upComingPayment']}}</h2>
                                <p class="info">@lang('Upcoming Payment')</p>
                            </div>
                            <div class="dashboard__card-icon dashboard__card-icon-8">
                                <img src="{{asset($themeTrue.'images/icon2/9.png')}}" alt="...">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="dashboard__card dashboard__card-1">
                            <div class="dashboard__card-content">
                                <h2 class="price">
                                    <sup>{{config('basic.currency_symbol')}}</sup>{{$upcoming['upComingAmount']}}</h2>
                                <p class="info">@lang('Upcoming Amount')</p>
                            </div>
                            <div class="dashboard__card-icon dashboard__card-icon-1">
                                <img src="{{asset($themeTrue.'images/icon2/1.png')}}" alt="...">
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="dashboard__card dashboard__card-6">
                            <div class="dashboard__card-content">
                                <h2 class="price">{{$buySellPost}}</h2>
                                <p class="info">@lang('Buy Post')</p>
                            </div>
                            <div class="dashboard__card-icon dashboard__card-icon-6">
                                <img src="{{asset($themeTrue.'images/icon2/7.png')}}" alt="...">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="dashboard__card dashboard__card-6">
                            <div class="dashboard__card-content">
                                <h2 class="price">{{$postOffer}}</h2>
                                <p class="info">@lang('My Proposal')</p>
                            </div>
                            <div class="dashboard__card-icon dashboard__card-icon-6">
                                <img src="{{asset($themeTrue.'images/icon2/7.png')}}" alt="...">
                            </div>
                        </div>
                    </div>

                @endif

                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="dashboard__card dashboard__card-8">
                        <div class="dashboard__card-content">
                            <h2 class="price">{{$payouts}}</h2>
                            <p class="info">@lang('Payout Balance')</p>
                        </div>
                        <div class="dashboard__card-icon dashboard__card-icon-8">
                            <img src="{{asset($themeTrue.'images/icon2/9.png')}}" alt="...">
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="dashboard__card dashboard__card-7">
                        <div class="dashboard__card-content">
                            <h2 class="price">{{$tickets}}</h2>
                            <p class="info">@lang('Support Tickets')</p>
                        </div>
                        <div class="dashboard__card-icon dashboard__card-icon-7">
                            <img src="{{asset($themeTrue.'images/icon2/8.png')}}" alt="...">
                        </div>
                    </div>
                </div>

            </div>


            @if(0 < count($paymentLog ))
                @if(config('basic.sell_post'))
                    <div class="row justify-content-between bg-gradient mt-5">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered transection__table mt-2" id="service-table">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th>@lang('SL No.')</th>
                                        <th>@lang('Sell Post')</th>
                                        <th>@lang('Amount')</th>
                                        <th>@lang('Status')</th>
                                        <th>@lang('Released At')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($paymentLog as $key=>$item)
                                        <tr>
                                            <td data-label="@lang('SL No.')">{{++$key}}</td>
                                            <td data-label="@lang('Sell Post')">{{$item->title}}</td>
                                            <td data-label="@lang('Amount')">{{config('basic.currency_symbol')}} {{getAmount($item->sellPostPayment->price)}}</td>
                                            <td data-label="@lang('Status')">
                                                @if($item->sellPostPayment->status == 3)
                                                    <span class="badge bg-warning">@lang('Pending')</span>
                                                @else
                                                    @if($item->sellPostPayment->payment_release ==1 )
                                                        <span class="badge bg-success">@lang('Released')</span>
                                                    @else
                                                        <span class="badge bg-secondary">@lang('Upcoming')</span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td data-label="@lang('Released At')">
                                                @if($item->sellPostPayment->status == 3)
                                                    -
                                                @else
                                                    @if($item->sellPostPayment->payment_release ==1 )
                                                        {{Carbon\Carbon::parse($item->released_at)->format('d M, Y H:i')}}
                                                    @elseif($item->sellPostPayment->payment_release == 0 )
                                                        {{Carbon\Carbon::parse($item->created_at)->addDays(config('basic.payment_released'))->format('d M, Y H:i')}}
                                                    @else
                                                        -
                                                    @endif
                                                @endif
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

                        </div>
                    </div>
                @endif
            @else
                <div class="row justify-content-between bg-gradient mt-5">
                    <div class="col-md-12">
                        <div class="mt-4">
                            <h5>@lang('Last 5 Transactions')</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered transection__table mt-2" id="service-table">
                                <thead class="thead-dark">
                                <tr>
                                    <th>@lang('SL No.')</th>
                                    <th>@lang('Transaction ID')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Remarks')</th>
                                    <th>@lang('Time')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($transactions as $key=>$transaction)
                                    <tr>
                                        <td data-label="@lang('SL No.')">{{++$key}}</td>
                                        <td data-label="@lang('Transaction ID')">@lang($transaction->trx_id)</td>
                                        <td data-label="@lang('Amount')">
                                    <span
                                        class="font-weight-bold text-{{($transaction->trx_type == "+") ? trans('success'): trans('danger')}}">{{($transaction->trx_type == "+") ? '+': '-'}}{{getAmount($transaction->amount, config('basic.fraction_number')). ' ' . trans(config('basic.currency'))}}</span>
                                        </td>
                                        <td data-label="@lang('Remarks')"> @lang($transaction->remarks)</td>
                                        <td data-label="@lang('Time')">
                                            {{ dateTime($transaction->created_at, 'd M Y h:i A') }}
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
                    </div>
                </div>
            @endif
        </div>
    </div>

@endsection
@push('script')
@endpush
