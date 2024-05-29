@extends('admin.layouts.app')

@section('title')
    @lang('Edit Top Up')
@endsection

@section('content')
    <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
        <div class="card-body">
            <div class="media mb-4 justify-content-end">
                <a href="{{ route('admin.category') }}" class="btn btn-sm  btn-primary mr-2">
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
                        <form method="post" action="{{ route('admin.categoryUpdate', [$id, $language->id]) }}"
                              class="mt-4" enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <div class="row">
                                <div class="col-sm-12 col-md-12 mb-3">
                                    <label for="name"> @lang('Category Name') </label>
                                    <input type="text" name="name[{{ $language->id }}]"
                                           class="form-control  @error('name' . '.' . $language->id) is-invalid @enderror"
                                           value="<?php echo old('name' . $language->id, isset($categoryDetails[$language->id]) ? @$categoryDetails[$language->id][0]->name : ''); ?>">
                                    <div class="invalid-feedback">
                                        @error('name' . '.' . $language->id)
                                        @lang($message)
                                        @enderror
                                    </div>
                                    <div class="valid-feedback"></div>
                                </div>

                                @if ($loop->index == 0)
                                    <div class="col-sm-12 col-md-6">
                                        <label for="appStoreLink"> @lang('Apple Store Link') </label>
                                        <input type="text" name="appStoreLink"
                                               class="form-control  @error('appStoreLink') is-invalid @enderror"
                                               value="<?php echo old('appStoreLink' . $language->id, isset($categoryDetails[$language->id]) ? @$categoryDetails[$language->id][0]->category->appStoreLink : ''); ?>">
                                        <div class="invalid-feedback">
                                            @error('appStoreLink')
                                            @lang($message)
                                            @enderror
                                        </div>
                                        <div class="valid-feedback"></div>
                                    </div>

                                    <div class="col-sm-12 col-md-6">
                                        <label for="playStoreLink"> @lang('Play Store Link') </label>
                                        <input type="text" name="playStoreLink"
                                               class="form-control  @error('playStoreLink') is-invalid @enderror"
                                               value="<?php echo old('playStoreLink' . $language->id, isset($categoryDetails[$language->id]) ? @$categoryDetails[$language->id][0]->category->playStoreLink : ''); ?>">
                                        <div class="invalid-feedback">
                                            @error('playStoreLink')
                                            @lang($message)
                                            @enderror
                                        </div>
                                        <div class="valid-feedback"></div>
                                    </div>
                                @endif


                                <div class="col-sm-12 col-md-12 my-3">
                                    <div class="form-group ">
                                        <label for="details"> @lang('Details') </label>
                                        <textarea
                                            class="form-control summernote @error('details' . '.' . $language->id) is-invalid @enderror"
                                            name="details[{{ $language->id }}]" id="summernote" rows="15"
                                            value="<?php echo old('details' . $language->id, isset($categoryDetails[$language->id]) ? @$categoryDetails[$language->id][0]->details : ''); ?>">
                                            <?php echo old('details' . $language->id, isset($categoryDetails[$language->id]) ? @$categoryDetails[$language->id][0]->details : ''); ?>
                                        </textarea>

                                        <div class="invalid-feedback">
                                            @error('details' . '.' . $language->id)
                                            @lang($message)
                                            @enderror
                                        </div>
                                        <div class="valid-feedback"></div>
                                    </div>
                                </div>


                                @if ($loop->index == 0)
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="image">@lang('Image')</label>
                                            <div class="image-input ">
                                                <label for="image-upload" id="image-label"><i
                                                        class="fas fa-upload"></i></label>
                                                <input type="file" name="image" placeholder="@lang('Choose image')"
                                                       id="image">
                                                <img id="image_preview_container" class="preview-image"
                                                     src="{{ getFile(config('location.category.path') . (isset($categoryDetails[$language->id]) ? @$categoryDetails[$language->id][0]->category->image : '')) }}"
                                                     alt="@lang('preview image')">
                                            </div>
                                            <span
                                                class="text-secondary">@lang('Image size') {{config('location.category.size')}} @lang('px')</span>
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
                                                         src="{{ getFile(config('location.category.path') . (isset($categoryDetails[$language->id]) ? @$categoryDetails[$language->id][0]->category->thumb : '')) }}"
                                                         alt="preview image">
                                                </div>
                                            </div>
                                            <span
                                                class="text-secondary">@lang('Thumb size') {{config('location.voucher.thumb')}} @lang('px')</span>
                                            @error('thumb')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="image">@lang('Instruction Image')</label>
                                        <div class="form-group position-relative">
                                            <div class="image-input z0">
                                                <label for="image-upload" id="instruction_image-label"><i
                                                        class="fas fa-upload"></i></label>
                                                <input type="file" name="instruction_image"
                                                       placeholder="@lang('Choose image')" id="instruction_image">
                                                <img id="instruction_image_preview_container" class="preview-image"
                                                     src="{{ getFile(config('location.category.path') . (isset($categoryDetails[$language->id]) ? @$categoryDetails[$language->id][0]->category->instruction_image : '')) }}"
                                                     alt="@lang('preview image')">
                                            </div>
                                            @if($categoryDetails[$language->id][0]->category->instruction_image)
                                                <button
                                                    class="btn btn-danger notiflix-confirm removeFile z9"
                                                    data-route="{{ route('admin.top.image.delete',$categoryDetails[$language->id][0]->category->id) }}"
                                                    data-toggle="modal"
                                                    data-target="#delete-modal"
                                                    type="button"
                                                    title="Delete Image">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            @endif

                                        </div>

                                        <span
                                            class="text-secondary">@lang('Instruction Image size') {{config('location.category.size')}} @lang('px')</span>
                                        @error('instruction_image')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                @endif

                                @if ($loop->index == 0)

                                    <div class="col-md-12">
                                        <div class="card smoke-bg">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between mb-3">

                                                    <h5 class="card-title">@lang('User Form')</h5>
                                                    <a href="javascript:void(0)" class="btn btn-dark btn-sm btn-rounded"
                                                       id="generate"><i class="fa fa-plus-circle"></i>
                                                        {{ trans('Add Field') }}</a>
                                                </div>

                                                <div class=" row addedField">
                                                    @if ($categoryDetails[$language->id][0]->category->form_field)
                                                        @foreach (@$categoryDetails[$language->id][0]->category->form_field as $k => $v)
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <div class="input-group">

                                                                        <input name="field_name[]" class="form-control"
                                                                               type="text" value="{{ $v->field_level }}"
                                                                               required
                                                                               placeholder="{{ trans('Field Name') }}">

                                                                        <select name="type[]" class="form-control  ">
                                                                            <option value="text"
                                                                                    @if ($v->type == 'text') selected @endif>
                                                                                {{ trans('Input Text') }}</option>
                                                                            <option value="textarea"
                                                                                    @if ($v->type == 'textarea') selected @endif>
                                                                                {{ trans('Textarea') }}</option>
                                                                            <option value="file"
                                                                                    @if ($v->type == 'file') selected @endif>
                                                                                {{ trans('File upload') }}</option>
                                                                        </select>

                                                                        <select name="validation[]"
                                                                                class="form-control  ">
                                                                            <option value="required"
                                                                                    @if ($v->validation == 'required') selected @endif>
                                                                                {{ trans('Required') }}</option>
                                                                            <option value="nullable"
                                                                                    @if ($v->validation == 'nullable') selected @endif>
                                                                                {{ trans('Optional') }}</option>
                                                                        </select>

                                                                        <span class="input-group-btn">
                                                                <button class="btn btn-danger  delete_desc"
                                                                        type="button">
                                                                    <i class="fa fa-times"></i>
                                                                </button>
                                                            </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                @endif

                                <div class="col-sm-12 col-md-12 my-3">
                                    <div class="form-group ">
                                        <label for="instruction_text"> @lang('Instruction Text') </label>
                                        <textarea
                                            class="form-control summernote @error('instruction_text' . '.' . $language->id) is-invalid @enderror"
                                            name="instruction_text[{{ $language->id }}]" id="summernote" rows="15"
                                            value="<?php echo old('instruction_text' . $language->id, isset($categoryDetails[$language->id]) ? @$categoryDetails[$language->id][0]->instruction : ''); ?>">
                                            <?php echo old('instruction_text' . $language->id, isset($categoryDetails[$language->id]) ? @$categoryDetails[$language->id][0]->instruction : ''); ?></textarea>
                                        <div class="invalid-feedback">
                                            @error('instruction_text' . '.' . $language->id)
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
                                                   value="<?php echo old('discount_amount' . $language->id, isset($categoryDetails[$language->id]) ? @$categoryDetails[$language->id][0]->Category->discount_amount : ''); ?>">

                                            <div class="input-group-append">
                                                <select class="form-control  mb-3" name="discount_type">
                                                    <option value="0"
                                                            @if (@$categoryDetails[$language->id][0]->Category->discount_type == '0') selected @endif>@lang(config('basic.currency'))</option>
                                                    <option value="1"
                                                            @if (@$categoryDetails[$language->id][0]->Category->discount_type == '1') selected @endif>
                                                        %
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                        @error('discount_amount')
                                        <div class="invalid-feedback">
                                            @lang($message)
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label>@lang('Discount Status') </label>
                                        <div class="custom-switch-btn">
                                            <input type='hidden' value='1' name='discount_status'>
                                            <input type="checkbox" name="discount_status" class="custom-switch-checkbox"
                                                   id="discount_status"
                                                   value="0" <?php if (@$categoryDetails[$language->id][0]->category->discount_status == 0):echo 'checked'; endif ?> >
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
                                                   value="0" <?php if (@$categoryDetails[$language->id][0]->category->featured == 0):echo 'checked'; endif ?> >
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
                                                       value="0" <?php if (@$categoryDetails[$language->id][0]->category->status == 0):echo 'checked'; endif ?> >
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

                            <button type="submit"
                                    class="btn waves-effect waves-light btn-rounded btn-primary btn-block mt-3">@lang('Save')</button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    </div>



    <!-- Delete Modal -->
    <div id="delete-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="primary-header-modalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h4 class="modal-title" id="primary-header-modalLabel">@lang('Delete Confirmation')
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                    </button>
                </div>
                <div class="modal-body">
                    <p>@lang('Are you sure to remove this instruction image?')</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">@lang('Close')</button>
                    <form action="" method="post" class="deleteRoute">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-primary">@lang('Yes')</button>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

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
            $('.notiflix-confirm').on('click', function () {
                var route = $(this).data('route');
                $('.deleteRoute').attr('action', route)
            })
        });

        $(document).ready(function (e) {

            $("#generate").on('click', function () {
                var form = `<div class="col-md-12">
                <div class="form-group">
                    <div class="input-group">
                        <input name="field_name[]" class="form-control " type="text" value="" required placeholder="{{ trans('Field Name') }}">

                        <select name="type[]"  class="form-control  ">
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

            $('#instruction_image').change(function () {
                let reader = new FileReader();
                reader.onload = (e) => {
                    $('#instruction_image_preview_container').attr('src', e.target.result);
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
