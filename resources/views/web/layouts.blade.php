<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
    <!--[if IE]>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <![endif]-->
    <meta name="description" content="">
    <meta name="author" content="ScriptsBundle">
    <title>Jazeema</title>
    <!-- =-=-=-=-=-=-= Favicons Icon =-=-=-=-=-=-= -->
    <!-- <link rel="icon" href="images/favicon.ico" type="image/x-icon" /> -->
    <!-- =-=-=-=-=-=-= Mobile Specific =-=-=-=-=-=-= -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        preg_match('/([a-z]*)@/i', request()->route()->getActionName(), $matches);
    @endphp
    <title>{{ucfirst(request()->route()->getActionMethod())}} {{str_replace('Controller','',$matches[1])}}
        - {{setting('sitename_'.\DataLanguage::get())}} {{__('Jazeema')}}</title>

    <base href="{{asset('assets/website/assets/')}}">

    <!-- =-=-=-=-=-=-= Bootstrap CSS Style =-=-=-=-=-=-= -->
    <link rel="stylesheet" href="css/bootstrap.css">

    <!-- =-=-=-=-=-=-= Font Awesome =-=-=-=-=-=-= -->
    <link rel="stylesheet" href="css/font-awesome.css" type="text/css">
    <!-- =-=-=-=-=-=-= Flat Icon =-=-=-=-=-=-= -->
    <link href="css/flaticon.css" rel="stylesheet">
    <!-- =-=-=-=-=-=-= Et Line Fonts =-=-=-=-=-=-= -->
    <link rel="stylesheet" href="css/et-line-fonts.css" type="text/css">
    <!-- =-=-=-=-=-=-= Menu Drop Down =-=-=-=-=-=-= -->
    <link rel="stylesheet" href="css/forest-menu.css" type="text/css">
    <!-- =-=-=-=-=-=-= Animation =-=-=-=-=-=-= -->
    <link rel="stylesheet" href="css/animate.min.css" type="text/css">
    <!-- =-=-=-=-=-=-= Select Options =-=-=-=-=-=-= -->
    <link href="css/select2.min.css" rel="stylesheet"/>

    <!-- =-=-=-=-=-=-= noUiSlider =-=-=-=-=-=-= -->
    <link href="css/nouislider.min.css" rel="stylesheet">
    <!-- =-=-=-=-=-=-= Listing Slider =-=-=-=-=-=-= -->
    <link href="css/slider.css" rel="stylesheet">
    <!-- =-=-=-=-=-=-= Owl carousel =-=-=-=-=-=-= -->
    <link rel="stylesheet" type="text/css" href="css/owl.carousel.css">
    <link rel="stylesheet" type="text/css" href="css/owl.theme.css">
    <!-- =-=-=-=-=-=-= Check boxes =-=-=-=-=-=-= -->
    <link href="skins/minimal/minimal.css" rel="stylesheet">
    <!-- =-=-=-=-=-=-= Responsive Media =-=-=-=-=-=-= -->
    <link href="css/responsive-media.css" rel="stylesheet">
    <!-- =-=-=-=-=-=-= Template Color =-=-=-=-=-=-= -->
    <link rel="stylesheet" id="color" href="css/colors/defualt.css">
    <!-- =-=-=-=-=-=-= Template CSS Style =-=-=-=-=-=-= -->
    <link rel="stylesheet" href="css/style.css">
    <!-- =-=-=-=-=-=-= datePicker =-=-=-=-=-=-= -->
    <link rel="stylesheet" href="css/jquery.datetimepicker.min.css">

    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    {{--<style href="http://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" ></style>--}}

<style>
    #search_map{
        width: 100%;
        height: 500px;
    }

    .search_input{
        margin-left: 6px;
        margin-bottom: 15px;
        display: inline-block;
        width: 20%;
        height: 50px;
        border-radius: 25px;
        border: 1px solid #e5e5e5 !important;
        font-size: 1.1em;
        vertical-align: top;
    }

    .search_form .defult_select{
        width: 135px ;
        margin-left: 6px;
        display: inline-block;
        height: 54px;
        border-radius: 25px;
        border: 1px solid #e5e5e5;
        font-size: 1.1em;

    }

    .search_form .search_form_btns{
        display: inline-block;
        margin-left: 6px;
        border-radius: 25px;
        border: 1px solid #e5e5e5;
        margin-bottom: 16px;
    }

    .search_form .select2-container--default{
        min-width: 13% !important;
        width: auto !important;
        margin-left: 6px;
        vertical-align: top;
    }

    .search_form  .select2-container--default .select2-selection--multiple{
        display: inline-block;
        width: 100%;
        border: 1px solid #e5e5e5;
        box-shadow: none;
        vertical-align: text-bottom;
        border-radius: 25px;
        height: 50px;
        font-size: 1.1em;
        padding-left: 10px;
        padding-top: 5px;
    }

    .search_form .select2-container--default .select2-selection--single{
        display: inline-block;
        width: 100%;
        border: 1px solid #e5e5e5;
        box-shadow: none;
        line-height: 0;
        vertical-align: text-bottom;
        border-radius: 25px;
        height: 50px;
        font-size: 1.1em;
        padding-left: 10px;

    }

    .fast_search_div .select2-container--default .select2-selection--single{
        display: inline-block;
        width: 100%;
        border: 1px solid #e5e5e5;
        box-shadow: none;
        line-height: 0;
        vertical-align: text-bottom;
        border-radius: 10px;
        height: 50px;
        font-size: 1.2em;

    }
    .toggle-search{
        display: inline-block;
    }

    .toggle.toggle-search-btn .toggle-handle {
        display: none;
    }

    .toggle.toggle-search-btn {
        border-radius: 10px;
    }
    .toggle-search-btn .toggle-on.btn  {
        padding-left: 24px;
    }



    .toggle-search-container{
        @if(!auth()->check())
        padding-top: 4px;
        @endif
    }

    .search_map_btns{
        margin-left: 10px;
        border-radius: 20px;
        width: 10%;
        margin-top: 15px;
    }

    /*.toggle.toggle_search_price{*/
        /*!*display: inline-block;*!*/
        /*!*margin-left: 6px;*!*/
        /*!*border-radius: 25px;*!*/
        /*!*border: 1px solid #e5e5e5;*!*/
    /*}*/
    /*.toggle.toggle_search_price .toggle-handle {*/
        /*display: none;*/
    /*}*/

</style>
    @yield('header')


</head>
<body>
<!-- =-=-=-=-=-=-= map Modal =-=-=-=-=-=-= -->
<div class="modal fade modal-login" tabindex="-1" role="dialog" aria-hidden="true" id="search-map-preview">
<div class="modal-dialog" style="width: 80%;">
<div class="modal-content">
<div class="modal-body">

{{--<h2 class="login-head"></h2>--}}
{{--<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span--}}
{{--class="sr-only">Close</span></button>--}}
<div  id="search_map"></div>
<div style="text-align: center;">
    <a  class="btn btn-default   search_map_btns" data-dismiss="modal">{{__('Done')}}</a>
    <a  class="btn btn-default  search_map_btns" onclick="click_search();">{{__('Search')}}</a>
    <a  class="btn btn-default   search_map_btns" onclick="clear_search_map();">{{__('Clear')}}</a>
</div>

</div>
</div>
</div>
</div>

<script>


</script>

<!-- =-=-=-=-=-=-= map Modal =-=-=-=-=-=-= -->
<!-- =-=-=-=-=-=-= login Modal =-=-=-=-=-=-= -->
<div class="modal fade modal-login" tabindex="-1" role="dialog" aria-hidden="true" id="login-preview">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">

                <h2 class="login-head">{{__('login')}}</h2>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span
                            class="sr-only">Close</span></button>
                <!-- content goes here -->
                <form id="login_form">
                    <div class="form-group">
                        <label>{{__('Email')}}</label>
                        <input placeholder="Your Email" name="login_email" class="form-control" type="email">
                    </div>
                    <div class="form-group">
                        <label>{{__('Password')}}</label>
                        <input placeholder="Your Password" name="login_password" class="form-control" type="password">
                    </div>
                    {{--<div class="form-group">--}}
                    {{--<div class="row">--}}
                    {{--<div class="col-xs-12">--}}
                    {{--<div class="skin-minimal">--}}
                    {{--<ul class="list">--}}
                    {{--<li>--}}
                    {{--<input  type="checkbox" id="minimal-checkbox-1">--}}
                    {{--<label for="minimal-checkbox-1">Remember Me</label>--}}
                    {{--</li>--}}
                    {{--</ul>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    <button class="btn btn-theme btn-lg btn-block" type="submit">{{__('Login With Us')}}</button>
                </form>

            </div>
        </div>
    </div>
</div>
<!-- =-=-=-=-=-=-= login Modal =-=-=-=-=-=-= -->
<!-- =-=-=-=-=-=-= signup Modal =-=-=-=-=-=-= -->
<div class="modal fade modal-register" tabindex="-1" role="dialog" aria-hidden="true" id="register-preview">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">

                <h2 class="login-head">{{__('Signup')}}</h2>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span
                            class="sr-only">Close</span></button>
                <!-- content goes here -->
                <form id="signupForm" method="post" enctype="multipart/form-data">
                    <div class="form-group {!! formError($errors,'type',true) !!}">
                        {!! Form::label('type', __('Type').':') !!}
                        {!! Form::select('type',['individual'=>__('Individual'),'company'=>__('company')],isset($result->id) ? $result->type:old('type'),['class'=>'form-control','id'=>'type_id','onChange'=>'changeType()']) !!}
                        {!! formError($errors,'type') !!}

                    </div>
                    <div id="select_type" style="display: none;">
                        <div class="form-group{!! formError($errors,'company_business',true) !!}">
                            {!! Form::label('company_business', __('Company Business').':') !!}
                            {!! Form::text('company_business',isset($result->id) ? $result->company_business:old('company_business'),['class'=>'form-control']) !!}

                            {!! formError($errors,'company_business') !!}
                        </div>

                        <div class="form-group{!! formError($errors,'company_name',true) !!}">
                            <div class="controls">
                                {!! Form::label('company_name', __('Company Name').':') !!}
                                {!! Form::text('company_name',isset($result->id) ? $result->company_name:old('company_name'),['class'=>'form-control']) !!}
                            </div>
                            {!! formError($errors,'company_name') !!}
                        </div>

                    </div>
                    <div class="form-group {!! formError($errors,'firstname',true) !!}">
                        <label for="firstname">{{__('First Name')}}</label>
                        <input  placeholder="{{__('First Name')}}" class="form-control" type="text" name="firstname">
                        {!! formError($errors,'firstname') !!}
                    </div>

                    <div class="form-group {!! formError($errors,'lastname',true) !!}">
                        <label for="lastname">{{__('Last Name')}}</label>
                        <input placeholder="{{__('Last Name')}}" class="form-control" type="text" name="lastname">
                        {!! formError($errors,'lastname') !!}
                    </div>
                    <div class="form-group {!! formError($errors,'email',true) !!}">
                        <label for="email">{{__('Email')}}</label>
                        <input placeholder="{{__('Your Email')}}" class="form-control" type="email" name="email">
                        {!! formError($errors,'email') !!}
                    </div>
                    <div class="form-group {!! formError($errors,'password',true) !!}">
                        <label for="password"></label>
                        <input placeholder="{{__('Your Password')}}" class="form-control" type="password"
                               name="password">
                        {!! formError($errors,'password') !!}
                    </div>
                    <div class="form-group {!! formError($errors,'password_confirmation',true) !!}">
                        <label for="password_confirmation">{{__('Confirm Password')}}</label>
                        <input placeholder="{{__('Confirm Password')}}" class="form-control" type="password"
                               name="password_confirmation">
                        {!! formError($errors,'password_confirmation') !!}
                    </div>
                    <div class="form-group {!! formError($errors,'phone',true) !!}">
                        <div class="controls">
                            {!! Form::label('phone', __('Phone').':') !!}
                            {!! Form::number('phone',isset($result->id) ? $result->phone:old('phone'),['class'=>'form-control']) !!}
                        </div>
                        {!! formError($errors,'phone') !!}
                    </div>
                    <div class="form-group {!! formError($errors,'mobile',true) !!}">
                        <div class="controls">
                            {!! Form::label('mobile', __('Mobile').':') !!}
                            {!! Form::number('mobile',isset($result->id) ? $result->mobile:old('mobile'),['class'=>'form-control']) !!}
                        </div>
                        {!! formError($errors,'mobile') !!}
                    </div>
                    <div class="form-group{!! formError($errors,'gender',true) !!}">

                        {!! Form::label('gender', __('Gender').':') !!}
                        {!! Form::select('gender',['male'=>__('Male'),'female'=>__('Female')],isset($result->id) ? $result->gender:old('gender'),['class'=>'form-control']) !!}
                        {!! formError($errors,'gender') !!}
                    </div>
                    <div class="form-group custom-file-upload {!! formError($errors,'image',true) !!}" id="#bb">
                        <label for="image">{{__('Upload Image')}}</label>
                        <input placeholder="Confirm Password" class="form-control" type="file" name="image">
                    </div>

                    <div class="form-group">
                        <label>{{__('Your Location')}}</label>
                        <div id="map_register" class="form-control"></div>
                        <input type="hidden" name="lat">
                        <input type="hidden" name="lng">

                    </div>

                    <div class="form-group {!! formError($errors,'user_job_id',true) !!}">
                        <div class="controls">

                            {!! Form::label('user_job_id', __('Select User Job')) !!}
                            {!! Form::select('user_job_id',[0=>__('select job')]+$userJobs,isset($result->id) ? $result->user_job_id:old('user_job_id'),['class'=>'form-control user_job_id','onChange'=>'get_attribute();']) !!}
                        </div>
                        {!! formError($errors,'user_job_id') !!}
                    </div>


                    <div id="attribute_div_list">


                    </div>


                    {{--<div class="form-group">--}}
                    {{--<div class="row">--}}
                    {{--<div class="col-xs-12">--}}
                    {{--<div class="skin-minimal">--}}
                    {{--<ul class="list">--}}
                    {{--<li>--}}
                    {{--<input  type="checkbox" id="minimal-checkbox-1">--}}
                    {{--<label for="minimal-checkbox-1">I agree the terms of use</label>--}}
                    {{--</li>--}}
                    {{--</ul>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    <button class="btn btn-theme btn-lg btn-block" >{{__('Register')}}</button>
                </form>

            </div>
        </div>
    </div>
</div>
<!-- =-=-=-=-=-=-= signup Modal =-=-=-=-=-=-= -->

<style>
    #map_register{
        height: 200px;
        width: 100%;
        border: 1px solid #ccc;
    }
</style>



<!-- =-=-=-=-=-=-= Transparent Header =-=-=-=-=-=-= -->
<div class="transparent-header">
    <!-- Top Bar -->
    <div class="header-top">
        <div class="container">
            <div class="row">
                <!-- Header Top Left -->
                <div class="header-top-left col-md-12 col-sm-12 col-xs-12 hidden-xs">
                    <ul class="listnone">
{{--                        @php $itemCategories = ActiveParentCategories(10,0,0); @endphp--}}
{{--                        @if($itemCategories->isNotEmpty())--}}
{{--                            @foreach($itemCategories as $category)--}}
{{--                                <li><a href="{{route('web.item.category',$category->{'slug_'.\DataLanguage::get()} )}}">--}}
{{--                                        <img src="{{img($category->icon)}}" alt="{{$category->name}}"> {{$category->name}}--}}
{{--                                    </a></li>--}}
{{--                            @endforeach--}}
{{--                        @endif--}}
                        @php   $itemCategories = ActiveParentCategories(10,0,0); @endphp
                        @if($itemCategories->isNotEmpty())
                            @foreach($itemCategories as $category)
                                @php   $subCategories = ActiveParentCategories(10,0,$category->id); @endphp
                            <li class="dropdown hoverTrigger">
                                @if(request()->route()->getName() == 'web.index')
                                <a  @if($subCategories->isNotEmpty())href="javascript:void(0)"@else
                                  onclick="new_items('{{$category->id}}','category');runHomeGetItems();arrange_items();loading();"
                                @endif class="dropdown-toggle open-catg" data-toggle="dropdown" role="button"
                                   aria-haspopup="true" aria-expanded="false">
                                    @else
                                        <a  href="{{url('/?category='.$category->id)}}" class="dropdown-toggle open-catg" data-toggle="dropdown" role="button"
                                            aria-haspopup="true" aria-expanded="false">
                                    @endif
                                    <img src="{{img($category->icon)}}" alt="{{$category->name}}"> {{$category->name}}

                                    @if($subCategories->isNotEmpty())
                                    <span class="caret"></span>
                                @endif
                                </a>


                                @if($subCategories->isNotEmpty())
                                <ul class="dropdown-menu">
                                    @foreach($subCategories as $row)
                                        <li>
                                       {{--<a href="{{route('web.item.category',$row->{'slug_'.\DataLanguage::get()} )}}">--}}
                                            @if(request()->route()->getName() == 'web.index')
                                            <a onclick="new_items('{{$row->id}}','category');runHomeGetItems();arrange_items();loading();">
                                             @else
                                              <a href="{{url('/?category='.$row->id)}}">
                                             @endif
                                                <img src="{{img($row->icon)}}" alt="{{$row->name}}"> {{$row->name}}
                                        </a>
                                            {{--<a href="{{route('web.item.category',$category->{'slug_'.\DataLanguage::get()} )}}">{{$category->name}}</a>--}}
                                        </li>
                                    @endforeach
                                </ul>

                                @endif
                            </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>

        </div>
    </div>
    <!-- Top Bar End -->
    <!-- Navigation Menu -->
    <div class="clearfix"></div>
    <!-- menu start -->
    <nav id="menu-1" class="mega-menu">
        <!-- menu list items container -->
        <section class="menu-list-items">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <!-- menu logo -->
                        <ul class="menu-logo col-md-2 no-padding">
                            <li>
                                <a href="{{route('web.index')}}"><img src="{{asset('logo.png')}}" alt="logo"> </a>
                            </li>
                        </ul>
                        <!-- menu links -->
                        {{--<ul class="menu-links">--}}
                            {{--<li class="dropdown" id="my-catg">--}}
                                {{--<a href="#" class="dropdown-toggle open-catg" data-toggle="dropdown" role="button"--}}
                                   {{--aria-haspopup="true" aria-expanded="false">--}}
                                    {{--<img src="{{url('/assets/website/icons/Menu.png')}}" alt="">--}}
                                {{--</a>--}}
                                {{--<ul class="dropdown-menu drop-down-multilevel" id="urgent">--}}
                                    {{--<li>--}}
                                        {{--<a href="javascript:void(0)">aaaaaaaaa<i--}}
                                                    {{--class="fa fa-angle-right fa-indicator"></i></a>--}}
                                        {{--<ul class="drop-down-multilevel">--}}
                                            {{--<li><a href="listing.html">Listing Grid 1</a></li>--}}
                                            {{--<li><a href="listing-1.html">Listing Grid 2</a></li>--}}
                                            {{--<li><a href="listing-2.html">Listing Grid 3</a></li>--}}
                                        {{--</ul>--}}
                                    {{--</li>--}}
                                    {{--<li><a href="#">bbbbbbbbb</a></li>--}}
                                    {{--<li><a href="#">ccccccccc</a></li>--}}
                                    {{--<li><a href="#">ddddddddd</a></li>--}}
                                    {{--<li><a href="#">eeeeeeeee</a></li>--}}
                                {{--</ul>--}}
                            {{--</li>--}}

                        {{--</ul>--}}
                        {{--<form class="submit-form search-form col-md-4 col-sm-8 col-xs-8"--}}
                              {{--method="post">--}}
<div class="fast_search_div col-md-5 col-sm-8 col-xs-8">
    <select  name="id" class="form-control fast_search_item" ></select>
</div>




                            {{--<div class="search-wrapper">--}}

                                {{--<ul class="" style="display: inline-block;vertical-align: bottom;">--}}
                                    {{--<li class="dropdown">--}}
                                        {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"--}}
                                           {{--aria-haspopup="true" aria-expanded="false" id="sort1"><img src="icons/9.png"--}}
                                                                                                      {{--alt=""> Sorting by--}}
                                            {{--<span class="caret"></span></a>--}}
                                        {{--<ul class="dropdown-menu">--}}
                                            {{--<li><a href="#">aaaaaaaaa</a></li>--}}
                                            {{--<li><a href="#">bbbbbbbbb</a></li>--}}
                                            {{--<li><a href="#">ccccccccc</a></li>--}}
                                            {{--<li><a href="#">ddddddddd</a></li>--}}
                                            {{--<li><a href="#">eeeeeeeee</a></li>--}}
                                        {{--</ul>--}}
                                    {{--</li>--}}
                                {{--</ul>--}}
                                {{--<input class="form-control" placeholder="Search" type="text">--}}
                            {{--</div>--}}
                        {{--</form>--}}


                        <div class="other-drop no-padding">
                            <div class="toggle-search-container">
                                <input id="toggle-search-btn"  data-style="toggle-search-btn" type="checkbox"  data-toggle="toggle" data-on=" <i class='fa fa-search'></i> Less" data-off=" <i class='fa fa-search'></i> More" data-onstyle="danger" data-offstyle="info">
                            </div>
                            {{--<input type="checkbox" checked data-toggle="toggle" data-style="search_btn">--}}
                            {{--<ul class="">--}}
                                {{--<li class="dropdown">--}}
                                    {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"--}}
                                       {{--aria-haspopup="true" aria-expanded="false" id="sort"><img src="icons/9.png" alt=""> Sorting by--}}
                                        {{--<span class="caret"></span></a>--}}
                                    {{--<ul class="dropdown-menu">--}}
                                        {{--<li><a href="#">aaaaaaaaa</a></li>--}}
                                        {{--<li><a href="#">bbbbbbbbb</a></li>--}}
                                        {{--<li><a href="#">ccccccccc</a></li>--}}
                                        {{--<li><a href="#">ddddddddd</a></li>--}}
                                        {{--<li><a href="#">eeeeeeeee</a></li>--}}
                                    {{--</ul>--}}
                                {{--</li>--}}
                            {{--</ul>--}}
                        </div>
                        <!-- start profile -->
                        @if(Auth()->check())
                            <div class="my-profile">
                                <img src="{{img(Auth::user()->image,'users')}}" alt="">
                                <span class="user-name">{{Auth::user()->fullname}}</span>
                            </div>
                        @endif
                        <ul class="right-links">
                            @if(!auth()->check())
                                <li><a href="#register-preview" data-toggle="modal">{{__('join')}}</a></li>
                                <li><a href="#login-preview" data-toggle="modal">{{__('login')}}</a></li>

                            @else
                                <li><a target="_self" href="{{route('web.user.logout')}}">{{__('logout')}}</a></li>

                            @endif
                            <li class="for-lang">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                   aria-haspopup="true" aria-expanded="false">{{__('Language')}}</a>
                                <ul class="dropdown-menu">
                                    <li><a href="{{route('web.index','ar')}}">العربيه</a></li>
                                    <li><a href="{{route('web.index','en')}}">English</a></li>
                                </ul>
                            </li>
                            <li><a href="#">Get app</a></li>
                        </ul>


                    </div>

                    <div class="col-lg-12 col-md-12" >
                            <form id="search_form" action="{{route('web.index')}}" method="get" class="search_form"  style="display: none;">
                                {{--{{csrf_field()}}--}}
                                <hr>
                                @php   $itemTypes = ActiveItemTypes(6); @endphp
                                @if($itemTypes->isNotEmpty())
                                <select   name="search_type" onchange="get_search_attribute();" class="form-control search_form_select_type" >
                                    <option></option>
                                    @foreach($itemTypes as $type)
                                        <option   @if(!empty($_GET['search_type']) && $_GET['search_type'] == $type->{'slug_'.\DataLanguage::get()} ) selected @endif value="{{$type->{'slug_'.\DataLanguage::get()} }}">{{$type->name}}</option>
                                    @endforeach
                              </select>
                                @endif
                                @if($itemCategories->isNotEmpty())
                                <select  name="search_category" onchange="get_search_attribute();" class="form-control search_form_select_category" >
                                <option></option>

                                 @foreach($itemCategories as $category)
                                  @php   $subCategories = ActiveParentCategories(10,0,$category->id); @endphp
                                    @if($subCategories->isNotEmpty())
                                   <optgroup label="{{$category->name}}">
                                       @foreach($subCategories as $sub)
                                           <option @if(!empty($_GET['search_category']) && $_GET['search_category'] == $sub->{'slug_'.\DataLanguage::get()} ) selected @endif value="{{ $sub->{'slug_'.\DataLanguage::get()} }}"> {{$sub->name}}</option>
                                       @endforeach
                                    </optgroup>
                                    @else
                                     <option @if(!empty($_GET['search_category']) && $_GET['search_category'] == $category->{'slug_'.\DataLanguage::get()} ) selected @endif value="{{$category->{'slug_'.\DataLanguage::get()} }}"> {{$category->name}}</option>
                                     @endif
                                    @endforeach
                                </select>
                                @endif

                                <select  name="search_sort_by" onchange="check_location()"  class="form-control search_form_select_sort_by" >
                                    <option></option>
                                    <option @if(!empty($_GET['search_sort_by']) && $_GET['search_sort_by'] == 'rank') selected @endif value="rank">{{__('Rank')}}</option>
                                    <option @if(!empty($_GET['search_sort_by']) && $_GET['search_sort_by'] == 'deals') selected @endif value="deals">{{__('Deals')}}</option>
                                    <option @if(!empty($_GET['search_sort_by']) && $_GET['search_sort_by'] == 'views') selected @endif value="views">{{__('Views')}}</option>
                                    <option @if(!empty($_GET['search_sort_by']) && $_GET['search_sort_by'] == 'like') selected @endif value="like">{{__('Likes')}}</option>
                                    <option @if(!empty($_GET['search_sort_by']) && $_GET['search_sort_by'] == 'share') selected @endif value="share">{{__('Shares')}}</option>
                                    <option @if(!empty($_GET['search_sort_by']) && $_GET['search_sort_by'] == 'comments') selected @endif value="comments">{{__('Comments')}}</option>
                                    <option @if(!empty($_GET['search_sort_by']) && $_GET['search_sort_by'] == 'price') selected @endif value="price">{{__('Price')}}</option>
                                    <option @if(!empty($_GET['search_sort_by']) && $_GET['search_sort_by'] == 'quantity') selected @endif value="quantity">{{__('Quantity')}}</option>
                                    <option @if(!empty($_GET['search_sort_by']) && $_GET['search_sort_by'] == 'id') selected @endif value="id">{{__('Add Time')}}</option>
                                    <option @if(!empty($_GET['search_sort_by']) && $_GET['search_sort_by'] == 'distance') selected @endif value="distance">{{__('Distance')}}</option>
                                </select>

                                <select  name="search_sort_role" class="form-control   search_form_select_sort_role" >
                                    <option></option>
                                    <option @if(!empty($_GET['search_sort_role']) && $_GET['search_sort_role'] == 'desc') selected @endif value="desc">{{__('Highest first')}}</option>
                                    <option @if(!empty($_GET['search_sort_role']) && $_GET['search_sort_role'] == 'asc') selected @endif value="asc">{{__('Least first')}}</option>
                                </select>

                                <input type="text" @if(!empty($_GET['search_word']) ) value="{{$_GET['search_word']}}" @endif class="form-control search_input" id="search_word" name="search_word"  placeholder="{{__('Keyword for search')}}">
                                <input type="hidden" name="search_lat" @if(!empty($_GET['search_lat']) ) value="{{$_GET['search_lat']}}" @endif >
                                <input type="hidden" name="search_lng" @if(!empty($_GET['search_lng']) ) value="{{$_GET['search_lng']}}" @endif >
                                <a id="open_search_map" data-toggle="modal" href="#search-map-preview" class="btn btn-default search_form_btns" >{{__('Location')}}</a>
                                {{--<input id="toggle_search_price"  data-style="toggle_search_price" type="checkbox"  data-toggle="toggle" data-on="{{__('Price')}}" data-off="{{__('Price')}}" data-onstyle="default" data-offstyle="default">--}}
                                {{--<a id="toggle_search_price" class="btn btn-default search_form_btns" >{{__('Price')}}</a>--}}

                                <button id="submit_search_btn" type="submit" class="btn btn-default search_form_btns"><i class="fa fa-search"></i> {{__('search')}}</button>
                                    <input name="search_price_from" @if(!empty($_GET['search_price_from']) ) value="{{$_GET['search_price_from']}}" @endif class="form-control search_input" type="number" placeholder="{{__('Least Price')}}" title="{{__('Least Price')}}">
                                    <input name="search_price_to" @if(!empty($_GET['search_price_to']) ) value="{{$_GET['search_price_to']}}" @endif class="form-control search_input" type="number" placeholder="{{__('Highest price')}}" title="{{__('Highest price')}}">
                                <div id="loading_attr" style="display: none;"><img  src="{{img('loading.gif')}}" alt="{{__('loading')}}..."></div>

                                <div  id="attribute_div_list_search"></div>


                            </form>
                    </div>
                </div>
            </div>
        </section>



        @include('web.partial.user-menu')



        @if($itemTypes->isNotEmpty())
            <section class="third-links">
                <div class="container">
                    <div class="col-md-12">
                        <ul class="list-unstyled">
                            @foreach($itemTypes as $type)
                                <li>
                                    {{--<a href="{{route('web.item.type',$type->{'slug_'.\DataLanguage::get()} )}}" >--}}
                                    @if(request()->route()->getName() == 'web.index')
                                        <a onclick="new_items('{{$type->id}}','type');runHomeGetItems();arrange_items();loading();">
                                        @else
                                         <a href="{{url('/?type='.$type->id)}}" onclick="new_items('{{$type->id}}','type');runHomeGetItems();arrange_items();loading();">
                                         @endif
                                        <img src="{{img($type->icon)}}" alt="{{$type->name}}">
                                        {{$type->name}}
                                    </a>
                                </li>

                            @endforeach
                        </ul>
                        <!-- Material checked -->
                    </div>
                </div>
            </section>
        @endif


    </nav>
    <!-- menu end -->
</div>
<!-- Navigation Menu End -->
<div id="padding_div" style="display: none;"></div>

@yield('content')










<!-- Back To Top -->
<a href="#0" class="cd-top">Top</a>
<!-- =-=-=-=-=-=-= JQUERY =-=-=-=-=-=-= -->

<script src="js/jquery.min.js"></script>
<script src="js/notify.min.js"></script>

<script src="js/masonary.min.js"></script>
<!-- Bootstrap Core Css  -->
<script src="js/bootstrap.min.js"></script>
<!-- Jquery Easing -->
<script src="js/easing.js"></script>
<!-- Menu Hover  -->
<script src="js/forest-megamenu.js"></script>
<!-- Jquery Appear Plugin -->
<script src="js/jquery.appear.min.js"></script>
<!-- Numbers Animation   -->
<script src="js/jquery.countTo.js"></script>
<!-- Jquery Smooth Scroll  -->
<script src="js/jquery.smoothscroll.js"></script>
<!-- Jquery Select Options  -->
<script src="js/select2.min.js"></script>
<!-- datepicker  -->
<script src="js/jquery.datetimepicker.full.min.js"></script>
<script src="js/jquery.datetimepicker.min.js"></script>
<!-- noUiSlider -->
<script src="js/nouislider.all.min.js"></script>
<!-- Carousel Slider  -->
<script src="js/carousel.min.js"></script>
<script src="js/slide.js"></script>
<!-- Image Loaded  -->
<script src="js/imagesloaded.js"></script>
<script src="js/isotope.min.js"></script>
<!-- CheckBoxes  -->
<script src="js/icheck.min.js"></script>
<!-- Jquery Migration  -->
<script src="js/jquery-migrate.min.js"></script>
<!-- Sticky Bar  -->
<script src="js/theia-sticky-sidebar.js"></script>
<!-- Style Switcher -->
<script src="js/color-switcher.js"></script>
<!-- Template Core JS -->
<script src="js/custom.js"></script>
<script src="js/modernizr.js"></script>

<script src="js/underscore.js"></script>
<script src="{{asset('js/app.js')}}"></script>

<script>




    function check_location(){
        if( $('.search_form_select_sort_by').val() === 'distance'){
            if(!$("input[name=search_lat]").val() && !$("input[name=search_lng]").val()){
                $('.search_form_select_sort_by').val(null).trigger('change');
                notify('error','{{__('No Location Selected')}}');
                $('#open_search_map').click();
            }}
    }

    //unused
    function change_search_sort_roles() {
        var sort_by = $('.search_form_select_sort_by').val();
        var sortArray = ['rank','views','like','share','comments','price','quantity','deals'];
        if(jQuery.inArray(sort_by,sortArray ) !== -1){
            $('.search_form_select_sort_role').html('<option value="desc">{{__('Highest first')}}</option><option value="asc">{{__('Least first')}}</option>');
        }
        if(sort_by === 'id'){
            $('.search_form_select_sort_role').html('<option value="desc">{{__('Newest first')}}</option><option value="asc">{{__('Oldest first')}}</option>');
        }
    }


    function notify(status, msg) {
        $.notify(msg, status);
    }

    function like(itemID) {
        $.get('{{route('web.ajax.get',['type'=>'like'])}}', {item_id: itemID}, function (out) {
            if (out.status == true) {
                $('#like_icon_' + itemID).css('color', out.color);
                notify('success',out.msg);
            } else {
                notify('error', out.msg);
            }
        }, 'json');
    }

    function share(itemID) {
        $.get('{{route('web.ajax.get',['type'=>'share'])}}', {item_id: itemID}, function (out) {
            if (out.status == true) {
                notify('success', out.msg);
                $('#share-now').modal('hide');
            } else {
                notify('error', out.msg);
            }
        }, 'json');
    }

    function commentByBtn(itemID) {
        $.get('{{route('web.ajax.get',['type'=>'comment'])}}',
            {item_id: itemID, comment: $('#comment_input_' + itemID).val()}, function (out) {
                if (out.status == true) {
                    notify('success', out.msg);
                    $('#comment_input_' + itemID).val('')

                } else {
                    notify('error', out.msg);
                }
            }, 'json');
    }


    function commentByEnter(itemID) {
        if(event.keyCode == 13) {
            $.get('{{route('web.ajax.get',['type'=>'comment'])}}',
                {item_id: itemID, comment: $('#comment_input_' + itemID).val()}, function (out) {
                    if (out.status == true) {
                        notify('success', out.msg);
                        $('#comment_input_' + itemID).val('')

                    } else {
                        notify('error', out.msg);
                    }
                }, 'json');
        }

    }



    $(document).ready(function () {



        @if(!empty($_GET['search_word']) || !empty($_GET['search_category']) || !empty($_GET['search_type'])
        || !empty($_GET['search_sort_by']) || !empty($_GET['search_sort_role'])  || !empty($_GET['search_price_from']) || !empty($_GET['search_price_to'])
         || (!empty($_GET['search_lat']) && !empty($_GET['search_lng'])))

        if($('#toggle-search-btn').prop('checked') !== true){
            $('#toggle-search-btn').prop('checked', true).change();

            $(window).on('load', function() {
                {{--@if(!empty($_GET['search_price_from']) || !empty($_GET['search_price_to']))--}}
                {{--$('#search_price_div').show();--}}
                {{--@endif  --}}
                var search_form_height = $('#search_form').height();
                var search_price_div_height = $('#search_price_div').height();
                $('#padding_div').css('height', (search_form_height - 50) );
                setTimeout(function() {
                    $('#search_form').slideDown();
                    $('#padding_div').slideDown();

                }, 2000);
            });

        }

        get_search_attribute();

        @endif

        $(".toggle-search-container").click(function () {
            //alert('hi');
            var search_form_height = $('#search_form').height();
            $('#padding_div').css('height',search_form_height);
            $('#search_form').slideToggle();
            $('#padding_div').slideToggle();

        });

        $("#toggle_search_price").click(function(){
            $("#search_price_div").slideToggle();
            setTimeout(function() {
                var search_form_height = $('#search_form').height();
                $('#padding_div').css('height',search_form_height+15);
            }, 400);


        });

        $('.search_form_select_type').select2({
            placeholder: "{{__('Type')}}",
            allowClear: true
        });
        $('.search_form_select_category').select2({
            placeholder: "{{__('Category')}}",
            allowClear: true
        });
        $('.search_form_select_sort_by').select2({
            placeholder:  "{{__('Sort by')}}",
            allowClear: true
        });
        $('.search_form_select_sort_role').select2({
            placeholder:  "{{__('Sort role')}}",
            allowClear: true
        });

       // getLocation();


        {{--$("img").each(function() {--}}
        {{--// if image already loaded, we can check it's height now--}}
        {{--if (this.complete) {--}}
        {{--checkImg(this);--}}
        {{--} else {--}}
        {{--// if not loaded yet, then set load and error handlers--}}
        {{--$(this).load(function() {--}}
        {{--checkImg(this);--}}
        {{--}).error(function() {--}}
        {{--// img did not load correctly--}}
        {{--// set new .src here--}}

        {{--this.src = "{{asset('no_image.png')}}";--}}
        {{--});--}}

        {{--}--}}
        {{--});--}}
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    {{--function like(itemID) {--}}

    {{--$.post('{{route('web.item.like')}}',{'id':itemID},function(out){--}}
    {{--if(out.status == true){--}}
    {{--$('#like'+itemID).css('color',out.data.color);--}}
    {{--}else{--}}
    {{--toastr('error',"{{__("Please Try Again Later")}}");--}}
    {{--}--}}
    {{--},'json')--}}
    {{--}--}}


    // function getLocation() {
    //     if (navigator.geolocation) {
    //         navigator.geolocation.getCurrentPosition(showPosition);
    //     } else {
    //         notify("Geolocation is not supported by this browser.");
    //     }
    // }
    // function showPosition(position) {
    //     $("input[name=search_lat]").val(position.coords.latitude);
    //     $("input[name=search_lng]").val(position.coords.longitude);
    //     alert( "Latitude: " + position.coords.latitude +
    //         "\n Longitude: " + position.coords.longitude);
    // }


    function get_attribute() {
        var user_job_id = $('#user_job_id').val();
        $.post('{{route('web.user.get-attribute')}}', {'user_job_id': user_job_id}, function (out) {
            if (out.status == false) {

                $('#attribute_div_list').empty();
                // toastr.error(out.msg, 'Error', {"closeButton": true});
            } else {
                $('#attribute_div_list').html('');
                for (var i = 0; i < out.data.length; i++) {
                    var attribute = out.data[i];
                    //console.log(out.data);

                    var drow = $('<div>').attr('class', 'attribute_row form-group');
                    drow.append($('<label>').html(attribute.name));

                    if (attribute.type == 'text') {
                        drow.append($('<input>', {
                            name: 'attribute[' + attribute.id + ']',
                            id: 'attribute_' + attribute.id,
                            class: 'form-control'
                        }));
                    } else if (attribute.type == 'textarea') {
                        drow.append($('<textarea>', {
                            name: 'attribute[' + attribute.id + ']',
                            id: 'attribute_' + attribute.id,
                            class: 'form-control'
                        }));
                    } else if (attribute.type == 'select') {
                        var select = $('<select>', {
                            name: 'attribute[' + attribute.id + ']',
                            class: 'form-control',
                            style: 'width:100%',
                            id: 'attribute_' + attribute.id
                        });
                        if (attribute.values) {
                            var options = [];
                            for (var x = 0; x < attribute.values.length; x++) {
                                var attr_value = attribute.values[x];
                                options.push($('<option>', {value: attr_value.id}).html(attr_value.name));
                            }

                            drow.append(select.html(options));
                        }
                    } else if (attribute.type == 'multi_select') {
                        var select = $('<select>', {
                            name: 'attribute[' + attribute.id + '][]',
                            class: 'form-control select2',
                            style: 'width:100%',
                            multiple: '',
                            id: 'attribute_' + attribute.id
                        });
                        if (attribute.values) {
                            var options = [];
                            for (var x = 0; x < attribute.values.length; x++) {
                                var attr_value = attribute.values[x];
                                options.push($('<option>', {value: attr_value.id}).html(attr_value.name));
                            }

                            drow.append(select.html(options));
                        }
                    }else if (attribute.type == 'date'){
                        drow.append($('<input>', {
                            name: 'attribute[' + attribute.id + ']',
                            id: 'attribute_' + attribute.id,
                            class: 'form-control',
                            placeholder:'dd/mm/yy',
                            type:'text'
                        }));
                    }else if (attribute.type == 'datetime'){
                        drow.append($('<input>', {
                            name: 'attribute[' + attribute.id + ']',
                            id: 'attribute_' + attribute.id,
                            class: 'form-control',
                            placeholder:'d-m-y H:i:s',
                            type:'text'
                        }));
                    }else if (attribute.type == 'file'){
                        drow.append($('<input>', {
                            type:'file',
                            name: 'attribute[' + attribute.id + ']',
                            id: 'attribute_' + attribute.id,
                            class: 'form-control'
                        }));
                    }


                    $('#attribute_div_list').append(drow);
                    if(attribute.type == 'select' || attribute.type == 'multi_select'){
                    $('#attribute_' + attribute.id).select2();
                    }
                    if(attribute.type == 'date'){
                        $( '#attribute_' + attribute.id ).datetimepicker({
                            timepicker: false,
                            format: 'Y-m-d',
                        });
                    }
                    if(attribute.type == 'datetime'){
                        $( '#attribute_' + attribute.id ).datetimepicker({
                            format: 'Y-m-d H:i:s'
                        });
                    }

                }
            }

        }, 'json');
    }

    $('#login_form').submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        e.preventDefault();
        $.ajax({
            type: "post",
            url: '{{ route('web.user.login') }}',
            processData: false,
            contentType: false,
            data: formData,
            success: function (out) {

                if (out.status == true) {
                    window.location = out.redirect;
                } else {
                    notify('error', out.msg);
                }
            },
            error: function (out) {

                notify('error', 'Login Error');

            }
        });


    });



    $('#signupForm').submit(function (e) {
        e.preventDefault();
        $('small').remove();
        var formData = new FormData(this);
       
        e.preventDefault();
        $.ajax({
            type: "post",
            url: '{{ route('web.user.register') }}',
            processData: false, contentType: false,
            data: formData,
            success: function (out) {
                // alert(out.msg);
                if (out.status == true) {
                    window.location = out.redirect;
                    //   notify('success',out.msg);
                    // toastr.success(out.msg, 'Success', {"closeButton": true});
                    $('#signupForm')[0].reset();
                } else {
                    //console.log(out.data);

                   // alert(out.data[Object.keys(out.data)[0]]);
                    $.each(out.data, function (index, value) {


                        var inputName = index.split(".");
                        if (inputName[1]) {
                            if(value == out.data[Object.keys(out.data)[0]]){
                                $('#attribute_' + inputName[1]).focus();
                            }
                          {{--// $('#attribute_' + inputName[1]).after(' <small style="color:red">'+"{{__('This field is required ')}}"+'</small>');--}}
                            $('#attribute_' + inputName[1]).after(' <small style="color:red">' + value + '</small>');
                        } else {
                            if(value == out.data[Object.keys(out.data)[0]]){
                                $("input[name=" + index + "]").focus();
                            }
                            $("input[name=" + index + "]").after(' <small style="color:red">' + value + '</small>');
                            $("select[name=" + index + "]").after(' <small style="color:red">' + value + '</small>');
                        }
                        //  notify('error',value);

                    });

                }
            },
            error: function (out) {
                console.log(out);
                notify('error','Error!  please try again later.');
            }
        });


    });

    function changeType() {
        var type = $('#type_id').val();
       // console.log(type);
        if (type == 'company') {
            $('#select_type').show();
        } else {
            $('#select_type').hide();
        }
    }

    // function toastr(status,msg) {
    //     //status [success,info,warning,error]
    //     Command: toastr[status](msg);
    //  }


</script>

<footer id="footer" >
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h4>About Jazeema</h4>
                @if(!empty(setting('footer_text_'.\DataLanguage::get())))<p>{{setting('footer_text_'.\DataLanguage::get())}}</p>
                @else
                <p>......</p>
                @endif

            </div>
            <div class="col-md-6 col-sm-12">
                <div class="col-md-6 col-sm-6">
                    <h4>contact us</h4>
                    <ol class="list-unstyled">
                        @if(!empty(setting('mobile1')))<li>{{setting('mobile1')}}</li>@endif
                            @if(!empty(setting('mobile2')))<li>{{setting('mobile2')}}</li>@endif
                            @if(!empty(setting('email')))<li>{{setting('email')}}</li>@endif
                         <li>
                                @if(!empty(setting('facebook')))<a target="_blank" href="{{setting('facebook')}}"><i class="fa fa-facebook"></i></a>@endif
                                @if(!empty(setting('twitter'))) <a target="_blank" href="{{setting('twitter')}}"><i class="fa fa-twitter"></i></a>@endif
                                @if(!empty(setting('instagram')))<a target="_blank" href="{{setting('instagram')}}"><i class="fa fa-instagram"></i></a>@endif
                                @if(!empty(setting('youtube')))<a target="_blank" href="{{setting('youtube')}}"><i class="fa fa-youtube"></i></a>@endif
                        </li>
                    </ol>
                </div>
                <div class="col-md-6 col-sm-6">
                    <h4>links</h4>
                    <ol class="list-unstyled">
                        <li><a style="color: white;" href="{{route('web.about-us')}}"> {{__('contact us')}}</a></li>
                        <li><a style="color: white;" href="{{route('web.about-us')}}"> {{__('about us')}}</a></li>
                        {{--<li>site-map</li>--}}
                        <li><a style="color: white;" href="{{route('web.index')}}">{{__('Home Page')}}</a></li>
                        <li><a style="color: white;" href="{{route('web.user.add-items')}}">{{__('Add item')}}</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

</footer>
<div class="down-footer" id="down_footer">
    <p class="text-center">{{__('All rights reserved to jazeema')}} &copy {{date('Y')}}</p>
</div>




<script>
    var search_map;
    var search_marker;
    var map1;
    var set_marker;

    function initMap_search() {
        var myLatLng = {lat: 26.820553, lng: 30.802498};
        search_map = new google.maps.Map(document.getElementById('search_map'), {
            center: myLatLng,
            zoom: 8
        });

        @if(!empty($_GET['search_lat']) && !empty($_GET['search_lng']))
        var focus_search_marker = new google.maps.LatLng(parseFloat('{{$_GET['search_lat']}}'),parseFloat('{{$_GET['search_lng']}}'));
        search_marker = new google.maps.Marker({
            map: search_map,
            draggable: false,
            animation: google.maps.Animation.DROP,
            icon:'https://developers.google.com/maps/documentation/javascript/examples/full/images/library_maps.png',
            position: focus_search_marker
        });
        search_map.setCenter(focus_search_marker);
        @endif

        google.maps.event.addListener(search_map, 'click', function(event) {
            placeSearchMarker(event.latLng);
            $("input[name=search_lat]").val(event.latLng.lat());
            $("input[name=search_lng]").val(event.latLng.lng());
            if(!$('.search_form_select_sort_by').val()){
                $('.search_form_select_sort_by').val('distance').trigger('change');
                $('.search_form_select_sort_role').val('asc').trigger('change');
            }
            //console.log('lat: ' +event.latLng.lat());
            //console.log('lng: ' +event.latLng.lng());
        });

    }

    function placeSearchMarker(location) {
        deleteSearchOverlays();
        search_marker = new google.maps.Marker({
            map: search_map,
            draggable: false,
            animation: google.maps.Animation.DROP,
            icon:'https://developers.google.com/maps/documentation/javascript/examples/full/images/library_maps.png',
            position: location
        });
       // search_marker.addListener('click', toggleBounce_set);
    }

    function deleteSearchOverlays() {
        if (search_marker) {
            search_marker.setMap(null);
        }
    }

    function initMap(){
            if(typeof initMap_register == 'function')
            initMap_register();
            if(typeof initMap_item_details == 'function')
            initMap_item_details();
            if(typeof initMap_edit_profile == 'function')
            initMap_edit_profile();
            if(typeof initMap_edit_item == 'function')
            initMap_edit_item();
            if(typeof initMap_add_item == 'function')
            initMap_add_item();
            if(typeof initMap_about == 'function')
            initMap_about();
            if(typeof initMap_items == 'function')
            initMap_items();
            if(typeof initMap_search == 'function')
            initMap_search();
    }

    function initMap_register() {
        var myLatLng = {lat: 26.820553, lng: 30.802498};
        map1 = new google.maps.Map(document.getElementById('map_register'), {
            center: myLatLng,
            zoom: 8
        });

        google.maps.event.addListener(map1, 'click', function(event) {
            placeMarker(event.latLng);
            $("input[name=lat]").val(event.latLng.lat());
            $("input[name=lng]").val(event.latLng.lng());
           // console.log('lat: ' +event.latLng.lat());
           // console.log('lng: ' +event.latLng.lng());
        });

    }

    function placeMarker(location) {
        deleteOverlays();
        set_marker = new google.maps.Marker({
            map: map1,
            draggable: false,
            animation: google.maps.Animation.DROP,
            position: location
        });
        set_marker.addListener('click', toggleBounce_set);

    }

    function deleteOverlays() {
        if (set_marker) {
            set_marker.setMap(null);
        }
    }

    function toggleBounce_set() {
        if (set_marker.getAnimation() !== null) {
            set_marker.setAnimation(null);
        } else {
            set_marker.setAnimation(google.maps.Animation.BOUNCE);
        }
    }

    function click_search() {
        $('#submit_search_btn').click();
    }

    function clear_search_map() {
        deleteSearchOverlays();
        $("input[name=search_lat]").val('');
        $("input[name=search_lng]").val('');
        if($('.search_form_select_sort_by').val() === 'distance'){
            $('.search_form_select_sort_by').val(null).trigger('change');
        }
        if (item_marker) {
            for (i in item_marker) {
                if(item_marker[i].index === "search"){
                    item_marker[i].setMap(null);
                }
            }
        }
    }


    jQuery(document).ready(function () {
        google.maps.event.addDomListener(window, 'load', initMap);
    });
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBbD0LpXp1x2hhJskG05TiMh-jB2QV4jG0&callback=initMap" async defer></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script>

    $(".fast_search_item").select2({
        ajax: {
            url: '{{route('web.item.fastSearchItem')}}',
            dataType: 'json',
            delay: 250,
            type:'get',
            data: function (params) {
                return {
                    search: params.term,
                    type: 'public'
                    //page: params.page
                };
            },
            processResults: function (data, params) {
                // parse the results into the format expected by Select2
                // since we are using custom formatting functions we do not need to
                // alter the remote JSON data, except to indicate that infinite
                // scrolling can be used
                params.page = params.page || 1;

                return {
                    results: data.items,
                    pagination: {
                        more: (params.page * 30) < data.total_count
                    }
                };
            },
            cache: true
        },
        placeholder: '{{__('Search for Item')}}',
        minimumInputLength: 1,
        templateResult: formatRepoItem,
        templateSelection: formatRepoSelectionItem
    });

    function formatRepoItem (repo) {
        // console.log(repo);
        if (repo.loading) {
            return repo.text;
        }


        var $container = $(

            "<div class='select2-result-repository row clearfix'>" +
            "<div class='select2-result-repository__avatar col-sm-2'><img style='width:100%;height: 70px; border-radius: 10px;' src='" + repo.image +"' /></div>" +
            "<div class='select2-result-repository__meta col-sm-10' style='display: inline-block;'>" +
            "<div class='select2-result-repository__title' style='font-family: cursive;font-size: 15px;font-weight: 850;'></div>" +
            "<div class='select2-result-repository__description' style='font-size: 12px;font-weight: 450;'></div>" +
            "<div class='select2-result-repository__statistics row' style='font-size: 10px;font-weight: 400;'>" +
            "<div class='select2-result-repository__forks col-md-3'><i class='fa fa-thumbs-up'></i> </div>" +
            "<div class='select2-result-repository__stargazers col-md-3'><i class='fa fa-star'></i> </div>" +
            "<div class='select2-result-repository__watchers col-md-4'><i class='fa fa-eye'></i> </div>" +
            "</div>" +
            "</div>" +
            "</div>"

        );

        $container.find(".select2-result-repository__title").text(repo.name);
        $container.find(".select2-result-repository__description").text(repo.description);
        $container.find(".select2-result-repository__forks").append(repo.like + " {{__('likes')}}");
        $container.find(".select2-result-repository__stargazers").append(repo.rank + " {{__('Stars')}}");
        $container.find(".select2-result-repository__watchers").append(repo.views + " {{__('Watchers')}}");

        return $container;
    }

    function formatRepoSelectionItem (repo) {
        if(repo.name){
             window.location.replace(repo.item_link);
            return repo.name || repo.text;
        }

        return repo.name || repo.text;
    }
    
    function redirect_item(repo) {
        return window.location.replace(repo.item_link);
    }



     // get $_GET by client side
    // var urlParams;
    // (window.onpopstate = function () {
    //     var match,
    //         pl     = /\+/g,  // Regex for replacing addition symbol with a space
    //         search = /([^&=]+)=?([^&]*)/g,
    //         decode = function (s) { return decodeURIComponent(s.replace(pl, " ")); },
    //         query  = window.location.search.substring(1);
    //
    //     urlParams = {};
    //     while (match = search.exec(query))
    //         urlParams[decode(match[1])] = decode(match[2]);
    // })();

    // get $_GET by server side
    var urlParams = @php echo json_encode($_GET, JSON_HEX_TAG);  @endphp

    //console.log(urlParams['attribute']);


    function get_search_attribute() {
        $('#attribute_div_list_search').html('');
        var search_type = $('.search_form_select_type').val();
        var search_category = $('.search_form_select_category').val();
        if (search_type && search_category) {
            $('#loading_attr').show();
            //   alert(search_type+search_category);
            $.post('{{route('web.search.get-attribute')}}', {'search_category': search_category, 'search_type': search_type}, function (out) {
                if (out.status == false) {
                    $('#attribute_div_list_search').html('');
                } else {
                    $('#attribute_div_list_search').html('');

                    //$('#attribute_div_list_search').append('<br>');
                    for (var i = 0; i < out.data.length; i++) {
                        var attribute = out.data[i];
                        var drow;

                        if (attribute.type == 'text') {
                            drow = $('<input>', {
                                name: 'attribute[' + attribute.name + '-' +attribute.id+ ']',
                                class: 'form-control search_input',
                                placeholder: attribute.name,
                                title: attribute.name,
                                value:(urlParams['attribute'] && urlParams['attribute'][attribute.name + '-' +attribute.id]) ? urlParams['attribute'][attribute.name + '-' +attribute.id] : '' ,
                                id: 'attribute_' + attribute.id
                            });
                        } else if (attribute.type == 'number') {
                            drow = $('<input>', {
                                name: 'attribute[' + attribute.name + '-' +attribute.id+ ']',
                                class: 'form-control search_input',
                                placeholder: attribute.name,
                                title: attribute.name,
                                type: 'number',
                                value:(urlParams['attribute'] && urlParams['attribute'][attribute.name + '-' +attribute.id]) ? urlParams['attribute'][attribute.name + '-' +attribute.id] : '' ,
                                id: 'attribute_' + attribute.id
                            });
                        } else if (attribute.type == 'date') {
                            drow = $('<input>', {
                                name: 'attribute[' + attribute.name + '-' +attribute.id+ ']',
                                class: 'form-control search_input',
                                type: 'text',
                                title: attribute.name,
                                placeholder: attribute.name,
                                value:(urlParams['attribute'] && urlParams['attribute'][attribute.name + '-' +attribute.id]) ? urlParams['attribute'][attribute.name + '-' +attribute.id] : '' ,
                                id: 'attribute_' + attribute.id
                            });
                        }  else if (attribute.type == 'datetime') {
                            drow = $('<input>', {
                                name: 'attribute[' + attribute.name + '-' +attribute.id+ ']',
                                type: 'text',
                                title: attribute.name,
                                class: 'form-control search_input',
                                placeholder: attribute.name,
                                value:(urlParams['attribute'] && urlParams['attribute'][attribute.name + '-' +attribute.id]) ? urlParams['attribute'][attribute.name + '-' +attribute.id] : '' ,
                                id: 'attribute_' + attribute.id
                            });
                        } else if (attribute.type == 'file') {
                            drow = $('<input>', {
                                name: 'attribute[' + attribute.name + '-' +attribute.id+ ']',
                                class: 'form-control search_input',
                                type: 'file',
                                title: attribute.name,
                                placeholder: attribute.name,
                                value:(urlParams['attribute'] && urlParams['attribute'][attribute.name + '-' +attribute.id]) ? urlParams['attribute'][attribute.name + '-' +attribute.id] : '' ,
                                id: 'attribute_' + attribute.id
                            });
                        } else if (attribute.type == 'textarea') {
                            drow = $('<textarea>', {
                                name: 'attribute[' + attribute.name + '-' +attribute.id+ ']',
                                class: 'form-control search_input',
                                placeholder: attribute.name,
                                title: attribute.name,
                                rows: "1",
                                id: 'attribute_' + attribute.id
                            }).html((urlParams['attribute'] && urlParams['attribute'][attribute.name + '-' +attribute.id]) ? urlParams['attribute'][attribute.name + '-' +attribute.id] : '');
                        } else if (attribute.type == 'select') {
                            var select = $('<select >', {
                                name: 'attribute[' + attribute.name + '-' +attribute.id+ ']',
                                class: 'form-control',
                                title: attribute.name,
                                id: 'attribute_' + attribute.id
                            });
                            if (attribute.values) {
                                var options = [];
                                options.push($('<option>'));
                                for (var x = 0; x < attribute.values.length; x++) {
                                    var attr_value = attribute.values[x];
                                    if(urlParams['attribute'] && attr_value.id == urlParams['attribute'][attribute.name + '-' +attribute.id]){
                                        options.push($('<option>', {value: attr_value.id,selected:"selected"}).html(attr_value.name));
                                    }else{
                                        options.push($('<option>', {value: attr_value.id}).html(attr_value.name));
                                    }
                                    //options.push($('<option>', {value: attr_value.id}).html(attr_value.name));
                                }

                                drow = select.html(options);
                            }
                        } else if (attribute.type == 'multi_select') {
                            var select = $('<select >', {
                                name: 'attribute[' + attribute.name + '-' +attribute.id+ '][]',
                                class: 'form-control',
                                multiple:'',
                                title: attribute.name,
                                id: 'attribute_' + attribute.id
                            });
                            if (attribute.values) {
                                var options = [];
                                for (var x = 0; x < attribute.values.length; x++) {
                                    var attr_value = attribute.values[x];

                                    if(urlParams['attribute'] && jQuery.inArray(JSON.stringify( attr_value.id ),urlParams['attribute'][attribute.name + '-' +attribute.id]) !== -1) {
                                        options.push($('<option>', {value: attr_value.id,selected:"selected"}).html(attr_value.name));
                                    }else{
                                        options.push($('<option>', {value: attr_value.id}).html(attr_value.name));
                                    }

                                }

                                drow = select.html(options);
                            }
                        }

                        $('#attribute_div_list_search').append(drow);




                        if(attribute.type === 'multi_select' || attribute.type === 'select'){
                            $('#attribute_' + attribute.id).select2({
                                placeholder : attribute.name,
                                allowClear: true
                            });
                        }
                        if(attribute.type === 'date'){
                            $( '#attribute_' + attribute.id ).datetimepicker({
                                timepicker: false,
                                format: 'Y-m-d',
                            });
                        }
                        if(attribute.type === 'datetime'){
                            $( '#attribute_' + attribute.id ).datetimepicker({
                                format: 'Y-m-d H:i:s'
                            });
                        }
                    }
                    //after for loop


                }

                //after if condition
                var form_height = $('#search_form').height();
                $('#padding_div').css('height',form_height+15);

                $('#loading_attr').hide();
            }, 'json');

        }

    }





</script>

@yield('footer')

</body>
</html>