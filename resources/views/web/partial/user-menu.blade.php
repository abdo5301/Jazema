<style>
    .mega-menu .second-links .drop-down-multilevel{
        top: auto;
    }
</style>

<section class="second-links">
    <div class="container">
        <ul class="menu-links second-menu-links" style="display: none; max-height: 400px; overflow: auto;">
            <li><a href="{{'/'}}"><span class="home-icons"></span>{{__('HOME')}} </a></li>
            <!-- active class -->
            @if( \Auth::check())

            <li class="hoverTrigger">
                <a href="javascript:void(0)"><span class="name-icons"></span>{{auth()->user()->full_name}} <i class="fa fa-angle-down fa-indicator"></i><div class="mobileTriggerButton"></div></a>
                <!-- drop down multilevel  -->
                <ul class="drop-down-multilevel effect-expand-top" style="transition: all 400ms ease;">
                    <li><a href="{{route('web.user.profile',auth()->user()->slug)}}">{{__('View Profile')}}</a></li>
                    <li><a href="{{route('web.user.edit-profile')}}">{{__('Edit Profile')}}</a></li>
                </ul>
            </li>

                <li><a href="{{route('web.user.profile',[Auth::user()->slug,'showItems'=>true])}}"><span class="control-icons"></span>{{__('My Items')}} </a></li>
                <li><a href="{{route('web.user.deals')}}"><span  class="deals-icons"></span>{{__('my deals')}} </a></li>
                <li><a href="{{route('web.user.stages')}}"><span class="business-icons"></span>{{__('My Stages')}} </a></li>


            <li class="hoverTrigger">
                <a href="javascript:void(0)"><span class="comm-icons"></span>{{__('My Communications')}} <i class="fa fa-angle-down fa-indicator"></i><div class="mobileTriggerButton"></div></a>
                <!-- drop down multilevel  -->
                <ul class="drop-down-multilevel effect-expand-top" style="transition: all 400ms ease;">
                    <li><a  target="_blank" href="{{route('web.user.friends')}}">{{__('Friend List')}}</a></li>
                    <li><a  target="_blank" href="{{route('web.user.followers')}}">{{__('Followers')}}</a></li>
                    <li><a  target="_blank" href="{{route('web.user-mail')}}">{{__('Mail')}}</a></li>
                </ul>
            </li>


            <li class="hoverTrigger">
                <a href="javascript:void(0)"><span class="add-icons "></span>{{__('Add new')}}  <i class="fa fa-angle-down fa-indicator"></i><div class="mobileTriggerButton"></div></a>
                <!-- drop down multilevel  -->
                <ul class="drop-down-multilevel effect-expand-top" style="transition: all 400ms ease;">
                    <li><a href="{{route('web.user.add-items')}}">{{__('item')}} </a></li>
                    <li><a href="{{route('web.user.add-stage')}}">{{__('stage')}} </a></li>
                </ul>
            </li>

            @endif


            @php $pages = \App\Models\Page::all();   @endphp
            @if(!empty($pages))
            <li class="hoverTrigger">
                <a href="javascript:void(0)"><span class="help-icons"></span>{{__('Help')}}  <i class="fa fa-angle-down fa-indicator"></i><div class="mobileTriggerButton"></div></a>
                <!-- drop down multilevel  -->
                <ul class="drop-down-multilevel effect-expand-top" style="transition: all 400ms ease;">
                    <li><a href="{{route('web.about-us')}}">{{__('About Us') }}</a></li>
                    @foreach($pages as $page)
                    @if($page->id == 1) @continue @endif
                    <li><a href="{{route('web.page',$page->{'slug_'.\DataLanguage::get()})}}">{{$page->{'name_'.\DataLanguage::get()} }}</a></li>
                    @endforeach
                </ul>
            </li>
            @endif


            {{--<li><a href="contact.html"><span class="sett-icons"></span>settings </a></li>--}}
        </ul>
    </div>
</section>

