@extends($theme.'layouts.user')
@section('title',trans('Sell Post Create'))

@section('content')
    <!-- UPLOAD SELL POST -->
    <section class="upload-sell-post">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12">
                    <div class="form-box">
                        <form action="{{route('user.sellStore')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="col-md-12 mb-5">

                                <div class="form-group">
                                    <label
                                        for="exampleFormControlInput1"
                                        class="form-label"
                                    >@lang('category')
                                    </label>
                                    <select
                                        class="form-select" name="category"
                                        id="category"
                                        aria-label="Default select example">
                                        <option selected disabled>@lang('Select Category')</option>
                                        @foreach($categoryList as $item)
                                            <option value="{{$item->id}}" @if(@$category->id==$item->id) selected @endif
                                            >@lang(@optional($item->details)->name)</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label
                                            for="exampleFormControlInput1"
                                            class="form-label"
                                        >@lang('Title')
                                        </label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            name="title"
                                            value="{{old('title')}}"
                                            id="exampleFormControlInput1"
                                            placeholder="Title"
                                        />
                                        @if($errors->has('title'))
                                            <div
                                                class="error text-danger">@lang($errors->first('title')) </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group header-box-title">
                                        <label
                                            for="exampleFormControlInput1"
                                            class="form-label">@lang('price')
                                        </label>
                                        @if(isset($category) && !empty($category->sell_charge))
                                            <span class="info sellCharge" data-resource="{{$category->sell_charge}}"
                                                  data-bs-toggle="modal" data-bs-target="#sellCharge"
                                                  title="@lang("How much of this will i earn")">
                                                <img class="info-icon" src="{{ asset($themeTrue) . '/images/icon/info.png' }}" alt="..."/>
                                            </span>
                                        @endif
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="input-group append">
                                                    <input
                                                        type="text"
                                                        class="form-control price"
                                                        name="price"
                                                        value="{{old('price')}}"
                                                        id="exampleFormControlInput1"
                                                        placeholder="Price"
                                                    />

                                                    <button class="game-btn" type="button">{{config('basic.currency')}}</button>

                                                </div>

                                                @if($errors->has('price'))
                                                    <div
                                                        class="error text-danger">@lang($errors->first('price'))
                                                    </div>
                                                @endif

                                            </div>
                                        </div>


                                    </div>
                                </div>
                                @if(isset($category) && !empty($category->form_field))
                                    <div class="col-md-12">
                                        <div class="dark-bg p-3">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="header-box-title">

                                                        <h6>@lang('Please Enter '.$category->details->name.' credentials')
                                                            <span class="info" data-bs-toggle="tooltip"
                                                                  title="@lang("This credentials need for admin approval")">
                                                                    <img class="info-icon"
                                                                         src="{{ asset($themeTrue) . '/images/icon/info.png' }}"
                                                                         alt="..."/>
                                                                </span>
                                                        </h6>
                                                    </div>
                                                </div>

                                                @forelse($category->form_field as $k => $v)
                                                    <div class="col-md-6">
                                                        @if ($v->type == 'text')
                                                            <div class="form-group">
                                                                <label
                                                                    for="exampleFormControlInput1"
                                                                    class="form-label"
                                                                >{{ trans($v->field_level) }}

                                                                    @if ($v->validation == 'required')
                                                                        <span class="text-danger">*</span>
                                                                    @endif
                                                                </label>
                                                                <input name="{{ $k }}" type="text" class="form-control"
                                                                       value="{{old($k)}}"
                                                                       placeholder="{{ trans($v->field_level) }}"
                                                                @if ($v->validation == 'required')  @endif />

                                                                @error($k)
                                                                <span
                                                                    class="text-danger">{{ $message  }}</span>
                                                                @enderror
                                                            </div>
                                                        @elseif($v->type == 'textarea')
                                                            <div class="form-group">
                                                                <label
                                                                    for="exampleFormControlInput1"
                                                                    class="form-label"
                                                                >{{ trans($v->field_level) }}

                                                                    @if ($v->validation == 'required')
                                                                        <span class="text-danger">*</span>
                                                                    @endif
                                                                </label>

                                                                <textarea name="{{ $k }}" class="form-control"
                                                                    @if ($v->validation == 'required')  @endif>{{old($k)}}</textarea>
                                                                @if ($errors->has($k))
                                                                    <span
                                                                        class="text-danger">{{ trans($errors->first($k)) }}</span>
                                                                @endif

                                                            </div>
                                                        @elseif($v->type == 'file')
                                                            <div class="form-group">
                                                                <label
                                                                    for="exampleFormControlInput1"
                                                                    class="form-label"
                                                                >{{ trans($v->field_level) }}

                                                                    @if ($v->validation == 'required')
                                                                        <span class="text-danger">*</span>
                                                                    @endif
                                                                </label>

                                                                <input name="{{ $k }}" type="file" class="form-control"
                                                                       placeholder="{{ trans($v->field_level) }}"
                                                                @if ($v->validation == 'required')   @endif />

                                                                @if ($errors->has($k))
                                                                    <span
                                                                        class="text-danger">{{ trans($errors->first($k)) }}</span>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                @empty
                                                @endforelse
                                            </div>

                                        </div>
                                    </div>
                                @endif
                                @if(isset($category) && !empty($category->post_specification_form))
                                    <div class="col-md-12">
                                        <div class="dark-bg p-3">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="header-box-title">

                                                        <h6>@lang('Please Enter '.$category->details->name.' Specification')
                                                            <span class="info" data-bs-toggle="tooltip"
                                                                  title="@lang("This Specification need for admin approval")">
                                                                    <img class="info-icon"
                                                                         src="{{ asset($themeTrue) . '/images/icon/info.png' }}"
                                                                         alt="..."/>
                                                                </span>
                                                        </h6>
                                                    </div>
                                                </div>

                                                @forelse($category->post_specification_form as $k => $v)
                                                    <div class="col-md-6">
                                                        @if ($v->type == 'text')
                                                            <div class="form-group">
                                                                <label
                                                                    for="exampleFormControlInput1"
                                                                    class="form-label"
                                                                >{{ trans($v->field_level) }}

                                                                    @if ($v->validation == 'required')
                                                                        <span class="text-danger">*</span>
                                                                    @endif
                                                                </label>
                                                                <input name="{{ $k }}" type="text" class="form-control"
                                                                       value="{{old($k)}}"
                                                                       placeholder="{{ trans($v->field_level) }}"
                                                                @if ($v->validation == 'required')  @endif />

                                                                @error($k)
                                                                <span
                                                                    class="text-danger">{{ $message  }}</span>
                                                                @enderror
                                                            </div>
                                                        @elseif($v->type == 'textarea')
                                                            <div class="form-group">
                                                                <label
                                                                    for="exampleFormControlInput1"
                                                                    class="form-label"
                                                                >{{ trans($v->field_level) }}

                                                                    @if ($v->validation == 'required')
                                                                        <span class="text-danger">*</span>
                                                                    @endif
                                                                </label>

                                                                <textarea name="{{ $k }}" class="form-control"
                                                                @if ($v->validation == 'required')  @endif>{{old($k)}}</textarea>
                                                                @if ($errors->has($k))
                                                                    <span
                                                                        class="text-danger">{{ trans($errors->first($k)) }}</span>
                                                                @endif

                                                            </div>
                                                        @elseif($v->type == 'file')
                                                            <div class="form-group">
                                                                <label
                                                                    for="exampleFormControlInput1"
                                                                    class="form-label"
                                                                >{{ trans($v->field_level) }}

                                                                    @if ($v->validation == 'required')
                                                                        <span class="text-danger">*</span>
                                                                    @endif
                                                                </label>

                                                                <input name="{{ $k }}" type="file" class="form-control"
                                                                       placeholder="{{ trans($v->field_level) }}"
                                                                @if ($v->validation == 'required')   @endif />

                                                                @if ($errors->has($k))
                                                                    <span
                                                                        class="text-danger">{{ trans($errors->first($k)) }}</span>
                                                                @endif
                                                            </div>
                                                        @endif

                                                    </div>
                                                @empty
                                                @endforelse
                                            </div>

                                        </div>
                                    </div>
                                @endif
                                <div class="col-md-12 mb-4 mb-5">
                                    <label
                                        for="exampleFormControlTextarea1"
                                        class="form-label"
                                    >@lang('Description')</label
                                    >
                                    <textarea
                                        class="form-control"
                                        name="details"
                                        id="exampleFormControlTextarea1"
                                        rows="5"
                                        placeholder="Description"
                                    >{{old('details')}}</textarea>
                                    @if($errors->has('details'))
                                        <div
                                            class="error text-danger">@lang($errors->first('details')) </div>
                                    @endif
                                </div>
                                <div class="col-md-12 mb-5">
                                    <label
                                        for="exampleFormControlTextarea1"
                                        class="form-label">@lang('Message to the Reviewer')</label>
                                    <textarea
                                        class="form-control"
                                        name="comments"
                                        id="exampleFormControlTextarea1"
                                        rows="2"
                                        placeholder="Comment"
                                    >{{old('comments')}}</textarea>
                                    @if($errors->has('comments'))
                                        <div
                                            class="error text-danger">@lang($errors->first('comments')) </div>
                                    @endif
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <a href="javascript:void(0)" class="btn btn-success float-left mt-3 generate">
                                            <i class="fa fa-image"></i> @lang('Add Image')</a>
                                    </div>
                                    @if($errors->has('image'))
                                        <div
                                            class="error text-danger">@lang($errors->first('image')) </div>
                                    @endif
                                </div>

                                <div class="row addedField mt-3">

                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="game-btn mt-5">
                                        @lang('Submit now')
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="sellCharge" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content ">
                <div class="modal-header-custom modal-colored-header bg-custom">
                    <h4 class="modal-title" id="myModalLabel">@lang('How Much You Earn')</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>

                <div class="modal-body-custom">
                    <div class="withdraw-detail">

                        <div class="form-group mb-2">
                            <label>@lang('Price')</label>
                            <div class="input-group">
                                <input type="text" class="form-control modal-price" value="">
                                <div class="input-group-append">
                                    <button class="btn btn-success-custom copy-btn"
                                            type="button">{{config('basic.currency')}}</button>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <div class="input-group">
                                        <label>@lang('You will earn')</label>
                                        <label class="earn ms-2"></label>
                                        <div class="input-group-append">
                                            <label class="ms-2">{{config('basic.currency')}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">

                                    <div class="input-group">
                                        <label>@lang('Sell Charge')</label>
                                        <label class="charge ms-2"></label>
                                        <div class="input-group-append">
                                            <label class="ms-2">{{config('basic.currency')}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer-custom">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Close')
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')

    <script>
        "use strict";

        var sellCharge, price, totalCharge, sellerEarn;

        $(document).on('click', '.sellCharge', function () {
            sellCharge = $(this).data('resource');
            price = $('.price').val();
            if (price < 0) {
                $('.earn').val(0);
                $('.charge').val(0);
                return 0;
            }
            totalCharge = sellCharge * price / 100;
            sellerEarn = price - totalCharge;

            $('.modal-price').val(price);
            $('.earn').text(sellerEarn);
            $('.charge').text(totalCharge);

        });
        $(document).on('keyup', '.modal-price', function () {
            this.value = this.value.replace(/[^0-9\.]/g, '');
            price = $(this).val();

            if (price < 0) {
                $('.charge').text(0);
                $('.earn').text(0);
                return 0;
            }
            sellCharge = $('.sellCharge').data('resource');
            totalCharge = sellCharge * price / 100;
            sellerEarn = price - totalCharge;


            $('.charge').text(totalCharge);

            $('.earn').text(sellerEarn);
            $('.price').val(price);

        });


        $('#image').change(function () {
            let reader = new FileReader();
            reader.onload = (e) => {
                $('#image_preview_container').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        });


        $(".generate").on('click', function () {
            var form = `<div class="col-sm-12 col-md-4 image-column d-block">
                                <div class="form-group position-relative">

                                        <div class="image-input  z0">
                                            <label for="image-upload" id="image-label"><i class="fas fa-upload"></i></label>
                                            <input type="file" name="image[]" placeholder="@lang('Choose image')" class="image-preview">
                                            <img id="image_preview_container" class="preview-image "	src="{{ getFile(config('location.sellingPost.path')) }}" alt="@lang('preview image')">

                                        </div>

                                         <button class="btn btn-danger delete_desc removeFile z9 " type="button">
                                                <i class="fa fa-times"></i>
                                        </button>
                                </div>
                </div> `;
            $('.addedField').append(form)
        });


        $(document).on('click', '.delete_desc', function () {
            $(this).closest('.form-group').parents('.image-column').remove();
        });


        $(document).on('change', '.image-preview', function () {
            let currentIndex = $('.image-preview').index(this);
            $(this).attr('name', `image[${currentIndex}]`);
            let reader = new FileReader();
            let _this = this;
            reader.onload = (e) => {
                $(_this).siblings('.preview-image').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        });

        $(document).on('change', "#category", function () {
            let value = $(this).find('option:selected').val();
            window.location.href = "{{route('user.sellCreate')}}/?category=" + value
        });

    </script>
@endpush
