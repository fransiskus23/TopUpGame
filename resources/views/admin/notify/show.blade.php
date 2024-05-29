@extends('admin.layouts.app')
@section('title')
    @lang('Default Template')
@endsection
@section('content')

    <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
        <div class="card-body">


            <div class="table-responsive">
                <table id="zero_config"
                       class="table table-striped table-bordered no-wrap">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">@lang('No.')</th>
                        <th scope="col">@lang('Name')</th>
                        <th scope="col">@lang('Status')</th>
                            <th scope="col">@lang('Action')</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($notifyTemplate as $template)
                        <tr>
                            <td> {{$loop->iteration}} </td>
                            <td>{{ $template->name }}</td>
                            <td>

                                        <span class="badge badge-light">
                                                <i class="fa fa-circle {{ $template->status == 0 ? 'text-danger danger' : 'text-success success' }}  font-12"></i> {{ $template->status == 0 ? 'DeActive' : 'Active' }}
                                            </span>
                            </td>
                            <td>
                                <a  href="{{ route('admin.notify-template.edit',$template->id) }}" class="btn btn-sm btn-outline-primary " title="@lang('Edit')"><i class="fas fa-edit" aria-hidden="true"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>





        </div>
    </div>
@endsection
@push('style-lib')
    <link href="{{asset('assets/admin/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
@endpush
@push('js')
    <script src="{{ asset('assets/admin/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/datatable-basic.init.js') }}"></script>
@endpush
