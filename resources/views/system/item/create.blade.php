@extends('system.layouts')
@section('header')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/forms/selects/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/css/plugins/forms/wizard.css')}}">
@endsection
@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-4 col-xs-12">
                    <h4>
                        {{$pageTitle}}
                    </h4>
                </div>
                <div class="content-header-right col-md-8 col-xs-12">
                    <div class=" content-header-title mb-0" style="float: right;">
                        @include('system.breadcrumb')
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Server-side processing -->
                <section id="server-processing">
                    <div class="row">
                        <div class="col-xs-12">
                            @if($errors->any())
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="alert alert-danger">
                                            {{print_r($errors->all())}}
                                            {{__('Some fields are invalid please fix them')}}
                                        </div>
                                    </div>
                                </div>
                            @elseif(Session::has('status'))
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="alert alert-{{Session::get('status')}}">
                                            {{ Session::get('msg') }}
                                        </div>
                                    </div>
                                </div>
                        @endif
                        {!! Form::open(['route' => isset($result->id) ? ['system.item.update',$result->id]:'system.item.store','files'=>true, 'method' => isset($result->id) ?  'PATCH' : 'POST','class'=>'number-tab-steps wizard-circle','id'=> isset($result->id) ?  'itemEditForm' : 'itemForm']) !!}
                        <!-- Step 1 -->
                            <h6>{{__('Information')}}</h6>
                            <fieldset style="padding: 0px;">
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h2>{{__('Information')}}</h2>
                                        </div>
                                        <div class="card-block card-dashboard">

                                            <div class="form-group col-sm-12{!! formError($errors,'user_id',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('user_id', __('User').':') !!}
                                                    {!! Form::select('user_id',isset($result->id) ? [$result->user_id =>$result->user->Fullname]:[''=>__('Select User')],isset($result->id) ? $result->user_id:old('user_id'),['style'=>'width: 100%;' ,'id'=>'user_id','class'=>'form-control col-md-12','onchange'=>'get_merchant_template_option()']) !!}

                                                </div>
                                                {!! formError($errors,'user_id') !!}
                                            </div>



                                            <div class="form-group col-sm-6{!! formError($errors,'item_category_id',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('item_category_id', __('Item Category').':') !!}
                                                        {!! Form::select('item_category_id',$ItemCategory,old('item_category_id'),['class'=>'select2 form-control','id'=>'item_category_id','onchange'=>'get_attribute();get_merchant_template_option()']) !!}
                                                </div>

                                                {!! formError($errors,'item_category_id') !!}
                                            </div>
                                            <div class="form-group col-sm-6{!! formError($errors,'item_type_id',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('item_type_id', __('Item Type').':') !!}
                                                    {!! Form::select('item_type_id',$ItemType,old('item_type_id'),['class'=>'select2 form-control','id'=>'item_type_id','onchange'=>'get_attribute(),get_item_type();']) !!}
                                                </div>

                                                {!! formError($errors,'item_type_id') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h2>{{__('English Data')}}</h2>
                                        </div>
                                        <div class="card-block card-dashboard">
                                            <div class="form-group col-sm-12{!! formError($errors,'name_en',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('name_en', __('Item Name (English)').':') !!}
                                                    {!! Form::text('name_en',isset($result->id) ? $result->name_en:old('name_en'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'name_en') !!}
                                            </div>
                                            <div class="form-group col-sm-12{!! formError($errors,'description_en',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('description_en', __('Item description (English)').':') !!}
                                                    {!! Form::textarea('description_en',isset($result->id) ? $result->description_en:old('description_en'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'description_en') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h2>{{__('Arabic Data')}}</h2>
                                        </div>
                                        <div class="card-block card-dashboard">
                                            <div class="form-group col-sm-12{!! formError($errors,'name_ar',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('name_ar', __('Item Name (Arabic)').':') !!}
                                                    {!! Form::text('name_ar',isset($result->id) ? $result->name_ar:old('name_ar'),['class'=>'form-control ar']) !!}
                                                </div>
                                                {!! formError($errors,'name_ar') !!}
                                            </div>
                                            <div class="form-group col-sm-12{!! formError($errors,'description_ar',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('description_ar', __('Item description (Arabic)').':') !!}
                                                    {!! Form::textarea('description_ar',isset($result->id) ? $result->description_ar:old('description_ar'),['class'=>'form-control ar']) !!}
                                                </div>
                                                {!! formError($errors,'description_ar') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-block card-dashboard">

                                            <div class="form-group col-sm-12{!! formError($errors,'status',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('status', __('Status').':') !!}
                                                    {!! Form::select('status',['active'=>__('Active'),'in-active'=>__('In-Active')],isset($result->id) ? $result->status:old('status'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'status') !!}
                                            </div>
                                            <div id="item_type_div" style="display: none;">
                                                <div class="form-group col-sm-6{!! formError($errors,'quantity',true) !!}">
                                                    <div class="controls">
                                                        {!! Form::label('quantity', __('Quantity').':') !!}
                                                        {!! Form::number('quantity',isset($result->id) ? $result->price:old('quantity'),['class'=>'form-control']) !!}
                                                    </div>
                                                    {!! formError($errors,'status') !!}
                                                </div>
                                                <div class="form-group col-sm-6{!! formError($errors,'price',true) !!}">
                                                    <div class="controls">
                                                        {!! Form::label('price', __('Price').':') !!}
                                                        {!! Form::number('price',isset($result->id) ? $result->price:old('price'),['class'=>'form-control']) !!}
                                                    </div>
                                                    {!! formError($errors,'status') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </fieldset>

                            <!-- Step 2 -->
                            <h6>{{__('Images')}}</h6>
                            <fieldset style="padding: 0px;">

                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="col-sm-6">
                                                <h2>{{__('Images')}}</h2>
                                                @if(formError($errors,'image',true))
                                                    <p class="text-xs-left">
                                                        <small class="danger text-muted">{{__('Error File Upload')}}</small>
                                                    </p>
                                                @endif
                                            </div>

                                        </div>
                                        <div class="card-block card-dashboard">
                                            <div class="uploaddata">

                                                @if(isset($temp_image))
                                                    @foreach($temp_image as $key=> $row)
                                                        <div class="image_row_{{$key}}" style="display: table;">
                                                            <img width="200px" class="image_tag form-group col-sm-6"
                                                                 src="{{asset('storage/'.$row->path)}}" alt=""/>

                                                            <a class="delete_image_btn"
                                                               onclick="remove_image('{{$key}}','{{$row->id}}')">{{__('Delect')}}
                                                                <i class="fa fa-trash"></i></a>
                                                        </div>
                                                    @endforeach


                                                @endif

                                                @if(isset($result->id))

                                                    @foreach($result->upload  as $key=> $row)
                                                        <div class="image_row_{{$key}}" style="display: table;">
                                                            <img width="200px" class="image_tag form-group col-sm-6"
                                                                 src="{{asset('storage/'.$row->path)}}" alt=""/>

                                                            <a class="delete_image_btn"
                                                               onclick="remove_image('{{$key}}','{{$row->id}}')">{{__('Delect')}}
                                                                <i class="fa fa-trash"></i></a>
                                                        </div>
                                                    @endforeach
                                                @endif


                                                <div class="row" style="padding-top:10px;">

                                                    <div class="col-xs-2">
                                                        <button id="uploadBtn" class="btn btn-large btn-primary">Choose
                                                            File
                                                        </button>
                                                    </div>

                                                    <div class="col-xs-10">
                                                        <div id="progressOuter" class="progress progress-striped active"
                                                             style="display:none;">
                                                            <div id="progressBar"
                                                                 class="progress-bar progress-bar-success"
                                                                 role="progressbar" aria-valuenow="45" aria-valuemin="0"
                                                                 aria-valuemax="100" style="width: 0%">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row" style="padding-top:10px;">
                                                    <div class="col-xs-10">
                                                        <div id="msgBox">
                                                        </div>
                                                    </div>
                                                </div>


                                                <div id="image_list">
                                                    <input type="hidden"
                                                           @if(!empty(old('temp_id'))) value="{{old('temp_id')}}"
                                                           @else value="{{$temp_id}}" @endif name="temp_id"
                                                           id="temp_id">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="image_temp_tag" style="display: none">
                                    <div class="image_row" style="display: table;">
                                        <img width="200px" class="image_tag form-group col-sm-6" src="" alt=""/>

                                        <a class="delete_image_btn">{{__('Delect')}} <i class="fa fa-trash"></i></a>
                                    </div>
                                </div>
                                <div id="image_temp" style="display: none">
                                    <div class="image_row" style="display: table;width: 100%">
                                        <div class="form-group col-sm-6{!! formError($errors,'image',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('image', __('Image')) !!}
                                                {!! Form::file('',['class'=>'form-control image']) !!}
                                            </div>
                                            {!! formError($errors,'image') !!}
                                        </div>
                                        <div class="form-group col-sm-4{!! formError($errors,'image_title',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('image_title', __('Title')) !!}
                                                {!! Form::text('','',['class'=>'form-control image_title']) !!}
                                            </div>
                                            {!! formError($errors,'image_title') !!}
                                        </div>
                                        <div class="col-sm-2 form-group">
                                            <a href="javascript:void(0);"
                                               onclick="$(this).closest('.image_row').remove();" class="text-danger">
                                                <i class="fa fa-lg fa-trash mt-3"></i>
                                            </a>
                                        </div>

                                    </div>
                                </div>

                            </fieldset>

                            <!-- Step 4 -->

                            <h6>{{__('Options')}}</h6>
                            <fieldset style="padding: 0px;">

                                {{--<div class="card-header">--}}
                                    {{--<h2>{{__('Options')}}</h2>--}}
                                {{--</div>--}}
                                <div style="text-align: right;" class="col-sm-6">
                                    <button type="button" class="btn btn-primary fa fa-plus " onclick="add_option()">
                                        <span>{{__('Add Option')}}</span>
                                    </button>
                                </div>

                                <div class="col-xl-12 col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">{{__('Options')}}</h4>
                                        </div>
                                        <div class="card-content">
                                            <div class="card-body">

                                                <div class="nav-vertical">
                                                    <ul id="option_li_list" class="nav nav-tabs nav-left flex-column"
                                                        style="height: 102.391px;">

                                                        @if(old('option'))

                                                            @foreach(old('option') as $key => $row)
                                                                <li class="nav-item option_li_row option_li_row_{{$key}} ">
                                                                    @if(!isset($result->id))
                                                                        <a id="remove_option_{{$key}}"
                                                                           href="javascript:void(0);"
                                                                           style="display: inline;"
                                                                           onclick="remove_option('{{$key}}')"
                                                                           class="text-danger">
                                                                            <i class="fa fa-lg fa-trash mt-3"></i>
                                                                        </a>
                                                                    @endif
                                                                    <a @if(!isset($result->id)) style="display: inline;"
                                                                       @endif
                                                                       class="nav-link option_a_row option_a_row{{$key}}"
                                                                       onclick="change_tab('{{$key}}')"
                                                                       id="tab_{{$key}}"
                                                                       data-toggle="tab"
                                                                       aria-controls="tabv_{{$key}}"
                                                                       href="#tabv_{{$key}}"
                                                                       aria-expanded="true">Option {{$key+1}}</a>
                                                                </li>
                                                            @endforeach
                                                        @elseif(isset($result->id))

                                                            @if($result->option)

                                                                @foreach($result->option as $key => $row)
                                                                    <li class="nav-item option_li_row option_li_row_{{$row->id}} ">

                                                                        <a
                                                                                class="nav-link option_a_row  option_a_row{{$row->id}}"
                                                                                onclick="change_tab('{{$row->id}}')"
                                                                                id="tab_{{$row->id}}"
                                                                                data-toggle="tab"
                                                                                aria-controls="tabv_{{$row->id}}"
                                                                                href="#tabv_{{$row->id}}"
                                                                                aria-expanded="true">Option {{$key+1}}</a>
                                                                    </li>
                                                                @endforeach
                                                            @endif


                                                        @endif

                                                    </ul>
                                                    <div id="option_div_list" class="tab-content px-1">
                                                        @if(old('option'))

                                                            @foreach(old('option') as $key => $row)

                                                                <div role="tabpanel"
                                                                     class="tab-pane option_div_row option_div_row_{{$key}}"
                                                                     id="tabv_{{$key}}"
                                                                     aria-expanded="true"
                                                                     aria-labelledby="tab_{{$key}}">


                                                                    @if(isset($result->id) && isset($row['id']))
                                                                        <div class="col-sm-10"></div>
                                                                        <input type="hidden"
                                                                               name="option[{{$key}}][template_option]"
                                                                               value="new">
                                                                        <input type="hidden" name="option[{{$key}}][id]"
                                                                               value="{{$row['id']}}">

                                                                    @else


                                                                        <div class="form-group col-sm-10{!! formError($errors,'option.'.$key.'.template_option',true) !!}">
                                                                            <div class="controls">
                                                                                {!! Form::label('template_option', __('Template Option').':') !!}
                                                                                <div class="input-group input-group-lg">
                                                                                    @if(isset($current_template_option))
                                                                                        {!! Form::select('option['.$key.'][template_option]',[''=>__('Select Option'),'new'=>__('New Option')]+$current_template_option,$row['template_option'],['id'=>'template_option_'.$key,'class'=>'form-control template_option']) !!}
                                                                                    @else
                                                                                        {!! Form::select('option['.$key.'][template_option]',[''=>__('Select Option'),'new'=>__('New Option')],isset($row['template_option'])?$row['template_option']:'',['id'=>'template_option_'.$key,'class'=>'form-control template_option']) !!}
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                            {!! formError($errors,'option.'.$key.'.template_option') !!}
                                                                        </div>

                                                                    @endif

                                                                    <div class="form-group col-sm-2{!! formError($errors,'option.'.$key.'.option_sort',true) !!}">
                                                                        <div class="controls">
                                                                            {!! Form::label('option_sort', __('Sort').':') !!}
                                                                            <div class="input-group input-group-lg">
                                                                                {!! Form::number('option['.$key.'][option_sort]',$row['option_sort'],['class'=>'form-control option_sort']) !!}

                                                                            </div>
                                                                        </div>
                                                                        {!! formError($errors,'option.'.$key.'.option_sort') !!}
                                                                    </div>

                                                                    @if($row['template_option'] == 'new')
                                                                        <div id="new_option">

                                                                            <div class="form-group col-sm-6{!! formError($errors,'option.'.$key.'.option_name_ar',true) !!}">
                                                                                <div class="controls">
                                                                                    {!! Form::label('option_name_ar', __('Name Ar').':') !!}
                                                                                    <div class="input-group input-group-lg">
                                                                                        {!! Form::text('option['.$key.'][option_name_ar]',$row['option_name_ar'],['class'=>'form-control option_name_ar']) !!}

                                                                                    </div>
                                                                                </div>
                                                                                {!! formError($errors,'option.'.$key.'.option_name_ar') !!}
                                                                            </div>

                                                                            <div class="form-group col-sm-6{!! formError($errors,'option.'.$key.'.option_name_en',true) !!}">
                                                                                <div class="controls">
                                                                                    {!! Form::label('option_name_en', __('Name En').':') !!}
                                                                                    <div class="input-group input-group-lg">
                                                                                        {!! Form::text('option['.$key.'][option_name_en]',$row['option_name_en'],['class'=>'form-control option_name_en']) !!}

                                                                                    </div>
                                                                                </div>
                                                                                {!! formError($errors,'option.'.$key.'.option_name_en') !!}
                                                                            </div>


                                                                            @if( ( !isset($result->id) && !isset($row['id'])) ||  ($result->id && !isset($row['id'])))
                                                                                <div class="form-group col-sm-6{!! formError($errors,'option.'.$key.'.option_is_required',true) !!}">
                                                                                    <div class="controls">
                                                                                        {!! Form::label('option_is_required', __('Is Required').':') !!}
                                                                                        <div class="input-group input-group-lg">
                                                                                            {!! Form::select('option['.$key.'][option_is_required]',['yes'=>__('Yes'),'no'=>__('No')],$row['option_is_required'],['class'=>'form-control option_is_required']) !!}

                                                                                        </div>
                                                                                    </div>
                                                                                    {!! formError($errors,'option.'.$key.'.option_is_required') !!}
                                                                                </div>

                                                                                <div class="form-group col-sm-6{!! formError($errors,'option.'.$key.'.option_type',true) !!}">
                                                                                    <div class="controls">
                                                                                        {!! Form::label('option_type', __('Type').':') !!}
                                                                                        <div class="input-group input-group-lg">
                                                                                            {!! Form::select('option['.$key.'][option_type]',['text'=>__('Text'),'textarea'=>__('Text Area'),'select'=>__('Select'),'radio'=>__('Radio'),'check'=>__('Check Box')],$row['option_type'],['class'=>'form-control option_type']) !!}

                                                                                        </div>
                                                                                    </div>
                                                                                    {!! formError($errors,'option.'.$key.'.option_type') !!}
                                                                                </div>

                                                                            @elseif(isset($result->id) && isset($row['id']))
                                                                                <div class="form-group col-sm-4{!! formError($errors,'option.'.$key.'.option_is_required',true) !!}">
                                                                                    <div class="controls">
                                                                                        {!! Form::label('option_is_required', __('Is Required').':') !!}
                                                                                        <div class="input-group input-group-lg">
                                                                                            {!! Form::select('option['.$key.'][option_is_required]',['yes'=>__('Yes'),'no'=>__('No')],$row['option_is_required'],['class'=>'form-control option_is_required']) !!}

                                                                                        </div>
                                                                                    </div>
                                                                                    {!! formError($errors,'option.'.$key.'.option_is_required') !!}
                                                                                </div>

                                                                                <div class="form-group col-sm-4{!! formError($errors,'option.'.$key.'.option_type',true) !!}">
                                                                                    <div class="controls">
                                                                                        {!! Form::label('option_type', __('Type').':') !!}
                                                                                        <div class="input-group input-group-lg">
                                                                                            {!! Form::select('option['.$key.'][option_type]',['text'=>__('Text'),'textarea'=>__('Text Area'),'select'=>__('Select'),'radio'=>__('Radio'),'check'=>__('Check Box')],$row['option_type'],['class'=>'form-control option_type']) !!}

                                                                                        </div>
                                                                                    </div>
                                                                                    {!! formError($errors,'option.'.$key.'.option_type') !!}
                                                                                </div>

                                                                                {{--<div class="form-group col-sm-4{!! formError($errors,'option.'.$key.'.option_status',true) !!}">--}}
                                                                                    {{--<div class="controls">--}}
                                                                                        {{--{!! Form::label('option_status', __('Status').':') !!}--}}
                                                                                        {{--<div class="input-group input-group-lg">--}}
                                                                                            {{--{!! Form::select('option['.$key.'][option_status]',['active'=>__('Active'),'in-active'=>__('In Active'),'deleted'=>__('Deleted')],$row['option_status'],['class'=>'form-control option_status']) !!}--}}
                                                                                        {{--</div>--}}
                                                                                    {{--</div>--}}
                                                                                    {{--{!! formError($errors,'option.'.$key.'.option_status') !!}--}}
                                                                                {{--</div>--}}

                                                                            @endif

                                                                            @if( $row['option_type'] == 'select' || $row['option_type'] == 'radio' || $row['option_type'] == 'check' )


                                                                                <div id="option_value_list_{{$key}}">
                                                                                    <div style="text-align: right;"
                                                                                         class="col-sm-6">
                                                                                        <button type="button"
                                                                                                class="btn btn-primary fa fa-plus add_option_value_button"
                                                                                                onclick="add_option_value({{$key}})">
                                                                                            <span>{{__('Add Value')}}</span>
                                                                                        </button>
                                                                                    </div>
                                                                                    {{--{{dd($row)}}--}}
                                                                                    @foreach($row['option_value_name_ar'] as $key2 => $value )

                                                                                        <div class="option_value_row"
                                                                                             style="width: 100%;display: table;">
                                                                                            <div class="form-group col-sm-3{!! formError($errors,'option.'.$key.'.option_value_name_ar.'.$key2,true) !!}">
                                                                                                <div class="controls">
                                                                                                    {!! Form::label('option_value_name_ar', __('Name Ar').':') !!}
                                                                                                    <div class="input-group input-group-lg">
                                                                                                        {!! Form::text('option['.$key.'][option_value_name_ar][]',$row['option_value_name_ar'][$key2],['class'=>'form-control option_value_name_ar']) !!}
                                                                                                    </div>
                                                                                                </div>
                                                                                                {!! formError($errors,'option.'.$key.'.option_value_name_ar.'.$key2) !!}
                                                                                            </div>
                                                                                            <div class="form-group col-sm-3{!! formError($errors,'option.'.$key.'.option_value_name_en.'.$key2,true) !!}">
                                                                                                <div class="controls">
                                                                                                    {!! Form::label('option_value_name_en', __('Name En').':') !!}
                                                                                                    <div class="input-group input-group-lg">
                                                                                                        {!! Form::text('option['.$key.'][option_value_name_en][]',$row['option_value_name_en'][$key2],['class'=>'form-control option_value_name_en']) !!}
                                                                                                    </div>
                                                                                                </div>
                                                                                                {!! formError($errors,'option.'.$key.'.option_value_name_en.'.$key2) !!}
                                                                                            </div>

                                                                                            @if( ( !isset($result->id) && !isset($row['id']) ) ||  ( isset($result->id) && !isset($row['id']) ) )


                                                                                                <div class="form-group col-sm-2{!! formError($errors,'option.'.$key.'.option_value_price_prefix.'.$key2,true) !!}">
                                                                                                    <div class="controls">
                                                                                                        {!! Form::label('option_value_price_prefix', __('Prefix').':') !!}
                                                                                                        <div class="input-group input-group-lg">
                                                                                                            <select name="option[{{$key}}][option_value_price_prefix][]"
                                                                                                                    id=""
                                                                                                                    class="form-control option_value_price_prefix">
                                                                                                                <option @if($row['option_value_price_prefix'][$key2] == '+') selected
                                                                                                                        @endif value="+">
                                                                                                                    +
                                                                                                                </option>
                                                                                                                <option @if($row['option_value_price_prefix'][$key2] == '-') selected
                                                                                                                        @endif value="-">
                                                                                                                    -
                                                                                                                </option>
                                                                                                            </select>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    {!! formError($errors,'option.'.$key.'.option_value_price_prefix.'.$key2) !!}
                                                                                                </div>
                                                                                                <div class="form-group col-sm-3{!! formError($errors,'option.'.$key.'.option_value_price.'.$key2,true) !!}">
                                                                                                    <div class="controls">
                                                                                                        {!! Form::label('option_value_price', __('Price').':') !!}
                                                                                                        <div class="input-group input-group-lg">
                                                                                                            {!! Form::text('option['.$key.'][option_value_price][]',$row['option_value_price'][$key2],['class'=>'form-control option_value_price']) !!}
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    {!! formError($errors,'option.'.$key.'.option_value_price.'.$key2) !!}
                                                                                                </div>
                                                                                                <div class="form-group col-sm-1">

                                                                                                    <a href="javascript:void(0);"
                                                                                                       onclick="$(this).closest('.option_value_row').remove();"
                                                                                                       class="text-danger">
                                                                                                        <i class="fa fa-lg fa-trash mt-3"></i>
                                                                                                    </a>

                                                                                                </div>
                                                                                            @elseif(isset($result->id) && isset($row['id']))
                                                                                                <input type="hidden"
                                                                                                       name="option[{{$key}}][option_value_id][]"
                                                                                                       value="{{$row['option_value_id'][$key2]}}">
                                                                                                <div class="form-group col-sm-2{!! formError($errors,'option.'.$key.'.option_value_price_prefix.'.$key2,true) !!}">
                                                                                                    <div class="controls">
                                                                                                        {!! Form::label('option_value_price_prefix', __('Prefix').':') !!}
                                                                                                        <div class="input-group input-group-lg">
                                                                                                            <select name="option[{{$key}}][option_value_price_prefix][]"
                                                                                                                    id=""
                                                                                                                    class="form-control option_value_price_prefix">
                                                                                                                <option @if($row['option_value_price_prefix'][$key2] == '+') selected
                                                                                                                        @endif value="+">
                                                                                                                    +
                                                                                                                </option>
                                                                                                                <option @if($row['option_value_price_prefix'][$key2] == '-') selected
                                                                                                                        @endif value="-">
                                                                                                                    -
                                                                                                                </option>
                                                                                                            </select>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    {!! formError($errors,'option.'.$key.'.option_value_price_prefix.'.$key2) !!}
                                                                                                </div>
                                                                                                <div class="form-group col-sm-2{!! formError($errors,'option.'.$key.'.option_value_price.'.$key2,true) !!}">
                                                                                                    <div class="controls">
                                                                                                        {!! Form::label('option_value_price', __('Price').':') !!}
                                                                                                        <div class="input-group input-group-lg">
                                                                                                            {!! Form::text('option['.$key.'][option_value_price][]',$row['option_value_price'][$key2],['class'=>'form-control option_value_price']) !!}
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    {!! formError($errors,'option.'.$key.'.option_value_price.'.$key2) !!}
                                                                                                </div>
                                                                                                <div class="form-group col-sm-2{!! formError($errors,'option.'.$key.'.option_value_status.'.$key2,true) !!}">
                                                                                                    <div class="controls">
                                                                                                        {!! Form::label('option_value_status', __('Status').':') !!}
                                                                                                        <div class="input-group input-group-lg">
                                                                                                            <select name="option[{{$key}}][option_value_status][]"
                                                                                                                    id=""
                                                                                                                    class="form-control option_value_status">
                                                                                                                <option @if($row['option_value_status'][$key2] == 'active') selected
                                                                                                                        @endif value="active">{{__('Active')}}</option>
                                                                                                                <option @if($row['option_value_status'][$key2] == 'in-active') selected
                                                                                                                        @endif value="in-active">{{__('In Active')}}</option>
                                                                                                                <option @if($row['option_value_status'][$key2] == 'deleted') selected
                                                                                                                        @endif value="deleted">{{__('Delete')}}</option>
                                                                                                            </select>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    {!! formError($errors,'option.'.$key.'.option_value_status.'.$key2) !!}
                                                                                                </div>



                                                                                            @endif


                                                                                        </div>
                                                                                    @endforeach

                                                                                </div>


                                                                            @else

                                                                                <div id="option_value_list_{{$key}}"
                                                                                     style="display: none">
                                                                                    <div style="text-align: right;"
                                                                                         class="col-sm-6">
                                                                                        <button type="button"
                                                                                                class="btn btn-primary fa fa-plus add_option_value_button"
                                                                                                onclick="add_option_value({{$key}})">
                                                                                            <span>{{__('Add Value')}}</span>
                                                                                        </button>
                                                                                    </div>

                                                                                </div>

                                                                            @endif


                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            @endforeach


                                                        @elseif(isset($result->id))

                                                            @if($result->option)

                                                                @foreach($result->option()->where('status','!=','deleted')->get() as $key => $row)

                                                                    <div role="tabpanel"
                                                                         class="tab-pane option_div_row option_div_row_{{$row['id']}}"
                                                                         id="tabv_{{$row['id']}}"
                                                                         aria-expanded="true"
                                                                         aria-labelledby="tab_{{$row['id']}}">

                                                                        <div class="col-sm-10"></div>
                                                                        <input type="hidden"
                                                                               name="option[{{$row['id']}}][template_option]"
                                                                               value="new">
                                                                        <input type="hidden"
                                                                               name="option[{{$row['id']}}][id]"
                                                                               value="{{$row['id']}}">

                                                                        <div class="form-group col-sm-2{!! formError($errors,'option.'.$row['id'].'.option_sort',true) !!}">
                                                                            <div class="controls">
                                                                                {!! Form::label('option_sort', __('Sort').':') !!}
                                                                                <div class="input-group input-group-lg">
                                                                                    {!! Form::number('option['.$row['id'].'][option_sort]',$row['sort'],['class'=>'form-control option_sort']) !!}

                                                                                </div>
                                                                            </div>
                                                                            {!! formError($errors,'option.'.$row['id'].'.option_sort') !!}
                                                                        </div>

                                                                        <div id="new_option">
                                                                            <div class="form-group col-sm-6{!! formError($errors,'option.'.$row['id'].'.option_name_ar',true) !!}">
                                                                                <div class="controls">
                                                                                    {!! Form::label('option_name_ar', __('Name Ar').':') !!}
                                                                                    <div class="input-group input-group-lg">
                                                                                        {!! Form::text('option['.$row['id'].'][option_name_ar]',$row['name_ar'],['class'=>'form-control option_name_ar']) !!}
                                                                                    </div>
                                                                                </div>
                                                                                {!! formError($errors,'option.'.$row['id'].'.option_name_ar') !!}
                                                                            </div>

                                                                            <div class="form-group col-sm-6{!! formError($errors,'option.'.$row['id'].'.option_name_en',true) !!}">
                                                                                <div class="controls">
                                                                                    {!! Form::label('option_name_en', __('Name En').':') !!}
                                                                                    <div class="input-group input-group-lg">
                                                                                        {!! Form::text('option['.$row['id'].'][option_name_en]',$row['name_en'],['class'=>'form-control option_name_en']) !!}

                                                                                    </div>
                                                                                </div>
                                                                                {!! formError($errors,'option.'.$row['id'].'.option_name_en') !!}
                                                                            </div>



                                                                            <div class="form-group col-sm-4{!! formError($errors,'option.'.$row['id'].'.option_is_required',true) !!}">
                                                                                <div class="controls">
                                                                                    {!! Form::label('option_is_required', __('Is Required').':') !!}
                                                                                    <div class="input-group input-group-lg">
                                                                                        {!! Form::select('option['.$row['id'].'][option_is_required]',['yes'=>__('Yes'),'no'=>__('No')],$row['is_required'],['class'=>'form-control option_is_required']) !!}

                                                                                    </div>
                                                                                </div>
                                                                                {!! formError($errors,'option.'.$row['id'].'.option_is_required') !!}
                                                                            </div>

                                                                            <div class="form-group col-sm-4{!! formError($errors,'option.'.$row['id'].'.option_type',true) !!}">
                                                                                <div class="controls">
                                                                                    {!! Form::label('option_type', __('Type').':') !!}
                                                                                    <div class="input-group input-group-lg">
                                                                                        {!! Form::select('option['.$row['id'].'][option_type]',['text'=>__('Text'),'textarea'=>__('Text Area'),'select'=>__('Select'),'radio'=>__('Radio'),'check'=>__('Check Box')],$row['type'],['class'=>'form-control option_type','onchange'=>'check_option_type('.$row['id'].')','id'=>'option_type_'.$row['id']]) !!}

                                                                                    </div>
                                                                                </div>
                                                                                {!! formError($errors,'option.'.$row['id'].'.option_type') !!}
                                                                            </div>

                                                                            <div class="form-group col-sm-4{!! formError($errors,'option.'.$row['id'].'.option_status',true) !!}">
                                                                                <div class="controls">
                                                                                    {!! Form::label('option_status', __('Status').':') !!}
                                                                                    <div class="input-group input-group-lg">
                                                                                        {!! Form::select('option['.$row['id'].'][option_status]',['active'=>__('Active'),'in-active'=>__('In Active'),'deleted'=>__('Deleted')],$row['status'],['class'=>'form-control option_status']) !!}
                                                                                    </div>
                                                                                </div>
                                                                                {!! formError($errors,'option.'.$row['id'].'.option_status') !!}
                                                                            </div>

                                                                            @if( $row['type'] == 'select' || $row['type'] == 'radio' || $row['type'] == 'check' )


                                                                                <div id="option_value_list_{{$row['id']}}">
                                                                                    <div style="text-align: right;"
                                                                                         class="col-sm-6">
                                                                                        <button type="button"
                                                                                                class="btn btn-primary fa fa-plus add_option_value_button"
                                                                                                onclick="add_option_value({{$row['id']}})">
                                                                                            <span>{{__('Add Value')}}</span>
                                                                                        </button>
                                                                                    </div>


                                                                                    @foreach($row->values()->where('status','!=','deleted')->get() as $key2 => $value )
                                                                                        <input type="hidden"
                                                                                               name="option[{{$row['id']}}][option_value_id][]"
                                                                                               value="{{$value['id']}}">

                                                                                        <div class="option_value_row"
                                                                                             style="width: 100%;display: table;">
                                                                                            <div class="form-group col-sm-3{!! formError($errors,'option.'.$row['id'].'.option_value_name_ar.'.$value['id'],true) !!}">
                                                                                                <div class="controls">
                                                                                                    {!! Form::label('option_value_name_ar', __('Name Ar').':') !!}
                                                                                                    <div class="input-group input-group-lg">
                                                                                                        {!! Form::text('option['.$row['id'].'][option_value_name_ar][]',$value['name_ar'],['class'=>'form-control option_value_name_ar']) !!}
                                                                                                    </div>
                                                                                                </div>
                                                                                                {!! formError($errors,'option.'.$row['id'].'.option_value_name_ar.'.$key2) !!}
                                                                                            </div>
                                                                                            <div class="form-group col-sm-3{!! formError($errors,'option.'.$row['id'].'.option_value_name_en.'.$value['id'],true) !!}">
                                                                                                <div class="controls">
                                                                                                    {!! Form::label('option_value_name_en', __('Name En').':') !!}
                                                                                                    <div class="input-group input-group-lg">
                                                                                                        {!! Form::text('option['.$row['id'].'][option_value_name_en][]',$value['name_en'],['class'=>'form-control option_value_name_en']) !!}
                                                                                                    </div>
                                                                                                </div>
                                                                                                {!! formError($errors,'option.'.$row['id'].'.option_value_name_en.'.$value['id']) !!}
                                                                                            </div>
                                                                                            <div class="form-group col-sm-2{!! formError($errors,'option.'.$row['id'].'.option_value_price_prefix.'.$value['id'],true) !!}">
                                                                                                <div class="controls">
                                                                                                    {!! Form::label('option_value_price_prefix', __('Prefix').':') !!}
                                                                                                    <div class="input-group input-group-lg">
                                                                                                        <select name="option[{{$row->id}}][option_value_price_prefix][]"
                                                                                                                id=""
                                                                                                                class="form-control option_value_price_prefix">
                                                                                                            <option @if($value['price_prefix'] == '+') selected
                                                                                                                    @endif value="+">
                                                                                                                +
                                                                                                            </option>
                                                                                                            <option @if($value['price_prefix'] == '-') selected
                                                                                                                    @endif value="-">
                                                                                                                -
                                                                                                            </option>
                                                                                                        </select>
                                                                                                    </div>
                                                                                                </div>
                                                                                                {!! formError($errors,'option.'.$row['id'].'.option_value_price_prefix.'.$value['id']) !!}
                                                                                            </div>
                                                                                            <div class="form-group col-sm-2{!! formError($errors,'option.'.$row['id'].'.option_value_price.'.$value['id'],true) !!}">
                                                                                                <div class="controls">
                                                                                                    {!! Form::label('option_value_price', __('Price').':') !!}
                                                                                                    <div class="input-group input-group-lg">
                                                                                                        {!! Form::text('option['.$row['id'].'][option_value_price][]',$value['price'],['class'=>'form-control option_value_price']) !!}
                                                                                                    </div>
                                                                                                </div>
                                                                                                {!! formError($errors,'option.'.$row['id'].'.option_value_price.'.$value['id']) !!}
                                                                                            </div>

                                                                                            <div class="form-group col-sm-2{!! formError($errors,'option.'.$row['id'].'.option_value_status.'.$value['id'],true) !!}">
                                                                                                <div class="controls">
                                                                                                    {!! Form::label('option_value_status', __('Status').':') !!}
                                                                                                    <div class="input-group input-group-lg">
                                                                                                        <select name="option[{{$row->id}}][option_value_status][]"
                                                                                                                id=""
                                                                                                                class="form-control option_value_status">
                                                                                                            <option @if($value['status'] == 'active') selected
                                                                                                                    @endif value="active">{{__('Active')}}</option>
                                                                                                            <option @if($value['status'] == 'in-active') selected
                                                                                                                    @endif value="in-active">{{__('In Active')}}</option>
                                                                                                            <option @if($value['status'] == 'deleted') selected
                                                                                                                    @endif value="deleted">{{__('Delete')}}</option>
                                                                                                        </select></div>
                                                                                                </div>
                                                                                                {!! formError($errors,'option.'.$row['id'].'.option_value_status.'.$value['id']) !!}
                                                                                            </div>


                                                                                        </div>
                                                                                    @endforeach

                                                                                </div>


                                                                            @else

                                                                                <div id="option_value_list_{{$row['id']}}"
                                                                                     style="display: none">
                                                                                    <div style="text-align: right;"
                                                                                         class="col-sm-6">
                                                                                        <button type="button"
                                                                                                class="btn btn-primary fa fa-plus add_option_value_button"
                                                                                                onclick="add_option_value({{$row['id']}})">
                                                                                            <span>{{__('Add Value')}}</span>
                                                                                        </button>
                                                                                    </div>

                                                                                </div>

                                                                            @endif


                                                                        </div>

                                                                    </div>
                                                                @endforeach
                                                            @endif

                                                        @endif
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div id="option_temp" style="display: none">

                                        <div id="option_li_temp">
                                            <li class="nav-item option_li_row ">
                                                <a id="remove_option" href="javascript:void(0);"
                                                   style="display: inline;"
                                                   onclick="remove_option()"
                                                   class="text-danger">
                                                    <i class="fa fa-lg fa-trash mt-3"></i>
                                                </a>
                                                <a style="display: inline;" class="nav-link option_a_row " id="tab_1"
                                                   data-toggle="tab"
                                                   aria-controls="tabv_1" href="#tabv_1" aria-expanded="true">Tab 1</a>

                                            </li>
                                        </div>

                                        <div id="option_div_temp">
                                            <div role="tabpanel" class="tab-pane option_div_row" id="tabv_1"
                                                 aria-expanded="true" aria-labelledby="tab_1">

                                                <div class="form-group col-sm-10{!! formError($errors,'template_option',true) !!}">
                                                    <div class="controls">
                                                        {!! Form::label('template_option', __('Template Option').':') !!}
                                                        <div class="input-group input-group-lg">
                                                            {!! Form::select('',[''=>__('Select Option'),'new'=>__('New Option')],'',['id'=>'template_option','class'=>'form-control template_option']) !!}
                                                        </div>
                                                    </div>
                                                    {!! formError($errors,'template_option') !!}
                                                </div>

                                                <div class="form-group col-sm-2{!! formError($errors,'option_sort',true) !!}">
                                                    <div class="controls">
                                                        {!! Form::label('option_sort', __('Sort').':') !!}
                                                        <div class="input-group input-group-lg">
                                                            {!! Form::number('',isset($result->id) ? $result->option_sort:old('option_sort'),['class'=>'form-control option_sort']) !!}

                                                        </div>
                                                    </div>
                                                    {!! formError($errors,'option_sort') !!}
                                                </div>


                                                <div id="new_option" style="display: none">
                                                    <div class="form-group col-sm-6{!! formError($errors,'option_name_ar',true) !!}">
                                                        <div class="controls">
                                                            {!! Form::label('option_name_ar', __('Name Ar').':') !!}
                                                            <div class="input-group input-group-lg">
                                                                {!! Form::text('','',['class'=>'form-control option_name_ar']) !!}

                                                            </div>
                                                        </div>
                                                        {!! formError($errors,'option_name_ar') !!}
                                                    </div>
                                                    <div class="form-group col-sm-6{!! formError($errors,'option_name_en',true) !!}">
                                                        <div class="controls">
                                                            {!! Form::label('option_name_en', __('Name En').':') !!}
                                                            <div class="input-group input-group-lg">
                                                                {!! Form::text('','',['class'=>'form-control option_name_en']) !!}

                                                            </div>
                                                        </div>
                                                        {!! formError($errors,'option_name_ar') !!}
                                                    </div>

                                                    <div class="form-group col-sm-6{!! formError($errors,'option_is_required',true) !!}">
                                                        <div class="controls">
                                                            {!! Form::label('option_is_required', __('Is Required').':') !!}
                                                            <div class="input-group input-group-lg">
                                                                {!! Form::select('',['yes'=>__('Yes'),'no'=>__('No')],'',['class'=>'form-control option_is_required']) !!}

                                                            </div>
                                                        </div>
                                                        {!! formError($errors,'option_is_required') !!}
                                                    </div>
                                                    <div class="form-group col-sm-6{!! formError($errors,'option_type',true) !!}">
                                                        <div class="controls">
                                                            {!! Form::label('option_type', __('Type').':') !!}
                                                            <div class="input-group input-group-lg">
                                                                {!! Form::select('',['text'=>__('Text'),'textarea'=>__('Text Area'),'select'=>__('Select'),'radio'=>__('Radio'),'check'=>__('Check Box'),'date'=>__('Date'),'datetime'=>__('Datetime'),'location'=>__('Location')],'',['class'=>'form-control option_type']) !!}

                                                            </div>
                                                        </div>
                                                        {!! formError($errors,'option_type') !!}
                                                    </div>


                                                    <div id="option_value_list" style="display: none">
                                                        <div style="text-align: right;" class="col-sm-6">
                                                            <button type="button"
                                                                    class="btn btn-primary fa fa-plus add_option_value_button"
                                                                    onclick="add_option_value()">
                                                                <span>{{__('Add Value')}}</span>
                                                            </button>
                                                        </div>


                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="option_value_temp">
                                            <div class="option_value_row" style="width: 100%;display: table;">
                                                <div class="form-group col-sm-3{!! formError($errors,'option_value_name_ar',true) !!}">
                                                    <div class="controls">
                                                        {!! Form::label('option_value_name_ar', __('Name Ar').':') !!}
                                                        <div class="input-group input-group-lg">
                                                            {!! Form::text('','',['class'=>'form-control option_value_name_ar']) !!}
                                                        </div>
                                                    </div>
                                                    {!! formError($errors,'option_value_name_ar') !!}
                                                </div>
                                                <div class="form-group col-sm-3{!! formError($errors,'option_value_name_en',true) !!}">
                                                    <div class="controls">
                                                        {!! Form::label('option_value_name_en', __('Name En').':') !!}
                                                        <div class="input-group input-group-lg">
                                                            {!! Form::text('','',['class'=>'form-control option_value_name_en']) !!}
                                                        </div>
                                                    </div>
                                                    {!! formError($errors,'option_value_name_en') !!}
                                                </div>
                                                <div class="form-group col-sm-2{!! formError($errors,'option_value_price_prefix',true) !!}">
                                                    <div class="controls">
                                                        {!! Form::label('option_value_price_prefix', __('Prefix').':') !!}
                                                        <div class="input-group input-group-lg">
                                                            {!! Form::select('',['+'=>'+','-'=>'-'],'',['class'=>'form-control option_value_price_prefix']) !!}
                                                        </div>
                                                    </div>
                                                    {!! formError($errors,'option_value_price_prefix') !!}
                                                </div>
                                                <div class="form-group col-sm-3{!! formError($errors,'option_value_price',true) !!}">
                                                    <div class="controls">
                                                        {!! Form::label('option_value_price', __('Price').':') !!}
                                                        <div class="input-group input-group-lg">
                                                            {!! Form::number('','',['class'=>'form-control option_value_price']) !!}
                                                        </div>
                                                    </div>
                                                    {!! formError($errors,'option_value_price') !!}
                                                </div>
                                                <div class="form-group col-sm-1">

                                                    <a href="javascript:void(0);"
                                                       onclick="$(this).closest('.option_value_row').remove();"
                                                       class="text-danger">
                                                        <i class="fa fa-lg fa-trash mt-3"></i>
                                                    </a>
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </fieldset>

                            <!-- Step 5 -->
                            <h6>{{__('Attributes')}}</h6>
                            <fieldset style="padding: 0px;">


                                {{--<div class="card-header">--}}
                                    {{--<h2>{{__('Attributes')}}</h2>--}}
                                {{--</div>--}}

                                <div class="col-xl-12 col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">{{__('Attribute')}}</h4>
                                        </div>
                                        <div class="card-content">
                                            <div class="card-body">

                                                <div class="nav-vertical">

                                                    <div id="attribute_div_list" class="tab-content px-1">

                                                        @if(isset($result->id))

                                                            @if($current_attribute)
                                                                {{dd($current_attribute)}}
                                                                @foreach($current_attribute as $key=>$value)

                                                                    <div class="attribute_div_row attribute_div_row_{{$value->id}}">
                                                                        <div class="form-group col-sm-10{!! formError($errors,'attribute.'.$value->id,true) !!}">
                                                                            <div class="controls">
                                                                                {!! Form::label('attribute',$value->name_ar) !!}
                                                                                <div class="input-group input-group-lg">
                                                                                    @if($value->type == 'text')
                                                                                        {!! Form::text('attribute['.$value->id.']',$value->value,['id'=>'attribute_'.$value->id,'class'=>'form-control']) !!}
                                                                                        @elseif($value->type == 'number')
                                                                                            {!! Form::number('attribute['.$value->id.']',$value->value,['id'=>'attribute_'.$value->id,'class'=>'form-control']) !!}
                                                                                    @elseif($value->type == 'textarea')
                                                                                        {!! Form::textarea('attribute['.$value->id.']',$value->value,['id'=>'attribute_'.$value->id,'class'=>'form-control']) !!}
                                                                                    @elseif($value->type == 'select')
                                                                                        {!! Form::select('attribute['.$value->id.']',[''=>__('Select Attribute')]+array_column( $value->values->toArray(),'name_ar','id'),$value->value,['id'=>'attribute_'.$value->id,'class'=>'form-control newselect2']) !!}
                                                                                    @elseif($value->type == 'multi-select')
                                                                                        <select name="attribute[{{$value->id}}][]"
                                                                                                id="" multiple
                                                                                                class="form-control newselect2">
                                                                                            <option value="">{{__('Select Attribute')}}</option>
                                                                                            @foreach($value->values()->select('*','name_'.\DataLanguage::get().' as name')->get() as $oneval )
                                                                                                <option @if(is_array(explode(',',$value->value)))  @if(in_array($oneval->id,explode(',',$value->value))) selected
                                                                                                        @endif  @endif value="{{$oneval->id}}">{{$oneval->name}}</option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    @endif


                                                                                </div>
                                                                            </div>
                                                                            {!! formError($errors,'attribute.'.$value->id.'.template_attribute') !!}
                                                                        </div>
                                                                    </div>

                                                                @endforeach
                                                            @endif

                                                        @endif
                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                    </div>


                                </div>


                            </fieldset>


                    {!! Form::close() !!}
                </section>
                <!--/ Javascript sourced data -->
            </div>
        </div>
    </div>

    <!-- ////////////////////////////////////////////////////////////////////////////-->
@endsection
@section('footer')
    <script src="{{asset('assets/system/js/scripts')}}/custom/custominput.js"></script>
    <script src="{{asset('assets/system')}}/vendors/js/forms/select/select2.full.min.js"
            type="text/javascript"></script>
    <script src="{{asset('assets/system')}}/js/scripts/select2/select2.custom.js" type="text/javascript"></script>
    <script src="{{asset('assets/system')}}/vendors/js/extensions/jquery.steps.min.js" type="text/javascript"></script>
    <script src="{{asset('assets/system')}}/js/scripts/forms/wizard-steps.js" type="text/javascript"></script>


    <script src="{{asset('assets/system')}}/SimpleAjaxUploader.js"></script>



    <script>

        $(document).ready(function () {

            $(function(){
                $('.datepicker').datetimepicker({
                    viewMode: 'months',
                    format: 'YYYY-MM-DD'
                });
            });

            $(function(){
                $('.dateTimepicker').datetimepicker({
                    viewMode: 'months',
                    format: 'YYYY-MM-DD HH::MM:SS'
                });
            });

            @if(isset($result->id))
                get_merchant_template_option()
                    @endif


            var btn = document.getElementById('uploadBtn'),
                progressBar = document.getElementById('progressBar'),
                progressOuter = document.getElementById('progressOuter'),
                msgBox = document.getElementById('msgBox');


            var uploader = new ss.SimpleUpload({
                button: btn,
                url: '{{route('upload-temp-image')}}',
                name: 'uploadfile',
                multipart: true,
                hoverClass: 'hover',
                focusClass: 'focus',
                responseType: 'json',
                data: {
                    '_token': $('[name=csrf-token]').attr('content'),
                    'temp_id': '@if(!empty(old('temp_id'))) {{old('temp_id')}} @else {{$temp_id}} @endif'
                },
                startXHR: function () {
                    progressOuter.style.display = 'block'; // make progress bar visible
                    this.setProgressBar(progressBar);
                },
                onSubmit: function () {
                    msgBox.innerHTML = ''; // empty the message box
                    btn.innerHTML = 'Uploading...'; // change button text to "Uploading..."
                },
                onComplete: function (filename, response) {
                    btn.innerHTML = 'Choose Another File';
                    progressOuter.style.display = 'none'; // hide progress bar when upload is completed

                    if (!response) {
                        msgBox.innerHTML = 'Unable to upload file';
                        return;
                    }

                    if (response.success === true) {
                        if (response.data) {
                            var length = $('#image_list .image_row').length;
                            var img = $('#image_temp_tag').clone();
                            img.find('.image_row').addClass('image_row_' + length);
                            img.find('.image_tag').attr('src', response.data.path);
                            //img.find('.image_id').attr('name',response.data.image_id).attr('id','image_id_'+length);
                            img.find('.delete_image_btn').attr('onclick', 'remove_image(' + length + ',' + response.data.image_id + ')');
                            $('#image_list').append(img.html());
                        }
                        msgBox.innerHTML = '<strong>' + escapeTags(filename) + '</strong>' + ' successfully uploaded.';
                    } else {
                        if (response.msg) {
                            msgBox.innerHTML = escapeTags(response.msg);
                        } else {
                            msgBox.innerHTML = 'An error occurred and the upload failed.';
                        }
                    }
                },
                onError: function () {
                    progressOuter.style.display = 'none';
                    msgBox.innerHTML = 'Unable to upload file';
                }
            });

        });

        ajaxSelect2('#user_id', 'user');

        function remove_image(number, id) {

            $.post('{{route('product-remove-image')}}', {'id': id}, function (out) {
                if (out.status == true)
                    $('.image_row_' + number).remove();
            }, 'json');
        }

        function escapeTags(str) {
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;');
        }


        //        function price_type() {
        //            $('#price_multi').css('display', 'block');
        //        }
        function remove_option(number) {
            $('.option_li_row_' + number).remove();
            $('.option_div_row_' + number).remove();
        }

     
        function get_merchant_template_option() {
            var value = $('#user_id').val();
            var category_id = $('#item_category_id').val();
            if (value || category_id) {
            $.get('{{route('product-template-option')}}', {'user_id': value,'category_id':category_id}, function (out) {
                $('#template_option').html(out);
            });

        }
        }


        function choose_template_new(number) {
            var value = $('#template_option_' + number).val();
            if (value == 'new') {
                $('#new_option_' + number).show();
            } else {
                $('#new_option_' + number).hide();
            }
        }

        function add_option_value(number) {
            var length = $('#option_value_list_' + number).length;
            var clonedRow = $('#option_value_temp').clone();
            clonedRow.find('.option_value_name_ar').attr('name', 'option[' + number + '][option_value_name_ar][]');
            clonedRow.find('.option_value_name_en').attr('name', 'option[' + number + '][option_value_name_en][]');
            clonedRow.find('.option_value_price_prefix').attr('name', 'option[' + number + '][option_value_price_prefix][]');
            clonedRow.find('.option_value_price').attr('name', 'option[' + number + '][option_value_price][]');
            clonedRow.find('.option_value_status').attr('name', 'option[' + number + '][option_value_status][]');
            $('#option_value_list_' + number).append(clonedRow.html());
        }

        function check_option_type(number) {
            var value = $('#option_type_' + number).val();
            if (value == 'select' || value == 'radio' || value == 'check') {
                $('#option_value_list_' + number).show();
            } else {
                $('#option_value_list_' + number).hide();
            }
        }

        function change_tab(number) {
            console.log(number);
            $('.option_div_row').css('display', 'none');
            $('.option_div_row_' + number).css('display', 'block');
            $('.option_a_row').removeClass('active');
            $('#tab_' + number).addClass('active');
        }
var publicLength = $('#option_li_list li').length;

        function add_option() {
            var li_length = publicLength;
            var cloned_li_row = $('#option_li_temp').clone();
            var number = +li_length + 1;
            cloned_li_row.find('.option_a_row').attr('id', 'tab_' + li_length).attr('aria-controls', 'tabv_' + li_length)
                .attr('href', '#tabv_' + li_length).html('Option ' + number).attr('onclick', 'change_tab(' + li_length + ')');
            cloned_li_row.find('#remove_option').attr('id', 'remove_option_' + li_length).attr('onclick', 'remove_option(' + li_length + ')');
            cloned_li_row.find('.option_li_row').addClass('option_li_row_' + li_length);

            var clone_div_row = $('#option_div_temp').clone();

            clone_div_row.find('.option_div_row').attr('id', '#tabv_' + li_length).attr('aria-labelledby', 'tab_' + li_length)
                .addClass('option_div_row_' + li_length);
            clone_div_row.find('#new_option').attr('id', 'new_option_' + li_length);
            clone_div_row.find('#option_value_list').attr('id', 'option_value_list_' + li_length);
            clone_div_row.find('.add_option_value_button').attr('onclick', 'add_option_value(' + li_length + ')');

            clone_div_row.find('.template_option').attr('onchange', 'choose_template_new(' + li_length + ')').attr('id', 'template_option_' + li_length).attr('name', 'option[' + li_length + '][template_option]');
            clone_div_row.find('.option_sort').attr('id', 'option_sort' + li_length).attr('name', 'option[' + li_length + '][option_sort]');
            clone_div_row.find('.option_type').attr('id', 'option_type_' + li_length).attr('onchange', 'check_option_type(' + li_length + ')').attr('name', 'option[' + li_length + '][option_type]');
            clone_div_row.find('.option_name_ar').attr('name', 'option[' + li_length + '][option_name_ar]');
            clone_div_row.find('.option_name_en').attr('name', 'option[' + li_length + '][option_name_en]');
            clone_div_row.find('.option_min_select').attr('name', 'option[' + li_length + '][option_min_select]');
            clone_div_row.find('.option_max_select').attr('name', 'option[' + li_length + '][option_max_select]');
            clone_div_row.find('.option_is_required').attr('name', 'option[' + li_length + '][option_is_required]');
            clone_div_row.find('.option_status').attr('name', 'option[' + li_length + '][option_status]');


            $('#option_li_list').append(cloned_li_row.html());
            $('#option_div_list').append(clone_div_row.html());
            change_tab(li_length);
            publicLength = +publicLength + 1;
        }

        function add_image() {
            var length = $('#image_list').length;
            var clonedRow = $('#image_temp').clone();
            clonedRow.find('.image').attr('id', 'image_' + length).attr('name', 'image[]');
            clonedRow.find('.image_title').attr('id', 'image_title_' + length).attr('name', 'image_title[]');
            $('#image_list').append(clonedRow.html());
        }


        function remove_attribute(number) {
            $('.attribute_li_row_' + number).remove();
            $('.attribute_div_row' + number).remove();
        }

        {{--function get_merchant_template_attribute() {--}}
            {{--var value = $('#user_id').val();--}}
            {{--$.get('{{route('product-template-attribute')}}', {'user_id': value}, function (out) {--}}
                {{--$('#template_attribute').html(out);--}}
            {{--});--}}

        {{--}--}}

        function choose_attribute_template_new(number) {
            var value = $('#template_attribute_' + number).val();
            if (value == 'new') {
                $('#new_attribute_' + number).show();
            } else {
                $('#new_attribute_' + number).hide();
            }
        }
        function change_attribute_tab(number) {
            $('.attribute_div_row').css('display', 'none');
            $('.attribute_div_row_' + number).css('display', 'block');
            $('.attribute_a_row').removeClass('active');
            $('#attribute_tab_' + number).addClass('active');
        }
function  get_item_type() {
    var item_type_id = $('#item_type_id').val();
   // console.log(item_type_id);
    $.post('{{route('system.check-item-type')}}', {'item_type_id': item_type_id}, function (out) {
        console.log(out.status);
        if (out.status == true) {
            $('#item_type_div').show();
        } else {
            $('#item_type_div').hide();
        }


});
}
        function get_attribute() {
            $(function(){
                $('.datepicker').datetimepicker({
                    viewMode: 'months',
                    format: 'YYYY-MM-DD'
                });
            });
            $(function(){
                $('.dateTimepicker').datetimepicker({
                    viewMode: 'months',
                    format: 'YYYY-MM-DD HH::MM:SS'
                });
            });
            var item_type_id = $('#item_type_id').val();
            var category_id = $('#item_category_id').val();
            if (item_type_id && category_id) {
                $.post('{{route('attribute.get-attribute')}}', {'category_id': category_id,'item_type_id': item_type_id}, function (out) {
                    if (out.status == false) {
                        $('#attribute_div_list').empty();
                        //  toastr.error(out.msg, 'Error', {"closeButton": true});
                    } else {
                        $('#attribute_div_list').html('');
                        for (var i = 0; i < out.data.length; i++) {
                            var attribute = out.data[i];
                            var drow = $('<div>').attr('class', 'attribute_row');
                            drow.append($('<label>').html(attribute.name));
                            if (attribute.type == 'text') {
                                drow.append($('<input>', {
                                    name: 'attribute[' + attribute.id + ']',
                                    class: 'form-control'
                                }));
                            }
                            else if (attribute.type == 'number') {
                                drow.append($('<input>', {
                                    name: 'attribute[' + attribute.id + ']',
                                    class: 'form-control',
                                    type:'number'
                                }));
                            } else if (attribute.type == 'file') {
                                drow.append($('<input>', {
                                    name: 'attribute[' + attribute.id + ']',
                                    type: 'file',
                                    class: 'form-control'
                                }));
                            } else if (attribute.type == 'date') {
                                    drow.append($('<input>', {
                                        name: 'attribute[' + attribute.id + ']',
                                        type:'text',
                                        class: 'form-control datepicker'
                                    }));
                                }else if (attribute.type == 'datetime') {
                                    drow.append($('<input>', {
                                        name: 'attribute[' + attribute.id + ']',
                                        type:'text',
                                        class: 'form-control dateTimepicker'
                                    }));
                                }
                            else if (attribute.type == 'file') {
                                    drow.append($('<input>', {
                                        name: 'attribute[' + attribute.id + ']',
                                        class: 'form-control'
                                    }));
                            } else if (attribute.type == 'textarea') {
                                drow.append($('<textarea>', {
                                    name: 'attribute[' + attribute.id + ']',
                                    class: 'form-control'
                                }));
                            } else if (attribute.type == 'select') {
                                var select = $('<select>', {
                                    name: 'attribute[' + attribute.id + ']',
                                    style :'width:100%;',
                                    class: 'form-control',
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
                            }
                            else if (attribute.type == 'multi_select') {
                                var select = $('<select>', {
                                    name: 'attribute[' + attribute.id + '][]',
                                    class: 'form-control select2',
                                    multiple: '',
                                    style :'width:100%;',
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
                            }


                            $('#attribute_div_list').append(drow);
                            $('#attribute_' + attribute.id).select2();
                        }
                    }

                }, 'json');
            }
        }


        $('#itemForm').submit(function (e) {
            e.preventDefault();

            $.post('{{route('system.item.store')}}', $('#itemForm').find(":input").serialize(), function (out) {
                $('.validation_error_msg').remove();
                $('.product_row').css('border-color', '#aaa');


                if (out.status == false) {
                    toastr.error(out.msg, 'Error', {"closeButton": true});
                    if (out.data) {
                        $.each(out.data, function (index, value) {
                            $('[for="' + index + '"]').html($('[for="' + index + '"]').html() + '  <p style="color:red;display: inline-block;" class="validation_error_msg" id="error_msg_' + index + '" >' + value + '</p>');

                        });
                    }
                }else {
                    toastr.success(out.msg, 'Success', {"closeButton": true});
                   $('#itemForm')[0].reset();
                  //  setTimeout(location.reload,3000);
                }
            }, 'json')


        });


        $(function () {
            var observer = new MutationObserver(function (mutations) {
                if ($('#map-events').is(':visible')) {
                    if (!mapInitilized) {
                        google.maps.event.trigger(map, 'resize');
                        mapInitilized = true;
                    }
                }
            });
            observer.observe(document.queryeslector('#steps-uid-0-p-1'), {
                attributes: true
            });
        });


    </script>
@endsection