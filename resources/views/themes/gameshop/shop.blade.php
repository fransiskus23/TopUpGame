@extends($theme . 'layouts.app')
@section('title', trans('Shop Now'))

@section('content')
    <section class="shop-section">
        <div class="container">

            <div class="row">
                <div class="col-lg-4 pe-lg-5">
                    <div class="filter-area">
                        <!-- INPUT FIELD -->
                        <div class="filter-box">
                            <h4>@lang('search')</h4>
                            <form action="" method="get">
                                <div class="input-group">
                                    <input
                                        type="text"
                                        name="search"
                                        value="{{old('search',request()->search)}}"
                                        class="form-control"
                                        placeholder="@lang('Search items')"
                                        aria-describedby="basic-addon"
                                    />
                                    <span class="input-group-text" id="basic-addon">
                              <button type="submit">
                                 <img src="{{asset($themeTrue).'/images/icon/search.png'}}" alt="..."/>
                              </button>
                           </span>
                                </div>
                            </form>

                        </div>

                        <!-- SEARCH BY CATEGORIES -->
                        <div class="filter-box mt-3">
                            <h4>@lang('Categories')</h4>
                            <form action="" method="get" id="sortByCategory">
                                <div class="check-box">
                                    @if (config('basic.top_up'))
                                        <div class="form-check mb-3">
                                            <input
                                                name="sortByCategory"
                                                class="form-check-input"
                                                type="checkbox"
                                                value="topUp"
                                                @if(isset(request()->sortByCategory) && in_array('topUp',explode(',',request()->sortByCategory))) checked
                                                @endif
                                                id="check1"
                                            />
                                            <label class="form-check-label" for="check1">
                                                @lang('Top Up')
                                            </label>
                                        </div>
                                    @endif
                                    @if (config('basic.voucher'))
                                        <div class="form-check mb-3">
                                            <input
                                                name="sortByCategory"
                                                class="form-check-input"
                                                type="checkbox"
                                                value="voucher"
                                                @if(isset(request()->sortByCategory) && in_array('voucher',explode(',',request()->sortByCategory))) checked
                                                @endif
                                                id="check2"
                                            />
                                            <label class="form-check-label" for="check2">
                                                @lang('Vouchers')
                                            </label>
                                        </div>
                                    @endif
                                    @if (config('basic.gift_card'))
                                        <div class="form-check">
                                            <input
                                                name="sortByCategory"
                                                class="form-check-input"
                                                type="checkbox"
                                                value="giftCard"
                                                @if(isset(request()->sortByCategory) && in_array('giftCard',explode(',',request()->sortByCategory))) checked
                                                @endif
                                                id="check3"
                                            />
                                            <label class="form-check-label" for="check3">
                                                @lang('Gift card')
                                            </label>
                                        </div>
                                    @endif

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
                            <div class="col-md-6 d-flex mt-4 mt-md-0 justify-content-md-end align-items-center">
                                <span class="pe-3">@lang('SORT BY')</span>
                                <form action="" method="get" id="sortBy">
                                    <select name="sortBy"
                                            class="form-control form-select"
                                            aria-label="Default select example">

                                        <option value="all" @if(request()->sortBy == 'all') selected @endif>
                                            @lang('All Type')
                                        </option>
                                        <option value="popular" @if(request()->sortBy == 'popular') selected @endif>
                                            @lang('Popular')
                                        </option>

                                        <option value="latest" @if(request()->sortBy == 'latest') selected @endif>
                                            @lang('Latest')
                                        </option>
                                        <option value="featured" @if(request()->sortBy == 'featured') selected @endif>
                                            @lang('Featured')
                                        </option>
                                        <option value="discount" @if(request()->sortBy == 'discount') selected @endif>
                                            @lang('Discount')
                                        </option>
                                        <option value="date" @if(request()->sortBy == 'date') selected @endif>
                                            @lang('Date')
                                        </option>

                                    </select>
                                </form>
                            </div>
                        </div>
                        <div class="row g-4">
                            @forelse($items as $key => $item)
                                <div class="col-md-3 col-sm-4 col-4">
                                    <a href="{{$item->detailsRoute}}" class="text-white">
                                        <div class="img-box">
                                            <img
                                                src="{{$item->imgPath}}"
                                                alt="{{optional($item->details)->name}}"
                                                title="{{optional($item->details)->name}}"
                                                class="img-fluid"
                                            />
                                            <div class="tags">
                                                @if($item->discount_amount)
                                                    @if($item->discount_type =='0')
                                                        <span>{{config('basic.currency_symbol')}}{{$item->discount_amount}}</span>
                                                    @else
                                                        <span>{{$item->discount_amount}}%</span>
                                                    @endif

                                                @endif
                                            </div>
                                            <p class="pt-2 mb-0">
                                                {{\Illuminate\Support\Str::limit(optional($item->details)->name,17)}}
                                            </p>
                                        </div>

                                    </a>
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
@endsection
@push('script')

    <script>
         'use strict';
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
                        var new_url = "{{url()->current()}}?sortBy=all&sortByCategory=" + checkedVal;
                        window.history.pushState("data", "", new_url);

                        setTimeout(function () {
                            window.location.reload()
                        }, 1000)
                    }
                }

            })


        })
    </script>
@endpush
