@extends('system.layouts')
<div style="z-index: 9999999;" class="modal fade text-xs-left" id="filter-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <label class="modal-title text-text-bold-600" id="myModalLabel33">{{__('Filter')}}</label>
            </div>
            {!! Form::open(['method'=>'get']) !!}
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



@section('content')

    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12">
                    <h4>
                        {{$pageTitle}}
                        <a data-toggle="modal" data-target="#filter-modal" class="btn btn-outline-primary"><i class="ft-search"></i> {{__('Filter')}}</a>

                    </h4>
                </div>
                <div class="content-header-right col-md-6 col-xs-12 mb-2">
                    <div class="content-header-title mb-0" style="float: right;">
                        @include('system.breadcrumb')
                    </div>
                </div>
            </div>
            <div class="content-body">

                <section id="server-processing">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="card">
                                <div class="card-body collapse in">
                                    <div class="card-block card-dashboard">

                                        <div class="card-body">
                                            <div class="col-md-12">
                                                <table style="background-color: antiquewhite;" class="table table-bordered table-striped">
                                                    {{--<tbody>--}}
                                                    {{--<tr>--}}
                                                        {{--<td>{{__('From Date Time')}}</td>--}}
                                                    {{--</tr>--}}
                                                    {{--<tr>--}}
                                                        {{--<td>{{__('Owner Type')}}</td>--}}
                                                    {{--</tr>--}}
                                                    {{--</tbody>--}}
                                                </table>
                                            </div>
                                            <div class="nav-vertical" >
                                                    <div class="col-md-4" style="z-index: 9999">
                                                    <ul class="nav nav-tabs nav-left nav-border-left" style="width: 100%">
                                                        @foreach($staff as $key => $value)
                                                            <li class="nav-item" style="overflow: hidden">
                                                                <a  class="nav-link @if($key == 0)active @endif read-data" id="{{$value->id}}"
                                                                    data-toggle="tab" aria-controls="tabVerticalLeft1{{$key}}" onclick="getAmount({{$value->id}},'{{request('created_at1')}}','{{request('created_at2')}}')"
                                                                    href="javascript:;" > <span style="padding-right: 0px;">{{$value->firstname}} {{$value->lastname}}<br></span> <span>{{amount($value->total_super,true)}}</span></a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                <div class="tab-content px-1 col-md-8">
                                                    <img src="{{asset('assets/system/loading.gif')}}" style="display: none;margin:0 auto;" id="image">
                                                    <div id="append-data">{{__('Please Select Supervisor')}}</div>
                                                </div>
                                    </div>
                                </div>
                            </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </section>

            </div>
        </div>
    </div>
@endsection
@section('header')
    <style type="text/css">
        a.panel-heading {
            display: block;
        }
        .panel-primary .panel-heading[aria-expanded="true"], .panel-primary .panel-heading a:hover, .panel-primary .panel-heading a:focus, .panel-primary a.panel-heading:hover, .panel-primary a.panel-heading:focus {
            background-color: #286090;
        }

        .panel-danger .panel-heading[aria-expanded="true"], .panel-danger .panel-heading a:hover, .panel-danger .panel-heading a:focus, .panel-danger a.panel-heading:hover, .panel-danger a.panel-heading:focus {
            background-color: #c9302c;
        }

        .panel-default .panel-heading[aria-expanded="true"], .panel-default .panel-heading a:hover, .panel-default .panel-heading a:focus, .panel-default a.panel-heading:hover, .panel-default a.panel-heading:focus {
            background-color: #dcdcdc;
        }

        .panel-info .panel-heading[aria-expanded="true"], .panel-info .panel-heading a:hover, .panel-info .panel-heading a:focus, .panel-info a.panel-heading:hover, .panel-info a.panel-heading:focus {
            background-color: #31b0d5;
        }

        .panel-success .panel-heading[aria-expanded="true"], .panel-success .panel-heading a:hover, .panel-success .panel-heading a:focus, .panel-success a.panel-heading:hover, .panel-success a.panel-heading:focus {
            background-color: #449d44;
        }

        .panel-warning .panel-heading[aria-expanded="true"], .panel-warning .panel-heading a:hover, .panel-warning .panel-heading a:focus, .panel-warning a.panel-heading:hover, .panel-warning a.panel-heading:focus {
            background-color: #ec971f;
        }

        .panel-group .panel, .panel-group .panel-heading {
            border: none !important;
        }

        .panel-group .panel-body {
            border: 1px solid #ddd !important;
            border-width: 0 1px 1px 1px !important;
        }

        .panel-group .panel-heading a, .panel-group a.panel-heading {
            outline: 0;
        }

        .panel-group .panel-heading a:hover, .panel-group .panel-heading a:focus, .panel-group a.panel-heading:hover, .panel-group a.panel-heading:focus {
            text-decoration: none;
        }

        .panel-group .panel-heading .icon-indicator {
            margin-right: 10px;
        }

        .panel-group .panel-heading .icon-indicator:before {
            content: "\e114";
        }

        .panel-group .panel-heading.collapsed .icon-indicator:before {
            content: "\e080";
        }
    </style>

    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/extensions/pace.css')}}">
    <link rel="stylesheet" type="text/css"
          href="{{asset('assets/system/vendors/css/pickers/daterange/daterangepicker.css')}}">
    <link rel="stylesheet" type="text/css"
          href="{{asset('assets/system/vendors/css/pickers/datetime/bootstrap-datetimepicker.css')}}">
    <link rel="stylesheet" type="text/css"
          href="{{asset('assets/system/vendors/css/pickers/pickadate/pickadate.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/forms/selects/select2.min.css')}}">

@endsection


@section('footer')

    <script src="{{asset('assets/system')}}/vendors/js/forms/select/select2.full.min.js"
            type="text/javascript"></script>

    <!-- BEGIN PAGE VENDOR JS-->
    <script src="{{asset('assets/system/vendors/js/pickers/dateTime/moment-with-locales.min.js')}}"
            type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/dateTime/bootstrap-datetimepicker.min.js')}}"
            type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/picker.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/picker.date.js')}}"
            type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/picker.time.js')}}"
            type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/legacy.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/daterange/daterangepicker.js')}}"
            type="text/javascript"></script>
    <!-- END PAGE VENDOR JS-->

    {{--<script src="{{asset('assets/system/js/scripts/pickers/dateTime/picker-date-time.js')}}" type="text/javascript"></script>--}}

    <script type="text/javascript">

        $(document).ready(function(){
            $('#baseVerticalLeft1-tab0').click();
        });
        
        loadedKey = [];
        $(function () {
            $('.datepicker').datetimepicker({
                viewMode: 'months',
                format: 'YYYY-MM-DD'
            });
        });


        function getAmount(id,create_at1=null,create_at2=null) {

                $('#super_id').val(id);
                $("#image").css("display", "block");
                $('#append-data').css("display", "none");
                $.get("{{route('merchant.merchant.total-consumed-ajax')}}", {'id': id,'created_at2':create_at2,'created_at1':create_at1,'downloadExcel':'false'}, function (data) {
                    $("#image").css("display", "none");
                    $('#append-data').css("display", "block");
                    $('#append-data').html(data);
                    $('#filter-modal').modal('hide');
                });
        }

    </script>
@endsection

