@extends('web.layouts')

@section('content')


    @if(auth()->check())
        @include('web.partial.user-profile-banner')
    @endif





    <!-- =-=-=-=-=-=-= Main Content Area =-=-=-=-=-=-= -->
    <div class="main-content-area reset clearfix view-profile">

        <!-- =-=-=-=-=-=-= Featured Ads =-=-=-=-=-=-= -->
        <section class="custom-padding">
            <!-- Main Container -->
            <div class="container menu-list-items">
                <!-- Row -->
                <div class="row">
                    @if($errors->any())
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="alert alert-danger">
                                    {{__('Some fields are invalid please fix them')}}
                                </div>
                            </div>
                        </div>
                    @elseif(Session::has('status'))
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="alert alert-{{Session::get('status')}}">
                                    <button type="button" id="close" class="close" data-dismiss="modal" aria-label="Close" style="float:right;">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    {{ Session::get('msg') }}
                                </div>
                            </div>
                        </div>
                    @endif


                        @if(auth()->check())
                            @include('web.partial.user-sidebar')
                        @endif

                    <div class="tab-content">
                        <div class="table_below tab-pane fade in active" id="dealsIn">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover responsive">
                                    <thead>
                                    <tr>
                                        <th>{{__('Name')}}</th>
                                        <th>{{__('Show To Friends')}}</th>
                                        <th>{{__('Show To Follower')}}</th>
                                        <th>{{__('Show To Public')}}</th>
                                        <th>{{__('Edit')}}</th>
                                        {{--<th>{{__('Delete')}}</th>--}}
                                    </tr>
                                    </thead>
                                    @foreach($stages as $stage)
                                        <tr id="stage_item_{{$stage->id}}">
                                            <td>{{$stage->name}}</td>
                                            <td>{{$stage->show_to_friends}}</td>
                                            <td>{{$stage->show_to_followers}}</td>
                                            <td>{{$stage->show_to_public}}</td>
                                            {{--<td><a href="{{route('web.user.stage-edit',$stage->id)}}">{{__('Edit')}}</a></td>--}}
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><i class="ft-cog icon-left"></i>
                                                    <span class="caret"></span></button>
                                                    <ul class="dropdown-menu" style="min-width: 81px !important;">
                                                        <li class="dropdown-item"><a href='{{route('web.user.stage-edit',$stage->id)}}'>{{__('Edit')}}</a></li>
                                                        <li class="dropdown-item"><a href="javascript:void(0);" onclick="deleteStage('{{route('web.user.stage-delete',$stage->id)}}','{{$stage->id}}')">{{__('Delete')}}</a></li>

                                                    </ul>
                                                </div>
                                            </td>
                                            {{--<td><a type="button" onclick="deleteStage('{{route('web.user.stage-delete',$stage->id)}}')" href="javascript:void(0);">{{__('delete')}}<i class="fa fa-trash"></i> </a></td>--}}
                                        </tr>
                                        @endforeach
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>


                    </div>
                    <!-- Row End -->
                </div>
            </div>
        </section>
        <!-- Main Container End -->
    </div>



@endsection

@section('footer')


    <script>
        function chanegStatus(id){
            // $('#changeStatus').submit(function (e) {
            e.preventDefault();
            var url = '{{ route('web.user.deal.update-status', ":id") }}';
            url = url.replace(':id',id);
            $.post(url, $('#changeStatus').find(":input").serialize(), function (test) {
                if (test.status == false) {
                    $.each(test.data, function (index, value) {
                        notify('error', value);
                    });

                } else {
                    notify('success', test.msg);
                }
            }, 'json');
            // });
        }
        function deleteStage(routeName,id){

            if(!confirm("{{__('Do you want to delete this Stage?')}}")){
                return false;
            }

            $.post(
                routeName,
                {
                    '_method':'DELETE',
                    '_token':$('meta[name="csrf-token"]').attr('content')
                },
                function(response){
                    if(response.status == true){
                        notify('success','{{__('Stage has been deleted Successfully')}}');
                        $("#stage_item_"+id).remove();

                    }else{
                        {{--notify('success','{{__('Mail has been deleted Successfully')}}');--}}
                        notify('error','{{__('Can not delete this Stage')}}');
                    }

                }
            )
        }
    </script>
@endsection
