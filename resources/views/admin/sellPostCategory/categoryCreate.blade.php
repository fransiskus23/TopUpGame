@extends('admin.layouts.app')

@section('title')
    @lang('Create Category')
@endsection

@section('content')
    <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
        <div class="card-body">
            <div class="media mb-4 justify-content-end">
                <a href="{{ route('admin.sellPostCategory') }}" class="btn btn-sm  btn-primary mr-2">
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
                        <form method="post" action="{{ route('admin.sellPostCategoryStore', $language->id) }}" class="mt-4"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-sm-12 col-md-12 mb-3">
                                    <label for="name"> @lang('Category Name') </label>
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


                                @if ($loop->index == 0)

                                    <div class="col-md-12">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between mb-3">
                                                <h5 class="card-title">@lang('User Credential Form')</h5>
                                                <a href="javascript:void(0)" class="btn btn-dark btn-sm btn-rounded"
                                                   id="generate"><i class="fa fa-plus-circle"></i>
                                                    {{ trans('Add Field') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-12 row addedField">

                                    </div>

                                    <div class="col-md-12">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between mb-3">
                                                <h5 class="card-title">@lang('User Specification Form')</h5>
                                                <a href="javascript:void(0)" class="btn btn-dark btn-sm btn-rounded"
                                                   id="generate-specification"><i class="fa fa-plus-circle"></i>
                                                    {{ trans('Add Field') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-12 row addedSpecification">

                                    </div>
                                @endif

                                @if ($loop->index == 0)
                                    <div class="col-sm-12 col-md-6">
                                        <div class="form-group">
                                            <label for="image">@lang('Image')</label>
                                            <div class="image-input ">
                                                <label for="image-upload" id="image-label"><i
                                                        class="fas fa-upload"></i></label>
                                                <input type="file" name="image" placeholder="@lang('Choose image')"
                                                       id="image">
                                                <img id="image_preview_container" class="preview-image"
                                                     src="{{ getFile(config('location.category.path')) }}"
                                                     alt="@lang('preview image')">
                                            </div>
                                            <span class="text-secondary">@lang('Image size') {{config('location.category.size')}} @lang('px')</span>
                                            @error('image')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Status')</label>
                                                    <div class="custom-switch-btn">
                                                        <input type='hidden' value='1' name='status'>
                                                        <input type="checkbox" name="status" class="custom-switch-checkbox"
                                                               id="status"
                                                               value="0" >
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
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-12">
                                                <label for="name"> @lang('Sell Charge') </label>
                                                <div class="input-group">
                                                    <input type="text" name="sell_charge"
                                                           class="form-control
                                                           value="{{ old('sell_charge') }}" required>
                                                    <div class="input-group-prepend">
                                                        <button class="form-control  mb-3">
                                                         @lang('%')
                                                        </button>
                                                    </div>

                                                </div>
                                                <div class="invalid-feedback">
                                                    @error('sell_charge')
                                                    @lang($message)
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            </div>

                            <button type="submit"
                                    class="btn waves-effect waves-light btn-rounded btn-primary btn-block mt-3">@lang('Save')
                            </button>
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

        $(document).ready(function() {
            $('select[name=category_id]').select2({
                selectOnClose: true
            });
        });
    </script>

    <script>
        "use strict";

        $(document).ready(function(e) {

            $("#generate").on('click', function() {
                var form = `<div class="col-md-12">
                <div class="form-group">
                    <div class="input-group">
                        <input name="field_name[]" class="form-control " type="text" value="" required placeholder="{{ trans('Field Name') }}">

                        <select name="type[]"  class="form-control d-none">
                            <option value="text">{{ trans('Input Text') }}</option>
                            <option value="textarea">{{ trans('Textarea') }}</option>
                            <option value="file">{{ trans('File upload') }}</option>
                        </select>

                        <select name="validation[]"  class="form-control  ">
                            <option value="required">{{ trans('Required') }}</option>
                            <option value="nullable">{{ trans('Optional') }}</option>
                        </select>

                        <span class="input-group-btn">
                            <button class="btn btn-danger delete_desc" type="button">
                                <i class="fa fa-times"></i>
                            </button>
                        </span>
                    </div>
                </div>
            </div> `;

                $('.addedField').append(form)
            });


            $(document).on('click', '.delete_desc', function() {
                $(this).closest('.input-group').parent().remove();
            });

            $("#generate-specification").on('click', function() {
                var form = `<div class="col-md-12">
                <div class="form-group">
                    <div class="input-group">
                        <input name="field_specification[]" class="form-control " type="text" value="" required placeholder="{{ trans('Field Name') }}">

                        <select name="type[]"  class="form-control d-none">
                            <option value="text">{{ trans('Input Text') }}</option>
                            <option value="textarea">{{ trans('Textarea') }}</option>
                            <option value="file">{{ trans('File upload') }}</option>
                        </select>

                        <select name="validation_specification[]"  class="form-control  ">
                            <option value="required">{{ trans('Required') }}</option>
                            <option value="nullable">{{ trans('Optional') }}</option>
                        </select>

                        <span class="input-group-btn">
                            <button class="btn btn-danger delete_desc" type="button">
                                <i class="fa fa-times"></i>
                            </button>
                        </span>
                    </div>
                </div>
            </div> `;

                $('.addedSpecification').append(form)
            });


            $(document).on('click', '.delete_desc', function() {
                $(this).closest('.input-group').parent().remove();
            });



            $('#image').change(function() {
                let reader = new FileReader();
                reader.onload = (e) => {
                    $('#image_preview_container').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            });




        });
    </script>

    <script>
        "use strict";
        $(document).ready(function(e) {

            $('#image').change(function() {
                let reader = new FileReader();
                reader.onload = (e) => {
                    $('#image_preview_container').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            });

            $('.summernote').summernote({
                height: 250,
                callbacks: {
                    onBlurCodeview: function() {
                        let codeviewHtml = $(this).siblings('div.note-editor').find('.note-codable')
                            .val();
                        $(this).val(codeviewHtml);
                    }
                }
            });


        });

        $(document).ready(function() {
            $('select').select2({
                selectOnClose: true
            });
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
