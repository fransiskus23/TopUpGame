@extends('admin.layouts.app')

@section('title')
    @lang('Create Gift Card')
@endsection

@section('content')
    <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
        <div class="card-body">
            <div class="media mb-4 justify-content-end">
                <a href="{{ route('admin.giftCard') }}" class="btn btn-sm  btn-primary mr-2">
                    <span><i class="fas fa-arrow-left"></i> @lang('Back')</span>
                </a>
            </div>


            <ul class="nav nav-tabs" id="myTab" role="tablist">
                @foreach ($languages as $key => $language)
                    <li class="nav-item">
                        <a class="nav-link {{ $loop->first ? 'active' : '' }}" data-toggle="tab"
                           href="#lang-tab-{{ $key }}" role="tab" aria-controls="lang-tab-{{ $key }}"
                           aria-selected="{{ $loop->first ? 'true' : 'false' }}">@lang($language->name)</a>
                    </li>
                @endforeach
            </ul>

            <div class="tab-content mt-2" id="myTabContent">
                @foreach ($languages as $key => $language)
                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="lang-tab-{{ $key }}"
                         role="tabpanel">
                        <form method="post" action="{{ route('admin.giftCardStore', $language->id) }}" class="mt-4"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-sm-12 col-md-12 mb-3">
                                    <label for="name"> @lang('Name') </label>
                                    <input type="text" name="name[{{ $language->id }}]"
                                           class="form-control  @error('name' . '.' . $language->id) is-invalid @enderror"
                                           value="{{ old('name' . '.' . $language->id) }}">
                                    <div class="invalid-feedback">
                                        @error('name' . '.' . $language->id)
                                        @lang($message)
                                        @enderror
                                    </div>
                                    <div class="valid-feedback"></div>
                                </div>


                                <div class="col-sm-12 col-md-12 my-3">
                                    <div class="form-group ">
                                        <label for="details"> @lang('Details') </label>
                                        <textarea
                                            class="form-control summernote @error('details' . '.' . $language->id) is-invalid @enderror"
                                            name="details[{{ $language->id }}]" id="summernote" rows="15"
                                            value="{{ old('details' . '.' . $language->id) }}">{{ old('details' . '.' . $language->id) }}</textarea>
                                        <div class="invalid-feedback">
                                            @error('details' . '.' . $language->id)
                                            @lang($message)
                                            @enderror
                                        </div>
                                        <div class="valid-feedback"></div>
                                    </div>
                                </div>
                                @if ($loop->index == 0)
                                    <div class="col-md-3">
                                        <label for="name"> @lang('Discount Amount') </label>
                                        <div class="input-group">
                                            <input type="text" name="discount_amount"
                                                   class="form-control  @error('name' . '.' . $language->id) is-invalid @enderror"
                                                   value="{{ old('discount_amount') }}">
                                            <div class="input-group-prepend">
                                                <select class="form-control  mb-3" name="discount_type"
                                                        aria-label=".form-select-lg example">
                                                    <option value="0">@lang(config('basic.currency'))</option>
                                                    <option value="1">%</option>
                                                </select>
                                            </div>

                                        </div>

                                        <div class="invalid-feedback">
                                            @error('discount_amount')
                                            @lang($message)
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <label>@lang('Discount Status') </label>
                                        <div class="custom-switch-btn">
                                            <input type='hidden' value='1' name='discount_status'>
                                            <input type="checkbox" name="discount_status" class="custom-switch-checkbox"
                                                   id="discount_status"
                                                   value="0">
                                            <label class="custom-switch-checkbox-label" for="discount_status">
                                                <span class="custom-switch-checkbox-inner"></span>
                                                <span class="custom-switch-checkbox-switch"></span>
                                            </label>
                                        </div>
                                        @error('discount_status')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label>@lang('Featured') </label>
                                        <div class="custom-switch-btn">
                                            <input type='hidden' value='1' name='featured'>
                                            <input type="checkbox" name="featured" class="custom-switch-checkbox"
                                                   id="featured"
                                                   value="0">
                                            <label class="custom-switch-checkbox-label" for="featured">
                                                <span class="custom-switch-checkbox-inner"></span>
                                                <span class="custom-switch-checkbox-switch"></span>
                                            </label>
                                        </div>

                                        @error('featured')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror

                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>@lang('Status')</label>
                                            <div class="custom-switch-btn">
                                                <input type='hidden' value='1' name='status'>
                                                <input type="checkbox" name="status" class="custom-switch-checkbox"
                                                       id="status"
                                                       value="0">
                                                <label class="custom-switch-checkbox-label" for="status">
                                                    <span class="custom-switch-checkbox-inner"></span>
                                                    <span class="custom-switch-checkbox-switch"></span>
                                                </label>
                                            </div>
                                            @error('status')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                @endif
                            </div>
                            @if ($loop->index == 0)
                                <div class="row mt-3">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="image">{{ 'Image' }}</label>
                                            <div class="image-input ">
                                                <label for="image-upload" id="image-label"><i
                                                        class="fas fa-upload"></i></label>
                                                <input type="file" name="image" placeholder="@lang('Choose image')"
                                                       id="image">
                                                <img id="image_preview_container" class="preview-image"
                                                     src="{{ getFile(config('location.category.path')) }}"
                                                     alt="@lang('preview image')">
                                            </div>
                                            <span
                                                class="text-secondary">@lang('Image size') {{config('location.giftCard.size')}} @lang('px')</span>
                                            @error('image')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="image">{{ trans('Thumb') }}</label>
                                            <div class="form-group">
                                                <div class="image-input ">
                                                    <label for="image-upload" id="image-label"><i
                                                            class="fas fa-upload"></i></label>
                                                    <input type="file" name="thumb" placeholder="@lang('Choose thumb')"
                                                           id="favicon">
                                                    <img id="favicon_preview_container" class="preview-image"
                                                         src="{{ getFile(config('location.giftCard.path')) }}"
                                                         alt="preview image">
                                                </div>
                                            </div>
                                            <span
                                                class="text-secondary">@lang('Thumb size') {{config('location.giftCard.thumb')}} @lang('px')</span>
                                            @error('thumb')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <button type="submit"
                                    class="btn waves-effect waves-light btn-rounded btn-primary btn-block mt-3">@lang('Save')</button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/summernote.min.css') }}">
@endpush
@push('js-lib')
    <script src="{{ asset('assets/admin/js/summernote.min.js') }}"></script>
@endpush


@push('js')
    <script>
        "use strict";

        $(document).ready(function () {
            $('select[name=category_id]').select2({
                selectOnClose: true
            });
        });
    </script>

    <script>
        "use strict";

        $(document).ready(function (e) {
            $('.addedField').append(form)
        });


        $(document).on('click', '.delete_desc', function () {
            $(this).closest('.input-group').parent().remove();
        });


        $('#image').change(function () {
            let reader = new FileReader();
            reader.onload = (e) => {
                $('#image_preview_container').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        });


        $('#favicon').change(function () {
            let reader = new FileReader();
            reader.onload = (e) => {
                $('#favicon_preview_container').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        });


        $('.summernote').summernote({
            height: 250,
            callbacks: {
                onBlurCodeview: function () {
                    let codeviewHtml = $(this).siblings('div.note-editor').find('.note-codable')
                        .val();
                    $(this).val(codeviewHtml);
                }
            }
        });
    </script>



    @if ($errors->any())
        @php
            $collection = collect($errors->all());
            $errors = $collection->unique();
        @endphp
        <script>
            "use strict";
            @foreach ($errors as $error)
            Notiflix.Notify.Failure("{{ trans($error) }}");
            @endforeach
        </script>
    @endif

@endpush
