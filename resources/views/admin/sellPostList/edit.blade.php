@extends('admin.layouts.app')

@section('title')
    @lang(optional($sellPost->category)->details->name)
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
                <div class="card-body">
                    <div class="media mb-4 justify-content-between">
                        @if($sellPost->status==0)
                            <span class="badge badge-warning badge-pill">@lang('Pending')</span>
                        @elseif($sellPost->status==1)
                            <span class="badge badge-success badge-pill">@lang('Approved')</span>
                        @elseif($sellPost->status==2)
                            <span class="badge badge-warning badge-pill">@lang('Re Submission')</span>
                        @elseif($sellPost->status==3)
                            <span class="badge badge-warning badge-pill">@lang('Hold')</span>
                        @elseif($sellPost->status==4)
                            <span class="badge badge-danger badge-pill">@lang('Soft Rejected')</span>
                        @elseif($sellPost->status==5)
                            <span class="badge badge-danger badge-pill">@lang('Hard Rejected')</span>
                        @endif
                        <div class="media mb-4 justify-content-start">
                            <a href="{{ route('admin.gameSellList') }}" class="btn btn-sm  btn-primary mr-2">
                                <span><i class="fas fa-arrow-left"></i> @lang('Back')</span>
                            </a>

                            <div class="dropdown mb-2 text-right">
                                <button class="btn btn-sm  btn-dark" type="button"
                                        data-toggle="modal" data-target="#action" aria-haspopup="true" aria-expanded="false">
                                    <span><i class="fas fa-bars pr-2"></i> @lang('Action')</span>
                                </button>
                            </div>

                        </div>

                    </div>

                    <div class="tab-content mt-2" id="myTabContent">
                        <form method="post" action="{{route('admin.sell.update',$sellPost->id)}}"
                              class="mt-4" enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <input type="hidden" name="category" value="{{$sellPost->category_id}}">
                            <div class="row">
                                <div class="col-sm-12 col-md-12 mb-3">
                                    <label for="title"> @lang('Title') </label>
                                    <input type="text" name="title"
                                           class="form-control  @error('title') is-invalid @enderror"
                                           value="{{$sellPost->title}}">
                                    <div class="invalid-feedback">
                                        @error('title')
                                        @lang($message)
                                        @enderror
                                    </div>
                                    <div class="valid-feedback"></div>
                                </div>

                                <div class="col-sm-12 col-md-12 mb-3 header-box-title">
                                    <label for="title"> @lang('Price') </label>
                                    <span class="info sellCharge" title="@lang("How much of this will i earn ?")" data-resource="{{$sellPost->sell_charge}}" data-toggle="modal" data-target="#sellCharge">
                                        <img class="info-icon" src="{{ asset($themeTrue) . '/images/icon/info.png' }}"
                                             alt="..."/>
                                    </span>
                                    <div class="input-group">
                                        <input type="text" name="price"
                                               class="form-control price"
                                               value="{{$sellPost->price}}">
                                        <div class="input-group-prepend">
                                            <button type="button" class="form-control" value="">@lang(config('basic.currency'))</button>
                                        </div>
                                    </div>

                                    <div class="invalid-feedback">
                                        @error('price')
                                        @lang($message)
                                        @enderror
                                    </div>
                                </div>


                                <div class="col-sm-12 col-md-12 my-3">
                                    <div class="form-group ">
                                        <label for="details"> @lang('Details') </label>
                                        <textarea class="form-control summernote @error('details') is-invalid @enderror"
                                                  name="details" id="summernote" rows="15" value="">{{$sellPost->details}}
                                        </textarea>

                                        <div class="invalid-feedback">
                                            @error('details')
                                            @lang($message)
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-12 my-3">
                                    <div class="form-group ">
                                        <label for="comments"> @lang('Comments') </label>
                                        <textarea class="form-control summernote @error('comments') is-invalid @enderror"
                                                  name="comments" id="summernote" rows="10" value="">{{$sellPost->comments}}
                                        </textarea>

                                        <div class="invalid-feedback">
                                            @error('comments')
                                            @lang($message)
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                @if(isset($sellPost) && !empty($sellPost->credential))
                                    <div class="col-md-12 custom-back mb-4">
                                        <div class="dark-bg p-3">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="header-box-title">
                                                        <h6>@lang($sellPost->category->details->name.' Credential')
                                                        </h6>
                                                    </div>
                                                </div>
                                                @forelse($sellPost->credential as $k => $v)
                                                    <div class="col-md-6">
                                                        @if ($v->type == 'text')
                                                            <div class="form-group">
                                                                <label
                                                                    for="exampleFormControlInput1"
                                                                    class="form-label"
                                                                >{{ trans($v->field_name) }}

                                                                    @if ($v->validation == 'required') <span class="text-danger">*</span> @endif
                                                                </label>
                                                                <input name="{{ $k }}" type="text" class="form-control"
                                                                       value="{{ trans($v->field_value) }}"
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
                                                                >{{ trans($v->field_name) }}

                                                                    @if ($v->validation == 'required') <span class="text-danger">*</span> @endif
                                                                </label>

                                                                <textarea name="{{ $k }}" class="form-control"
                                                                @if ($v->validation == 'required')  @endif>{{old($k)}}</textarea>
                                                                @if ($errors->has($k))
                                                                    <span class="text-danger">{{ trans($errors->first($k)) }}</span>
                                                                @endif

                                                            </div>
                                                        @elseif($v->type == 'file')
                                                            <div class="form-group">
                                                                <label
                                                                    for="exampleFormControlInput1"
                                                                    class="form-label"
                                                                >{{ trans($v->field_name) }}

                                                                    @if ($v->validation == 'required') <span class="text-danger">*</span> @endif
                                                                </label>

                                                                <input name="{{ $k }}" type="file" class="form-control"
                                                                       placeholder="{{ trans($v->field_value) }}"
                                                                @if ($v->validation == 'required')   @endif />

                                                                @if ($errors->has($k))
                                                                    <span class="text-danger">{{ trans($errors->first($k)) }}</span>
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

                                @if(isset($sellPost) && !empty($sellPost->post_specification_form))
                                    <div class="col-md-12 custom-back">
                                        <div class="dark-bg p-3">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="header-box-title">
                                                        <h6>@lang($sellPost->category->details->name.' Specification')
                                                        </h6>
                                                    </div>
                                                </div>
                                                @forelse($sellPost->post_specification_form as $k => $v)
                                                    <div class="col-md-6">
                                                        @if ($v->type == 'text')
                                                            <div class="form-group">
                                                                <label
                                                                    for="exampleFormControlInput1"
                                                                    class="form-label"
                                                                >{{ trans($v->field_name) }}

                                                                    @if ($v->validation == 'required') <span class="text-danger">*</span> @endif
                                                                </label>
                                                                <input name="{{ $k }}" type="text" class="form-control"
                                                                       value="{{ trans($v->field_value) }}"
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
                                                                >{{ trans($v->field_name) }}

                                                                    @if ($v->validation == 'required') <span class="text-danger">*</span> @endif
                                                                </label>

                                                                <textarea name="{{ $k }}" class="form-control"
                                                                @if ($v->validation == 'required')  @endif>{{old($k)}}</textarea>
                                                                @if ($errors->has($k))
                                                                    <span class="text-danger">{{ trans($errors->first($k)) }}</span>
                                                                @endif

                                                            </div>
                                                        @elseif($v->type == 'file')
                                                            <div class="form-group">
                                                                <label
                                                                    for="exampleFormControlInput1"
                                                                    class="form-label"
                                                                >{{ trans($v->field_name) }}

                                                                    @if ($v->validation == 'required') <span class="text-danger">*</span> @endif
                                                                </label>

                                                                <input name="{{ $k }}" type="file" class="form-control"
                                                                       placeholder="{{ trans($v->field_value) }}"
                                                                @if ($v->validation == 'required')   @endif />

                                                                @if ($errors->has($k))
                                                                    <span class="text-danger">{{ trans($errors->first($k)) }}</span>
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

                                <div class="col-lg-12 col-md-6 mt-3 mb-4">
                                    <div class="form-group">
                                        <a href="javascript:void(0)" class="btn btn-success float-left mt-3 generate">
                                            <i class="fa fa-image"></i> @lang('Add More')</a>
                                    </div>
                                    <div class="invalid-feedback">
                                        @error('image')
                                        @lang($message)
                                        @enderror
                                    </div>
                                </div>


                                <div class="col-lg-12">

                                    <div class="row addedField mt-3">
                                        @if($sellPost->image)
                                            @for($i = 0; $i<count($sellPost->image); $i++)
                                                <div class="col-sm-12 col-md-4 image-column">
                                                    <div class="form-group position-relative">
                                                        <div class="image-input z0">
                                                            <label for="image-upload" id="image-label"><i class="fas fa-upload"></i></label>
                                                            <img id="image_preview_container" class="preview-image"	src="{{ getFile(config('location.sellingPost.path').@$sellPost->image[$i]) }}" alt="@lang('preview image')">
                                                        </div>


                                                        <button
                                                            class="btn btn-danger notiflix-confirm removeFile z9"
                                                            data-route="{{ route('admin.sell.image.delete',[$sellPost->id,$sellPost->image[$i]]) }}"
                                                            data-toggle="modal"
                                                            data-target="#delete-modal"
                                                            type="button"
                                                            title="Delete Image"
                                                        >
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endfor
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <button type="submit"
                                    class="btn waves-effect waves-light btn-rounded btn-primary btn-block mt-3">@lang('Save')
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title"><i class="fa fa-history"></i> @lang('Activity Log')</h5>
                    <ul class="list-unstyled">
                        @forelse($activity as $k => $row)
                            <li class="media d-block">
                                <div class="d-block w-100">
                                    <div class="d-flex no-block align-items-center">
                                        <div class="mr-3">
                                            <a href="javascript:void(0)" title="{{optional($row->activityable)->username}}">
                                            <img src="{{optional($row->activityable)->imgPath}}" alt="user" class="rounded-circle" width="45" height="45">
                                            </a>
                                        </div>
                                        <div class="w-100">
                                            <h5 class="text-dark mb-0 font-16 font-weight-medium">@lang($row->title)</h5>
                                            <span class="text-muted font-14"><i class="fa fa-clock"></i> {{diffForHumans($row->created_at)}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="pl-5 pt-2">
                                    <p>@lang($row->description)</p>
                                </div>
                            </li>

                        @empty
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Sell Charge -->
    <div class="modal fade" id="sellCharge" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h5 class="modal-title"><i class="fa fa-plus-circle"></i> @lang('How Much You Earn')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                </div>
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="price" class="font-weight-bold"> @lang('Price') </label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ config('basic.currency_symbol') }}</span>
                                </div>
                                <input type="text" name="" class="form-control edit-price modal-price"
                                       value="">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <label>@lang('Seller Earn')</label>
                                        <label class="earn ml-2">0</label>
                                        <div class="input-group-prepend">
                                            <label class="ml-2">{{config('basic.currency')}}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <label>@lang('You will earn ')</label>
                                        <label class="charge ml-2"></label>
                                        <div class="input-group-prepend">
                                            <label class="ml-2">{{config('basic.currency')}}</label>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal"><span>@lang('Close')</span>
                        </button>
                    </div>
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
                    <p>@lang('Are you sure to delete this?')</p>
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

    <!-- Action Modal -->
    <div class="modal fade" id="action" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h5 class="modal-title"><i class="fa fa-plus-circle"></i> @lang('Action')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                </div>
                <form action="{{ route('admin.sellPostAction') }}" method="post">
                    @csrf
                    <input type="hidden" name="sell_post_id" value="{{$sellPost->id}}" >
                    <div class="modal-body">

                        <div class="form-group">
                            <label class="font-weight-bold">@lang('Status') </label>
                            <select class="form-control" name="status" aria-label=".form-select-lg example"
                                    required>

                                <option value="" selected disabled>@lang('Select Status')</option>
                                <option value="1">@lang('Approve')
                                </option>
                                <option value="3">@lang('Hold')
                                </option>
                                <option value="4">@lang('Soft Rejected')
                                </option>
                                <option value="5">@lang('Hard Rejected')
                                </option>

                            </select>
                        </div>


                        <div class="form-group">
                            <label for="comments" class="font-weight-bold"> @lang('Comment') </label>
                            <textarea name="comments" rows="4" class="form-control" value="" required></textarea>

                            @error('comments')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal"><span>@lang('Close')</span>
                        </button>
                        <button type="submit" class="btn btn-primary"><span>@lang('Submit')</span></button>

                    </div>
                </form>
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

        var sellCharge,price,totalCharge,sellerEarn;

        $(document).on('click', '.sellCharge', function () {
            sellCharge=$(this).data('resource');
            price= $('.price').val();
            if (price < 0) {
                $('.earn').text(0);
                $('.charge').text(0);
                return 0;
            }
            totalCharge=sellCharge*price/100;
            sellerEarn=price-totalCharge;

            $('.modal-price').val(price);
            $('.earn').text(sellerEarn);
            $('.charge').text(totalCharge);

        });
        $(document).on('keyup', '.modal-price', function () {
            this.value = this.value.replace(/[^0-9\.]/g,'');
            price= $(this).val();
            if (price < 0) {
                $('.charge').text(0);
                $('.earn').text(0);
                return 0;
            }
            sellCharge=$('.sellCharge').data('resource');
            totalCharge=sellCharge*price/100;
            sellerEarn=price-totalCharge;

            $('.charge').text(totalCharge);

            $('.earn').text(sellerEarn);
            $('.price').val(price);

        });

        $(document).ready(function(e) {

            $(document).ready(function() {
                $('.notiflix-confirm').on('click', function() {
                    var route = $(this).data('route');
                    $('.deleteRoute').attr('action', route)
                })
            });
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

            $(".generate").on('click', function () {
                var form = `<div class="col-sm-12 col-md-4 image-column">
                                <div class="form-group">
                                        <div class="image-input position-relative z0">
                                            <label for="image-upload" id="image-label"><i class="fas fa-upload"></i></label>
                                            <input type="file" name="image[]" placeholder="@lang('Choose image')" class="image-preview" required>
                                            <img id="image_preview_container" class="preview-image "	src="{{ getFile(config('location.gameSell.path')) }}" alt="@lang('preview image')">

                                        </div>

                                         <button class="btn btn-danger delete_desc removeFile z9" type="button">
                                                <i class="fa fa-times"></i>
                                        </button>

                                </div>
                                       @if(config("location.product.size"))
                    <span class="text-muted mb-2">{{trans('Image size should be')}} {{config("location.gameSell.size")}} {{trans('px')}}</span>
                                        @endif
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
