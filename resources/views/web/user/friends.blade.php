@extends('web.layouts')
@section('header')
    @endsection
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
                    <!-- Middle Content Box -->

                    <ul class="nav nav-tabs " style="padding-left: 297px;border-bottom: 0;">
                        <li class="col-md-5 col-sm-12 col-xs-12" style="float: right;text-align: center;"><a data-toggle="tab" href="#FriendRequests">{{__('Friend Requests')}}</a></li>
                        <li class="active col-md-4 col-sm-12 col-xs-12" style="text-align: center;"><a data-toggle="tab" href="#Friends">{{__('Friends')}}</a></li>
                    </ul>


                    @if(auth()->check())
                        @include('web.partial.user-sidebar')
                    @endif

                    <div class="tab-content">

                        <div class="table_below tab-pane fade in active" id="Friends">

                        <div class="col-md-9 col-sm-12 col-xs-12">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th scope="col">{{__('Friend Name')}}</th>
                            <th scope="col">{{__('Image')}}</th>
                            <th scope="col">{{__('Remove')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($friends as $friend)
                        <tr id="friend_item_{{$friend['id']}}">
                            <td><a a style="color: blue" href="{{route('web.user.profile',$friend['slug'])}}">{{$friend['firstname']}} {{$friend['lastname']}}</a></td>
                            <td>  <img style="width: 70px;height: 70px;" src="{{img($friend['image'],'users')}}" alt=""></td>
                            <td><a style="color: red" onclick="unfriend('{{route('web.user.unfriend',$friend['id'])}}','{{$friend['id']}}')" href="javascript:void(0);"> {{__('Delete')}}</a></td>
                        </tr>
                            @endforeach
                        </tbody>
                    </table>
                        <div class="pull-left">
                            @if(request()->input('page') !=0)
                                Showing  <span style="color: grey">{{$friends->count() * request()->input('page')}} of {{ $friends->total() }}entries</span>
                            @else
                                Showing  <span style="color: grey">{{$friends->count()}} of {{ $friends->total() }}entries</span>
                            @endif
                        </div>
                        <div class="pull-right">
                            {{ $friends->appends(request()->input())->links() }}
                        </div>
                    </div>


                </div>


                        <div class="table_below tab-pane fade in " id="FriendRequests">
                            <div class="col-md-9 col-sm-12 col-xs-12">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th scope="col">{{__('Friend Name')}}</th>
                                        <th scope="col">{{__('Image')}}</th>
                                        <th scope="col">{{__('Accept')}}</th>
                                        <th scope="col">{{__('Remove')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($requests as $request)
                                        <tr id="friend_row_{{$request['id']}}">
                                            <td><a a style="color: blue" href="{{route('web.user.profile',$request['user']['slug'])}}">{{$request['user']['firstname']}} {{$request['user']['lastname']}}</a></td>
                                            <td>  <img style="width: 70px;height: 70px;" src="{{img($request['user']['image'],'users')}}" alt=""></td>
                                            <td><a style="color: green" onclick="friendRequestAction('{{route('web.user.friend-request.action',['id'=>$request['id'],'type'=>'accept'])}}','{{$request['id']}}')" href="javascript:void(0);"> {{__('Accept')}}</a></td>
                                            <td><a style="color: red" onclick="friendRequestAction('{{route('web.user.friend-request.action',['id'=>$request['id'],'type'=>'cancel'])}}','{{$request['id']}}')" href="javascript:void(0);"> {{__('Delete')}}</a></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="pull-left">
                                    @if(request()->input('page') !=0)
                                        Showing  <span style="color: grey">{{$requests->count() * request()->input('page')}} of {{ $requests->total() }}entries</span>
                                    @else
                                        Showing  <span style="color: grey">{{$requests->count()}} of {{ $requests->total() }}entries</span>
                                    @endif
                                </div>
                                <div class="pull-right">
                                    {{ $requests->appends(request()->input())->links() }}
                                </div>
                            </div>


                        </div>

            </div>
            </div>
            </div>
        </section>

    </div>



@endsection

@section('footer')
    <script src="{{asset('assets/system')}}/SimpleAjaxUploader.js"></script>


    <script>
        $(document).ready(function () {
            $(".page-link").attr("target", "_self");
        });
        function unfriend(routeName,id){

            if(!confirm("Do you want to remove this Friend?")){
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
                        notify('success',response.msg);
                        $("#friend_item_"+id).remove();
                    }else{
                        notify('error','{{__('Can\'t delete this friend')}}');
                    }

                }
            )
        }


        function friendRequestAction(routeName,id){

            if(!confirm("Do you want to do this action?")){
                return false;
            }

            $.post(
                routeName,
                {
                    '_method':'POST',
                    '_token':$('meta[name="csrf-token"]').attr('content')
                },
                function(response){
                    if(response.status == true){
                        notify('success',response.msg);
                        $("#friend_row_"+id).remove();

                    }else{
                        notify('error','{{__('Can\'t delete this friend')}}');
                    }

                }
            )
        }

    </script>
@endsection
