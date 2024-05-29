@extends($theme . 'layouts.app')
@section('title', trans('Buy Now'))

@section('content')
    <!-- SHOP SECTION -->
    <section class="shop-section sell-post">
        <div class="container">
            <div class="row">
                <div class="col-md-4  pe-lg-5">
                    <div class="filter-area">
                        <!-- INPUT FIELD -->
                        <div class="filter-box">
                            <h4>@lang('search')</h4>
                            <form action="" method="get" id="searchFormSubmit">
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
                                         <img src="{{asset($themeTrue).'/images/icon/search.png'}}" alt="..."/>
                                      </button>
                                   </span>
                                </div>


                                <input type="hidden" class="js-input-from" name="minPrice" value="0" readonly/>
                                <input type="hidden" class="js-input-to" value="0" name="maxPrice" readonly/>

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
                                    value=""/>
                                <label for="customRange1" class="form-label mt-3">
                                    {{config('basic.currency_symbol')}}{{$min}}
                                    - {{config('basic.currency_symbol')}}{{$max}}</label>

                            </div>
                        </div>

                        <!-- SEARCH BY CATEGORIES -->
                        <div class="filter-box mt-3">
                            <h4>@lang('Categories')</h4>
                            <form action="" method="get" id="sortByCategory">
                                <div class="check-box">
                                    @forelse($categories as $category)
                                        <div class="form-check mb-3">
                                            <input
                                                class="form-check-input sortByCategory"
                                                name="sortByCategory"
                                                type="checkbox"
                                                value="{{$category->id}}"
                                                @if(isset(request()->sortByCategory) && in_array($category->id,explode(',',request()->sortByCategory))) checked
                                                @endif
                                                id="check{{$category->id}}"
                                            />
                                            <label class="form-check-label cursor-pointer" for="check{{$category->id}}">
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
                <div class="col-md-8 mt-5 mt-lg-0">
                    <div class="item-area">
                        <div class="row align-items-center mb-5">
                            <div class="col-md-6">
                                <span>@lang('SHOWING ALL') {{$sellPost->total()}} @lang('RESULTS')</span>
                            </div>
                            <div
                                class="col-md-6 d-flex mt-4 mt-md-0 justify-content-md-end align-items-center">
                                <span class="pe-3">@lang('SORT BY')</span>
                                <form action="" method="get" id="sortBy">
                                    <select name="sortBy"
                                            class="form-control form-select"
                                            aria-label="Default select example">
                                        <option selected value="latest"
                                                @if(request()->sortBy =='latest') selected @endif>@lang('Latest')</option>
                                        <option value="low_to_high"
                                                @if(request()->sortBy == 'low_to_high') selected @endif>
                                            @lang('Price low to high')
                                        </option>
                                        <option value="high_to_low"
                                                @if(request()->sortBy == 'high_to_low') selected @endif>@lang('Price high to low')</option>
                                    </select>
                                </form>
                            </div>
                        </div>
                        <div class="row g-4">
                            @forelse($sellPost as $item)
                                <div class="col-md-12 col-sm-6">
                                    <div class="game-box d-md-flex">
                                        @if($item->image)
                                            <div class="img-box image-slider owl-carousel">
                                                @for($i = 0; $i<count($item->image); $i++)
                                                    <img
                                                        src="{{ getFile(config('location.sellingPost.path') . @$item->image[$i]) }}"
                                                        class="img-fluid"
                                                        alt="..."
                                                    />
                                                @endfor
                                            </div>
                                        @endif
                                        <div>
                                            <a href="{{route('sellPost.details',[@slug($item->title),$item->id])}}">
                                                <h5 class="name">{{\Illuminate\Support\Str::limit($item->title,25)}}</h5>
                                                <div class="d-flex justify-content-between">
                                                    <span class="game-level"
                                                    >@lang('Price'): <span>{{getAmount($item->price)}} {{config('basic.currency')}}</span></span
                                                    >
                                                    @if($item->payment_lock == 1)
                                                        @if(Auth::check() && Auth::id()==$item->lock_for)
                                                            <span
                                                                class="badge bg-secondary">@lang('Waiting Payment')</span>
                                                        @elseif(Auth::check() &&  Auth::id()==$item->user_id)
                                                            <span
                                                                class="badge bg-warning text-dark">@lang('Payment Processing')</span>
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
                                                <button class="game-btn-sm makeOffer" data-resource="{{$item->id}}"
                                                        data-bs-toggle="modal" data-bs-target="#makeOffer">
                                                    @lang('make offer')
                                                    <img
                                                        src="{{asset($themeTrue).'/images/icon/arrow-white.png'}}"
                                                        alt="..."
                                                    />
                                                </button>
                                            @endif
                                        @elseif(Auth::check()==false)
                                            @if($item->payment_lock == 0)
                                                <button class="game-btn-sm makeOffer" data-resource="{{$item->id}}"
                                                        data-bs-toggle="modal" data-bs-target="#makeOffer">
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
                    {{$sellPost->appends($_GET)->links($theme.'partials.pagination')}}
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
                                        <button class="btn btn-success-custom copy-btn"
                                                type="button">{{config('basic.currency')}}</button>
                                    </div>
                                    @error('amount')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div>
                                <div class="form-group">
                                    <label for="description" class="font-weight-bold"> @lang('Description') </label>
                                    <textarea name="description" rows="4" class="form-control custom earn" value=""
                                              required></textarea>
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
            $('.makeOffer').on('click', function () {
                $('.sell_post_id').val($(this).data('resource'));
            })
        });

        $(document).ready(function () {

            $('select[name=sortBy]').on('change', function () {
                $("#sortBy").submit();
            })

            $('.form-check-input').on('click', function () {
                var checkedVal = $(this).val();

                if (window.location.href.indexOf("sortByCategory") > -1) {

                    const queryString = window.location.search;
                    const urlParams = new URLSearchParams(queryString);


                    var sortByCategory = urlParams.get('sortByCategory');
                    var categoryParams = sortByCategory.split(",");

                    var url = new URL('{{url()->full()}}');
                    var search_params = url.searchParams;
                    var newArr = [];
                    for (let i = 0; i < categoryParams.length; i++) {
                        newArr.push(categoryParams[i])
                    }

                    if (this.checked == false) {
                        for (let i = 0; i < newArr.length; i++) {
                            if (newArr[i] === checkedVal) {
                                newArr.splice(i, 1);
                            }
                        }
                    } else {
                        newArr.push(checkedVal)
                    }
                    var text = newArr.toString();
                    if (text.charAt(0) == ',') {
                        text = text.slice(1);
                    }


                    urlParams.set('sortByCategory', text);
                    var new_url = "{{url()->current()}}?" + urlParams;
                    let new_set_url = new_url.replaceAll('%2C', ",");
                    window.history.pushState("data", "", new_set_url);

                    setTimeout(function () {
                        window.location.reload()
                    }, 1000)


                } else {
                    const queryString = window.location.search;
                    const urlParams = new URLSearchParams(queryString);
                    if (urlParams.has('sortByCategory') == false) {
                        var new_url = "{{url()->current()}}?sortBy=desc&sortByCategory=" + checkedVal;
                        window.history.pushState("data", "", new_url);

                        setTimeout(function () {
                            window.location.reload()
                        }, 1000)
                    }
                }

            })


        });


        var $range = $(".js-range-slider"),
            $inputFrom = $(".js-input-from"),
            $inputTo = $(".js-input-to"),
            instance,
            min = 0,
            max = {{$max}};

        // RANGE SLIDER
        $(".js-range-slider").ionRangeSlider({
            type: "double",
            min: min,
            max: max,
            from: {{request('minPrice') ?? $min}},
            to: {{request('maxPrice') ?? $max}},
            onStart: updateInputs,
            onChange: updateInputs,
            onFinish: finishInputs
        });

        function updateInputs(data) {
            $inputFrom.prop("value", data.from);
            $inputTo.prop("value", data.to);
        }

        function finishInputs(data) {
            $inputFrom.prop("value", data.from);
            $inputTo.prop("value", data.to);

            setTimeout(function () {
                $('#searchFormSubmit').submit();
            }, 2000)
        }
    </script>

@endpush
