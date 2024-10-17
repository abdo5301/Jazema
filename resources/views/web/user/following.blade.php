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
                <!-- Middle Content Box -->


                    <ul class="nav nav-tabs " style="padding-left: 297px;border-bottom: 0;">
                        <li class="col-md-5 col-sm-12 col-xs-12" style="float: right;text-align: center;"><a data-toggle="tab" href="#Followers">{{__('Followers')}}</a></li>
                        <li class="active col-md-4 col-sm-12 col-xs-12" style="text-align: center;"><a data-toggle="tab" href="#Following">{{__('Following')}}</a></li>
                    </ul>
                        @if(auth()->check())
                            @include('web.partial.user-sidebar')
                        @endif

                    <div class="tab-content">

                        <div class="table_below tab-pane fade in active" id="Following">

                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th scope="col">{{__('Following  Name')}}</th>
                                        <th scope="col">{{__('Image')}}</th>
                                        <th scope="col">{{__('Remove')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(!empty($following))
                                    @foreach($following as $item)
                                        <tr id="follow_item_{{$item['id']}}">
                                            <td><a a style="color: blue" href="{{route('web.user.profile',$item['to_user']['slug'])}}">{{$item['to_user']['firstname']}} {{$item['to_user']['lastname']}}</a></td>
                                            <td>  <img style="width: 70px;height: 70px;" src="{{img($item['to_user']['image'],'users')}}" alt=""></td>
                                            <td><a style="color: red" onclick="deleteFollowing('{{route('web.user.unfollow')}}','{{$item['to_user_id']}}','{{$item['id']}}')" href="javascript:void(0);"> {{__('Delete')}}</a></td>
                                        </tr>
                                    @endforeach
                                        @endif
                                    </tbody>
                                </table>
                                <div class="pull-left">
                                    @if(request()->input('page') !=0)
                                        Showing  <span style="color: grey">{{$following->count() * request()->input('page')}} of {{ $following->total() }}entries</span>
                                    @else
                                        Showing  <span style="color: grey">{{$following->count()}} of {{ $following->total() }}entries</span>
                                    @endif
                                </div>
                                <div class="pull-right">
                                    {{ $following->appends(request()->input())->links() }}
                                </div>
                            </div>
                        </div>
                        <div class="table_below tab-pane fade in " id="Followers">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th scope="col">{{__('Follower Name')}}</th>
                                        <th scope="col">{{__('Image')}}</th>
                                        {{--<th scope="col">{{__('Remove')}}</th>--}}
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($followers as $friend)
                                        <tr>
                                            <td><a a style="color: blue" href="{{route('web.user.profile',$friend['user']['slug'])}}">{{$friend['user']['firstname']}} {{$friend['user']['lastname']}}</a></td>
                                            <td>  <img style="width: 70px;height: 70px;" src="{{img($friend['user']['image'],'users')}}" alt=""></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="pull-left">
                                    @if(request()->input('page') !=0)
                                        Showing  <span style="color: grey">{{$followers->count() * request()->input('page')}} of {{ $followers->total() }}entries</span>
                                    @else
                                        Showing  <span style="color: grey">{{$followers->count()}} of {{ $followers->total() }}entries</span>
                                    @endif
                                </div>
                                <div class="pull-right">
                                    {{ $followers->appends(request()->input())->links() }}
                                </div>
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


        function deleteFollowing(routeName,user_id,id){

            if(!confirm("Do you want to unfollow this user ?")){
                return false;
            }

            $.post(
                routeName,
                {
                    'user_id':user_id,
                    'type':'follow',
                    '_method':'POST',
                    '_token':$('meta[name="csrf-token"]').attr('content')
                },
                function(response){
                    if(response.status == true){
                        notify('success',response.msg);
                        $("#follow_item_"+id).remove();
                    }else{
                        notify('error','{{__('Can Not Do this action')}}');
                    }

                }
            )
        }

    </script>
@endsection
