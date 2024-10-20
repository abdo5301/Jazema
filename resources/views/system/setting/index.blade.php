@extends('system.layouts')
@section('header')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/forms/selects/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/forms/icheck/icheck.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/forms/icheck/custom.css')}}">
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
                            @elseif(Session::has('settingStatus'))
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="alert alert-success">
                                            {{__('Setting Has been updated successfully')}}
                                        </div>
                                    </div>
                                </div>
                            @endif
                            {!! Form::open(['route' => 'system.setting.update','method' => 'PATCH' ,'files' => true]) !!}
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-block">
                                            <div class="nav-vertical">
                                                <ul class="nav nav-tabs nav-left nav-border-left">
                                                    @foreach($settingGroups as $key => $value)
                                                        <li class="nav-item">
                                                            @if($value->group_name == 'map' )
                                                                <a id="baseVerticalLeft1-tab1_{{$key}}" data-toggle="tab" aria-controls="tabVerticalLeft11_{{$key}}" href="#setting_map_tab"  aria-expanded="false" class="nav-link">{{__(title_case(str_replace('_',' ',$value->group_name)))}}</a>
                                                            @else
                                                            <a id="baseVerticalLeft1-tab1_{{$key}}" data-toggle="tab" aria-controls="tabVerticalLeft11_{{$key}}" href="#tabVerticalLeft11_{{$key}}" @if($key == 0) aria-expanded="true" class="nav-link active" @else aria-expanded="false" class="nav-link" @endif>{{__(title_case(str_replace('_',' ',$value->group_name)))}}</a>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                                <div class="tab-content px-1">
                                                    @foreach($settingGroups as $key => $value)
                                                        @if($key == 0)
                                                            <div role="tabpanel" class="tab-pane active" id="tabVerticalLeft11_{{$key}}" aria-expanded="true" aria-labelledby="baseVerticalLeft1-tab1_{{$key}}">
                                                                @else
                                                                     @if($value->group_name == 'map' )
                                                                        <div class="tab-pane"  id="setting_map_tab" aria-labelledby="baseVerticalLeft1-tab1_{{$key}}">
                                                                            <div id="setting_map"></div>
                                                                            @else
                                                                        <div class="tab-pane" id="tabVerticalLeft11_{{$key}}" aria-labelledby="baseVerticalLeft1-tab1_{{$key}}">
                                                                     @endif
                                                                        @endif
                                                                        @foreach($setting[$key] as $sKey => $sValue)
                                                                            @if($sValue->input_type == 'text')
                                                                                <div class="form-group col-sm-12{!! formError($errors,$sValue->name,true) !!}">
                                                                                    <div class="controls">
                                                                                        {!! Form::label($sValue->name,$sValue->{'shown_name_'.\DataLanguage::get()}.':') !!}
                                                                                        {!! Form::text($sValue->name,$sValue->value,['class'=>'form-control']) !!}
                                                                                    </div>
                                                                                    {!! formError($errors,$sValue->name) !!}
                                                                                </div>
                                                                            @elseif($sValue->input_type == 'map')

                                                                                        {!! Form::hidden($sValue->name,$sValue->value,['class'=>'form-control']) !!}

                                                                            @elseif($sValue->input_type == 'number')
                                                                                <div class="form-group col-sm-12{!! formError($errors,$sValue->name,true) !!}">
                                                                                    <div class="controls">
                                                                                        {!! Form::label($sValue->name,$sValue->{'shown_name_'.\DataLanguage::get()}.':') !!}
                                                                                        {!! Form::number($sValue->name,$sValue->value,['class'=>'form-control']) !!}
                                                                                    </div>
                                                                                    {!! formError($errors,$sValue->name) !!}
                                                                                </div>
                                                                            @elseif($sValue->input_type == 'textarea')
                                                                                <div class="form-group col-sm-12{!! formError($errors,$sValue->name,true) !!}">
                                                                                    <div class="controls">
                                                                                        {!! Form::label($sValue->name,$sValue->{'shown_name_'.\DataLanguage::get()}.':') !!}
                                                                                        {!! Form::textarea($sValue->name,$sValue->value,['class'=>'form-control','rows'=>3]) !!}
                                                                                    </div>
                                                                                    {!! formError($errors,$sValue->name) !!}
                                                                                </div>
                                                                            @elseif($sValue->input_type == 'select')
                                                                                <div class="form-group col-sm-12{!! formError($errors,$sValue->name,true) !!}">
                                                                                    <div class="controls">
                                                                                        {!! Form::label($sValue->name,$sValue->{'shown_name_'.\DataLanguage::get()}.':') !!}
                                                                                        @php
                                                                                            $listValues = $sValue->option_list;
                                                                                            $listSelect = [];
                                                                                            foreach($listValues as $lKey => $lValue){
                                                                                            $listSelect[$lKey] = __($lValue);
                                                                                            }
                                                                                        @endphp
                                                                                        {!! Form::select($sValue->name,$listSelect,$sValue->value,['class'=>'form-control']) !!}
                                                                                    </div>
                                                                                    {!! formError($errors,$sValue->name) !!}
                                                                                </div>
                                                                            @elseif($sValue->input_type == 'radio')
                                                                                <div class="form-group col-sm-12{!! formError($errors,$sValue->name,true) !!}">
                                                                                    <div class="skin-square">
                                                                                        {!! Form::label($sValue->name,$sValue->{'shown_name_'.\DataLanguage::get()}.':') !!}
                                                                                        @php
                                                                                            $listValues = $sValue->option_list;
                                                                                            $listRadio = [];
                                                                                            foreach($listValues as $lKey => $lValue){
                                                                                            $listRadio[$lKey] = __($lValue);
                                                                                            }
                                                                                        @endphp
                                                                                        @foreach($listRadio as $rKey => $rValue)
                                                                                            <fieldset>
                                                                                                {!! Form::radio($sValue->name,$rKey,$sValue->value == $rKey,['id'=>$sValue->name.'_'.$rKey]) !!}
                                                                                                <label for="{{$sValue->name.'_'.$rKey}}">{{$rValue}}</label>
                                                                                            </fieldset>
                                                                                        @endforeach
                                                                                    </div>
                                                                                    {!! formError($errors,$sValue->name) !!}
                                                                                </div>
                                                                            @elseif($sValue->input_type == 'multiple')
                                                                                <div id="multiple_{{$sValue->name}}">
                                                                                    <div style="text-align: right; padding-bottom: 10px;" class="col-sm-12">
                                                                                        <button type="button" class="btn btn-primary fa fa-plus" onclick="addInput('{{$sValue->name}}','{{ $sValue->{'shown_name_'.\DataLanguage::get()} }}');">
                                                                                            <span>{{__('Add')}} {{ $sValue->{'shown_name_'.\DataLanguage::get()} }}</span>
                                                                                        </button>
                                                                                    </div>
                                                                                    @php
                                                                                        $sValue->value = @unserialize($sValue->value);
                                                                                        if(!is_array($sValue->value)){
                                                                                            $sValue->value = [];
                                                                                        }
                                                                                    @endphp
                                                                                    <div id="uploaddata_{{$sValue->name}}">
                                                                                        @foreach($sValue->value as $keyMulti => $valueMulti)
                                                                                            <div class="div-with-files">
                                                                                                <div class="form-group col-sm-10">
                                                                                                    <div class="controls">
                                                                                                        {!! Form::label($sValue->name.'[]',$sValue->{'shown_name_'.\DataLanguage::get()}.':') !!}
                                                                                                        {!! Form::text($sValue->name.'[]',$valueMulti,['class'=>'form-control']) !!}
                                                                                                    </div>
                                                                                                    {!! formError($errors,$sValue->name.'[]') !!}
                                                                                                </div>
                                                                                                <div style="padding-top: 40px;" class="col-sm-2 form-group">
                                                                                                    <a style="color: red;" href="javascript:void(0);" class="remove-file"><i class="fa fa-trash"></i></a>
                                                                                                </div>
                                                                                                <div class="col-sm-12">
                                                                                                    <hr />
                                                                                                </div>
                                                                                            </div>
                                                                                        @endforeach
                                                                                    </div>
                                                                                </div>
                                                                            @elseif($sValue->input_type == 'checkbox')
                                                                                <div class="form-group col-sm-12{!! formError($errors,$sValue->name,true) !!}">
                                                                                    <div class="skin-square">
                                                                                        {!! Form::label($sValue->name,$sValue->{'shown_name_'.\DataLanguage::get()}.':') !!}
                                                                                        @php
                                                                                            $listValues = $sValue->option_list;
                                                                                            $listCheckbox = [];
                                                                                            foreach($listValues as $lKey => $lValue){
                                                                                            $listCheckbox[$lKey] = __($lValue);
                                                                                            }
                                                                                            $sValue->value = @unserialize($sValue->value);
                                                                                            if(!is_array($sValue->value)){
                                                                                            $sValue->value = [];
                                                                                            }
                                                                                        @endphp
                                                                                        @foreach($listCheckbox as $rKey => $rValue)
                                                                                            <fieldset>
                                                                                                {!! Form::checkbox($sValue->name.'[]',$rKey,in_array($rKey,$sValue->value),['id'=>$sValue->name.'_'.$rKey]) !!}
                                                                                                <label for="{{$sValue->name.'_'.$rKey}}">{{$rValue}}</label>
                                                                                            </fieldset>
                                                                                        @endforeach
                                                                                    </div>
                                                                                    {!! formError($errors,$sValue->name) !!}
                                                                                </div>
                                                                            @endif
                                                                        @endforeach
                                                                    </div>
                                                                    @endforeach
                                                            </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="card-block card-dashboard">
                                                {!! Form::submit(__('Update'),['class'=>'btn btn-success pull-right']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    {!! Form::close() !!}
                </section>
                <!--/ Javascript sourced data -->
            </div>
        </div>
    </div>
    <!-- ////////////////////////////////////////////////////////////////////////////-->
@endsection
@section('footer')
    <script src="{{asset('assets/system')}}/vendors/js/forms/icheck/icheck.min.js" type="text/javascript"></script>
    <script src="{{asset('assets/system')}}/js/scripts/forms/checkbox-radio.min.js" type="text/javascript"></script>


    <style>
        #setting_map{
            height: 300px;
            border: 1px solid #ccd6e6;
            width: 95%;
        }
    </style>
    <script>
        var map_setting;
        var set_marker;
        function initMap() {
                    @if(!empty(setting('lat')) && !empty(setting('lng')) && setting('lat') != 0 && setting('lng') != 0)
            var myLatLng = {lat: {{setting('lat')}}, lng: {{setting('lng')}}};
                    @else
            var myLatLng = {lat: 26.820553, lng: 30.802498}; // lat & lng of Cairo Egypt
            @endif
                map_setting = new google.maps.Map(document.getElementById('setting_map'), {
                center: myLatLng,
                zoom: 8
            });

            set_marker = new google.maps.Marker({
                map: map_setting,
                title:"Our Location",
                draggable: false,
                animation: google.maps.Animation.DROP,
                position: myLatLng
            });
            set_marker.addListener('click', toggleBounce_set);

            google.maps.event.addListener(map_setting, 'click', function(event) {
                placeMarker(event.latLng);
                $("input[name=lat]").val(event.latLng.lat());
                $("input[name=lng]").val(event.latLng.lng());
                console.log('lat: ' +event.latLng.lat());
                console.log('lng: ' +event.latLng.lng());
            });


        }

        function placeMarker(location) {
            deleteOverlays();
            set_marker = new google.maps.Marker({
                map: map_setting,
                draggable: true,
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


    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBbD0LpXp1x2hhJskG05TiMh-jB2QV4jG0&callback=initMap" async defer></script>

    <script type="text/javascript">
        $(document).ready(function(){
            CKEDITOR.replace('merchant_welcome_message');
        });

        function addInput($formName,$shownName){
            var $data = '<div class="div-with-files">'+
                '<div class="form-group col-sm-10">'+
                '<div class="controls">'+
                '   <label>'+$shownName+':</label>'+
                '   <input class="form-control" name="'+$formName+'[]" type="text" value="">'+
                '</div>'+
                '</div>'+
                '<div style="padding-top: 40px;" class="col-sm-2 form-group">'+
                '   <a style="color: red;" href="javascript:void(0);" class="remove-file"><i class="fa fa-trash"></i></a>'+
                '</div>'+
                '<div class="col-sm-12">'+
                '   <hr />'+
                '</div>'+
                '</div>';
            $('#uploaddata_'+$formName).append($data);
        }


        $('body').on('click','.remove-file',function(){
            $(this).parents('.div-with-files').remove();
        });

    </script>
@endsection