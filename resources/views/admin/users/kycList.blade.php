@extends('admin.layouts.app')
@section('title', $title)


@section('content')



    <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
        <div class="card-body">


            <div class="table-responsive">
                <table class="categories-show-table table table-hover table-striped table-bordered">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">@lang('No.')</th>
                        <th scope="col">@lang('Name')</th>
                        <th scope="col">@lang('Verification Type')</th>
                        <th scope="col">@lang('Status')</th>
                            <th scope="col">@lang('Action')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($logs as $item)
                        <tr>
                            <td data-label="@lang('No.')">{{loopIndex($logs) + $loop->index	 }}</td>
                            <td data-label="@lang('Username')">
                                <a href="{{route('admin.user-edit',[$item->user_id])}}">
                                    @lang(optional($item->user)->fullname)
                                </a>
                            </td>
                            <td data-label="@lang('Verification Type')">
                                {{kebab2Title($item->kyc_type)}}
                            </td>
                            <td data-label="@lang('Status')">
                                @if($item->status == 0)
                                    <span class="badge badge-pill badge-warning">{{trans('Pending')}}</span>
                                @elseif($item->status == 1)
                                    <span class="badge badge-pill badge-success">{{trans('Approved')}}</span>
                                @elseif($item->status == 2)
                                    <span class="badge badge-pill badge-danger">{{trans('Rejected')}}</span>
                                @endif

                            </td>

                                <td data-label="@lang('Action')">
                                    @php
                                        if($item->details){
                                                $details =[];
                                                  foreach($item->details as $k => $v){
                                                        if($v->type == "file"){
                                                            $details[kebab2Title($k)] = [
                                                                'type' => $v->type,
                                                                'field_name' => getFile(config('location.kyc.path').date('Y',strtotime($item->created_at)).'/'.date('m',strtotime($item->created_at)).'/'.date('d',strtotime($item->created_at)) .'/'.$v->field_name)
                                                                ];
                                                        }else{
                                                            $details[kebab2Title($k)] =[
                                                                'type' => $v->type,
                                                                'field_name' => $v->field_name
                                                            ];
                                                        }
                                                  }
                                            }else{
                                                $details = null;
                                            }
                                    @endphp

                                    <button
                                        class="edit_button   btn  {{($item->status == 0) ?  'btn-primary' : 'btn-success'}} text-white  btn-sm "
                                        data-toggle="modal" data-target="#myModal"
                                        data-title="{{($item->status == 0) ?  trans('Edit') : trans('Details')}}"

                                        data-id="{{ $item->id }}"
                                        data-info="{{json_encode($details)}}"
                                        data-route="{{route('admin.users.Kyc.action',$item->id)}}"
                                        data-status="{{$item->status}}">

                                        @if(($item->status == 0))
                                            <i class="fa fa-pencil-alt"></i>
                                        @else
                                            <i class="fa fa-eye"></i>
                                        @endif

                                    </button>
                                </td>
                            
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center" colspan="100%">@lang('No User Data')</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                {{$logs->appends(@$search)->links('partials.pagination')}}

            </div>
        </div>
    </div>




    <!-- Modal for Edit button -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content ">
                <div class="modal-header modal-colored-header bg-primary">
                    <h4 class="modal-title" id="myModalLabel">@lang('KYC Information')</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>

                <form role="form" method="POST" class="actionRoute" action="" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="modal-body">
                        <ul class="list-group withdraw-detail">
                        </ul>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
                        @if(Request::routeIs('admin.kyc.users.pending'))
                            <input type="hidden" class="action_id" name="id">
                            <button type="submit" class="btn btn-primary" name="status"
                                    value="1">@lang('Approve')</button>
                            <button type="submit" class="btn btn-danger" name="status"
                                    value="2">@lang('Reject')</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <script>
        "use strict";
        $(document).on("click", '.edit_button', function (e) {
            var id = $(this).data('id');

            $(".action_id").val(id);
            $(".actionRoute").attr('action', $(this).data('route'));
            var details = Object.entries($(this).data('info'));
            var list = [];
            details.map(function (item, i) {
                if (item[1].type == 'file') {
                    var singleInfo = `<br><img src="${item[1].field_name}" alt="..." class="w-100">`;
                } else {
                    var singleInfo = `<span class="font-weight-bold ml-3">${item[1].field_name}</span>  `;
                }
                list[i] = ` <li class="list-group-item"><span class="font-weight-bold "> ${item[0].replace('_', " ")} </span> : ${singleInfo}</li>`
            });
            $('.withdraw-detail').html(list);

        });

    </script>
@endpush
