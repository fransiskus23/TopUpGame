@extends($theme . 'layouts.app')
@section('title', trans('Post Details'))

@section('content')
    <!-- SELL POST DETAILS -->
    <section class="sell-post-details sell-post-details-custom">
        <div class="container">
            <div class="row g-4 g-md-5">
                <div class="col-md-7">
                    <div class="game-box d-md-flex">
                        <div class="img-box image-slider owl-carousel">
                            @for($i = 0; $i<count($sellPost->image); $i++)
                                <img
                                    src="{{ getFile(config('location.sellingPost.path') . @$sellPost->image[$i]) }}"
                                    class="img-fluid"
                                    alt="..."
                                />
                            @endfor
                        </div>
                        <div class="w-100 d-block">
                            <div class="d-flex flex-wrap justify-content-between ">
                                    <h5 class="name text-start text-sm-center">{{$sellPost->title}}</h5>
                                @if($sellPost->payment_status != '1')
                                    @if(Auth::check() &&  $sellPost->user_id != Auth::id() )
                                            <button type="button"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#paymentConfirm"
                                                    class="btn btn-success btn-sm"><i
                                                    class="fa fa-shopping-cart"></i> @lang('Buy')</button>

                                    @endif
                                @endif

                            </div>
                            <div class="d-flex justify-content-between mt-3">
                                <span class="game-level">
                                @lang('Price'):
                                     <span>{{getAmount($price)}} {{config('basic.currency')}}</span></span>
                                @if($sellPost->payment_status == 1)
                                    <span class="badge bg-success">@lang('Payment Completed')</span>
                                @else
                                    @if($sellPost->payment_lock == 1)
                                        @if(Auth::check() && Auth::id()==$sellPost->lock_for)
                                            <span class="badge bg-secondary">@lang('Waiting Payment')</span>
                                        @elseif(Auth::check() &&  Auth::id()==$sellPost->user_id)
                                            <span class="badge bg-warning text-dark">@lang('Payment Processing')</span>
                                        @else
                                            <span class="badge bg-warning">@lang('Going to Sell')</span>
                                        @endif
                                    @endif
                                @endif
                            </div>

                            <div class="row g-2 mt-3 more-info text-start text-sm-center">
                                @forelse($sellPost->post_specification_form as $k => $v)
                                    <div class="col-sm-6 col-12">
                                        <span>{{$v->field_name}}: {{$v->field_value}}</span>
                                    </div>
                                @empty
                                @endforelse
                            </div>
                        </div>
                    </div>
                    <div>
                        <p class="mt-4">
                            {{$sellPost->details}}
                        </p>
                    </div>
                </div>

                @if(Auth::check() &&  $sellPost->user_id == Auth::id())
                    <div class="col-md-5">

                        <div class="custom-card bg-gradient">
                            <div class="contact-box ">

                                <div class="offer-maker-list">
                                    <div class="d-flex justify-content-between mb-5">
                                        <h6>@lang('Offer History')</h6>
                                        <a href="{{route('user.sellPostOfferMore')."?postId=".$sellPost->id}}">
                                            <button class="game-btn-sm" id="btnSellAll">
                                                @lang('More')<img src="{{asset($themeTrue.'/images/icon/arrow-white.png')}}"
                                                                  alt="...">
                                            </button>
                                        </a>
                                    </div>
                                    @forelse($sellPostOffer as $k => $row)
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <a href="javascript:void(0)"
                                                   title="{{optional($row->user)->username}}">
                                                    <img src="{{optional($row->user)->imgPath}}" class="rounded-circle"
                                                         width="35" height="35" alt="Sample Image">
                                                </a>

                                                <span
                                                    class="d-block mt-3 base-color">{{config('basic.currency_symbol')}}{{getAmount($row->amount)}}</span>
                                            </div>

                                            <div class="flex-grow-1 ms-3">

                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h6>{{optional($row->user)->fullname}}</h6>

                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <div class="btn-group" role="group">
                                                            <button id="offerActionBtn" type="button"
                                                                    class="btn text-white dropdown-toggle"
                                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </button>

                                                            <ul class="dropdown-menu" aria-labelledby="offerActionBtn">

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

                                                    @if($sellPost->payment_lock == 1 && $sellPost->lock_for == $row->user_id && $sellPost->payment_status ==0)
                                                        <span
                                                            class="badge bg-warning text-dark">@lang('Payment Processing')</span>

                                                    @elseif($sellPost->payment_lock == 1 && $sellPost->lock_for == $row->user_id && $sellPost->payment_status ==1)
                                                        <span class="badge bg-success">@lang('Payment Complete')</span>

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
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    @if($sellPost->payment_lock == 0 && $sellPost->payment_status !=1)
                        <div class="col-md-5">
                            <div class="contact-box ">

                                <form action="{{route('user.sellPostOffer')}}" method="POST">
                                    @csrf
                                    <div class="mb-4 form-box">

                                        <h6 class="mb-3">@lang('Make a Offer')</h6>

                                        <input type="hidden" name="sell_post_id" value="{{$sellPost->id}}">
                                        <label for="exampleFormControlInput1" class="form-label">@lang('Amount')
                                        </label>

                                        <div class="input-group append">
                                            <input type="text" class="form-control" name="amount"
                                                   placeholder="Amount" required>
                                            <button class="game-btn text-cursor" type="button">{{config('basic.currency')}}</button>
                                        </div>
                                        @error('amount')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror

                                    </div>
                                    <div class="mb-4">
                                        <label for="exampleFormControlInput1" class="form-label">@lang('Description')
                                        </label>
                                        <textarea name="description" rows="4" class="form-control custom" required></textarea>
                                    </div>
                                    @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <button type="submit" class="game-btn-sm">
                                        @lang('make offer')
                                        <img src="{{ asset($themeTrue.'/images/icon/arrow-white.png')}}" alt="...">
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                @endif
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
                        <textarea name="description" rows="4" class="form-control custom earn mt-3" value=""
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
                        <label>@lang('Are you want to remove this offer ?')</label>
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
                        <label>@lang('Are you want to reject this offer ?')</label>
                    </div>
                    <div class="modal-footer-custom">

                        <button type="submit" class="btn btn-success-custom">@lang('Yes')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <!-- Offer Accept Model -->
    <div class="modal fade" id="paymentConfirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content ">
                <div class="modal-header-custom modal-colored-header bg-custom">
                    <h4 class="modal-title" id="myModalLabel">@lang('Confirmation')</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form action="{{route('user.sellPost.payment',$sellPost)}}" method="POST">
                    @csrf
                    <div class="modal-body-custom">
                        <p>@lang('Are you confirm to payment now ?')</p>
                    </div>
                    <div class="modal-footer-custom">
                        <button type="submit" class="btn btn-success-custom">@lang('Submit')
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
            $('.offerRemove').on('click', function () {
                $('.removeOfferId').val($(this).data('resource'));
            })
        })

        $(document).ready(function () {
            $('.offerReject').on('click', function () {
                $('.rejectOfferId').val($(this).data('resource'));
            })
        })

        $(document).ready(function () {
            $('.offerAccept').on('click', function () {
                $('.acceptOfferId').val($(this).data('resource'));
            })
        })
    </script>
@endpush
