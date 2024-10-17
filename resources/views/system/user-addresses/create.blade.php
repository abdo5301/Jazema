@extends('system.layouts')
@section('header')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/forms/selects/select2.min.css')}}">
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
                                        <div class="alert alert-{{Session::get('status')}}">
                                            {{ Session::get('msg') }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                            {!! Form::open(['route' => isset($result->id) ? ['system.address.update',$result->id]:'system.address.store','files'=>true, 'method' => isset($result->id) ?  'PATCH' : 'POST']) !!}
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-block card-dashboard">

                                        {!! Form::hidden('user_id',request('user_id'),['class'=>'form-control']) !!}
                                        <div class="form-group col-sm-6{!! formError($errors,'type',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('type', __('Type').':') !!}
                                                {!! Form::select('type',['work'=>__('Work'),'home'=>__('Home'),'other'=>__('Other')],isset($result->id) ? $result->type:old('type'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'type') !!}
                                        </div>

                                        <div class="form-group col-sm-6{!! formError($errors,'street_name',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('street_name', __('Street Name').':') !!}
                                                {!! Form::text('street_name',isset($result->id) ? $result->street_name:old('street_name'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'street_name') !!}
                                        </div>


                                        <div class="form-group col-sm-6{!! formError($errors,'building_number',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('building_number', __('Building Number').':') !!}
                                                {!! Form::text('building_number',isset($result->id) ? $result->building_number:old('building_number'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'building_number') !!}
                                        </div>

                                        <div class="form-group col-sm-6{!! formError($errors,'floor_number',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('floor_number', __('Floor Number').':') !!}
                                                {!! Form::text('floor_number',isset($result->id) ? $result->floor_number:old('floor_number'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'floor_number') !!}
                                        </div>


                                        <div class="form-group col-sm-12{!! formError($errors,'flat_number',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('flat_number', __('Flat Number').':') !!}
                                                {!! Form::text('flat_number',isset($result->id) ? $result->flat_number:old('flat_number'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'flat_number') !!}
                                        </div>


                                        <div class="form-group col-sm-12{!! formError($errors,'direction',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('direction', __('direction').':') !!}
                                                {!! Form::textarea('direction',isset($result->id) ? $result->direction:old('direction'), ['class' => 'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'direction') !!}
                                        </div>

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


                                            <div class="col-sm-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h2>{{__('Determine Location')}}</h2>
                                                    </div>
                                                    <div class="card-block card-dashboard">
                                                        <input style="width:80%" id="pac-input" class="controls form-control" type="text" placeholder="{{__('Search Box')}}">
                                                        <div id="map-events" class="height-400" style="margin-bottom: 15px;"></div>
                                                        <div class="form-group col-sm-6{!! formError($errors,'latitude',true) !!}">
                                                            <div class="controls">
                                                                {!! Form::label('latitude', __('Latitude').':') !!}
                                                                {!! Form::text('latitude',isset($result->id) ? $result->latitude:old('latitude'),['class'=>'form-control']) !!}
                                                            </div>
                                                            {!! formError($errors,'latitude') !!}
                                                        </div>
                                                        <div class="form-group col-sm-6{!! formError($errors,'longitude',true) !!}">
                                                            <div class="controls">
                                                                {!! Form::label('longitude', __('Longitude').':') !!}
                                                                {!! Form::text('longitude',isset($result->id) ? $result->longitude:old('longitude'),['class'=>'form-control']) !!}
                                                            </div>
                                                            {!! formError($errors,'longitude') !!}
                                                        </div>
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
                                            {!! Form::submit(__('Save'),['class'=>'btn btn-success pull-right']) !!}
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
@section('header')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
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
        $(document).ready(function() {
            $('.nationality').select2();
        });
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
   $(function(){
            $('.datepicker').datetimepicker({
                viewMode: 'years',
                format: 'YYYY-MM-DD'
            });

            // Branch
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
                $('#branch_latitude').val(location.lat());
                $('#branch_longitude').val(location.lng());
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

ajaxSelect2('#parent_id','users');
        $('#merchant_id').change(function(){

            // Get Staff Groups
           $.getJSON('{{route('system.ajax.get')}}',{
               'type': 'getMerchantStaffGroup',
               'merchant_id': $(this).val()
           },function($data){

               $return = new Array;
               $return.push('<option value="0">{{__('Select Staff Group')}}</option>');

               $.each($data,function(key,value){
                   $return.push('<option value="'+value.id+'">'+value.title+'</option>');
               });

               $('#merchant_staff_group_id').html($return.join("\n"));
           });

           // Get Merchant Branchs

            $.getJSON('{{route('system.ajax.get')}}',{
                'type': 'getMerchantBranchs',
                'merchant_id': $(this).val()
            },function($data){
                $return = new Array;
                $return.push('<option value="0">{{__('Select Branchs')}}</option>');

                $.each($data,function(key,value){
                    $return.push('<option value="'+value.id+'">'+value.name+'</option>');
                });

                $('#branches').html($return.join("\n"));
            });



        });
    </script>
@endsection