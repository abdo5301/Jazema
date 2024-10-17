@if(auth()->check())
    <div class="col-md-3 col-sm-12 col-xs-12">

        <div class="user-profile" id="profile_menu">
            <ul>
                <li @if(request()->segment(2) == 'profile'&& !isset($_GET['showItems'])) class="active" @endif ><a  href="{{route('web.user.profile',[Auth::user()->slug])}}">{{__('Profile')}}</a></li>
                <li @if(request()->segment(2) == 'edit-profile') class="active" @endif ><a  href="{{route('web.user.edit-profile')}}">{{__('Edit Profile')}}</a></li>
                <li  @if(request()->segment(2) == 'profile'&& isset($_GET['showItems'])) class="active" @endif ><a  href="{{route('web.user.profile',[Auth::user()->slug,'showItems'=>true])}}">{{__('My Items')}}</a></li>
                <li  @if(request()->segment(2) == 'add-items') class="active" @endif ><a  href="{{route('web.user.add-items')}}">{{__('Add New Item')}}</a></li>
                <li @if(request()->segment(2) == 'wishlist') class="active" @endif><a href="{{route('web.user.wishlist')}}">{{__('wishlist')}}</a></li>
                <li @if(request()->segment(2) == 'stages' || request()->segment(2) == 'stage-edit') class="active" @endif><a href="{{route('web.user.stages')}}">{{__('Stages')}}</a></li>
                <li @if(request()->segment(2) == 'add-stage') class="active" @endif><a href="{{route('web.user.add-stage')}}">{{__('Add Stage')}}</a></li>
                <li @if(request()->segment(2) == 'followers') class="active" @endif><a href="{{route('web.user.followers')}}">{{__('Followers')}}</a></li>
                <li @if(request()->segment(2) == 'friends') class="active" @endif><a href="{{route('web.user.friends')}}">{{__('Friends')}}</a></li>
                <li @if(request()->segment(2) == 'deals') class="active" @endif><a href="{{route('web.user.deals')}}">{{__('Deals')}}</a></li>
                <li @if(request()->segment(2) == 'mail') class="active" @endif><a href="{{route('web.user-mail')}}">{{__('mail')}}</a></li>

            </ul>
         
        </div>
    </div>

@endif

