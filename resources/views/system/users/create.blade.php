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
                                            {{__('Some fields are invalid please fix them')}}
                                        </div>
                                    </div>
                                </div>
                            @elseif(Session::has('status'))
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="alert alert-{{Session::get('status')}}" class="form_errors">
                                            {{ Session::get('msg') }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                            {!! Form::open(['route' => isset($result->id) ? ['system.users.update',$result->id]:'system.users.store','files'=>true, 'method' => isset($result->id) ?  'PATCH' : 'POST','class'=>'number-tab-steps wizard-circle','id'=> isset($result->id) ? 'orderEditForm':'orderForm']) !!}
                            <!-- Step 1 -->
                                <h6>{{__('Information')}}</h6>
                                <fieldset style="padding: 0px;">
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-block card-dashboard">

                                        <div class="form-group col-sm-6{!! formError($errors,'firstname',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('firstname', __('First Name').':') !!}
                                                {!! Form::text('firstname',isset($result->id) ? $result->firstname:old('firstname'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'firstname') !!}
                                        </div>


                                        <div class="form-group col-sm-6{!! formError($errors,'lastname',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('lastname', __('Last Name').':') !!}
                                                {!! Form::text('lastname',isset($result->id) ? $result->lastname:old('lastname'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'lastname') !!}
                                        </div>
                                        {{--<div class="clearfix"></div>--}}

                                        <div class="form-group col-sm-4{!! formError($errors,'email',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('email', __('Email').':') !!}
                                                {!! Form::email('email',isset($result->id) ? $result->email:old('email'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'email') !!}
                                        </div>


                                        <div class="form-group col-sm-4{!! formError($errors,'password',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('password', __('Password').':') !!}
                                                {!! Form::password('password', ['class' => 'form-control','id'=>'password']) !!}
                                            </div>
                                            {!! formError($errors,'password') !!}
                                        </div>

                                        <div class="form-group col-sm-4{!! formError($errors,'password_confirmation',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('password_confirmation', __('Retype Password').':') !!}
                                                {!! Form::password('password_confirmation', ['class' => 'form-control','id'=>'password_confirmation']) !!}
                                            </div>
                                            {!! formError($errors,'password_confirmation') !!}
                                        </div>


                                        <div class="form-group col-sm-6{!! formError($errors,'about',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('about', __('About').':') !!}
                                                {!! Form::textarea('about',isset($result->id) ? $result->about:old('about'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'about') !!}
                                        </div>
                                        <div class="form-group col-sm-6{!! formError($errors,'address',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('address', __('Address').':') !!}
                                                {!! Form::textarea('address',isset($result->id) ? $result->address:old('address'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'address') !!}
                                        </div>
                                        <div class="form-group col-sm-6{!! formError($errors,'status',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('status', __('Status').':') !!}
                                                {!! Form::select('status',['active'=>__('Active'),'in-active'=>__('In-Active')],isset($result->id) ? $result->status:old('status'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'status') !!}
                                        </div>

                                        <div class="form-group col-sm-6{!! formError($errors,'gender',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('gender', __('Gender').':') !!}
                                                {!! Form::select('gender',['male'=>__('Male'),'female'=>__('Female')],isset($result->id) ? $result->gender:old('gender'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'gender') !!}
                                        </div>


                                        <div class="form-group col-sm-6{!! formError($errors,'interisted_categories',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('interisted_categories', __('Select interested Categories')) !!}
                                                {!! Form::select('interisted_categories[]',['0'=>'Select Categories']+$itemCategories,isset($result->id) ? $result->interisted_categories:old('interisted_categories'),['class'=>'form-control interisted_categories','multiple'=>'multiple','style'=>'width:100%']) !!}
                                            </div>
                                            {!! formError($errors,'interisted_categories') !!}
                                        </div>

                                        <div class="form-group col-sm-12{!! formError($errors,'user_job_id',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('user_job_id', __('Select User Job')) !!}
                                                {!! Form::select('user_job_id',['0'=>'Select User Job']+$userJobs,isset($result->id) ? $result->user_job_id:old('user_job_id'),['class'=>'form-control user_job_id','onChange'=>'get_attribute();']) !!}
                                            </div>
                                            {!! formError($errors,'user_job_id') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                                    </fieldset>

                                <!-- Step 2-->

                                <h6>{{__('More Information')}}</h6>
                                <fieldset style="padding: 0px;">
                                    <div class="card">
                                        <div class="card-block card-dashboard">
                                            <div class="form-group col-sm-6{!! formError($errors,'phone',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('phone', __('Phone').':') !!}
                                                    {!! Form::number('phone',isset($result->id) ? $result->phone:old('phone'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'phone') !!}
                                            </div>
                                            <div class="form-group col-sm-6{!! formError($errors,'mobile',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('mobile', __('Mobile').':') !!}
                                                    {!! Form::number('mobile',isset($result->id) ? $result->mobile:old('mobile'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'mobile') !!}
                                            </div>
                                            <div class="form-group col-sm-6{!! formError($errors,'mobile2',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('mobile2', __('Mobile2').':') !!}
                                                    {!! Form::number('mobile2',isset($result->id) ? $result->mobile2:old('mobile2'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'mobile2') !!}
                                            </div>
                                            <div class="form-group col-sm-6{!! formError($errors,'mobile',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('mobile3', __('Mobile 3').':') !!}
                                                    {!! Form::number('mobile3',isset($result->id) ? $result->mobile3:old('mobile3'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'mobile3') !!}
                                            </div>




                                            <div class="form-group col-sm-12{!! formError($errors,'image',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('image', __('Image').':') !!}
                                                    {!! Form::file('image',['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'image') !!}
                                            </div>

                                            <div class="form-group col-sm-6{!! formError($errors,'facebook',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('facebook', __('Facebook').':') !!}
                                                    {!! Form::text('facebook',isset($result->id) ? $result->facebook:old('facebook'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'facebook') !!}
                                            </div>

                                            <div class="form-group col-sm-6{!! formError($errors,'youtube',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('youtube', __('Youtube').':') !!}
                                                    {!! Form::text('youtube',isset($result->id) ? $result->youtube:old('youtube'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'youtube') !!}
                                            </div>
                                            <div class="form-group col-sm-6{!! formError($errors,'linkedin',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('linkedin', __('Linkedin').':') !!}
                                                    {!! Form::text('linkedin',isset($result->id) ? $result->linkedin:old('linkedin'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'linkedin') !!}
                                            </div>
                                            <div class="form-group col-sm-6{!! formError($errors,'instgram',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('instgram', __('Inestgram').':') !!}
                                                    {!! Form::text('instgram',isset($result->id) ? $result->instgram:old('instgram'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'instgram') !!}
                                            </div>
                                            <div class="form-group col-sm-12{!! formError($errors,'google',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('google', __('Google').':') !!}
                                                    {!! Form::text('google',isset($result->id) ? $result->google:old('google'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'google') !!}
                                            </div>

                                        </div>
                                    </div>
                                </fieldset>
                                <!-- Step 3 -->
                                <h6>{{__('Location')}}</h6>
                                <fieldset style="padding: 0px;">
                                    <div class="col-sm-12">
                                        <div class="card">
                                            <div class="card-block card-dashboard">
                                        <div class="form-group col-sm-12{!! formError($errors,'type',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('type', __('Type').':') !!}
                                                {!! Form::select('type',['0'=>'Select Type','individual'=>__('Individual'),'company'=>__('company')],isset($result->id) ? $result->type:old('type'),['class'=>'form-control','id'=>'type_id','onChange'=>'changeType()']) !!}
                                            </div>
                                            {!! formError($errors,'type') !!}
                                        </div>
                                      <div id="select_type" style="display: none;">
                                        <div class="form-group col-sm-6{!! formError($errors,'company_business',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('company_business', __('Company Business').':') !!}
                                                {!! Form::text('company_business',isset($result->id) ? $result->company_business:old('company_business'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'company_business') !!}
                                        </div>
                                        <div class="form-group col-sm-6{!! formError($errors,'company_name',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('company_name', __('Company Name').':') !!}
                                                {!! Form::text('company_name',isset($result->id) ? $result->company_name:old('company_name'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'company_name') !!}
                                        </div>
                                      </div>
                                    </div>
                                <div class="card">
                                    <div class="card-block card-dashboard">
                                        <div class="form-group col-sm-12{!! formError($errors,'area_id',true) !!}">
                                            {{ Form::label('area_id',$areaData['type']->name) }}
                                            @php
                                                $arrayOfArea = $areaData['areas']->toArray();
                                                if(!$arrayOfArea){
                                                    $arrayOfArea = [];
                                                }else{
                                                    $arrayOfArea = array_column($arrayOfArea,'name','id');
                                                }
                                            @endphp
                                            {!! Form::select('area_id[]',array_merge([0=>__('Select Area')],$arrayOfArea),null,['class'=>'form-control','id'=>'area_id','onchange'=>'getNextAreas($(this).val(),"'.$areaData['type']->id.'",\'#nextAreasID\')']) !!}
                                            {!! formError($errors,'area_id') !!}
                                        </div>


                                        <div id="nextAreasID" class="col-md-12">

                                        </div>

                                        <div class="clearfix"></div>

                                        <div class="col-sm-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h2>{{__('Determine Location')}}</h2>
                                                </div>
                                                <div class="card-block card-dashboard">
                                                    <input style="width:80%" id="pac-input" class="controls form-control" type="text" placeholder="{{__('Search Box')}}">
                                                    <div id="map-events" class="height-400" style="margin-bottom: 15px;"></div>
                                                    <div class="form-group col-sm-6{!! formError($errors,'branch_latitude',true) !!}">
                                                        <div class="controls">
                                                            {!! Form::label('latitude', __('Latitude').':') !!}
                                                            {!! Form::text('latitude',old('latitude'),['class'=>'form-control']) !!}
                                                        </div>
                                                        {!! formError($errors,'latitude') !!}
                                                    </div>
                                                    <div class="form-group col-sm-6{!! formError($errors,'longitude',true) !!}">
                                                        <div class="controls">
                                                            {!! Form::label('longitude', __('Longitude').':') !!}
                                                            {!! Form::text('longitude',old('longitude'),['class'=>'form-control']) !!}
                                                        </div>
                                                        {!! formError($errors,'longitude') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </div>

                            </div>
                                </div>
                                </fieldset>
                             @if(!isset($result->id))
                                <!-- Step 4 -->
                                <h6>{{__('Attribute')}}</h6>
                                <fieldset style="padding: 0px;">
                                <div class="col-xs-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="card-block card-dashboard">
                                                <h4 class="card-title">{{__('Attribute')}}</h4>
                                            </div>
                                            <div class="card-content">
                                                <div class="card-body">

                                                    <div class="nav-vertical">

                                                        <div id="attribute_div_list">

                                                            @if(isset($result->id))
                                                                @if($current_attribute)

                                                                    @foreach($current_attribute as $key=>$value)
                                                                        {{--@foreach($v->attribute as $k=>$value)--}}
                                                                            {{--{{dd($value->attribute)}}--}}

                                                                        <div class="attribute_div_row attribute_div_row_{{$value->attribute->id}}">
                                                                            <div class="form-group col-sm-10{!! formError($errors,'attribute.'.$value->attribute->id,true) !!}">
                                                                                <div class="controls">
                                                                                    {!! Form::label('attribute',$value->attribute->name_ar) !!}
                                                                                    <div class="input-group input-group-lg">
                                                                                        @if($value->attribute->type == 'text')
                                                                                            {!! Form::text('attribute['.$value->attribute->id.']',$value->value,['id'=>'attribute_'.$value->attribute->id,'class'=>'form-control']) !!}
                                                                                        @elseif($value->type == 'textarea')
                                                                                            {!! Form::textarea('attribute['.$value->attribute->id.']',$value->value,['id'=>'attribute_'.$value->attribute->id,'class'=>'form-control']) !!}
                                                                                        @elseif($value->type == 'select')
                                                                                            {!! Form::select('attribute['.$value->attribute->id.']',[''=>__('Select Attribute')]+array_column( $value->attribute->values->toArray(),'name_ar','id'),$value->attribute->value,['id'=>'attribute_'.$value->attribute->id,'class'=>'form-control newselect2']) !!}
                                                                                        @elseif($value->attribute->type == 'multi-select')
                                                                                            <select name="attribute[{{$value->attribute->id}}][]"
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
                                                                                {!! formError($errors,'attribute.'.$value->attribute->id.'.template_attribute') !!}
                                                                            </div>
                                                                        </div>

                                                                    @endforeach
                                                                    {{--@endforeach--}}
                                                                @endif

                                                            @endif
                                                        </div>

                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                </fieldset>
                                   @endif
                            {{--<div class="col-xs-12">--}}
                                {{--<div class="card">--}}
                                    {{--<div class="card-body">--}}
                                        {{--<div class="card-block card-dashboard">--}}
                                            {{--{!! Form::submit(__('Save'),['class'=>'btn btn-success pull-right']) !!}--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        </div>
                    {!! Form::close() !!}
                </section>
                <!--/ Javascript sourced data -->
            </div>
        </div>
    </div>
    <!-- ////////////////////////////////////////////////////////////////////////////-->
@endsection
@section('header')
    {{--<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />--}}
    @endsection
@section('footer')
    <script src="{{asset('assets/system/js/scripts')}}/custom/custominput.js"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/dateTime/moment-with-locales.min.js')}}"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/dateTime/bootstrap-datetimepicker.min.js')}}"></script>
    <script src="{{asset('assets/system')}}/vendors/js/forms/select/select2.full.min.js" type="text/javascript"></script>

    <script src="{{asset('assets/system')}}/vendors/js/extensions/jquery.steps.min.js" type="text/javascript"></script>
    <script src="{{asset('assets/system')}}/js/scripts/forms/wizard-steps.js" type="text/javascript"></script>

    <script src="//maps.googleapis.com/maps/api/js?key={{env('gmap_key')}}&libraries=places&callback=initAutocomplete" type="text/javascript" async defer></script>
    <script src="{{asset('assets/system')}}/vendors/js/charts/gmaps.min.js" type="text/javascript"></script>
    <script src="//malsup.github.io/min/jquery.form.min.js" type="text/javascript"></script>

    <script>
        $(function () {
            $('.interisted_categories').select2();
        });

function changeType(){
var type= $('#type_id').val();
    console.log(type);
    if(type == 'company'){
        $('#select_type').show();
    }else{
        $('#select_type').hide();
    }
}
        ajaxSelect2('#parent_id','users');

        @php
            $startWorkWithArea = getLastNotEmptyItem(old('area_id'));
            if($startWorkWithArea){
                $areaData = \App\Libs\AreasData::getAreasUp($startWorkWithArea);
                echo '$runAreaLoop = true;$areaLoopData = [];';
                if($areaData){
                    foreach ($areaData as $key => $value){
                        echo '$areaLoopData['.$key.'] = '.$value.';';
                    }
                    echo '$(\'#area_id\').val(next($areaLoopData)).change();';
                }
            }
        @endphp


        $('#orderForm').submit(function (e) {
            e.preventDefault();

            $.post('{{route('system.users.store')}}', $('#orderForm').find(":input").serialize(), function (out) {
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
                   $('#orderForm')[0].reset();
                    setTimeout(location.reload,3000);
                }
            }, 'json')


        });
@if (isset($result->id))
$('#orderEditForm').submit(function (e) {
    e.preventDefault();

    $.post('{{route('system.users.update',$result->id)}}', $('#orderEditForm').find(":input").serialize(), function (out) {
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
            $('#orderForm')[0].reset();
           // setTimeout(location.reload,3000);
        }
    }, 'json')
});
@endif

        function get_attribute() {
            var user_job_id = $('#user_job_id').val();
            $.post('{{route('user.attribute.get-attribute')}}', {'user_job_id': user_job_id}, function (out) {
                if (out.status == false) {
                    
                        $('#attribute_div_list').empty();
                    // toastr.error(out.msg, 'Error', {"closeButton": true});
                } else {
                    $('#attribute_div_list').html('');
                    for (var i = 0; i < out.data.length; i++) {
                        var attribute = out.data[i];
                        var drow = $('<div>').attr('class', 'attribute_row');
                        drow.append($('<label>').html(attribute.name));

                        if (attribute.type == 'text') {
                            drow.append($('<input>', {name: 'attribute[' + attribute.id + ']', class: 'form-control'}));
                        } else if (attribute.type == 'textarea') {
                            drow.append($('<textarea>', {
                                name: 'attribute[' + attribute.id + ']',
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
                        }
                        else if (attribute.type == 'multi-select') {
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
                        }


                        $('#attribute_div_list').append(drow);
                        $('#attribute_' + attribute.id).select2();
                    }
                }

            }, 'json');
        }
            markers = [];
            var map = '';
            function initAutocomplete() {
                var pos = {
                    lat: 27.02194154036109,
                    lng: 31.148436963558197
                };
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position){
                        var pos = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        };
                        map.setCenter(pos);
                        var marker = new google.maps.Marker({
                            position: location,
                            map: map,
                        });
                        markers.push(marker);
                        $('#branch_latitude').val(pos.lat);
                        $('#branch_longitude').val(pos.lng);
                    });
                }
                map = new google.maps.Map(document.getElementById('map-events'), {
                    @if(old('latitude') && old('longitude'))
                    center: { lat: {{old('latitude')}}, lng: {{old('longitude')}} },
                    zoom: 16,
                    @else
                    center: pos,
                    zoom: 8,
                    @endif

                    mapTypeId: 'roadmap'
                });

                // Create the search box and link it to the UI element.
                var input = document.getElementById('pac-input');
                var searchBox = new google.maps.places.SearchBox(input);
                map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

                // Bias the SearchBox results towards current map's viewport.
                map.addListener('bounds_changed', function() {
                    searchBox.setBounds(map.getBounds());
                });

                map.addListener('click', function(e) {
                    placeMarker(e.latLng,map);
                });

                        @if(old('branch_latitude') && old('branch_longitude'))
                var marker = new google.maps.Marker({
                        position: {lat: {{old('branch_latitude')}}, lng: {{old('branch_longitude')}} },
                        map: map
                    });
                markers.push(marker);
                @endif

searchBox.addListener('places_changed', function() {
                    var places = searchBox.getPlaces();

                    if (places.length == 0) {
                        return;
                    }

                    // Clear out the old markers.
                    markers.forEach(function(marker) {
                        marker.setMap(null);
                    });


                    // For each place, get the icon, name and location.
                    var bounds = new google.maps.LatLngBounds();
                    places.forEach(function(place) {
                        if (!place.geometry) {
                            console.log("Returned place contains no geometry");
                            return;
                        }
                        if (place.geometry.viewport) {
                            // Only geocodes have viewport.
                            bounds.union(place.geometry.viewport);
                        } else {
                            bounds.extend(place.geometry.location);
                        }
                    });
                    map.fitBounds(bounds);
                });
            }

            function placeMarker(location,map) {
                clearOverlays();
                var marker = new google.maps.Marker({
                    position: location,
                    map: map,
                });
                var lng = location.lng();
                $('#latitude').val(location.lat());
                $('#longitude').val(location.lng());
                //console.log(lat+' And Long is: '+lng);
                markers.push(marker);
                //map.setCenter(location);
            }

            function clearOverlays() {
                for (var i = 0; i < markers.length; i++ ) {
                    markers[i].setMap(null);
                }
                markers.length = 0;
            }



    </script>
@endsection