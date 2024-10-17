@extends('web.layouts')

@section('header')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.css">
    <script> var global_stage_id = null;  </script>
    @if(auth()->check() == true && auth()->user()->id === $user->id)
    <style>
        .grid-item, .grid-sizer{
            width: 22%;
        }
    </style>
    @endif
@endsection

@section('content')
@if(!empty($user))
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

                    @if(auth()->check() && auth()->user()->id === $user->id)
                        @include('web.partial.user-sidebar')
                    @endif

                    <div  class="@if(auth()->check() == true && auth()->user()->id === $user->id) col-md-9 @else col-md-12  @endif col-xs-12 col-sm-12">


                        <div class="clearfix"></div>
                        <div class="tabs-in-details">
                            <div class="panel-heading">
                                <ul class="nav nav-tabs">
                                        <li @if(!isset($_GET['showItems']))class="active"@endif><a href="#UserAbout" onclick="clear_items();" data-toggle="tab" aria-expanded="true">{{__('ABOUT')}}</a>
                                    </li>
                                    <li @if(isset($_GET['showItems']))class="active"@endif><a href="#UserItems" id="items_show_link_tab" onclick="clear_items();runProfileGetItems();" data-toggle="tab" aria-expanded="false">{{__('ITEMS')}} ({{$user->items()->count()}})</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="panel with-nav-tabs panel-default">
                                <div class="panel-body">
                                    <div class="tab-content">

                                        <div   class="tab-pane fade in @if(isset($_GET['showItems'])) active @endif" id="UserItems">


                                            <style>
                                                .tags ul span.cat-links {
                                                    display: inline-block;
                                                    margin: 5px;
                                                }
                                            </style>
                                            <div class="tags" style="margin-bottom: 30px">
                                                <ul>
                                                    <li>
                                                        <span class="tags-title">{{__('Stages')}}</span>
                                                        @foreach($user->stages as $stage)
                                                            @if(!auth()->check())
                                                            @if($stage->show_to_public == 'yes')
                                                            <span class="cat-links"> <a href="javascript:void(0)" onclick="clear_items();runProfileGetItems('{{$stage->id}}');">{{$stage->name}}</a> </span>
                                                            @endif
                                                            @else
                                                            @if(auth()->id() != $user->id)
                                                            @if($stage->show_to_friends == 'yes' && is_friend($user->id))
                                                             <span class="cat-links"> <a href="javascript:void(0)" onclick="clear_items();runProfileGetItems('{{$stage->id}}');">{{$stage->name}}</a> </span>
                                                            @elseif($stage->show_to_followers == 'yes' && is_follow($user->id))
                                                             <span class="cat-links"> <a href="javascript:void(0)" onclick="clear_items();runProfileGetItems('{{$stage->id}}');">{{$stage->name}}</a> </span>
                                                             @elseif($stage->show_to_public == 'yes')
                                                              <span class="cat-links"> <a href="javascript:void(0)" onclick="clear_items();runProfileGetItems('{{$stage->id}}');">{{$stage->name}}</a> </span>

                                                              @endif
                                                            @else
                                                            <span class="cat-links"> <a href="javascript:void(0)" onclick="clear_items();runProfileGetItems('{{$stage->id}}');">{{$stage->name}}</a> </span>
                                                             @endif
                                                            @endif
                                                            @endforeach
                                                    </li>
                                                </ul>
                                            </div>

                                                {{--<ul class="" style="margin-bottom: 20px">--}}
                                                {{--<li class="dropdown" id="my-catg">--}}
                                                {{--<a href="#" class="dropdown-toggle open-catg" data-toggle="dropdown" role="button"--}}
                                                {{--aria-haspopup="true" aria-expanded="false">--}}
                                                {{--<img src="images/stage.png" alt=""> {{__('Stages')}} <i class="fa fa-chevron-down"></i>--}}
                                                {{--</a>--}}
                                                {{--<ul class="dropdown-menu drop-down-multilevel" id="urgent">--}}
                                                {{--@foreach($user->stages as $stage)--}}
                                                {{--<li><a href="javascript:void(0)" onclick="runProfileGetItems('{{$stage->id}}')">{{$stage->name}}</a></li>--}}
                                                {{--@endforeach--}}
                                                {{--</ul>--}}
                                                {{--</li>--}}

                                                {{--</ul>--}}


                                                <div class="clearfix"></div>

                                            <div class="rows grid" style="position: relative;" id="items">

                                            </div>

                                                <div class="col-md-12 col-xs-12 col-sm-12" id="loading">
                                                    <img src="{{img('loading.gif')}}" alt="{{__('loading')}}...">
                                                </div>
                                            <div class="col-md-12 col-xs-12 col-sm-12 text-danger" style="display: none;" id="no_items">
                                                {{__('No Items Remaining')}}
                                            </div>
                                        </div>

                                        <div class="tab-pane fade @if(!isset($_GET['showItems'])) active @endif in" id="UserAbout">

                                            <div class="media item-d-media">
                                                <div class="media-left">
                                                    <a href="{{route('web.user.profile',$user->slug)}}">
                                                        {{--<img src="{{img($user->image,'users')}}" alt="">--}}
                                                        <img class="media-object" src="{{img($user->image,'users')}}"
                                                             alt="{{$user->FullName}}">
                                                    </a>
                                                </div>
                                                <div class="media-body">
                                                    <h6 class="media-heading logo-name">{{$user->FullName}}</h6>
                                                    <p>{{$user->userJob->{'name_'.DataLanguage::get()} }}</p>
                                                    <p class="date">{{date('Y/m/d',strtotime($user->created_at))}}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="contact-via">
                                                <ul>
                                                    @if(!empty($user->mobile))
                                                        <li>
                                                            <a href="tel:{{$user->mobile}}"><span><i
                                                                            class="fa fa-mobile"></i></span>{{$user->mobile}}</a>
                                                        </li>
                                                    @endif
                                                        @if(!empty($user->mobile2))
                                                            <li>
                                                                <a href="tel:{{$user->mobile2}}"><span><i
                                                                                class="fa fa-mobile"></i></span>{{$user->mobile2}}</a>
                                                            </li>
                                                        @endif
                                                        @if(!empty($user->mobile3))
                                                            <li>
                                                                <a href="tel:{{$user->mobile3}}"><span><i
                                                                                class="fa fa-mobile"></i></span>{{$user->mobile3}}</a>
                                                            </li>
                                                        @endif
                                                        @if(!empty($user->phone))
                                                            <li>
                                                                <a href="tel:{{$user->phone}}"><span><i
                                                                                class="fa fa-phone"></i></span>{{$user->phone}}</a>
                                                            </li>
                                                        @endif

                                                    <li>
                                                        <a href="mailto:{{$user->email}}"><span><i
                                                                        class="fa fa-envelope"></i></span>{{$user->email}}</a>
                                                    </li>

                                                    <br>
                                                    @if(auth()->check() && auth()->user()->id != $user->id)

                                    <li style="display: @if(!empty($user_friend) && $user_friend->status == 'accept') inline-block @else none @endif" id="userAlreadyFriend_btn">
                                        <a  class="btn btn-primary btn-block  btn-xs" onclick="unFriend('{{$user->id}}')"  href="javascript:void(0)" title="{{__('Cancel friendship')}}"><i class="fa fa-check fa-lg fa-fw" aria-hidden="true"></i> {{__('Friends')}}  <i style="display: none;" class="fa fa-spinner fa-pulse fa-lg fa-fw btn_loader_friend"></i></a>
                                    </li>

                                    <li style="display: @if(!empty($user_friend) && $user_friend->status == 'pending') inline-block @else none @endif" id="userPendingFriend_btn">
                                        <a  class="btn btn-primary btn-block  btn-xs" onclick="unFriend('{{$user->id}}')"  href="javascript:void(0)" title="{{__('Cancel request')}}"><i class="fa fa-user-times fa-lg fa-fw" aria-hidden="true"></i> {{__('Friend Request sent')}} <i style="display: none;"  class="fa fa-spinner fa-pulse fa-lg fa-fw btn_loader_friend"></i></a>
                                    </li>

                                    <li style="display: @if(empty($user_friend)) inline-block @else none @endif" id="userAddFriend_btn">
                                        <a  class="btn btn-primary btn-block  btn-xs" onclick="addFriend('{{$user->id}}')"  href="javascript:void(0)" title="{{__('Add Friend')}}"><i class="fa fa-user-plus fa-lg fa-fw" aria-hidden="true"></i> {{__('Add Friend')}}  <i style="display: none;"  class="fa fa-spinner fa-pulse fa-lg fa-fw btn_loader_friend"></i></a>
                                    </li>

                                    <li style="display: @if(empty($user_follow)) inline-block @else none @endif" id="userFollow_btn">
                                        <a class="btn btn-info btn-block  btn-xs" onclick="follow('{{$user->id}}')"  href="javascript:void(0)" title="{{__('Follow')}}"><i class="fa fa-rss fa-lg fa-fw"></i> {{__('Follow')}}  <i style="display: none;" class="fa fa-spinner fa-pulse fa-lg fa-fw btn_loader_follow"></i></a>
                                    </li>

                                    <li style="display: @if(!empty($user_follow)) inline-block @else none @endif" id="userUnFollow_btn">
                                        <a class="btn btn-info btn-block  btn-xs" onclick="unFollow('{{$user->id}}')"  href="javascript:void(0)" title="{{__('Cancel Following')}}"><i class="fa fa-check fa-lg fa-fw"></i> {{__('Following')}}  <i style="display: none;" class="fa fa-spinner fa-pulse fa-lg fa-fw btn_loader_follow"></i></a>
                                    </li>


                                    @endif

                                                </ul>
                                            </div>
                                            <hr>
                                            @if(!empty($user->about))
                                                <div class="txt-about">
                                                    <h3>{{__('About')}}</h3>
                                                    <p>{{$user->about}}</p>
                                                </div>
                                                <hr>
                                            @endif

                                            <div class="tags">
                                                <ul>

                                                    @if(!empty($categories))
                                                        <li>
                                                            <span class="tags-title">{{__('Interested Categories')}}</span>
                                                            @foreach ($categories as $row)
                                                                <input type="hidden" value="{{$row->id}}">
                                                                <span class="cat-links">
                                                <a href="{{route('web.item.category',$row->{'slug_'.\DataLanguage::get() } ) }}">{{$row->{'name_'.\DataLanguage::get() } }}</a></span>
                                                            @endforeach
                                                        </li>
                                                        <hr>
                                                    @endif

                                                        <li>
                                                            <span class="tags-title">{{__('type')}}</span>
                                                            <span>{{$user->type}}</span>
                                                        </li>
                                                        <hr>

                                                        @if(!empty($user->company_name))
                                                        <li>
                                                            <span class="tags-title">{{__('Company')}}</span>
                                                            <span>{{__('name')}} <tag>{{$user->company_name}}</tag></span>
                                                            @if(!empty($user->company_business))
                                                                <span>{{__('business')}} <tag>{{$user->company_business}}</tag></span>
                                                            @endif
                                                        </li>
                                                         <hr>
                                                        @endif


                                                        <li>
                                                            <span class="tags-title">{{$user->firstname}} {{__('Rank')}} </span>
                                                            <span><tag> {{round($user->rank,1)}} </tag> @if(round($user->rank,1) > 0) <span id="rateYo"></span> @endif </span>
                                                        </li>
                                                        <hr>



                                                        <li>
                                                    <span class="tags-title">{{__('Gender')}}</span>
                                                    <span>{{$user->gender}}</span>
                                                    </li>
                                                    <hr>
                                                        @if(!empty($user->address))
                                                        <li>
                                                            <span class="tags-title">{{__('Address')}}</span><br>
                                                            <span>{{$user->address}}</span>
                                                        </li>
                                                            <hr>
                                                        @endif
                                                    <li>
                                                        <span class="tags-title">{{__('Social Links')}}</span>
                                                        @if(!empty($user->facebook))
                                                            <span class="tag-social"><a target="_blank" href="{{$user->facebook}}"><i
                                                                            class="fa fa-facebook"></i></a></span>
                                                        @endif
                                                        @if(!empty($user->twitter))
                                                            <span class="tag-social"><a target="_blank" href="{{$user->twitter}}"><i
                                                                            class="fa fa-twitter"></i></a></span>
                                                        @endif
                                                        @if(!empty($user->google))
                                                            <span class="tag-social"><a target="_blank" href="{{$user->google}}"><i
                                                                            class="fa fa-google"></i></a></span>
                                                        @endif
                                                        @if(!empty($user->instagram))
                                                            <span class="tag-social"><a target="_blank" href="{{$user->instagram}}"><i
                                                                            class="fa fa-instagram"></i></a></span>
                                                        @endif
                                                        @if(!empty($user->pinterest))
                                                            <span class="tag-social"><a target="_blank" href="{{$user->pinterest}}"><i
                                                                            class="fa fa-pinterest"></i></a></span>
                                                        @endif
                                                        @if(!empty($user->linkedin))
                                                            <span class="tag-social"><a target="_blank" href="{{$user->linkedin}}"><i
                                                                            class="fa fa-linkedin"></i></a></span>
                                                        @endif

                                                    </li>
                                                        <hr>

                                                        <li>
                                                            <span class="tags-title">{{__('Job')}}</span>
                                                            <span><tag>{{$user->userJob->{'name_'.DataLanguage::get()} }}</tag></span>
                                                        </li>
                                                        <hr>
                                                        @if(!empty($job_attr))
                                                            @foreach($job_attr as $attr_item)
                                                                <li>
                                                                    <span class="tags-title">{{$attr_item['name']}}</span>
                                                                    @if(in_array($attr_item['type'],['date','datetime','select','multi_select','number']))
                                                                        <span><tag>{{$attr_item['selected_value_name'] }}</tag></span>
                                                                    @else
                                                                       <br> <span>{{$attr_item['selected_value_name'] }}</span>
                                                                    @endif
                                                                </li><hr>
                                                            @endforeach


                                                        @endif



                                                </ul>
                                                {{--<div class="inp_group">--}}
                                                    {{--<label for="" class="stay-top">{{$user->firstname}} {{__('Rank')}}  </label>--}}
                                                    {{--<span>( {{round($user->rank,1)}} )</span>--}}
                                                    {{--<fieldset>--}}
                                                        {{--<div id="rateYo"></div>--}}
                                                    {{--</fieldset>--}}
                                                {{--</div>--}}
                                            </div>


                                            <div class="clearfix"></div>
                                            <div class="tabs-in-details">
                                                <div class="panel-heading">
                                                    <ul class="nav nav-tabs">
                                                        <li ><a href="#profile_messages" data-toggle="tab" aria-expanded="true">Messages</a>
                                                        </li>
                                                        <li class="active"><a href="#profile_sendMail" data-toggle="tab" aria-expanded="false">Mail</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="panel with-nav-tabs panel-default">
                                                    <div class="panel-body">
                                                        <div class="tab-content">

                                                            <div class="tab-pane fade " id="profile_messages">

                                                            </div>

                                                            <div class="tab-pane fade active in" id="profile_sendMail">
                                                                <form role="form" class="mail-form" id="sendMail" action="" method="post">
                                                                    <div class="form-group">
                                                                        <input type="hidden" name="to_user_id" value="{{$user->id}}">
                                                                        @if(auth()->check())
                                                                            <input style="width: 100%" placeholder="Subject" class="form-control" type="text" name="subject" required>
                                                                            <textarea name="message" class="form-control" placeholder="Write you mail here ..." required></textarea>
                                                                        @else
                                                                            <input style="width: 100%" placeholder="Full name" class="form-control" type="text" name="name" required>
                                                                            <input style="width: 100%" placeholder="Phone number" class="form-control" type="tel" name="mobile" required>
                                                                            <input style="width: 100%" placeholder="Email" class="form-control" type="email" name="email" required>
                                                                            <input style="width: 100%" placeholder="Subject" class="form-control" type="text" name="subject" required>
                                                                            <textarea name="message" class="form-control" placeholder="Write you mail here ..." required></textarea>
                                                                        @endif

                                                                    </div>
                                                                    <button class="btn btn-theme" type="submit">{{__('Send')}}</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>


                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>


                    </div>



                    <!-- Middle Content Box End -->
                </div>


                <!-- Row End -->
            </div>
            <!-- Main Container End -->
        </section>


        <!-- Main Content Area End -->



    </div>
@endsection


@section('footer')


    <script>

        $(document).ready(function () {
            clear_items();
            runProfileGetItems();
            $("#footer").hide();
            $("#down_footer").hide();
            $("#rateYo").rateYo({
                rating:'{{round($user->rank,1)}}',
                //rating: 2.7,
                readOnly: true,
                spacing: "5px",
                ratedFill: "#3aa4c1"
            });



        });

        function addFriend(user_id){
            $(".btn_loader_friend").css('display','inline-block');
            $.post('{{route('web.user.add-friend')}}', {'user_id':user_id,'type':'friend'}, function (out) {

                if(out.status == true){
                    $(".btn_loader_friend").css('display','none');
                    notify('success', out.msg);
                    $("#userAddFriend_btn").css('display','none');
                    $("#userPendingFriend_btn").css('display','inline-block');
                }else{
                    $(".btn_loader_friend").css('display','none');
                    notify('error', out.msg);
                }

            }, 'json');

        }

        function unFriend(user_id){
            $(".btn_loader_friend").css('display','inline-block');
            $.post('{{route('web.user.remove-friend')}}', {'user_id':user_id,'type':'friend'}, function (out) {

                if(out.status == true){
                    $(".btn_loader_friend").css('display','none');
                    notify('success', out.msg);
                    $("#userAlreadyFriend_btn").css('display','none');
                    $("#userPendingFriend_btn").css('display','none');
                    $("#userAddFriend_btn").css('display','inline-block');
                }else{
                    $(".btn_loader_friend").css('display','none');
                    notify('error', out.msg);
                }

            }, 'json');
        }

        function follow(user_id){
            $(".btn_loader_follow").css('display','inline-block');
            $.post('{{route('web.user.follow')}}', {'user_id':user_id,'type':'follow'}, function (out) {

                if(out.status == true){
                    $(".btn_loader_follow").css('display','none');
                    notify('success', out.msg);
                    $("#userFollow_btn").css('display','none');
                    $("#userUnFollow_btn").css('display','inline-block');
                }else{
                    $(".btn_loader_follow").css('display','none');
                    notify('error', out.msg);
                }

            }, 'json');
        }

        function unFollow(user_id){
            $(".btn_loader_follow").css('display','inline-block');
            $.post('{{route('web.user.unfollow')}}', {'user_id':user_id,'type':'follow'}, function (out) {

                if(out.status == true){
                    $(".btn_loader_follow").css('display','none');
                    notify('success', out.msg);
                    $("#userUnFollow_btn").css('display','none');
                    $("#userFollow_btn").css('display','inline-block');
                }else{
                    $(".btn_loader_follow").css('display','none');
                    notify('error', out.msg);
                }

            }, 'json');
        }


        function clear_items(){
          //  global_stage_id = null;
            $("#items").html('');
        }

        function runProfileGetItems(stage_id) {
            $('#no_items').hide();
            $("#loading").show();
            if (stage_id != null) {
                global_stage_id = stage_id;
            }

            $.get("{{url()->full()}}", {'getData': true, 'offset': $("#items .item").length,'stage_id':stage_id}, function (items) {
                if(items.status == true){
                    //  console.log(items);
                    for(var i =0;i<= items.data.length;i++){
                        var item = items.data[i];
                        $("#items").append(item).masonry('reloadItems');
                    }

                }else{

                    $('#no_items').show();
                    {{--$("#loading").html("{{__("No Items remaining")}}");--}}
                }
                $("#loading").hide();
                $("#items").masonry();
            }, 'json');
        }





        // extension:
        setTimeout(function () {
            $("#items").masonry();
            // console.log('sort'+ Date());
        },3000);



        function getDocHeight() {
            var D = document;
            return Math.max(
                D.body.scrollHeight, D.documentElement.scrollHeight,
                D.body.offsetHeight, D.documentElement.offsetHeight,
                D.body.clientHeight, D.documentElement.clientHeight
            );
        }


        $(window).scroll(_.debounce(function(){
            if($(window).scrollTop() + $(window).height()  >  getDocHeight()- 100) {
                runProfileGetItems(global_stage_id);
                $("#items").masonry();
            }
        },500));


       var publicCountItems = '{{$user->items()->count()}}';

        function deleteItem(routeName, id) {

            if (!confirm("Do you want to delete this ?")) {
                return false;
            }

            $.post(
                routeName,
                {
                    '_method': 'DELETE',
                    '_token': $('meta[name="csrf-token"]').attr('content')
                },
                function (response) {

                    if (response.status == true) {
                        publicCountItems = +publicCountItems-1;
                        $('#item_row_'+id).remove();
                        $('#items_show_link_tab').html('{{__('ITEMS')}}'+'('+publicCountItems+')');
                        $("#items").masonry();
                        notify('success', 'Item has been deleted successfully');
                    } else {
                        notify('error', 'can not delete this');
                    }

                }
            )
        }


        $('#sendMail').submit(function (e) {
            e.preventDefault();
            $.post('{{route('web.item.send-mail')}}', $('#sendMail').find(":input").serialize(), function (out) {
                $('.validation_error_msg').remove();

                if (out.status == false) {
                    $.each(out.data, function (index, value) {
                        notify('error', value);
                    });

                } else {
                    notify('success', 'E-mail has been sent Successfully');
                    $('#sendMail')[0].reset();
                    //  setTimeout(location.reload,3000);
                }
            }, 'json')
        });


    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js"></script>

@endsection