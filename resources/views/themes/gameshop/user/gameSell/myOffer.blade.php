@extends($theme.'layouts.user')
@section('title')
    @lang('My Offer')
@endsection
@section('content')

    <div class="container login-section">
        <div class="row justify-content-between bg-gradient">
            <div class="col-md-12">
                <div class="contact-box mb-3 mx-2">
                    <form action="{{route('user.myOffer.search')}}" method="get">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-2">
                                    <input type="text" name="title" value="{{@request()->title}}" class="form-control" placeholder="@lang('Search for Title')">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-2">
                                    <input type="date" class="form-control" name="datetrx" id="datepicker">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-2 h-fill">
                                    <button type="submit" class="game-btn w-100">
                                        <i class="fas fa-search" aria-hidden="true"></i> Search</button>
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
                        <th scope="col">@lang('Category')</th>
                        <th scope="col">@lang('Title')</th>
                        <th scope="col">@lang('Price')</th>
                        <th scope="col">@lang('Offer Price')</th>
                        <th scope="col">@lang('Status')</th>
                        <th scope="col">@lang('Date - Time')</th>
                        <th scope="col">@lang('More')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($sellPostOffer as $k => $row)
                        <tr>
                            <td data-label="@lang('No.')">{{++$k}}</td>
                            <td data-label="@lang('Category')">@lang(@optional($row->sellPost)->category->details->name)</td>
                            <td data-label="@lang('Title')">@lang(@optional($row->sellPost)->title)
                                @if($row->sellPost->payment_status == 1)
                                    <span class="badge bg-info">@lang('sold')</span>
                                @endif
                            </td>
                            <td data-label="@lang('Price')" ><span class="font-weight-bold">{{config('basic.currency_symbol')}}</span>{{getAmount(@optional($row->sellPost)->price)}}
                            </td>
                            <td data-label="@lang('Offer Price')" ><span class="font-weight-bold">{{config('basic.currency_symbol')}}</span>{{getAmount($row->amount)}}
                            </td>
                            <td data-label="@lang('Status')">
                                @if($row->status == 0)
                                    <span class="font-weight-bold badge bg-warning">@lang('Pending')</span>
                                @elseif($row->status == 1)
                                    <span class="font-weight-bold badge bg-success">@lang('Accept')</span>
                                @elseif($row->status == 2)
                                    <span class="font-weight-bold badge bg-danger">@lang('Reject')</span>
                                @elseif($row->status == 3)
                                    <span class="font-weight-bold badge bg-primary">@lang('Resubmission')</span>
                                @endif
                            </td>
                            <td data-label="@lang('Date - Time')">{{dateTime($row->created_at, 'd M, Y h:i A')}}</td>
                            <td data-label="@lang('More')">
                                <div class="btn-group btn-group-sm" role="group">
                                    <div class="btn-group" role="group">
                                        <button id="offerActionBtn" type="button"
                                                class="btn text-white dropdown-toggle"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>

                                        <ul class="dropdown-menu" aria-labelledby="offerActionBtn">
                                            <li><a class="dropdown-item offerAccept"
                                                   href="{{route('sellPost.details',[slug($row->sellPost->title),$row->sell_post_id])}}">
                                                    <i class="text-success fa fa-eye"></i> @lang('Details')
                                                </a>
                                            </li>
                                            @if($row->sellPost->payment_status !=1)
                                                @if($row->uuid)
                                                    <li><a class="dropdown-item offerAccept"
                                                           href="{{route('user.offerChat',$row->uuid)}}">
                                                            <i class="text-white fa fa-comment-dots"></i> @lang('Conversation')
                                                        </a>
                                                    </li>
                                                @endif
                                            @endif
                                        </ul>
                                    </div>
                                </div>
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
            {{ $sellPostOffer->appends($_GET)->links($theme.'partials.pagination') }}
        </div>
    </div>

@endsection
@push('script')

@endpush
