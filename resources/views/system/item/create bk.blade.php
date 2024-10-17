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
                                            {{--{{print_r($errors->all())}}--}}
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
                        {!! Form::open(['route' => isset($result->id) ? ['merchant.product.update',$result->id]:'merchant.product.store','files'=>true, 'method' => isset($result->id) ?  'PATCH' : 'POST','class'=>'number-tab-steps wizard-circle']) !!}
                        <!-- Step 1 -->
                            <h6>{{__('Information')}}</h6>
                            <fieldset style="padding: 0px;">
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h2>{{__('Information')}}</h2>
                                        </div>
                                        <div class="card-block card-dashboard">
                                            <div class="form-group col-sm-6 {!! formError($errors,'merchant_id',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('merchant_id', __('Merchant').':') !!}
                                                    @if(isset($merchantData))
                                                        {!! Form::text('merchant_text', $merchantData->{'name_'.\DataLanguage::get()}.' #ID: '.$merchantData->id,['class'=>'form-control','readonly'=>'readonly']) !!}
                                                        {!! Form::hidden('merchant_id',null,['id'=>'new_merchant_id']) !!}
                                                    @else
                                                        @if(isset($result->id))
                                                            {!! Form::select('merchant_id',[$result->merchant->id => $result->merchant->{'name_'.\DataLanguage::get()}.' #ID: '.$result->merchant_id],isset($result->id) ? $result->merchant_id:old('merchant_id'),['class'=>'select2 form-control','onchange'=>'get_merchant_template_option()','id'=>'merchant_id']) !!}
                                                        @else
                                                            {!! Form::select('merchant_id',(isset($current_merchant)) ? [old('merchant_id')=>$current_merchant->{'name_'.\DataLanguage::get()}.' #ID:'.$current_merchant->id] : [__('Select Merchant')],old('merchant_id'),['style'=>'width: 100%;','class'=>'select2 form-control','onchange'=>'get_merchant_template_option();get_merchant_template_attribute()']) !!}
                                                        @endif
                                                    @endif
                                                </div>
                                                {!! formError($errors,'merchant_id') !!}
                                            </div>
                                            <div class="form-group col-sm-6{!! formError($errors,'merchant_product_category_id',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('merchant_product_category_id', __('Product Category').':') !!}
                                                    @if(isset($result->id))
                                                        {!! Form::select('merchant_product_category_id',$MerchantProductCategory,isset($result->id) ? $result->merchant_product_category_id:old('merchant_product_category_id'),['class'=>'select2 form-control','id'=>'category_id','onchange'=>'get_attribute();get_merchant_template_option()']) !!}
                                                    @else
                                                        {!! Form::select('merchant_product_category_id',$MerchantProductCategory,old('merchant_product_category_id'),['class'=>'select2 form-control','id'=>'category_id','onchange'=>'get_attribute();get_merchant_template_option()']) !!}
                                                    @endif
                                                </div>
                                                {!! formError($errors,'merchant_product_category_id') !!}
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
                                                    {!! Form::label('name_en', __('Product Name (English)').':') !!}
                                                    {!! Form::text('name_en',isset($result->id) ? $result->name_en:old('name_en'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'name_en') !!}
                                            </div>
                                            <div class="form-group col-sm-12{!! formError($errors,'description_en',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('description_en', __('Product description (English)').':') !!}
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
                                                    {!! Form::label('name_ar', __('Product Name (Arabic)').':') !!}
                                                    {!! Form::text('name_ar',isset($result->id) ? $result->name_ar:old('name_ar'),['class'=>'form-control ar']) !!}
                                                </div>
                                                {!! formError($errors,'name_ar') !!}
                                            </div>
                                            <div class="form-group col-sm-12{!! formError($errors,'description_ar',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('description_ar', __('Product description (Arabic)').':') !!}
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
                                            <div class="form-group col-sm-6{!! formError($errors,'price',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('price', __('Price').':') !!}
                                                    {!! Form::number('price',isset($result->id) ? $result->price:old('price'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'status') !!}
                                            </div>
                                            <div class="form-group col-sm-6{!! formError($errors,'status',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('status', __('Status').':') !!}
                                                    {!! Form::select('status',['active'=>__('Active'),'in-active'=>__('In-Active')],isset($result->id) ? $result->status:old('status'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'status') !!}
                                            </div>


                                            <div class="form-group col-sm-12{!! formError($errors,'Tax',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('tax', __('Tax').':') !!}
                                                    {!! Form::select('tax_ids[]',[''=>__('Select Tax')]+$tax,isset($result->id) ? explode(',',$result->tax_ids):old('tax'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'tax') !!}
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
                                                @if(formError($errors,'file.*',true))
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

                                <div class="card-header">
                                    <h2>{{__('Options')}}</h2>
                                </div>
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
                                                                    <a id="remove_option_{{$key}}"
                                                                       href="javascript:void(0);"
                                                                       style="display: inline;"
                                                                       onclick="remove_option('{{$key}}')"
                                                                       class="text-danger">
                                                                        <i class="fa fa-lg fa-trash mt-3"></i>
                                                                    </a>
                                                                    <a style="display: inline;"
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
                                                                    <li class="nav-item option_li_row option_li_row_{{$key}} ">
                                                                        <a id="remove_option_{{$key}}"
                                                                           href="javascript:void(0);"
                                                                           style="display: inline;"
                                                                           onclick="remove_option('{{$key}}')"
                                                                           class="text-danger">
                                                                            <i class="fa fa-lg fa-trash mt-3"></i>
                                                                        </a>
                                                                        <a style="display: inline;"
                                                                           class="nav-link option_a_row  option_a_row{{$key}}"
                                                                           onclick="change_tab('{{$key}}')"
                                                                           id="tab_{{$key}}"
                                                                           data-toggle="tab"
                                                                           aria-controls="tabv_{{$key}}"
                                                                           href="#tabv_{{$key}}"
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

                                                                            <div class="form-group col-sm-6{!! formError($errors,'option.'.$key.'.option_min_select',true) !!}">
                                                                                <div class="controls">
                                                                                    {!! Form::label('option_min_select', __('Min Select').':') !!}
                                                                                    <div class="input-group input-group-lg">
                                                                                        {!! Form::number('option['.$key.'][option_min_select]',$row['option_min_select'],['class'=>'form-control option_min_select']) !!}

                                                                                    </div>
                                                                                </div>
                                                                                {!! formError($errors,'option.'.$key.'.option_min_select') !!}
                                                                            </div>

                                                                            <div class="form-group col-sm-6{!! formError($errors,'option.'.$key.'.option_max_select',true) !!}">
                                                                                <div class="controls">
                                                                                    {!! Form::label('option_max_select', __('Max Select').':') !!}
                                                                                    <div class="input-group input-group-lg">
                                                                                        {!! Form::number('option['.$key.'][option_max_select]',isset($result->id) ? $result->option_max_select:old('option_max_select'),['class'=>'form-control option_max_select']) !!}

                                                                                    </div>
                                                                                </div>
                                                                                {!! formError($errors,'option.'.$key.'.option_max_select') !!}
                                                                            </div>

                                                                            <div class="form-group col-sm-6{!! formError($errors,'option.'.$key.'.option_is_required',true) !!}">
                                                                                <div class="controls">
                                                                                    {!! Form::label('option_is_required', __('Is Required').':') !!}
                                                                                    <div class="input-group input-group-lg">
                                                                                        {!! Form::select('option['.$key.'][option_is_required]',['yse'=>__('Yes'),'no'=>__('No')],$row['option_is_required'],['class'=>'form-control option_is_required']) !!}

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

                                                                            @if( $row['option_type'] == 'select' || $row['option_type'] == 'radio' || $row['option_type'] == 'check' )


                                                                                <div id="option_value_list">
                                                                                    <div style="text-align: right;"
                                                                                         class="col-sm-6">
                                                                                        <button type="button"
                                                                                                class="btn btn-primary fa fa-plus add_option_value_button"
                                                                                                onclick="add_option_value()">
                                                                                            <span>{{__('Add Value')}}</span>
                                                                                        </button>
                                                                                    </div>

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
                                                                                            <div class="form-group col-sm-2{!! formError($errors,'option.'.$key.'.option_value_price_prefix.'.$key2,true) !!}">
                                                                                                <div class="controls">
                                                                                                    {!! Form::label('option_value_price_prefix', __('Prefix').':') !!}
                                                                                                    <div class="input-group input-group-lg">
                                                                                                        {!! Form::select('option['.$key.'][option_value_price_prefix][]',['+'=>'+','-'=>'-'],$row['option_value_price_prefix'][$key2],['class'=>'form-control option_value_price_prefix']) !!}
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

                                                                                        </div>
                                                                                    @endforeach

                                                                                </div>


                                                                            @else

                                                                                <div id="option_value_list"
                                                                                     style="display: none">
                                                                                    <div style="text-align: right;"
                                                                                         class="col-sm-6">
                                                                                        <button type="button"
                                                                                                class="btn btn-primary fa fa-plus add_option_value_button"
                                                                                                onclick="add_option_value()">
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

                                                                @foreach($result->option as $key => $row)

                                                                    <div role="tabpanel"
                                                                         class="tab-pane option_div_row option_div_row_{{$key}}"
                                                                         id="tabv_{{$key}}"
                                                                         aria-expanded="true"
                                                                         aria-labelledby="tab_{{$key}}">

                                                                        <div class="form-group col-sm-10{!! formError($errors,'option.'.$key.'.template_option',true) !!}">
                                                                            <div class="controls">
                                                                                {!! Form::label('template_option', __('Template Option').':') !!}
                                                                                <div class="input-group input-group-lg">
                                                                                    {!! Form::select('option['.$key.'][template_option]',[''=>__('Select Option'),'new'=>__('New Option')]+$current_template_option,'new',['id'=>'template_option_'.$key,'class'=>'form-control template_option']) !!}
                                                                                </div>
                                                                            </div>
                                                                            {!! formError($errors,'option.'.$key.'.template_option') !!}
                                                                        </div>

                                                                        <div class="form-group col-sm-2{!! formError($errors,'option.'.$key.'.option_sort',true) !!}">
                                                                            <div class="controls">
                                                                                {!! Form::label('option_sort', __('Sort').':') !!}
                                                                                <div class="input-group input-group-lg">
                                                                                    {!! Form::number('option['.$key.'][option_sort]',$row['sort'],['class'=>'form-control option_sort']) !!}

                                                                                </div>
                                                                            </div>
                                                                            {!! formError($errors,'option.'.$key.'.option_sort') !!}
                                                                        </div>


                                                                        <div id="new_option">
                                                                            <div class="form-group col-sm-6{!! formError($errors,'option.'.$key.'.option_name_ar',true) !!}">
                                                                                <div class="controls">
                                                                                    {!! Form::label('option_name_ar', __('Name Ar').':') !!}
                                                                                    <div class="input-group input-group-lg">
                                                                                        {!! Form::text('option['.$key.'][option_name_ar]',$row['name_ar'],['class'=>'form-control option_name_ar']) !!}
                                                                                    </div>
                                                                                </div>
                                                                                {!! formError($errors,'option.'.$key.'.option_name_ar') !!}
                                                                            </div>

                                                                            <div class="form-group col-sm-6{!! formError($errors,'option.'.$key.'.option_name_en',true) !!}">
                                                                                <div class="controls">
                                                                                    {!! Form::label('option_name_en', __('Name En').':') !!}
                                                                                    <div class="input-group input-group-lg">
                                                                                        {!! Form::text('option['.$key.'][option_name_en]',$row['name_en'],['class'=>'form-control option_name_en']) !!}

                                                                                    </div>
                                                                                </div>
                                                                                {!! formError($errors,'option.'.$key.'.option_name_en') !!}
                                                                            </div>

                                                                            <div class="form-group col-sm-6{!! formError($errors,'option.'.$key.'.option_min_select',true) !!}">
                                                                                <div class="controls">
                                                                                    {!! Form::label('option_min_select', __('Min Select').':') !!}
                                                                                    <div class="input-group input-group-lg">
                                                                                        {!! Form::number('option['.$key.'][option_min_select]',$row['min_select'],['class'=>'form-control option_min_select']) !!}

                                                                                    </div>
                                                                                </div>
                                                                                {!! formError($errors,'option.'.$key.'.option_min_select') !!}
                                                                            </div>

                                                                            <div class="form-group col-sm-6{!! formError($errors,'option.'.$key.'.option_max_select',true) !!}">
                                                                                <div class="controls">
                                                                                    {!! Form::label('option_max_select', __('Max Select').':') !!}
                                                                                    <div class="input-group input-group-lg">
                                                                                        {!! Form::number('option['.$key.'][option_max_select]',$row['max_select'],['class'=>'form-control option_max_select']) !!}

                                                                                    </div>
                                                                                </div>
                                                                                {!! formError($errors,'option.'.$key.'.option_max_select') !!}
                                                                            </div>

                                                                            <div class="form-group col-sm-6{!! formError($errors,'option.'.$key.'.option_is_required',true) !!}">
                                                                                <div class="controls">
                                                                                    {!! Form::label('option_is_required', __('Is Required').':') !!}
                                                                                    <div class="input-group input-group-lg">
                                                                                        {!! Form::select('option['.$key.'][option_is_required]',['yse'=>__('Yes'),'no'=>__('No')],$row['is_required'],['class'=>'form-control option_is_required']) !!}

                                                                                    </div>
                                                                                </div>
                                                                                {!! formError($errors,'option.'.$key.'.option_is_required') !!}
                                                                            </div>

                                                                            <div class="form-group col-sm-6{!! formError($errors,'option.'.$key.'.option_type',true) !!}">
                                                                                <div class="controls">
                                                                                    {!! Form::label('option_type', __('Type').':') !!}
                                                                                    <div class="input-group input-group-lg">
                                                                                        {!! Form::select('option['.$key.'][option_type]',['text'=>__('Text'),'textarea'=>__('Text Area'),'select'=>__('Select'),'radio'=>__('Radio'),'check'=>__('Check Box')],$row['type'],['class'=>'form-control option_type']) !!}

                                                                                    </div>
                                                                                </div>
                                                                                {!! formError($errors,'option.'.$key.'.option_type') !!}
                                                                            </div>

                                                                            @if( $row['type'] == 'select' || $row['type'] == 'radio' || $row['type'] == 'check' )


                                                                                <div id="option_value_list">
                                                                                    <div style="text-align: right;"
                                                                                         class="col-sm-6">
                                                                                        <button type="button"
                                                                                                class="btn btn-primary fa fa-plus add_option_value_button"
                                                                                                onclick="add_option_value()">
                                                                                            <span>{{__('Add Value')}}</span>
                                                                                        </button>
                                                                                    </div>


                                                                                    @foreach($row->option_values as $key2 => $value )

                                                                                        <div class="option_value_row"
                                                                                             style="width: 100%;display: table;">
                                                                                            <div class="form-group col-sm-3{!! formError($errors,'option.'.$key.'.option_value_name_ar.'.$key2,true) !!}">
                                                                                                <div class="controls">
                                                                                                    {!! Form::label('option_value_name_ar', __('Name Ar').':') !!}
                                                                                                    <div class="input-group input-group-lg">
                                                                                                        {!! Form::text('option['.$key.'][option_value_name_ar][]',$value['name_ar'],['class'=>'form-control option_value_name_ar']) !!}
                                                                                                    </div>
                                                                                                </div>
                                                                                                {!! formError($errors,'option.'.$key.'.option_value_name_ar.'.$key2) !!}
                                                                                            </div>
                                                                                            <div class="form-group col-sm-3{!! formError($errors,'option.'.$key.'.option_value_name_en.'.$key2,true) !!}">
                                                                                                <div class="controls">
                                                                                                    {!! Form::label('option_value_name_en', __('Name En').':') !!}
                                                                                                    <div class="input-group input-group-lg">
                                                                                                        {!! Form::text('option['.$key.'][option_value_name_en][]',$value['name_en'],['class'=>'form-control option_value_name_en']) !!}
                                                                                                    </div>
                                                                                                </div>
                                                                                                {!! formError($errors,'option.'.$key.'.option_value_name_en.'.$key2) !!}
                                                                                            </div>
                                                                                            <div class="form-group col-sm-2{!! formError($errors,'option.'.$key.'.option_value_price_prefix.'.$key2,true) !!}">
                                                                                                <div class="controls">
                                                                                                    {!! Form::label('option_value_price_prefix', __('Prefix').':') !!}
                                                                                                    <div class="input-group input-group-lg">
                                                                                                        {!! Form::select('option['.$key.'][option_value_price_prefix][]',['+'=>'+','-'=>'-'],$value['price_prefix'],['class'=>'form-control option_value_price_prefix']) !!}
                                                                                                    </div>
                                                                                                </div>
                                                                                                {!! formError($errors,'option.'.$key.'.option_value_price_prefix.'.$key2) !!}
                                                                                            </div>
                                                                                            <div class="form-group col-sm-3{!! formError($errors,'option.'.$key.'.option_value_price.'.$key2,true) !!}">
                                                                                                <div class="controls">
                                                                                                    {!! Form::label('option_value_price', __('Price').':') !!}
                                                                                                    <div class="input-group input-group-lg">
                                                                                                        {!! Form::text('option['.$key.'][option_value_price][]',$value['price'],['class'=>'form-control option_value_price']) !!}
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

                                                                                        </div>
                                                                                    @endforeach

                                                                                </div>


                                                                            @else

                                                                                <div id="option_value_list"
                                                                                     style="display: none">
                                                                                    <div style="text-align: right;"
                                                                                         class="col-sm-6">
                                                                                        <button type="button"
                                                                                                class="btn btn-primary fa fa-plus add_option_value_button"
                                                                                                onclick="add_option_value()">
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
                                                    <div class="form-group col-sm-6{!! formError($errors,'option_min_select',true) !!}">
                                                        <div class="controls">
                                                            {!! Form::label('option_min_select', __('Min Select').':') !!}
                                                            <div class="input-group input-group-lg">
                                                                {!! Form::number('','',['class'=>'form-control option_min_select']) !!}

                                                            </div>
                                                        </div>
                                                        {!! formError($errors,'option_min_select') !!}
                                                    </div>
                                                    <div class="form-group col-sm-6{!! formError($errors,'option_max_select',true) !!}">
                                                        <div class="controls">
                                                            {!! Form::label('option_max_select', __('Max Select').':') !!}
                                                            <div class="input-group input-group-lg">
                                                                {!! Form::number('','',['class'=>'form-control option_max_select']) !!}

                                                            </div>
                                                        </div>
                                                        {!! formError($errors,'option_max_select') !!}
                                                    </div>
                                                    <div class="form-group col-sm-6{!! formError($errors,'option_is_required',true) !!}">
                                                        <div class="controls">
                                                            {!! Form::label('option_is_required', __('Is Required').':') !!}
                                                            <div class="input-group input-group-lg">
                                                                {!! Form::select('',['yse'=>__('Yes'),'no'=>__('No')],'',['class'=>'form-control option_is_required']) !!}

                                                            </div>
                                                        </div>
                                                        {!! formError($errors,'option_is_required') !!}
                                                    </div>
                                                    <div class="form-group col-sm-6{!! formError($errors,'option_type',true) !!}">
                                                        <div class="controls">
                                                            {!! Form::label('option_type', __('Type').':') !!}
                                                            <div class="input-group input-group-lg">
                                                                {!! Form::select('',['text'=>__('Text'),'textarea'=>__('Text Area'),'select'=>__('Select'),'radio'=>__('Radio'),'check'=>__('Check Box')],'',['class'=>'form-control option_type']) !!}

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
                                                            {!! Form::text('','',['class'=>'form-control option_value_price']) !!}
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


                                <div class="card-header">
                                    <h2>{{__('Attributes')}}</h2>
                                </div>
                                <div style="text-align: right;" class="col-sm-6">
                                    <button type="button" class="btn btn-primary fa fa-plus " onclick="add_attribute()">
                                        <span>{{__('Add Attribute')}}</span>
                                    </button>
                                </div>

                                <div class="col-xl-12 col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">{{__('Attribute')}}</h4>
                                        </div>
                                        <div class="card-content">
                                            <div class="card-body">

                                                <div class="nav-vertical">
                                                    <ul id="attribute_li_list" class="nav nav-tabs nav-left flex-column"
                                                        style="height: 102.391px;">

                                                        @if(old('attribute'))

                                                            @foreach(old('attribute') as $key=>$row)
                                                                <li class="nav-item attribute_li_row attribute_li_row_{{$key}} ">
                                                                    <a id="remove_attribute" href="javascript:void(0);"
                                                                       style="display: inline;"
                                                                       onclick="remove_attribute('{{$key}}')"
                                                                       class="text-danger">
                                                                        <i class="fa fa-lg fa-trash mt-3"></i>
                                                                    </a>
                                                                    <a style="display: inline;"
                                                                       class="nav-link attribute_a_row attribute_a_row_{{$key}}"
                                                                       onclick="change_attribute_tab('{{$key}}')"
                                                                       id="attribute_tab_{{$key}}"
                                                                       data-toggle="tab"
                                                                       aria-controls="attribute_tabv_{{$key}}"
                                                                       href="#attribute_tabv_{{$key}}"
                                                                       aria-expanded="true">Attribute {{$key + 1 }}</a>

                                                                </li>
                                                            @endforeach

                                                        @elseif(isset($result->id))

                                                            @if($result->attribute)
                                                                @foreach($result->attribute as $key=>$row)

                                                                    <li class="nav-item attribute_li_row attribute_li_row_{{$key}} ">
                                                                        <a id="remove_attribute"
                                                                           href="javascript:void(0);"
                                                                           style="display: inline;"
                                                                           onclick="remove_attribute('{{$key}}')"
                                                                           class="text-danger">
                                                                            <i class="fa fa-lg fa-trash mt-3"></i>
                                                                        </a>
                                                                        <a style="display: inline;"
                                                                           class="nav-link attribute_a_row attribute_a_row_{{$key}}"
                                                                           onclick="change_attribute_tab('{{$key}}')"
                                                                           id="attribute_tab_{{$key}}"
                                                                           data-toggle="tab"
                                                                           aria-controls="attribute_tabv_{{$key}}"
                                                                           href="#attribute_tabv_{{$key}}"
                                                                           aria-expanded="true">Attribute {{$key + 1 }}</a>

                                                                    </li>

                                                                @endforeach
                                                            @endif

                                                        @endif


                                                    </ul>
                                                    <div id="attribute_div_list" class="tab-content px-1">
                                                        @if(old('attribute'))

                                                            @foreach(old('attribute') as $key=>$attribute)

                                                                <div role="attribute_tabpanel"
                                                                     class="tab-pane attribute_div_row attribute_div_row_{{$key}}"
                                                                     id="attribute_tabv_{{$key}}"
                                                                     aria-expanded="true"
                                                                     aria-labelledby="attribute_tab_{{$key}}">

                                                                    <div class="form-group col-sm-10{!! formError($errors,'attribute.'.$key.'.template_attribute',true) !!}">
                                                                        <div class="controls">
                                                                            {!! Form::label('template_attribute', __('Template Attribute' . old('template_attribute.'.$key)).':') !!}
                                                                            <div class="input-group input-group-lg">
                                                                                @if(isset($current_template_attribute))
                                                                                    {!! Form::select('attribute['.$key.'][template_attribute]',[''=>__('Select Attribute'),'new'=>__('New Attribute')]+$current_template_attribute,$attribute['template_attribute'],['id'=>'template_attribute_'.$key,'class'=>'form-control template_attribute']) !!}
                                                                                @else
                                                                                    {!! Form::select('attribute['.$key.'][template_attribute]',[''=>__('Select Attribute'),'new'=>__('New Attribute')],$attribute['template_attribute'],['id'=>'template_attribute_'.$key,'class'=>'form-control template_attribute']) !!}

                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                        {!! formError($errors,'attribute.'.$key.'.template_attribute') !!}
                                                                    </div>

                                                                    <div class="form-group col-sm-2{!! formError($errors,'attribute.'.$key.'.attribute_sort',true) !!}">
                                                                        <div class="controls">
                                                                            {!! Form::label('attribute_sort', __('Sort').':') !!}
                                                                            <div class="input-group input-group-lg">
                                                                                {!! Form::number('attribute['.$key.'][attribute_sort]',$attribute['attribute_sort'],['class'=>'form-control attribute_sort']) !!}
                                                                            </div>
                                                                        </div>
                                                                        {!! formError($errors,'attribute.'.$key.'.attribute_sort') !!}
                                                                    </div>
                                                                    @if($attribute['template_attribute'] == 'new')
                                                                        <div id="new_attribute_{{$key}}">
                                                                            <div class="col-sm-6">
                                                                                <div class="card">
                                                                                    <div class="card-header">
                                                                                        <h2>{{__('English Data')}}</h2>
                                                                                    </div>
                                                                                    <div class="card-block card-dashboard">
                                                                                        <div class="form-group col-sm-12{!! formError($errors,'attribute.'.$key.'.attribute_name_en',true) !!}">
                                                                                            <div class="controls">
                                                                                                {!! Form::label('attribute_name_en', __('Attribute Name (English)').':') !!}
                                                                                                {!! Form::text('attribute['.$key.'][attribute_name_en]',$attribute['attribute_name_en'],['class'=>'form-control']) !!}
                                                                                            </div>
                                                                                            {!! formError($errors,'attribute.'.$key.'.attribute_name_en') !!}
                                                                                        </div>
                                                                                        <div class="form-group col-sm-12{!! formError($errors,'attribute.'.$key.'.attribute_description_en',true) !!}">
                                                                                            <div class="controls">
                                                                                                {!! Form::label('attribute_description_en', __('Attribute description (English)').':') !!}
                                                                                                {!! Form::textarea('attribute['.$key.'][attribute_description_en]',$attribute['attribute_description_en'],['class'=>'form-control']) !!}
                                                                                            </div>
                                                                                            {!! formError($errors,'attribute.'.$key.'.attribute_description_en') !!}
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
                                                                                        <div class="form-group col-sm-12{!! formError($errors,'attribute.'.$key.'.attribute_name_ar',true) !!}">
                                                                                            <div class="controls">
                                                                                                {!! Form::label('attribute_name_ar', __('Attribute Name (Arabic)').':') !!}
                                                                                                {!! Form::text('attribute['.$key.'][attribute_name_ar]',$attribute['attribute_name_ar'],['class'=>'form-control ar']) !!}
                                                                                            </div>
                                                                                            {!! formError($errors,'attribute.'.$key.'.attribute_name_ar') !!}
                                                                                        </div>
                                                                                        <div class="form-group col-sm-12{!! formError($errors,'attribute.'.$key.'.attribute_description_ar',true) !!}">
                                                                                            <div class="controls">
                                                                                                {!! Form::label('attribute_description_ar', __('Attribute description (Arabic)').':') !!}
                                                                                                {!! Form::textarea('attribute['.$key.'][attribute_description_ar]',$attribute['attribute_description_ar'],['class'=>'form-control ar']) !!}
                                                                                            </div>
                                                                                            {!! formError($errors,'attribute.'.$key.'.attribute_description_ar') !!}
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                    @endif


                                                                </div>
                                                            @endforeach

                                                        @elseif(isset($result->id))

                                                            @if($result->attribute)

                                                                @foreach($result->attribute as $key=>$attribute)

                                                                    <div role="attribute_tabpanel"
                                                                         class="tab-pane attribute_div_row attribute_div_row_{{$key}}"
                                                                         id="attribute_tabv_{{$key}}"
                                                                         aria-expanded="true"
                                                                         aria-labelledby="attribute_tab_{{$key}}">

                                                                        <div class="form-group col-sm-10{!! formError($errors,'attribute.'.$key.'.template_attribute',true) !!}">
                                                                            <div class="controls">
                                                                                {!! Form::label('template_attribute', __('Template Attribute :')) !!}
                                                                                <div class="input-group input-group-lg">
                                                                                    {!! Form::select('attribute['.$key.'][template_attribute]',[''=>__('Select Attribute'),'new'=>__('New Attribute')]+$current_template_attribute,'new',['id'=>'template_attribute_'.$key,'class'=>'form-control template_attribute']) !!}
                                                                                </div>
                                                                            </div>
                                                                            {!! formError($errors,'attribute.'.$key.'.template_attribute') !!}
                                                                        </div>

                                                                        <div class="form-group col-sm-2{!! formError($errors,'attribute.'.$key.'.attribute_sort',true) !!}">
                                                                            <div class="controls">
                                                                                {!! Form::label('attribute_sort', __('Sort').':') !!}
                                                                                <div class="input-group input-group-lg">
                                                                                    {!! Form::number('attribute['.$key.'][attribute_sort]',$attribute['sort'],['class'=>'form-control attribute_sort']) !!}
                                                                                </div>
                                                                            </div>
                                                                            {!! formError($errors,'attribute.'.$key.'.attribute_sort') !!}
                                                                        </div>

                                                                        <div id="new_attribute_{{$key}}">
                                                                            <div class="col-sm-6">
                                                                                <div class="card">
                                                                                    <div class="card-header">
                                                                                        <h2>{{__('English Data')}}</h2>
                                                                                    </div>
                                                                                    <div class="card-block card-dashboard">
                                                                                        <div class="form-group col-sm-12{!! formError($errors,'attribute.'.$key.'.attribute_name_en',true) !!}">
                                                                                            <div class="controls">
                                                                                                {!! Form::label('attribute_name_en', __('Attribute Name (English)').':') !!}
                                                                                                {!! Form::text('attribute['.$key.'][attribute_name_en]',$attribute['name_en'],['class'=>'form-control']) !!}
                                                                                            </div>
                                                                                            {!! formError($errors,'attribute.'.$key.'.attribute_name_en') !!}
                                                                                        </div>
                                                                                        <div class="form-group col-sm-12{!! formError($errors,'attribute.'.$key.'.attribute_description_en',true) !!}">
                                                                                            <div class="controls">
                                                                                                {!! Form::label('attribute_description_en', __('Attribute description (English)').':') !!}
                                                                                                {!! Form::textarea('attribute['.$key.'][attribute_description_en]',$attribute['description_en'],['class'=>'form-control']) !!}
                                                                                            </div>
                                                                                            {!! formError($errors,'attribute.'.$key.'.attribute_description_en') !!}
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
                                                                                        <div class="form-group col-sm-12{!! formError($errors,'attribute.'.$key.'.attribute_name_ar',true) !!}">
                                                                                            <div class="controls">
                                                                                                {!! Form::label('attribute_name_ar', __('Attribute Name (Arabic)').':') !!}
                                                                                                {!! Form::text('attribute['.$key.'][attribute_name_ar]',$attribute['name_ar'],['class'=>'form-control ar']) !!}
                                                                                            </div>
                                                                                            {!! formError($errors,'attribute.'.$key.'.attribute_name_ar') !!}
                                                                                        </div>
                                                                                        <div class="form-group col-sm-12{!! formError($errors,'attribute.'.$key.'.attribute_description_ar',true) !!}">
                                                                                            <div class="controls">
                                                                                                {!! Form::label('attribute_description_ar', __('Attribute description (Arabic)').':') !!}
                                                                                                {!! Form::textarea('attribute['.$key.'][attribute_description_ar]',$attribute['description_ar'],['class'=>'form-control ar']) !!}
                                                                                            </div>
                                                                                            {!! formError($errors,'attribute.'.$key.'.attribute_description_ar') !!}
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

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

                                    <div id="attribute_li_temp" style="display: none">
                                        <li class="nav-item attribute_li_row ">
                                            <a id="remove_attribute" href="javascript:void(0);"
                                               style="display: inline;"
                                               onclick="remove_attribute()"
                                               class="text-danger">
                                                <i class="fa fa-lg fa-trash mt-3"></i>
                                            </a>
                                            <a style="display: inline;" class="nav-link attribute_a_row "
                                               id="attribute_tab_1"
                                               data-toggle="tab"
                                               aria-controls="attribute_tabv_1" href="#attribute_tabv_1"
                                               aria-expanded="true">Tab 1</a>

                                        </li>
                                    </div>

                                    <div id="attribute_div_temp" style="display: none">
                                        <div role="attribute_tabpanel" class="tab-pane attribute_div_row"
                                             id="attribute_tabv_1"
                                             aria-expanded="true" aria-labelledby="attribute_tab_1">

                                            <div class="form-group col-sm-10{!! formError($errors,'template_attribute',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('template_attribute', __('Template Attribute').':') !!}
                                                    <div class="input-group input-group-lg">
                                                        {!! Form::select('',[''=>__('Select Attribute'),'new'=>__('New Attribute')],'',['id'=>'template_attribute','class'=>'form-control template_attribute']) !!}

                                                    </div>
                                                </div>
                                                {!! formError($errors,'template_attribute') !!}
                                            </div>

                                            <div class="form-group col-sm-2{!! formError($errors,'attribute_sort',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('attribute_sort', __('Sort').':') !!}
                                                    <div class="input-group input-group-lg">
                                                        {!! Form::number('','',['class'=>'form-control attribute_sort']) !!}
                                                    </div>
                                                </div>
                                                {!! formError($errors,'attribute_sort') !!}
                                            </div>

                                            <div id="new_attribute" style="display: none">
                                                <div class="col-sm-6">
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <h2>{{__('English Data')}}</h2>
                                                        </div>
                                                        <div class="card-block card-dashboard">
                                                            <div class="form-group col-sm-12{!! formError($errors,'attribute_name_en',true) !!}">
                                                                <div class="controls">
                                                                    {!! Form::label('attribute_name_en', __('Attribute Name (English)').':') !!}
                                                                    {!! Form::text('','',['class'=>'form-control attribute_name_en']) !!}
                                                                </div>
                                                                {!! formError($errors,'attribute_name_en') !!}
                                                            </div>
                                                            <div class="form-group col-sm-12{!! formError($errors,'attribute_description_en',true) !!}">
                                                                <div class="controls">
                                                                    {!! Form::label('attribute_description_en', __('Attribute description (English)').':') !!}
                                                                    {!! Form::textarea('','',['class'=>'form-control attribute_description_en']) !!}
                                                                </div>
                                                                {!! formError($errors,'attribute_description_en') !!}
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
                                                            <div class="form-group col-sm-12{!! formError($errors,'attribute_name_ar',true) !!}">
                                                                <div class="controls">
                                                                    {!! Form::label('attribute_name_ar', __('Attribute Name (Arabic)').':') !!}
                                                                    {!! Form::text('','',['class'=>'form-control attribute_name_ar']) !!}
                                                                </div>
                                                                {!! formError($errors,'attribute_name_ar') !!}
                                                            </div>
                                                            <div class="form-group col-sm-12{!! formError($errors,'attribute_description_ar',true) !!}">
                                                                <div class="controls">
                                                                    {!! Form::label('attribute_description_ar', __('Attribute description (Arabic)').':') !!}
                                                                    {!! Form::textarea('','',['class'=>'form-control attribute_description_ar']) !!}
                                                                </div>
                                                                {!! formError($errors,'attribute_description_ar') !!}
                                                            </div>
                                                        </div>
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

    </div>

    <script src="{{asset('assets/system')}}/SimpleAjaxUploader.js"></script>
    <script>
        function remove_image(number, id) {

            $.post('{{route('merchant.product-remove-image')}}', {'id': id}, function (out) {
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


    </script>


    <script>
        $(document).ready(function () {

            ajaxSelect2('#merchant_id', 'merchant');

            @if(isset($result->id))
                get_merchant_template_option()
            get_merchant_template_attribute()
                    @endif


            var btn = document.getElementById('uploadBtn'),
                progressBar = document.getElementById('progressBar'),
                progressOuter = document.getElementById('progressOuter'),
                msgBox = document.getElementById('msgBox');


            var uploader = new ss.SimpleUpload({
                button: btn,
                url: '{{route('merchant.upload-temp-image')}}',
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



        function price_type() {
            $('#price_multi').css('display', 'block');
        }
        function remove_option(number) {
            $('.option_li_row_' + number).remove();
            $('.option_div_row_' + number).remove();
        }

        function get_merchant_template_option() {
            var value = $('#merchant_id').val();
            var value2 = $('#category_id').val();
            $.get('{{route('merchant.product-template-option')}}', {'merchant_id': value,'category_id':value2}, function (out) {
                $('#template_option').html(out);
            });

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

        function add_option() {
            var li_length = $('#option_li_list li').length;
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


            $('#option_li_list').append(cloned_li_row.html());
            $('#option_div_list').append(clone_div_row.html());
            change_tab(li_length);
        }

        function add_price() {
            var length = $('#price_list').length;
            var clonedRow = $('#price_temp').clone();
            clonedRow.find('.price').attr('id', 'price_' + length).attr('name', 'price[]');
            clonedRow.find('.unit_ar').attr('id', 'unit_ar_' + length).attr('name', 'unit_ar[]');
            clonedRow.find('.unit_en').attr('id', 'unit_en_' + length).attr('name', 'unit_en[]');
            clonedRow.find('.price_sort').attr('id', 'price_sort_' + length).attr('name', 'price_sort[]');
            $('#price_list').append(clonedRow.html());
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

        function get_merchant_template_attribute() {
            var value = $('#merchant_id').val();
            $.get('{{route('merchant.product-template-attribute')}}', {'merchant_id': value}, function (out) {
                $('#template_attribute').html(out);
            });

        }

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

        function get_attribute(){
            var category_id = $('#category_id').val();
            $.post('{{route('merchant.attribute.get-attribute')}}',{'category_id':category_id},function(out){
                    if(out.status == false)
                        toastr.error(out.msg, 'Success', {"closeButton": true});
                    else{
                        for(var i=0;i<out.data;i++){
                            var attribute = out.data[i];

                        }
                    }

            },'json');
        }

        function add_attribute() {
            var li_length = $('#attribute_li_list li').length;
            var cloned_li_row = $('#attribute_li_temp').clone();
            var number = +li_length + 1;
            cloned_li_row.find('.attribute_a_row').attr('id', 'attribute_tab_' + li_length).attr('aria-controls', 'attribute_tabv_' + li_length)
                .attr('href', '#attribute_tabv_' + li_length).html('Attribute ' + number).attr('onclick', 'change_attribute_tab(' + li_length + ')');
            cloned_li_row.find('#remove_attribute').attr('id', 'remove_attribute_' + li_length).attr('onclick', 'remove_attribute(' + li_length + ')');
            cloned_li_row.find('.attribute_li_row').addClass('attribute_li_row_' + li_length);

            var clone_div_row = $('#attribute_div_temp').clone();

            clone_div_row.find('.attribute_div_row').attr('id', '#attribute_tabv_' + li_length).attr('aria-labelledby', 'attribute_tab_' + li_length)
                .addClass('attribute_div_row_' + li_length);
            clone_div_row.find('#new_attribute').attr('id', 'new_attribute_' + li_length);
            clone_div_row.find('#attribute_value_list').attr('id', 'attribute_value_list_' + li_length);

            clone_div_row.find('.template_attribute').attr('onchange', 'choose_attribute_template_new(' + li_length + ')').attr('id', 'template_attribute_' + li_length).attr('name', 'attribute[' + li_length + '][template_attribute]');
            clone_div_row.find('.attribute_sort').attr('id', 'attribute_sort' + li_length).attr('name', 'attribute[' + li_length + '][attribute_sort]');
            clone_div_row.find('.attribute_name_ar').attr('name', 'attribute[' + li_length + '][attribute_name_ar]');
            clone_div_row.find('.attribute_name_en').attr('name', 'attribute[' + li_length + '][attribute_name_en]');
            clone_div_row.find('.attribute_description_ar').attr('name', 'attribute[' + li_length + '][attribute_description_ar]');
            clone_div_row.find('.attribute_description_en').attr('name', 'attribute[' + li_length + '][attribute_description_en]');


            $('#attribute_li_list').append(cloned_li_row.html());
            $('#attribute_div_list').append(clone_div_row.html());
            change_attribute_tab(li_length);
        }


        $(function () {
            var observer = new MutationObserver(function (mutations) {
                if ($('#map-events').is(':visible')) {
                    if (!mapInitilized) {
                        google.maps.event.trigger(map, 'resize');
                        mapInitilized = true;
                    }
                }
            });
            observer.observe(document.querySelector('#steps-uid-0-p-1'), {
                attributes: true
            });
        });

        $('#merchant_id').change(function () {

            // Category
            $.getJSON('{{route('system.ajax.get')}}', {
                'type': 'getProductCategory',
                'merchant_id': $(this).val()
            }, function ($data) {
                $newData = new Array;
                $newData.push('<option value="">{{__('Select Product Category')}}</option>');
                $.each($data, function (key, value) {
                    $newData.push('<option value="' + value.id + '">' + value.name + '</option>');
                });

                $('#merchant_product_category_id').html($newData.join("\n"));
            });

        });

    </script>
@endsection