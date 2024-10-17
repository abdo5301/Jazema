@if(isset($user))
<section class="profile-banner">
        <div class="container">
            <div class="my-media">
                <div class="logo-img">
                    <a >
                        <img src="{{img($user->image,'users')}}" alt="">
                    </a>
                </div>
                <div class="profile-data">
                    <h4>{{$user->fullname}}</h4>
                    <p class="user-slog">{{$user->userJob->{'name_'.DataLanguage::get()} }}</p>
                    <p class="date-signed">{{$user->created_at->diffForHumans()}}</p>
                    {{--25 Apr 2018--}}
                </div>
            </div>
            @if(!empty($user->interisted_categories))
                <div class="right-links menu-list-items">

                    <ul class="menu-links profile-hover" style="max-height: 400px; ">

                        <li class="dropdown hoverTrigger">
                            <a href="#" class="dropdown-toggle open-catg" data-toggle="dropdown" role="button"
                               aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-bars"></i> {{__('My categories')}}<i class="fa fa-angle-down fa-indicator"></i>
                                <div class="mobileTriggerButton"></div>
                            </a>
                            <ul class="dropdown-menu drop-down-multilevel effect-expand-top" id="urgent"
                                style="transition: all 400ms ease;">
                                @foreach($categories as $row)
                                    <li><a target="_blank"
                                           href="{{route('web.item.category',$row->{'slug_'.\DataLanguage::get()} )}}">{{$row->{'name_'.\DataLanguage::get()} }}</a>
                                    </li>
                                @endforeach
                            </ul>


                        </li>



                    </ul>




                    {{--<ul class="nav nav-tabs " style="padding-left: 297px;border-bottom: 0;">--}}
                        {{--<li class="col-md-4 col-sm-12 col-xs-12" style="float: right;text-align: center;"><a data-toggle="tab" href="#ITEMS">{{__('ITEMS')}}</a></li>--}}
                        {{--<li class="active col-md-4 col-sm-12 col-xs-12" style="float: left;text-align: center;"><a data-toggle="tab" href="#ABOUT">{{__('ABOUT')}}</a></li>--}}
                    {{--</ul>--}}

                </div>
            @endif
        </div>
    </section>
@else
<section class="profile-banner">
    <div class="container">
        <div class="my-media">
            <div class="logo-img">
                <a href="#">
                    <img src="{{img(auth()->user()->image,'users')}}" alt="">
                </a>
            </div>
            <div class="profile-data">
                <h4>{{auth()->user()->full_name}}</h4>
                <p class="user-slog">{{auth()->user()->userJob->{'name_'.DataLanguage::get()} }}</p>
                <p class="date-signed">{{auth()->user()->created_at->diffForHumans()}}</p>
            </div>
        </div>
        @if(!empty(auth()->user()->interisted_categories))
            <div class="right-links menu-list-items">
                <ul class="menu-links profile-hover" style="max-height: 400px; ">
                    <li class="dropdown hoverTrigger">
                        <a href="#" class="dropdown-toggle open-catg" data-toggle="dropdown" role="button"
                           aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-bars"></i> {{__('my categories')}}<i class="fa fa-angle-down fa-indicator"></i>
                            <div class="mobileTriggerButton"></div>
                        </a>
                        <ul class="dropdown-menu drop-down-multilevel effect-expand-top" id="urgent"
                            style="transition: all 400ms ease;">
                            @foreach(auth()->user()->interested_categories_data() as $row)
                                <li><a target="_blank"
                                       href="{{route('web.item.category',$row->{'slug_'.\DataLanguage::get()} )}}">{{$row->{'name_'.\DataLanguage::get()} }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                </ul>
            </div>
        @endif
    </div>
</section>
@endif