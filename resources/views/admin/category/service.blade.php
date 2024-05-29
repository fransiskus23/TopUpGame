@extends('admin.layouts.app')

@section('title')
    @lang(@$game->details->name.' Services')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow my-3">
                    <div class="card-body">

                        <div class="d-flex justify-content-end">
                            <a href="{{route('admin.gameSampleFiles')}}" class="btn btn-sm btn-success btn-rounded"><i
                                    class="fa fa-download"></i> @lang('Sample')</a>
                        </div>

                        <form class="form-inline " action="{{route('admin.uploadBulkgameList',$game->id)}}"
                              method="post"
                              enctype="multipart/form-data">
                            @csrf

                            <div class="form-group row ">
                                <label for="inputGroupFile01" class="col-sm-3 col-form-label">@lang('Bulk Upload')
                                    :</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" name="upload" class="custom-file-input"
                                                   id="inputGroupFile01" required>
                                            <label class="custom-file-label" for="inputGroupFile01"></label>
                                        </div>
                                    </div>

                                    @error('image')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-primary btn-rounded mx-2"><i
                                            class="fa fa-upload"></i> @lang('Upload')
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card shadow my-3">
                    <div class="card-body">

                        <div class="media justify-content-between mb-4">
                            <div>
                                <button class="btn btn-sm btn-primary mr-2" type="button" data-toggle="modal"
                                        data-target="#add_service"><span><i
                                            class="fa fa-plus-circle"></i> @lang('Add New')</span>
                                </button>

                            </div>

                            <div class="dropdown mb-2 text-right">
                                <button class="btn btn-sm  btn-dark dropdown-toggle" type="button"
                                        id="dropdownMenuButton"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span><i class="fas fa-bars pr-2"></i> @lang('Action')</span>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <button class="dropdown-item" type="button" data-toggle="modal"
                                            data-target="#all_active">@lang('Active')</button>
                                    <button class="dropdown-item" type="button" data-toggle="modal"
                                            data-target="#all_inactive">@lang('Inactive')</button>
                                </div>
                            </div>
                        </div>


                        <div class="table-responsive">
                            <table class="categories-show-table table table-hover table-striped table-bordered"
                                   id="zero_config">
                                <thead class="thead-dark">
                                <tr>
                                    <th scope="col" class="text-center">
                                        <input type="checkbox" class="form-check-input check-all tic-check"
                                               name="check-all"
                                               id="check-all">
                                        <label for="check-all"></label>
                                    </th>

                                    <th scope="col">@lang('SL No.')</th>
                                    <th scope="col">@lang('Name')</th>
                                    <th scope="col">@lang('Price')</th>
                                    <th scope="col">@lang('Status')</th>
                                    <th scope="col">@lang('Action')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($gameServices as $key => $item)
                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox" id="chk-{{ $item->id }}"
                                                   class="form-check-input row-tic tic-check" name="check"
                                                   value="{{$item->id}}"
                                                   data-id="{{ $item->id }}">
                                            <label for="chk-{{ $item->id }}"></label>
                                        </td>

                                        <td data-label="@lang('SL No.')">{{ $loop->index + 1 }}</td>
                                        <td data-label="@lang('Name')">
                                            <div class="d-flex no-block align-items-center">
                                                <div class="">
                                                    <h5 class="text-dark mb-0 font-16 font-weight-medium">{{$item->name}}</h5>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-label="@lang('Price')">
                                            <span class="text-dark font-weight-medium">{{getAmount($item->price)}} {{config('basic.currency')}}
                                            </span>
                                        </td>

                                        <td data-label="@lang('Status')">
                                            <span class="badge badge-light"><i
                                                    class="fa fa-circle {{ $item->status == 0 ? 'text-danger danger' : 'text-success success' }}  font-12"></i> {{ $item->status == 0 ? 'Deactive' : 'Active' }}
                                            </span>
                                        </td>

                                        <td data-label="@lang('Action')">

                                            <div class="dropdown show dropup">
                                                <a class="dropdown-toggle p-3" href="#" id="dropdownMenuLink"
                                                   data-toggle="dropdown"
                                                   aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                </a>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">


                                                    <button class="dropdown-item edit-button" data-toggle="modal"
                                                            data-target="#update_service"
                                                            data-route="{{route('admin.gameServicesEdit',$item->id)}}"
                                                            data-resource="{{$item}}">
                                                        <i class="fa fa-edit text-warning pr-2"
                                                           aria-hidden="true"></i> @lang('Edit')
                                                    </button>

                                                    <a class="dropdown-item notiflix-confirm" href="javascript:void(0)"
                                                       data-target="#delete-modal"
                                                       data-route="{{ route('admin.gameServicesDelete', $item->id) }}"
                                                       data-toggle="modal">
                                                        <i class="fa fa-trash-alt text-danger pr-2"
                                                           aria-hidden="true"></i> @lang('Delete')
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                @empty
                                    <tr>
                                        <td colspan="100%">@lang('No Data Found')</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="all_active" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h5 class="modal-title">@lang('Services Active Confirmation')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                </div>
                <div class="modal-body">
                    <p>@lang("Are you really want to active the Services")</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal"><span>@lang('No')</span></button>
                    <form action="" method="post">
                        @csrf
                        <a href="" class="btn btn-primary active-yes"><span>@lang('Yes')</span></a>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="all_inactive" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h5 class="modal-title">@lang('Services DeActive Confirmation')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                </div>
                <div class="modal-body">
                    <p>@lang("Are you really want to Deactive the Services")</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal"><span>@lang('No')</span></button>
                    <form action="" method="post">
                        @csrf
                        <a href="" class="btn btn-primary inactive-yes"><span>@lang('Yes')</span></a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Service Modal Start -->
    <div class="modal fade" id="add_service" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h5 class="modal-title"><i class="fa fa-plus-circle"></i> @lang('Add Service')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                </div>
                <form action="{{route('admin.gameServicesStore',$game->id)}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name"> @lang('Name') </label>
                            <input type="text" name="name"
                                   class="form-control"
                                   value="" required>

                            @error('name')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="form-group">
                            <label for="name"> @lang('Price') </label>
                            <input type="text" name="price"
                                   class="form-control"
                                   value="" required>

                            @error('price')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="form-group">

                            <label for="status"> @lang('status') </label>
                            <input
                                data-toggle="toggle" id="status" data-onstyle="success"
                                data-offstyle="info" data-on="Active" data-off="Deactive"
                                data-width="100%"
                                type="checkbox" name="status" checked>
                            @error('status')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal"><span>@lang('Close')</span>
                        </button>
                        <button type="submit" class="btn btn-primary"><span>@lang('Add')</span></button>

                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Add Service Modal End -->


    <!-- Update Service Modal Start -->
    <div class="modal fade" id="update_service" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h5 class="modal-title"><i class="fa fa-pencil-alt"></i> @lang('Update Service')</h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">×
                    </button>
                </div>
                <form action="" method="post" class="update-action">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name"> @lang('Name') </label>
                            <input type="text" name="name"
                                   class="form-control edit-name"
                                   value="{{old('name')}}" required>

                            @error('name')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="name"> @lang('Price') </label>

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{config('basic.currency_symbol')}}</span>
                                </div>
                                <input type="text" name="price" class="form-control edit-price" value="{{old('price')}}"
                                       required>
                            </div>
                            @error('price')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror

                        </div>

                        <div class="form-group">

                            <label for="edit-status"> @lang('status') </label>
                            <input
                                data-toggle="toggle" id="edit-status" data-onstyle="success"
                                data-offstyle="info" data-on="Active" data-off="Deactive"
                                data-width="100%"
                                type="checkbox" name="status">
                            @error('status')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>


                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">
                            <span>@lang('Close')</span></button>
                        <button type="submit" class="btn btn-primary">
                            <span>@lang('Update')</span>
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Update Service Modal End -->


    <!-- Delete Modal Start -->
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
    </div>
    <!-- Delete Modal End -->

@endsection
@push('style-lib')
    <link href="{{ asset('assets/admin/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
@endpush
@push('js')
    <script src="{{ asset('assets/admin/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/datatable-basic.init.js') }}"></script>


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


    <script>
        $(document).on('click', '.edit-button', function () {
            $('.update-action').attr('action', $(this).data('route'))
            var obj = $(this).data('resource');
            $('.edit-name').val(obj.name);
            $('.edit-price').val(obj.price);

            if (obj.status == 1) {
                $('#edit-status').bootstrapToggle('on')
            } else {
                $('#edit-status').bootstrapToggle('off')
            }


        });

        $(document).on('click', '#check-all', function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });

        $(document).on('change', ".row-tic", function () {
            let length = $(".row-tic").length;
            let checkedLength = $(".row-tic:checked").length;
            if (length == checkedLength) {
                $('#check-all').prop('checked', true);
            } else {
                $('#check-all').prop('checked', false);
            }
        });

        //dropdown menu is not working
        $(document).on('click', '.dropdown-menu', function (e) {
            e.stopPropagation();
        });


        //multiple active
        $(document).on('click', '.active-yes', function (e) {
            e.preventDefault();
            var allVals = [];
            $(".row-tic:checked").each(function () {
                allVals.push($(this).attr('data-id'));
            });

            var strIds = allVals;

            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                url: "{{ route('admin.gameList.services.active') }}",
                data: {strIds: strIds},
                datatType: 'json',
                type: "post",
                success: function (data) {
                    location.reload();

                },
            });
        });

        //multiple deactive
        $(document).on('click', '.inactive-yes', function (e) {
            e.preventDefault();
            var allVals = [];
            $(".row-tic:checked").each(function () {
                allVals.push($(this).attr('data-id'));
            });

            var strIds = allVals;
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                url: "{{ route('admin.gameList.services.inactive') }}",
                data: {strIds: strIds},
                datatType: 'json',
                type: "post",
                success: function (data) {
                    location.reload();

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

    <script>
        'use strict'
        $(document).ready(function () {
            $('.notiflix-confirm').on('click', function () {
                var route = $(this).data('route');
                $('.deleteRoute').attr('action', route)
            })
        });
    </script>

@endpush
