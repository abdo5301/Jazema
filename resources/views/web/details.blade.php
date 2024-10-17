@extends('web.layouts')

@section('header')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.css">

    <style>
        .tags ul li{
            margin: 8px;
        }
    </style>
@endsection

@section('content')


    <!-- =-=-=-=-=-=-= rank item Modal =-=-=-=-=-=-= -->
    <div class="modal fade modal-register" tabindex="-1" role="dialog" aria-hidden="true" id="rank-preview">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">

                    {{--<h2 class="login-head">{{__('Your Evaluation')}}</h2>--}}
                    <button id="close_rank_modal" type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span
                                class="sr-only">Close</span></button>
                    <!-- content goes here -->
                    <form id="rank_item_form" method="post">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <input type="hidden" name="item_id" value="{{$item->id}}">
                        <div id="item_rank" style="margin-left: 169px;">
                        </div><div class="counter badge" style="margin-left: 174px;"></div>
                        {{--<button class="btn btn-theme btn-lg btn-block"  type="submit">{{__('Save')}}</button>--}}
                    </form>

                </div>
            </div>
        </div>
    </div>
    <!-- =-=-=-=-=-=-= rank item Modal =-=-=-=-=-=-= -->

    <!-- =-=-=-=-=-=-= deal Modal =-=-=-=-=-=-= -->
    <div class="modal fade modal-register" tabindex="-1" role="dialog" aria-hidden="true" id="deal-preview">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">

                    <h2 class="login-head">{{__('Deal Options')}}</h2>
                    <button id="close_deal_modal" type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span
                                class="sr-only">Close</span></button>
                    <!-- content goes here -->
                    <form id="deal_form" method="post">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <input type="hidden" name="item_id" value="{{$item->id}}">
                        <div id="drow_options_div">

                        </div>
                        <div class="form-group">
                            <label class="stay-top">{{__('Notes')}}</label>
                            <textarea name="notes" class="form-control">
                            </textarea>
                        </div>
                        <button class="btn btn-theme btn-lg btn-block"  type="submit">{{__('Deal')}}</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <!-- =-=-=-=-=-=-= deal Modal =-=-=-=-=-=-= -->
    <!-- =-=-=-=-=-=-= Home Banner 2 End =-=-=-=-=-=-= -->
    <!-- =-=-=-=-=-=-= Main Content Area =-=-=-=-=-=-= -->
    <div class="main-content-area reset clearfix">

        <!-- =-=-=-=-=-=-= Featured Ads =-=-=-=-=-=-= -->
        <section class="item-details">
            <!-- Main Container -->
            <div class="container">
                <!-- Row -->
                <div class="row">
                    <div class="item col-md-4">
                        <div class="clearfix">

                            <h5 class="item-title">
                              {{$item->{'name_'.\DataLanguage::get()} }}
                            </h5>
                            <!-- Ad Box -->
                            <div class="category-grid-box">

                                <!-- Ad Img -->
                                    <div class="category-grid-img">
                                        <div class="itm-overlay-top"></div>
                                        <div class="itm-overlay-bott"></div>
                                        @if(empty($item->upload) || !isset($item->upload[0]))
                                       <img class="img-responsive" alt="" src="images/asas.png">
                                            <span class="itm-views"><i class="fa fa-eye"></i> {{$item->views}}</span>
                                            <span class="itm-from"><img width="24px" height="24px" src="{{img($item->item_type->icon)}}" alt=""></span>
                                            <span class="itm-price">{{$item->price}} {{setting('currency')}}</span>
                                        @else
                                         @if(count($item->upload)> 1)
                                        <div class="owl-carousel owl-theme single-details">
                                           @foreach($item->upload as $img)
                                            <!-- Slide -->
                                                <div  class="item">
                                                    <img  src="{{img($img->path)}}" alt="{{$img->title}}">
                                                    <span class="itm-views"><i class="fa fa-eye"></i> {{$item->views}}</span>
                                                    <span class="itm-from"><img width="24px" height="24px"
                                                                                src="{{img($item->item_type->icon)}}" alt=""></span>
                                                    <span class="itm-price">{{$item->price}} {{setting('currency')}}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                          @else
                                             @if(!empty($item->upload[0]->path))
                                             <img class="img-responsive" alt=""  src="{{img($item->upload[0]->path)}}" alt="{{$item->upload[0]->title}}">
                                                    <span class="itm-views"><i class="fa fa-eye"></i> {{$item->views}}</span>
                                                    <span class="itm-from"><img width="24px" height="24px"
                                                                                src="{{img($item->item_type->icon)}}" alt=""></span>
                                                    <span class="itm-price">{{$item->price}} {{setting('currency')}}</span>
                                                @else
                                                    <img class="img-responsive" alt="" src="images/asas.png">
                                                    <span class="itm-views"><i class="fa fa-eye"></i> {{$item->views}}</span>
                                                    <span class="itm-from"><img width="24px" height="24px"
                                                                                src="{{img($item->item_type->icon)}}" alt=""></span>
                                                    <span class="itm-price">{{$item->price}} {{setting('currency')}}</span>
                                                @endif
                                            @endif
                                        @endif

                                        {{--<a href="" class="view-details">View Details</a>--}}
                                    </div>
                                    <!-- Ad Img End -->

                                <div class="short-description">
                                    <!-- Ad Category -->
                                    <div class="category-title">
                                <span>
                                 <a href="{{route('web.item.category',$item->item_category->id)}}" class="cat-first">{{$item->item_category->{'name_'.\App\Libs\DataLanguage::get()} }}</a>
                                    {{--@if($item->item_category->parent_id)--}}
                                        {{--<a href="#">{{$item->item_category->parent->{'name_'.\App\Libs\DataLanguage::get()} }}</a>--}}
                                    {{--@endif--}}
                                </span>
                                    </div>
                                    <!-- no of social interactions -->
                                    <ul class="list-unstyled social-rate">
                                        <li>
                                            <a href="javascript:;" onclick="like('{{$item->id}}')">
                                                <i id="like_icon_{{$item->id}}"
                                                   style=" @if($item->AuthLiked == true) color:#3aa4c1;  @else color:#cccccc; @endif"
                                                   class="fa fa-thumbs-up"></i><br>
                                                <span class="likes">{{short_num($item->like)}}</span>
                                            </a>
                                        </li>
                                        <li>
                                            <i style=" @if($item->AuthCommented) color:#3aa4c1;  @else color:#cccccc; @endif"
                                               class="fa fa-comments"></i><br>
                                            <span class="comments">{{short_num($item->comments)}}</span>
                                        </li>
                                        <li>
                                            <a href="#share-now" data-toggle="modal">
                                                <i id="share_icon_{{$item->id}}" class="fa fa-share-alt"></i><br>
                                                <span class="share">{{ short_num($item->share)}}</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#deal-now" data-toggle="modal">
                                                <img @if($item->AuthDealed)src="images/deel.png"
                                                     @else src="images/not-deal.png" @endif alt=""><br>
                                                <span class="deeels">{{short_num($item->deals)}}</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <!-- short description -->
                                <div class="itm-desc line-clamp">
                                    <p class="">{{$item->{'description_'.\DataLanguage::get()} }}
                                    <br>
                                    </p>
                                    @if(!empty($item->price))
                                    <b>{{__('Price')}}</b>   <p>   {{$item->price}}</p>
                                    @endif
                                        @if(!empty($item->quantity))
                                        <b>{{__('Quantity')}}</b>   <p>   {{$item->quantity}}</p>
                                    @endif

                                @if(!empty($item->selected_attributes_handled))
                                        <div style="padding-bottom: 10px;">
                                            @foreach($item->selected_attributes_handled as $attr)
                                             @if(empty($attr['selected_value_name'])) @continue  @endif
                                                <b>{{$attr['name']}}</b>   <p>   {{$attr['selected_value_name']}}</p>
                                            @endforeach
                                        </div>
                                    @endif

                                        <div style="display: flex;padding-bottom: 10px;">
                                            <span> <b>{{__('rank')}} </b><span class="badge" style="background-color: #60bad3;"> {{round($item->rank,1)}} </span> </span>
                                            <span> <b> {{' '.__('By')}} </b> <span class="badge" style="background-color: #60bad3;"> {{count_ranker($item->id,'App\Models\Item')}} </span>  </span>
                                            <span id="rateItem"> </span>
                                            @if(auth()->check() && !rank_check($item->id,'App\Models\Item'))
                                            <span id="open_item_rank_modal">
                                                <a class="btn btn-warning btn-sm"  href="#rank-preview"  data-toggle="modal">{{__('Evaluation')}}</a>
                                            </span>
                                            @endif
                                        </div>




                                </div>
                                <!-- Addition Info -->
                                {{--<div class="ad-btns">--}}
                                {{--<ul>--}}
                                {{--<li>--}}
                                {{--<a href="#"><img src="images/pub.png" alt="">Publish</a>--}}
                                {{--</li>--}}
                                {{--<li>--}}
                                {{--<a href="#"><img src="images/sav.png" alt=""> save</a>--}}
                                {{--</li>--}}
                                {{--</ul>--}}
                                {{--</div>--}}

                            <!-- deal wish btns -->
                                @if(auth()->check())
                                @if(auth()->user()->id != $item->user_id && !deal_check($item->id,$item->user_id))
                                <div style="padding-bottom: 10px;">
                                    <a id="open_deal_modal" href="#deal-preview" data-toggle="modal" class="btn btn-info btn-sm btn-block  text-center" aria-describedby="basic-addon1">
                                          {{__('Deal')}}  </a>
                                </div>
                                @if(!wish_check($item->id))
                                <div style="padding-bottom: 10px;">
                                    <a id="add_wish_btn" onclick="addToWishlist()" href="javascript:void(0)" data-toggle="modal" class="btn btn-warning btn-sm btn-block  text-center" aria-describedby="basic-addon1">
                                        {{__('Add to My Wishlist')}}  </a>
                                </div>
                                @endif
                                @endif
                                <!-- comment input -->
                                <div class="input-group post-comment">
                                  <span class="input-group-addon" id="basic-addon1">
                                    <a href="javascript:;" onclick="commentByBtn('{{$item->id}}')" class="submit-comment"><i
                                                class="fa fa-comments"></i></a>
                                  </span>
                                    <input type="text" class="form-control" onkeydown="commentByEnter('{{$item->id}}')" id="comment_input_{{$item->id}}"
                                           placeholder="{{__('Comment')}}" aria-describedby="basic-addon1">
                                </div>
                                @endif

                            </div>
                            <!-- Ad Box End -->
                        </div>
                        @if(!empty($item->lat) && !empty($item->lng) && $item->lat != 0 && $item->lng != 0)
                        <div class="tabs-in-branches">
                            <h3>{{__('Location ')}}</h3>

                            <div class="panel with-nav-tabs panel-default">
                                <div class="panel-body">
                                    <div class="tab-content">
                                        <div class="tab-pane fade active in" id="tab11">
                                            {{--<div id="map" style="width: 100%; height: 300px;"></div>--}}

                                          {{--<a href="javascript:void(0)" onclick="viewMap( '{{$item->lat}}','{{$item->lng}}','{{__('Location')}}')">{{$item->lat}}</a>--}}
                                            {{--<div class="row">--}}
                                                {{--<div class="col-md-8" id="map"></div>--}}
                                                {{--<div class="list-group-item col-md-12" id="instructions"></div>--}}
                                            {{--</div>--}}

                                            <div id="map_details"></div>


                                            {{--<iframe src="https://www.google.com/maps/embed?pb=!1m10!1m8!1m3!1d13809.10198898178!2d31.29124615!3d30.0862953!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2seg!4v1524869525493"--}}
                                                    {{--frameborder="0" style="border:0" allowfullscreen></iframe>--}}
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                            @endif
                    </div>
                    <div class="col-md-8">
                        <div class="media item-d-media">
                            <div class="media-left">
                                <a href="{{route('web.user.profile',$item->user->slug)}}">
                                    {{--<img src="{{img($user->image,'users')}}" alt="">--}}
                                    <img class="media-object" src="{{img($item->user->image,'users')}}"
                                         alt="{{$item->user->FullName}}">
                                </a>
                            </div>
                            {{--<div class="media-body">--}}
                                {{--<h6 class="media-heading logo-name">{{$item->user->FullName}}</h6>--}}
                                {{--<p>this is slogan</p>--}}
                                {{--<p class="date">{{date('Y/m/d',strtotime($item->created_at))}}--}}
                                    {{--<span class="time" style="margin-left: 10px;">23:00</span>--}}
                                {{--</p>--}}
                            {{--</div>--}}
                        </div>
                        <div class="contact-via">
                            <ul>
                                <li>
                                    <a href="{{route('web.user.profile',$item->user->slug)}}"><span><i
                                                    class="fa fa-user"></i></span>{{$item->user->FullName}}  </a>
                                </li>
                                @if(!empty($item->user->mobile))
                                    <li>
                                        <a href="tel:{{$item->user->mobile}}"><span><i
                                                        class="fa fa-phone"></i></span>{{$item->user->mobile}}</a>
                                    </li>
                                @endif
                                <li>
                                    <a href="mailto:{{$item->user->email}}"><span><i
                                                    class="fa fa-envelope"></i></span>{{__('contact via mail')}}</a>
                                </li>

                                <li>
                                    <a href="{{route('web.user.profile',$item->user->slug)}}"><span><i
                                                    class="fa fa-car"></i></span>{{__('Number of Items')}}
                                        ({{$item->user->items()->count()}})</a>
                                </li>
                                
                            </ul>
                        </div>
                        <div class="clearfix"></div>
                        <div class="tabs-in-details">
                            <div class="panel-heading">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#comments_tab" data-toggle="tab" aria-expanded="false">{{__('Comments').' ('.count($comments).')'}}</a>
                                    </li>
                                    <li class=""><a href="#messages_tab" data-toggle="tab" aria-expanded="true">{{__('Messages')}}</a>
                                    </li>
                                    <li class=""><a href="#mail_tab" data-toggle="tab" aria-expanded="false">{{__('Mail')}}</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="panel with-nav-tabs panel-default">
                                <div class="panel-body">
                                    <div class="tab-content">
                                        <div class="tab-pane fade" id="messages_tab">
                                            <div class="row">
                                            {{--Messages Tab--}}
                                            {{--<li class="my-message clearfix">--}}
                                            {{--<figure class="profile-picture">--}}
                                            {{--<img src="images/users/1.jpg" class="img-circle"--}}
                                            {{--alt="Profile Pic">--}}
                                            {{--</figure>--}}
                                            {{--<div class="message">--}}
                                            {{--With that budget we can make something pretty powerful. As--}}
                                            {{--soon as I get to the office we can start the team briefing!--}}
                                            {{--<div class="time"><i class="fa fa-clock-o"></i> Today 9:12--}}
                                            {{--AM--}}
                                            {{--</div>--}}
                                            {{--</div>--}}
                                            {{--</li>--}}

                                            {{--<li class="friend-message clearfix">--}}
                                            {{--<figure class="profile-picture">--}}
                                            {{--<img src="images/users/2.jpg" class="img-circle"--}}
                                            {{--alt="Profile Pic">--}}
                                            {{--</figure>--}}
                                            {{--<div class="message">--}}
                                            {{--Absolutely! Can't wait to get started!--}}
                                            {{--<div class="time"><i class="fa fa-clock-o"></i> Today 9:14--}}
                                            {{--AM--}}
                                            {{--</div>--}}
                                            {{--</div>--}}
                                            {{--</li>--}}

                                            {{--<li class="my-message clearfix">--}}
                                            {{--<figure class="profile-picture">--}}
                                            {{--<img src="images/users/1.jpg" class="img-circle"--}}
                                            {{--alt="Profile Pic">--}}
                                            {{--</figure>--}}
                                            {{--<div class="message">--}}
                                            {{--I am just grabbing the coffee and doughnuts. I will be at--}}
                                            {{--the office ASAP.--}}
                                            {{--<div class="time"><i class="fa fa-clock-o"></i> Today 9:17--}}
                                            {{--AM--}}
                                            {{--</div>--}}
                                            {{--</div>--}}
                                            {{--</li>--}}
                                            <div  class="col-md-3" style="float: left;overflow-y: scroll;max-height: 300px;">
                                                <h5>{{__('Online Users')}}</h5><hr>
                                                <ul id="online_users" class="list-group">

                                                </ul>
                                            </div>

                                            <div class="inline-mess col-md-9" style="float: right;">
                                            <ul class="messages" style="max-height: 300px;">

                                                @foreach($messages as $m)

                                                    @if($m->from_user_id == auth()->id() )

                                                    <li class="friend-message clearfix">
                                                        <figure class="profile-picture">
                                                            <img src="images/users/2.jpg" class="img-circle"
                                                                 alt="Profile Pic">
                                                        </figure>
                                                        <div class="message">
                                                         {{$m->message}}
                                                            <div class="time"><i class="fa fa-clock-o"></i>{{$m->created_at}}</div>
                                                        </div>
                                                    </li>
                                                    @else
                                                    <li class="my-message clearfix">
                                                        <figure class="profile-picture">
                                                            <img src="images/users/1.jpg" class="img-circle"
                                                                 alt="Profile Pic">
                                                        </figure>
                                                        <div class="message">
                                                            {{$m->message}}
                                                            <div class="time"><i class="fa fa-clock-o"></i>    {{$m->created_at}}</div>
                                                        </div>
                                                    </li>
                                                    @endif

                                                @endforeach


                                            </ul>
                                            <form role="form" class="">
                                            <div class="form-group">
                                            <input id="chat_text"  data-url="{{route('add')}}" style="width: 100%" placeholder="Type a message here..."
                                            class="form-control" type="text">
                                            </div>
                                            {{--<button class="btn btn-theme" type="submit">Send</button>--}}
                                            </form>
                                            </div>
                                            </div>
                                            {{--Map side--}}
                                            {{--<div class="inline-map">--}}
                                            {{--<iframe src="https://www.google.com/maps/embed?pb=!1m10!1m8!1m3!1d13809.10198898178!2d31.29124615!3d30.0862953!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2seg!4v1524869525493"--}}
                                            {{--frameborder="0" style="border:0" allowfullscreen></iframe>--}}
                                            {{--</div>--}}
                                        </div>
                                        <div class="tab-pane fade " id="mail_tab">
                                            <form role="form" class="mail-form" id="sendMail" action="" method="post">
                                                <div class="form-group">
                                                    <input type="hidden" name="item_id" value="{{$item->id}}">
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

                                        <div class="tab-pane fade active in" id="comments_tab">
                                            <div class="inline-mess" style="width: 100%;">
                                            <ul class="messages" style="max-height: 250px;overflow-y: auto;">
                                            @if(!empty($comments))
                                                @foreach($comments as $comm)
                                                    @if(empty($comm->user)) @continue  @endif
                                            <li class="friend-message clearfix">
                                            <figure class="profile-picture">
                                            <img style="border-radius: 70%;width: 40px;height: 40px;" @if(!empty($comm->user->image)) src="{{img($comm->user->image)}}" @else src="images/users/2.jpg" @endif  class="img-circle" alt="Profile Pic">
                                            </figure>
                                            <div class="message">
                                            {{$comm->comment}} <a href="{{route('web.user.profile',$comm->user->slug)}}" target="_blank">  {{'@'.$comm->user->firstname}} </a>
                                             <br>
                                            <div class="time"><i class="fa fa-clock-o"></i> {{$comm->created_at}} </div>
                                            </div>
                                            </li>
                                                @endforeach
                                            @endif

                                            </ul>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        @if(!empty($item->user->about))
                        <div class="txt-about">
                            <h3>{{__('About')}}</h3>
                           <p>{{$item->user->about}}</p>
                        </div>
                        @endif

                        <div class="tags">
                            <ul>
                                @php // dump($item->user->categories());  @endphp
                                @if(!empty($item->user->categories()))
                                    <li>
                                        <span class="tags-title">{{__('Intersted Categories')}}</span>
                                        @foreach ($categories as $row)
                                            {{--<input type="hidden" value="{{$row->id}}">--}}
                                            <span class="cat-links">
                                                <a href="{{route('web.item.category',$row->{'slug_'.\DataLanguage::get() } ) }}">{{$row->{'name_'.\DataLanguage::get() } }}</a>
                                            </span>
                                        @endforeach
                                    </li>
                                @endif
                                {{--<li>--}}
                                {{--<span class="tags-title">Payment</span>--}}
                                {{--<span class="pay">--}}
                                {{--<img src="images/pay.png" alt="">--}}
                                {{--</span>--}}
                                {{--<span class="pay" style="vertical-align: -webkit-baseline-middle;">--}}
                                {{--<img src="images/amz.png" alt="">--}}
                                {{--</span>--}}
                                {{--</li>--}}
                                {{--<li>--}}
                                {{--<span class="tags-title">Opening Hours</span>--}}
                                {{--<span>from <tag>8:00 AM</tag></span>--}}
                                {{--<span>to <tag>5:00 PM</tag></span>--}}
                                {{--</li>--}}
                                {{--<li>--}}
                                {{--<span class="tags-title">Other Contacts</span>--}}
                                {{--<span>+9752485221112</span>--}}
                                {{--</li>--}}
                                <li>
                                    <span class="tags-title">{{__('Other links')}}</span>
                                    @if(!empty($item->user->facebook))
                                        <span class="tag-social"><a href="{{$item->user->facebook}}"><i
                                                        class="fa fa-facebook"></i></a></span>
                                    @endif
                                    @if(!empty($item->user->twitter))
                                        <span class="tag-social"><a href="{{$item->user->twitter}}"><i
                                                        class="fa fa-twitter"></i></a></span>
                                    @endif
                                    @if(!empty($item->user->google))
                                        <span class="tag-social"><a href="{{$item->user->google}}"><i
                                                        class="fa fa-google"></i></a></span>
                                    @endif
                                    @if(!empty($item->user->instagram))
                                        <span class="tag-social"><a href="{{$item->user->instagram}}"><i
                                                        class="fa fa-instagram"></i></a></span>
                                    @endif
                                    @if(!empty($item->user->pinterest))
                                        <span class="tag-social"><a href="{{$item->user->pinterest}}"><i
                                                        class="fa fa-pinterest"></i></a></span>
                                    @endif
                                    @if(!empty($item->user->linkedin))
                                        <span class="tag-social"><a href="{{$item->user->linkedin}}"><i
                                                        class="fa fa-linkedin"></i></a></span>
                                    @endif

                                </li>
                                <li>
                                    <span class="tags-title">{{$item->user->firstname}} {{__('Rank')}} <span class="badge" style="background-color: #60bad3;"> {{round($item->user->rank,1)}} </span>
                                    <span> <b> {{' '.__('By')}} </b> <span class="badge" style="background-color: #60bad3;"> {{count_ranker($item->user->id,'App\Models\User')}} </span>  </span>
                                    </span>
                                    <span  id="rateOwner" style="display: inline-block;vertical-align: bottom;"></span>
                                </li>
                            </ul>

                        </div>

                    </div>
                </div>


                <div class="rows grid">
                    @foreach($related_items as $related_item)
                        @if($related_item->id == $item->id)
                        @continue
                        @endif
                    <div class="item col-md grid-sizer grid-item">
                        <div class="clearfix">
                            <div class="media">
                                <div class="media-left">
                                    <a href="#">
                                        <img class="media-object" src="{{img($related_item->user->image)}}" alt="">
                                    </a>
                                </div>
                                <div class="media-body">
                                    <h6 class="media-heading logo-name">{{$related_item->user->full_name}}</h6>
                                    <p class="date">{{$related_item->created_at->format('Y/m/d')}}<span class="time" style="margin-left: 10px;">{{$related_item->created_at->format('h:m')}}</span>
                                    </p>
                                </div>
                            </div>
                            <h5 class="item-title">
                                {{--<a href="#">a recent company for trading</a>--}}
                            </h5>
                            <!-- Ad Box -->
                            <div class="category-grid-box">
                                <!-- Ad Img -->
                                <div class="category-grid-img">
                                    <div class="itm-overlay-top"></div>
                                    <div class="itm-overlay-bott"></div>
                                    @if(!empty($related_item->upload) && !empty($related_item->upload[0]))
                                        @php //dd($related_item->upload) @endphp
                                        <img class="img-responsive" alt="" src="{{img($related_item->upload[0]->path)}}">
                                        @else
                                    <img class="img-responsive" alt="" src="images/asas.png">
                                    @endif
                                    <span class="itm-views"><i class="fa fa-eye"></i> {{$related_item->views}}</span>
                                    <span class="itm-from"><img width="24px" height="24px" src="{{img($related_item->item_type->icon)}}" alt=""></span>
                                    <span class="itm-price">{{$related_item->price}} {{setting('currency')}}</span>
                                    <a href="{{route('web.item.details',$related_item->{'slug_'.\DataLanguage::get() })}}" class="view-details">{{__('View Details')}}</a>
                                </div>
                                <!-- Ad Img End -->
                                <div class="short-description">
                                    <!-- Ad Category -->
                                    <div class="category-title">
                                <span>
                                  <a href="{{route('web.item.category',$related_item->item_category->id)}}" class="cat-first">{{$related_item->item_category->{'name_'.\DataLanguage::get() } }}</a>

                                </span>
                                    </div>
                                    <!-- no of social interactions -->
                                    <ul class="list-unstyled social-rate">
                                        <li>
                                            <i class="fa fa-thumbs-up"></i>
                                            <span class="likes">{{short_num($related_item->like)}}</span>
                                        </li>
                                        <li>
                                            <i class="fa fa-comments"></i>
                                            <span class="comments">{{short_num($related_item->comments)}}</span>
                                        </li>
                                        <li>
                                            <i class="fa fa-share-alt"></i>
                                            <span class="share">{{short_num($related_item->share)}}</span>
                                        </li>
                                        <li>
                                            <img src="images/deel.png" alt="">
                                            <span class="deeels">{{short_num($related_item->deals)}}</span>
                                        </li>
                                    </ul>
                                </div>
                                <!-- short description -->
                                <div class="itm-desc line-clamp">
                                    <p class="">{{$related_item->{'name_'.\DataLanguage::get()} }}
                                    </p>
                                </div>
                                <!-- Addition Info -->
                                {{--@if(auth()->check() && ($related_item->user_id == auth()->id()))--}}
                                {{--<div class="ad-btns">--}}
                                    {{--<ul>--}}
                                        {{--<li>--}}
                                            {{--<a href="javascript:void(0);" onclick="deleteItem('{{route('web.user.delete-items',$related_item->id)}}')"><img src="images/pub.png" alt="">{{__('Delete')}}</a>--}}
                                        {{--</li>--}}
                                        {{--<li>--}}
                                            {{--<a href="#"><img src="images/sav.png" alt="">{{__('Edit')}}</a>--}}
                                        {{--</li>--}}
                                    {{--</ul>--}}
                                {{--</div>--}}
                                {{--@endif--}}
                                <div class="input-group post-comment">
                                  <span class="input-group-addon" id="basic-addon1">
                                    <a href="javascript:;" onclick="commentByBtn('{{$related_item->id}}')" class="submit-comment"><i
                                                class="fa fa-comments"></i></a>
                                  </span>
                                    <input type="text" class="form-control" onkeydown="commentByEnter('{{$related_item->id}}')" id="comment_input_{{$related_item->id}}"
                                           placeholder="{{__('Comment')}}" aria-describedby="basic-addon1">
                                </div>
                            </div>
                            <!-- Ad Box End -->
                        </div>
                    </div>
                        @endforeach

                </div>


                <!-- Row End -->
            </div>
            <!-- Main Container End -->
        </section>

        {{--<div class="modal fade" id="modal-map" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">--}}
            {{--<div class="modal-dialog modal-lg" role="document">--}}
                {{--<div class="modal-content">--}}
                    {{--<div class="modal-header">--}}
                        {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>--}}
                        {{--<h4 class="modal-title" id="myModalLabel">View Map</h4>--}}
                    {{--</div>--}}
                    {{--<div class="modal-body">--}}
                        {{--<div class="row">--}}
                            {{--<div class="col-md-8" id="map"></div>--}}
                            {{--<div class="list-group-item col-md-12" id="instructions"></div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="modal-footer">--}}
                        {{--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}

    </div>
    <!-- Main Content Area End -->

@endsection

@section('footer')

    @if(!empty($item->lat) && !empty($item->lng) && $item->lat != 0 && $item->lng != 0)
        <style>
            #map_details{
                height: 100%;
            }

        </style>
        <script>

            var map_item_details;
            function initMap_item_details() {
                var myLatLng = {lat: {{$item->lat}}, lng: {{$item->lng}}};
                map_item_details = new google.maps.Map(document.getElementById('map_details'), {
                    center: myLatLng,
                    zoom: 8
                });

                location_marker = new google.maps.Marker({
                    map: map_item_details,
                    title:"{{$item->{'name_'.\DataLanguage::get()} }}",
                    draggable: false,
                    animation: google.maps.Animation.DROP,
                    position: myLatLng
                });
                location_marker.addListener('click', toggleBounce_itemDetails);
            }

            function toggleBounce_itemDetails() {
                if (location_marker.getAnimation() !== null) {
                    location_marker.setAnimation(null);
                } else {
                    location_marker.setAnimation(google.maps.Animation.BOUNCE);
                }


            }


        </script>
    @endif

    <script>
        $(document).ready(function() {
           // viewMap();
            drow_item_options();


            $("#rateOwner").rateYo({
                rating:'{{round($item->user->rank,1)}}',
                //rating: 2.7,
                readOnly: true,
                spacing: "5px",
                ratedFill: "#3aa4c1"
            });
            $("#rateItem").rateYo({
                rating:'{{round($item->rank,1)}}',
                readOnly: true,
                spacing: "2px",
                ratedFill: "#3aa4c1"
            });


            $("#item_rank").rateYo({
                spacing: "10px",
                precision: 2,
                //ratedFill: "#3aa4c1",
                multiColor: {
                    "startColor": "#FF0000", //RED
                    "endColor"  : "#00FF00"  //GREEN
                }, onChange: function (rating, rateYoInstance) {
                    $(this).next().text(rating);
                },onSet: function (rating, rateYoInstance) {
                    if(!confirm("Rating is set to: " + rating + "\nDo you want to save this rate?")){
                        return false;
                    }
                    rate_item(rating);
                    //alert("Rating is set to: " + rating);
                    //act here :

                }
            });



        });




function rate_item(item_rank) {
    $.post('{{route('web.item.rank-item')}}',{'item_id':'{{$item->id}}','rank':item_rank}, function (out) {
        if (out.status == false) {
            notify('error','Error please try again!');
        } else {
            notify('success','{{__('Done. Thank you for rating')}}');
            $('#close_rank_modal').click();
            $('#open_item_rank_modal').remove();
        }
    });
}

        function addToWishlist() {
            $.post('{{route('web.user.add-wish')}}',{'item_id':'{{$item->id}}', '_token':$('meta[name="csrf-token"]').attr('content')}, function (out) {
                if (out.status == false) {
                    notify('error','Error please try again!');
                } else {
                    notify('success','{{__('Item added to your wishlist.')}}');
                    $('#add_wish_btn').remove();
                }
            });
        }



        function drow_item_options(){
            $.post('{{route('web.user.get-options-item-deal')}}',{'id':'{{$item->id}}'}, function (out) {

                if (out.status == false) {
                    $('#drow_options_div').html('');
                    notify('error','Error !');
                } else {
                    $('#drow_options_div').html('');
                    //console.log(out.data);
                    for (var i = 0; i < out.data.length; i++) {
                        var option = out.data[i];
                        var drow = $('<div>').attr('class', 'attribute_row form-group');
                        drow.append($('<label>').html(option.name_ar));
                        if (option.type == 'text') {
                            drow.append($('<input>', {
                                name: 'options[' + option.id + ']',
                                class: 'form-control',
                                id: 'option_text_' + option.id,
                            }));
                        } else if (option.type == 'select') {
                            var select = $('<select >', {
                                name: 'options[' + option.id + ']',
                                style: 'width:100%;',
                                class: 'form-control',
                                id: 'option_select_' + option.id
                            });
                            if (option.values) {
                                var options = [];
                                for (var x = 0; x < option.values.length; x++) {
                                    var opt_value = option.values[x];
                                        options.push($('<option>', {value: opt_value.id}).html(opt_value.name_ar));
                                }

                                drow.append(select.html(options));
                            }
                        }

                        $('#drow_options_div').append(drow);
                        $('#option_select_' + option.id).select2();

                    }



                }
            }, 'json');
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#deal_form').submit(function (e) {
            e.preventDefault();
            $.post('{{route('web.user.make-deal')}}', $('#deal_form').find(":input").serialize(), function (out) {

                if (out.status == false) {
                    console.log(out);
                    $.each(out.data, function (index, value) {
                        notify('error', value);
                    });

                } else {
                    // setTimeout(location.reload,3000);
                    $('#close_deal_modal').click();
                    $('#open_deal_modal').remove();
                    notify('success', 'Deal Created Successfully');

                }
            }, 'json')
        });


       // function viewMap($latitude,$longitude,$title){
            // $('#instructions').html('');
            //         map = new GMaps({
            //             div: '#map',
            //             lat: $latitude,
            //             lng: $longitude
            //         });
            //
            //         map.addMarker({
            //             lat: $latitude,
            //             lng: $longitude,
            //             infoWindow: {
            //                 content: $title
            //             }
            //         });
       // }


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

        function deleteItem($routeName,$reload){

            if(!confirm("Do you want to delete this ?")){
                return false;
            }

            if($reload == undefined){
                $reload = 3000;
            }

            $.post(
                $routeName,
                {
                    '_method':'DELETE',
                    '_token':$('meta[name="csrf-token"]').attr('content')
                },
                function(response){

                        if(response.status == true){
                            notify('success','Item has been deleted successfully');
                            if($reload){
                                setTimeout(function(){location.reload();},$reload);
                            }
                        }else{
                            notify('success','can not delete this');
                        }

                }
            )
        }

    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js"></script>


    @endsection
