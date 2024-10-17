@extends('system.layouts')

@section('content')

    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row"></div>
            <div class="content-body">
                <div id="user-profile">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="card profile-with-cover">
                                <div class="card-img-top img-fluid bg-cover height-300" style="background: url('{{asset('assets/system/images/carousel/22.jpg')}}') 50%;"></div>
                                <div class="media profil-cover-details">
                                    @if($result->avatar)
                                        <div class="media-left pl-2 pt-2">
                                            <a href="jaascript:void(0);" class="profile-image">
                                                <img title="{{$result->firstname}} {{$result->lastname}}" src="{{asset('storage/'.image($result->avatar,70,70))}}"  class="rounded-circle img-border height-100"  />
                                            </a>
                                        </div>
                                    @endif
                                    <div class="media-body media-middle row">
                                        <div class="col-xs-6">
                                            <h3 class="card-title" style="margin-bottom: 0.5rem;">
                                                {{$result->firstname}} {{$result->lastname}}
                                                @if($result->status == 'in-active')
                                                    <b style="color: red;">(IN-ACTIVE)</b>
                                                @endif
                                            </h3>
                                            <span>{{$result->address}}</span>
                                        </div>
                                        <div class="col-xs-6 text-xs-right">
                                        </div>
                                    </div>
                                </div>
                                <nav class="navbar navbar-light navbar-profile">
                                    <button class="navbar-toggler hidden-sm-up" type="button" data-toggle="collapse" data-target="#exCollapsingNavbar2" aria-controls="exCollapsingNavbar2" aria-expanded="false" aria-label="Toggle navigation"></button>
                                    <div class="collapse navbar-toggleable-xs" id="exCollapsingNavbar2">
                                        <ul class="nav navbar-nav float-xs-right">
                                            <li class="nav-item active">
                                                {{--<a class="nav-link"  href="javascript:void(0);" onclick="urlIframe('{{route('system.staff-target.create',['id'=>$result->id])}}')"><i class="fa fa-dot-circle-o"></i> {{__('Add Target to :name',['name'=>$result->firstname.' '.$result->lastname])}} <span class="sr-only">(current)</span></a>--}}
                                            </li>

                                            <li class="nav-item active">
                                                <a class="nav-link"  href="javascript:void(0);" onclick="urlIframe('{{route('system.staff.edit',$result->id)}}')"><i class="fa fa-pencil-square-o"></i> {{__('Edit Staff info')}} <span class="sr-only">(current)</span></a>
                                            </li>
                                        </ul>
                                    </div>
                                </nav>
                            </div>
                        </div>
                    </div>



                    <div class="row">
                        <div class="col-md-12">
                            <section id="spacing" class="card">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        {{__('Staff Info')}}
                                        <span style="float: right;"><a class="btn btn-outline-primary"  href="javascript:void(0);" onclick="urlIframe('{{route('system.staff.edit',$result->id)}}')"><i class="fa fa-pencil"></i> {{__('Edit')}}</a></span>
                                    </h4>

                                </div>
                                <div class="card-body collapse in">
                                    <div class="card-block">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>{{__('Value')}}</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                <tr>
                                                    <td>{{__('ID')}}</td>
                                                    <td>{{$result->id}}</td>
                                                </tr>


                                                <tr>
                                                    <td>{{__('Name')}}</td>
                                                    <td>
                                                        {{$result->firstname}} {{$result->lastname}} ( {{$result->job_title}} )
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>{{__('E-Mail')}}</td>
                                                    <td>
                                                        <a href="mailto:{{$result->email}}">{{$result->email}}</a>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>{{__('Mobile')}}</td>
                                                    <td>
                                                        <a href="tel:{{$result->mobile}}">{{$result->mobile}}</a>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>{{__('Gender')}}</td>
                                                    <td>
                                                        {{ucfirst($result->gender)}}
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>{{__('Birthdate')}}</td>
                                                    <td>
                                                        {{$result->birthdate}}
                                                    </td>
                                                </tr>


                                                <tr>
                                                    <td>{{__('Description')}}</td>
                                                    <td>
                                                        <code>{{$result->description}}</code>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>{{__('Permission Group')}}</td>
                                                    <td>
                                                        <a href="{{route('system.permission-group.edit',$result->permission_group_id)}}">{{$result->permission_group->name}}</a>
                                                    </td>
                                                </tr>



                                                <tr>
                                                    <td>{{__('Created At')}}</td>
                                                    <td>
                                                        @if($result->created_at == null)
                                                            --
                                                        @else
                                                            {{$result->created_at->diffForHumans()}}
                                                        @endif
                                                    </td>
                                                </tr>


                                                </tbody>
                                            </table>


                                        </div>
                                    </div>
                                </div>
                            </section>

                        </div>

                    </div>






                </div>

            </div>
        </div>
    </div>
    <!-- ////////////////////////////////////////////////////////////////////////////-->

    <div class="modal fade text-xs-left" id="filter-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <label class="modal-title text-text-bold-600" id="myModalLabel33">{{__('Filter')}}</label>
                </div>
                {!! Form::open(['method'=>'GET'])!!}
                <div class="modal-body">

                    <div class="card-body">
                        <div class="card-block">
                            <div class="row">
                                <div class="col-md-6">
                                    <fieldset class="form-group">
                                        {{ Form::label('created_at1',__('Created From')) }}
                                        {!! Form::text('created_at1',null,['class'=>'form-control datepicker','id'=>'created_at1']) !!}
                                    </fieldset>
                                </div>
                                <div class="col-md-6">
                                    <fieldset class="form-group">
                                        {{ Form::label('created_at2',__('Created To')) }}
                                        {!! Form::text('created_at2',null,['class'=>'form-control datepicker','id'=>'created_at2']) !!}
                                    </fieldset>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <input type="reset" class="btn btn-outline-secondary btn-md" data-dismiss="modal" value="{{__('Close')}}">
                    <input type="submit" class="btn btn-outline-primary btn-md" value="{{__('Filter')}}">
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-map" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">View Map</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8" id="map"></div>
                        <div class="list-group-item col-md-12" id="instructions"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('header')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/extensions/pace.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/pickers/daterange/daterangepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/pickers/datetime/bootstrap-datetimepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/pickers/pickadate/pickadate.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/forms/selects/select2.min.css')}}">

    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/css/core/menu/menu-types/vertical-menu.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/css/core/menu/menu-types/vertical-overlay-menu.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/css/pages/users.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/css/pages/timeline.css')}}">

    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/treegrid/jquery.treegrid.css')}}">

    <style>
        #map{
            height: 500px !important;
            width: 100% !important;
        }
    </style>
@endsection

@section('footer')

    <script type="text/javascript" src="{{asset('assets/system/treegrid/jquery.treegrid.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/system/treegrid/jquery.treegrid.bootstrap3.js')}}"></script>

    <!-- BEGIN PAGE VENDOR JS-->
    <script src="{{asset('assets/system/vendors/js/pickers/dateTime/moment-with-locales.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/dateTime/bootstrap-datetimepicker.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/picker.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/picker.date.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/picker.time.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/legacy.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/daterange/daterangepicker.js')}}" type="text/javascript"></script>
    <!-- END PAGE VENDOR JS-->


    <script src="//maps.googleapis.com/maps/api/js?key={{env('gmap_key')}}" type="text/javascript" async defer></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/gmaps.js/0.4.25/gmaps.min.js" type="text/javascript"></script>

    <script type="text/javascript">

        function addManagedStaff(){
            $('#addManagedStaff-modal').modal('show');
        }

        function addManagedStaffPOST(){
            $formData = $('#add-managed-staff-form').serialize();

            $('#addManagedStaff-button').text('{{__('Loading...')}}').attr('disabled');

            $.post('{{route('system.staff.add-managed-staff')}}',$formData,function($data){
                $('#addManagedStaff-button').text('{{__('Submit')}}').removeAttr('disabled');

                if($data.status == false){
                    $('#addManagedStaff-alert').removeClass('alert-success')
                        .removeClass('alert-danger')
                        .addClass('alert-danger')
                        .text($data.msg);
                }else{
                    $('#addManagedStaff-alert').removeClass('alert-success')
                        .removeClass('alert-danger')
                        .addClass('alert-success')
                        .text($data.msg);

                    setTimeout(function(){
                        location.reload();
                    },2000);

                }
            },'json');
        }

        function viewMap($latitude,$longitude,$title){
            $('#instructions').html('');
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position){

                    $('#modal-map').modal('show');
                    $('#modal-map').on('shown.bs.modal', function (e) {
                        $latitudeMe = position.coords.latitude;
                        $longitudeMe = position.coords.longitude;
                        map = new GMaps({
                            div: '#map',
                            lat: $latitudeMe,
                            lng: $longitudeMe
                        });

                        map.addMarker({
                            lat: $latitude,
                            lng: $longitude,
                            infoWindow: {
                                content: $title
                            }
                        });

                        map.addMarker({
                            lat: $latitudeMe,
                            lng: $longitudeMe,
                            infoWindow: {
                                content: "{{__('My Location')}}"
                            }
                        });

                        map.travelRoute({
                            origin: [$latitudeMe, $longitudeMe],
                            destination: [$latitude, $longitude],
                            travelMode: 'driving',
                            step: function(e){
                                $('#instructions').append('<li class="list-group-item">'+e.instructions+'</li>');
                                $('#instructions li:eq('+e.step_number+')').delay(450*e.step_number).fadeIn(200, function(){
                                    map.setCenter(e.end_location.lat(), e.end_location.lng());
                                    map.drawPolyline({
                                        path: e.path,
                                        strokeColor: '#131540',
                                        strokeOpacity: 0.6,
                                        strokeWeight: 6
                                    });
                                });
                            }
                        });
                    });

                },function () {
                    $('#modal-map').modal('show');
                    $('#modal-map').on('shown.bs.modal', function (e) {
                        map = new GMaps({
                            div: '#map',
                            lat: $latitude,
                            lng: $longitude
                        });

                        map.addMarker({
                            lat: $latitude,
                            lng: $longitude,
                            infoWindow: {
                                content: $title
                            }
                        });
                    });
                });
            } else {
                $('#modal-map').modal('show');
                $('#modal-map').on('shown.bs.modal', function (e) {
                    map = new GMaps({
                        div: '#map',
                        lat: $latitude,
                        lng: $longitude
                    });

                    map.addMarker({
                        lat: $latitude,
                        lng: $longitude,
                        infoWindow: {
                            content: $title
                        }
                    });
                });
            }
        }
        $(document).ready(function() {
            $('#product-list,#merchant-staff').treegrid({
                expanderExpandedClass: 'fa fa-minus',
                expanderCollapsedClass: 'fa fa-plus'
            });


        });


        function changeSalesPOST(){
            var isConfirm =  confirm('Are You Sure ?');
            if(isConfirm){
                $('#changeSales-button').attr('disable','disable');
                $.post('{{route('system.staff.change-merchant-sales')}}',$('#changeSales-form').serialize(),function(response){
                    if(response.status == true){
                        $('#changeSales-modal').modal('hide');
                        $('#changeSales-link').hide();
                        toastr.success(response.data, 'Success', {"closeButton": true});
                    }
                    else{
                        $('#changeSales-button').removeAttr('disable');
                        toastr.error(response.data, 'Error', {"closeButton": true});
                    }

                },'JSON');
            }
        }

        function changeSupervisorPOST(){
            var isConfirm =  confirm('Are You Sure ?');
            if(isConfirm){
                $('#changeSales-button').attr('disable','disable');
                $.post('{{route('system.staff.change-sales-supervisor')}}',$('#changeSupervisor-form').serialize(),function(response){
                    if(response.status == true){
                        $('#changeSupervisor-modal').modal('hide');
                        $('#changeSupervisor-link').hide();
                        toastr.success(response.data, 'Success', {"closeButton": true});
                    }
                    else{
                        $('#changeSupervisor-button').removeAttr('disable');
                        toastr.error(response.data, 'Error', {"closeButton": true});
                    }

                },'JSON');
            }
        }

        function changeStatusPOST(){
            var isConfirm =  confirm('Are You Sure ?');
            if(isConfirm){
                $('#changeSales-button').attr('disable','disable');
                $.post('{{route('system.staff.change-status')}}',$('#changeStatus-form').serialize(),function(response){
                    if(response.status == true){
                        $('#changeStatus-modal').modal('hide');
                        if($('#status_id').val() == 'in-active') {
                            if ($('#changeSupervisor-modal').html() != 'undefined')
                                $('#changeSupervisor-modal').modal('show');
                            if ($('#changeSales-modal').html() != 'undefined')
                                $('#changeSales-modal').modal('show');
                        }
                        toastr.success(response.data, 'Success', {"closeButton": true});
                    }
                    else{
                        $('#changeStatus-button').removeAttr('disable');
                        toastr.error(response.data, 'Error', {"closeButton": true});
                    }

                },'JSON');
            }
        }

        function filterFunction($this){
            if($this == false) {
                $url = '{{url()->full()}}?is_total=true';
            }else {
                $url = '{{url()->full()}}?is_total=true&'+$this.serialize();
            }

            $dataTableVar.ajax.url($url).load();
            $('#filter-modal').modal('hide');
        }

        $(function(){
            $('.datepicker').datetimepicker({
                viewMode: 'months',
                format: 'YYYY-MM-DD'
            });
        });

    </script>
@endsection