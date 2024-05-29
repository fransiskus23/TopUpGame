@extends($theme . 'layouts.user')
@section('title', trans('Offer List'))

@section('content')
    <section class="sell-post-details offer-list-form search-box padding-top padding-bottom">
        <div class="container">

            <div class="row ">
                <div class="col-md-12">
                    <div class="contact-box my-3 ">

                        <div class="row justify-content-between align-items-center">
                            <div class="col-md-10">
                                <form action="" method="get" id="sortbyform">
                                    <div class="row mt-3">
                                        <div class="col-md-6 col-lg-3">
                                            <div class="form-group mb-2">
                                                <select name="postId"
                                                        class="form-control form-control-sm select-background">
                                                    <option value="">@lang('My All Post')</option>
                                                    @forelse($sellPostAll as $item)
                                                        <option @if(@request()->postId == $item->id) selected
                                                                @endif value="{{$item->id}}">@lang($item->title)</option>
                                                    @empty
                                                    @endforelse
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-lg-3">
                                            <div class="form-group mb-2">
                                                <input type="text" name="remark"
                                                       value="{{old('remark',@request()->remark)}}"
                                                       class="form-control form-control-sm"
                                                       placeholder="@lang("Type  user or message")">
                                            </div>
                                        </div>


                                        <div class="col-md-6 col-lg-3">
                                            <div class="form-group mb-2">
                                                <input type="date" class="form-control form-control-sm" name="datetrx"
                                                       id="datepicker">
                                            </div>
                                        </div>


                                        <input type="hidden" class="sortby_field" name="sortBy" value="">

                                        <div class="col-md-6 col-lg-3">
                                            <div class="form-group mb-2 h-fill">
                                                <button type="submit" class="w-100 game-btn-sm">
                                                    <i class="fas fa-search" aria-hidden="true"></i> @lang('Search')
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="col-md-2">
                                <div class="btn-group btn-group-sm sortByBtn" role="group">
                                    <div class="btn-group" role="group">
                                        <button id="sortByActionBtn" type="button"
                                                class="btn text-white dropdown-toggle"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                            @lang('Sort By') <i class="fas fa-ellipsis-v"></i>
                                        </button>

                                        <ul class="dropdown-menu"
                                            aria-labelledby="sortByActionBtn">
                                            <li>
                                                <a data-sortby="latest"
                                                   class="dropdown-item sortByAttempt @if(request()->sortBy =='latest') active @endif"
                                                   href="javascript:void(0)">@lang('Latest Offer')
                                                </a>
                                            </li>
                                            <li>
                                                <a data-sortby="high_to_low"
                                                   class="dropdown-item sortByAttempt @if(request()->sortBy =='high_to_low') active @endif"
                                                   href="javascript:void(0)">@lang('Price high to low')
                                                </a>
                                            </li>
                                            <li>
                                                <a data-sortby="low_to_high"
                                                   class="dropdown-item sortByAttempt @if(request()->sortBy =='low_to_high') active @endif"
                                                   href="javascript:void(0)">@lang('Price low to high')
                                                </a>
                                            </li>


                                            <li>
                                                <a data-sortby="processing"
                                                   class="dropdown-item sortByAttempt @if(request()->sortBy =='processing') active @endif"
                                                   href="javascript:void(0)">@lang('Payment Processing')
                                                </a>
                                            </li>


                                            <li>
                                                <a data-sortby="complete"
                                                   class="dropdown-item sortByAttempt @if(request()->sortBy =='complete') active @endif"
                                                   href="javascript:void(0)">@lang('Payment Completed')
                                                </a>
                                            </li>


                                            <li>
                                                <a data-sortby="pending"
                                                   class="dropdown-item sortByAttempt @if(request()->sortBy =='pending') active @endif"
                                                   href="javascript:void(0)">@lang('Pending')
                                                </a>
                                            </li>

                                            <li>
                                                <a data-sortby="rejected"
                                                   class="dropdown-item sortByAttempt @if(request()->sortBy =='rejected') active @endif"
                                                   href="javascript:void(0)">@lang('Rejected')
                                                </a>
                                            </li>
                                            <li>
                                                <a data-sortby="resubmission"
                                                   class="dropdown-item sortByAttempt @if(request()->sortBy =='resubmission') active @endif"
                                                   href="javascript:void(0)">@lang('Resubmission')
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12">
                    <div class="contact-box">

                        <div class="offer-maker-list">
                            @forelse($sellPostOffer as $k => $row)
                                <div class="d-flex


                                    @if(optional($row->sellPost)->payment_lock == 1 && optional($row->sellPost)->lock_for == $row->user_id && \Carbon\Carbon::now() < Carbon\Carbon::parse(optional($row->sellPost)->lock_at)->addMinutes(config('basic.payment_expired')))
                                    paid-making-payment
                                    @endif">
                                    <div class="flex-shrink-0 user">
                                        <a href="javascript:void(0)"
                                           title="{{optional($row->user)->username}}" class="position-relative">
                                            <img src="{{optional($row->user)->imgPath}}"
                                                 class="rounded-circle"
                                                 width="35" height="35" alt="Sample Image">
                                            <i class="active-light position-absolute fa fa-circle text-{{(optional($row->user)->lastSeen == true) ?trans('success'):trans('warning') }} font-12"
                                               title="{{(optional($row->user)->lastSeen == true) ?trans('Online'):trans('Away') }}"></i>
                                        </a>

                                        <span
                                            class="d-block mt-3 base-color"><sup>{{config('basic.currency_symbol')}}</sup>{{getAmount($row->amount)}}</span>
                                    </div>

                                    <div class="flex-grow-1 ms-3">

                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex justify-content-start ">
                                                <h6>{{optional($row->user)->fullname}}</h6>
                                                <span
                                                    class="ms-3 base-color">{{optional($row->sellPost)->title}}</span>
                                            </div>

                                            <div class="btn-group btn-group-sm" role="group">
                                                <div class="btn-group" role="group">
                                                    <button id="offerActionBtn" type="button"
                                                            class="btn text-white dropdown-toggle"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>

                                                    <ul class="dropdown-menu"
                                                        aria-labelledby="offerActionBtn">

                                                        @if($row->status != 1)
                                                            <li><a class="dropdown-item offerAccept"
                                                                   href="javascript:void(0)"
                                                                   data-resource="{{$row->id}}"
                                                                   data-bs-toggle="modal"
                                                                   data-bs-target="#offerAccept"><i
                                                                        class="text-success fa fa-check-circle"></i> @lang('Accept')
                                                                </a>
                                                            </li>
                                                        @else
                                                            <li><a class="dropdown-item"
                                                                   href="{{route('user.offerChat',$row->uuid)}}"><i
                                                                        class="text-success fa fa-comment"></i> @lang('Conversation')
                                                                </a>
                                                            </li>
                                                        @endif

                                                        @if($row->status == 0 || $row->status == 3)
                                                            <li><a class="dropdown-item offerReject"
                                                                   href="javascript:void(0)"
                                                                   data-resource="{{$row->id}}"
                                                                   data-bs-toggle="modal"
                                                                   data-bs-target="#offerReject"><i
                                                                        class="text-danger fa fa-times"></i> @lang('Reject')
                                                                </a>
                                                            </li>
                                                        @endif

                                                        @if($row->status == 2 || $row->status == 0 || $row->status == 3)
                                                            <li><a class="dropdown-item offerRemove"
                                                                   href="javascript:void(0)"
                                                                   data-resource="{{$row->id}}"
                                                                   data-bs-toggle="modal"
                                                                   data-bs-target="#offerRemove"><i
                                                                        class="text-danger fa fa-trash-alt"></i> @lang('Remove')
                                                                </a>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="d-flex ">

                                            @if(optional($row->sellPost)->payment_lock == 1 && optional($row->sellPost)->lock_for == $row->user_id && optional($row->sellPost)->payment_status ==0 && \Carbon\Carbon::now() < Carbon\Carbon::parse(optional($row->sellPost)->lock_at)->addMinutes(config('basic.payment_expired')) )
                                                <span
                                                    class="badge bg-warning text-dark">@lang('Payment Processing')</span>
                                            @elseif(optional($row->sellPost)->payment_lock == 1 && optional($row->sellPost)->lock_for == $row->user_id && optional($row->sellPost)->payment_status ==1)
                                                <span
                                                    class="badge bg-success">@lang('Payment Completed')</span>

                                            @else

                                                @if($row->status == 0)
                                                    <span class="badge bg-warning">@lang('Pending')</span>
                                                @elseif($row->status ==1)
                                                    <span class="badge bg-success">@lang('Accepted')</span>
                                                @elseif($row->status ==2)
                                                    <span class="badge bg-danger">@lang('Rejected')</span>
                                                @elseif($row->status ==3)
                                                    <span class="badge bg-info">@lang('Resubmission')</span>
                                                @endif
                                            @endif

                                            <small class="ms-3"><i
                                                    class="fa fa-clock"></i> {{diffForHumans($row->created_at)}}
                                            </small>
                                        </div>

                                        <p class="mt-3">@lang($row->description)</p>
                                    </div>
                                </div>
                                <hr>
                            @empty
                                <span>@lang('No data found')</span>
                            @endforelse


                            @if(0 < $sellPostOffer->total())
                                <div class="row justify-content-between align-items-center mt-3">
                                    <div class="col-md-4">
                                        <span>@lang('SHOWING ALL') {{$sellPostOffer->total()}} @lang('RESULTS')</span>

                                    </div>
                                    <div class="col-md-8">
                                        {{$sellPostOffer->appends($_GET)->links($theme.'partials.pagination')}}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </section>

    <!-- Offer Accept Model -->
    <div class="modal fade" id="offerAccept" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content ">
                <div class="modal-header-custom modal-colored-header bg-custom">
                    <h4 class="modal-title" id="myModalLabel">@lang('Accept Offer')</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form action="{{route('user.sellPostOfferAccept')}}" method="POST">
                    @csrf
                    <div class="modal-body-custom">
                        <input type="hidden" class="acceptOfferId" name="offer_id" value="">
                        <label>@lang('Say Something')</label>
                        <textarea name="description" rows="4" class="form-control custom mt-3" value=""
                                  required></textarea>
                        @error('description')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="modal-footer-custom">

                        <button type="submit" class="btn btn-success-custom">@lang('Submit')
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <!-- Offer Remove Model -->
    <div class="modal fade" id="offerRemove" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content ">
                <div class="modal-header-custom modal-colored-header bg-custom">
                    <h4 class="modal-title" id="myModalLabel">@lang('Remove Offer')</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form action="{{route('user.sellPostOfferRemove')}}" method="POST">
                    @csrf
                    <div class="modal-body-custom">
                        <input type="hidden" class="removeOfferId" name="offer_id" value="">
                        <label>@lang('Are you want to remove this offer?')</label>
                    </div>
                    <div class="modal-footer-custom">

                        <button type="submit" class="btn btn-success-custom">@lang('Yes')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Offer Reject Model -->
    <div class="modal fade" id="offerReject" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content ">
                <div class="modal-header-custom modal-colored-header bg-custom">
                    <h4 class="modal-title" id="myModalLabel">@lang('Reject Offer')</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form action="{{route('user.sellPostOfferReject')}}" method="POST">
                    @csrf
                    <div class="modal-body-custom">
                        <input type="hidden" class="rejectOfferId" name="offer_id" value="">
                        <label>@lang('Are you want to reject this offer?')</label>
                    </div>
                    <div class="modal-footer-custom">

                        <button type="submit" class="btn btn-success-custom">@lang('Yes')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('script')

    <script>
        'use strict';
        $(document).ready(function () {
            $('.sortByAttempt').on('click', function () {
                $('.sortby_field').val($(this).data('sortby'));
                setTimeout(function () {
                    $("#sortbyform").submit();
                }, 1000);
            });


            $('.makeOffer').on('click', function () {
                $('.sell_post_id').val($(this).data('resource'));
            });

            $(document).ready(function () {
                $('.offerRemove').on('click', function () {
                    $('.removeOfferId').val($(this).data('resource'));
                })
            });

            $(document).ready(function () {
                $('.offerReject').on('click', function () {
                    $('.rejectOfferId').val($(this).data('resource'));
                })
            });

            $(document).ready(function () {
                $('.offerAccept').on('click', function () {
                    $('.acceptOfferId').val($(this).data('resource'));
                })
            })
        })

    </script>

@endpush
