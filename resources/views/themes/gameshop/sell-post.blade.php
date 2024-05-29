@extends($theme . 'layouts.app')
@section('title', trans('Shop Now'))

@section('content')
    <!-- SHOP SECTION -->
    <section class="shop-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 pe-lg-5">
                    <div class="filter-area">
                        <!-- INPUT FIELD -->
                        <div class="filter-box">
                            <h4>@lang('search')</h4>
                            <form action="" method="">
                                <div class="input-group">
                                    <input
                                        type="text"
                                        name="search"
                                        class="form-control"
                                        placeholder="Search items"
                                        value="{{old('search',request()->search)}}"
                                        aria-label="Subscribe Newsletter"
                                        aria-describedby="basic-addon"
                                    />
                                    <span class="input-group-text" id="basic-addon">
                              <button>
                                 <img src="{{asset($themeTrue).'/images/icon/search.png'}}" alt="..." />
                              </button>
                           </span>
                                </div>
                            </form>
                        </div>
                        <!-- PRICE RANGE -->
                        <div class="filter-box mt-3">
                            <h4>@lang('Filter by price')</h4>
                            <div class="input-box">
                                <input
                                    type="text"
                                    class="js-range-slider"
                                    name="my_range"
                                    value=""
                                />
                                <label for="customRange1" class="form-label mt-3">
                                    @lang('$10 — $1,200')</label
                                >
                            </div>
                        </div>

                        <!-- SEARCH BY CATEGORIES -->
                        <div class="filter-box mt-3">
                            <h4>@lang('Categories')</h4>
                            <form action="" method="get" id="sortByCategory">
                                <div class="check-box">
                                    @forelse($category as $category)
                                        <div class="form-check mb-3">
                                            <input
                                                class="form-check-input sortByCategory"
                                                name="sortByCategory"
                                                type="checkbox"
                                                value="{{$category->id}}" @if(isset(request()->sortByCategory) ==$category->id )) checked @endif
                                                id="check1"
                                            />
                                            <label class="form-check-label" for="check1">
                                                {{optional($category->details)->name}}
                                            </label>
                                        </div>
                                    @empty
                                    @endforelse
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 mt-5 mt-lg-0">
                    <div class="item-area">
                        <div class="row align-items-center mb-5">
                            <div class="col-md-6">
                                <span>@lang('SHOWING ALL') {{$items->total()}} @lang('RESULTS')</span>
                            </div>
                            <div
                                class="col-md-6 d-flex mt-4 mt-md-0 justify-content-md-end align-items-center"
                            >
                                <span class="pe-3">@lang('SORT BY')</span>
                                <form action="" method="get" id="sortBy">
                                    <select name="sortBy"
                                        class="form-select"
                                        aria-label="Default select example"
                                    >
                                        <option selected value="latest" @if(request()->sortBy =='latest') selected @endif>@lang('Latest')</option>
                                        <option value="low_to_high" @if(request()->sortBy == 'low_to_high') selected @endif>
                                            @lang('Price low to high')
                                        </option>
                                        <option value="high_to_low" @if(request()->sortBy == 'high_to_low') selected @endif>@lang('Price high to low')</option>
                                    </select>
                                </form>
                            </div>
                        </div>
                        <div class="row g-4">
                            @forelse($sellPost as $item)
                                <div class="col-12">
                                    <div class="game-box d-md-flex">
                                            <div class="img-box image-slider owl-carousel">
                                                @for($i = 0; $i<count($item->image); $i++)
                                                    <img
                                                        src="{{ getFile(config('location.sellingPost.path') . @$item->image[$i]) }}"
                                                        class="img-fluid"
                                                        alt="..."
                                                    />
                                                @endfor
                                            </div>
                                        <div>
                                            <a href="{{route('sellPost.details',[@slug($item->title),$item->id])}}">
                                                <h5 class="name">{{$item->title}}</h5>
                                                <div class="d-flex justify-content-between">
                                                    <span class="game-level"
                                                    >@lang('Price'): <span>{{getAmount($item->price)}} {{config('basic.currency')}}</span></span
                                                    >
                                                    @if($item->payment_lock == 1)
                                                        @if(Auth::check() && Auth::id()==$item->lock_for)
                                                            <span class="badge bg-secondary">@lang('Waiting Payment')</span>
                                                        @elseif(Auth::check() &&  Auth::id()==$item->user_id)
                                                            <span class="badge bg-warning text-dark">@lang('Payment Processing')</span>
                                                        @else
                                                            <span class="badge bg-warning">@lang('Going to Sell')</span>
                                                        @endif
                                                    @endif
                                                </div>
                                            </a>
                                            <div class="row g-2 mt-3 more-info">
                                                @forelse($item->post_specification_form as $k => $v)
                                                    <div class="col-6">
                                                        <span>{{$v->field_name}}: {{$v->field_value}}</span>
                                                    </div>
                                                @empty
                                                @endforelse
                                            </div>
                                        </div>
                                        @if(Auth::check() && $item->user_id!=Auth::user()->id)
                                            @if($item->payment_lock == 0)
                                                <button class="game-btn-sm makeOffer" data-resource="{{$item->id}}" data-bs-toggle="modal" data-bs-target="#makeOffer">
                                                    @lang('make offer')
                                                    <img
                                                        src="{{asset($themeTrue).'/images/icon/arrow-white.png'}}"
                                                        alt="..."
                                                    />
                                                </button>
                                            @endif
                                            @elseif(Auth::check()==false)
                                             @if($item->payment_lock == 0)
                                                <button class="game-btn-sm makeOffer" data-resource="{{$item->id}}" data-bs-toggle="modal" data-bs-target="#makeOffer">
                                                    @lang('make offer')
                                                    <img
                                                        src="{{asset($themeTrue).'/images/icon/arrow-white.png'}}"
                                                        alt="..."
                                                    />
                                                </button>
                                             @endif
                                        @endif
                                    </div>
                                </div>
                                @empty
                                @endforelse
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col">
                    {{$items->appends($_GET)->links($theme.'partials.pagination')}}
                </div>
            </div>
        </div>
    </section>
    <!-- Modal for Make Offer -->
    <div class="modal fade" id="makeOffer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content ">
                <div class="modal-header-custom modal-colored-header bg-custom">
                    <h4 class="modal-title" id="myModalLabel">@lang('Make Offer')</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form action="{{route('user.sellPostOffer')}}" method="POST">
                    @csrf
                    <div class="modal-body-custom">
                          <div class="customize-modal">
                              <input type="hidden" class="sell_post_id" name="sell_post_id" value="">
                              <div class="form-group">
                                  <label for="amount" class="font-weight-bold"> @lang('Amount') </label>
                                  <div class="mb-3">
                                      <div class="input-group">
                                          <input type="text" name="amount" class="form-control earn" required></input>
                                          <button class="btn btn-success-custom copy-btn" type="button">{{config('basic.currency')}}</button>
                                      </div>
                                      @error('amount')
                                      <span class="text-danger">{{ $message }}</span>
                                      @enderror
                                  </div>
                              </div>
                              <div>
                                  <div class="form-group">
                                      <label for="description" class="font-weight-bold"> @lang('Description') </label>
                                      <textarea name="description" rows="4" class="form-control custom earn" value="" required></textarea>
                                  </div>
                                  @error('description')
                                  <span class="text-danger">{{ $message }}</span>
                                  @enderror
                              </div>
                          </div>
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
            $('select[name=sortBy]').on('change', function () {
                $("#sortBy").submit();
            })

            $('.sortByCategory').on('click', function () {
                $("#sortByCategory").submit();
            })

            $('.makeOffer').on('click', function () {
                $('.sell_post_id').val($(this).data('resource'));
            })
        })

    </script>

@endpush
