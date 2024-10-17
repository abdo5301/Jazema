@extends('system.layouts')
<!-- Modal -->
<div class="modal fade text-xs-left" id="filter-modal"  role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <label class="modal-title text-text-bold-600" id="myModalLabel33">{{__('Filter')}}</label>
            </div>
            {!! Form::open(['id'=>'filterForm','onsubmit'=>'filterFunction($(this));return false;']) !!}
            <div class="modal-body">

                <div class="card-body">
                    <div class="card-block">
                        <div class="row">
                            <div class="col-md-6">
                                <fieldset class="form-group">
                                    {{ Form::label('date1',__('Date 1')) }}
                                    {!! Form::text('date1',null,['class'=>'form-control datepicker','id'=>'date1']) !!}
                                </fieldset>
                            </div>


                            <div class="col-md-6">
                                <fieldset class="form-group">
                                    {{ Form::label('date2',__('Date 2')) }}
                                    {!! Form::text('date2',null,['class'=>'form-control datepicker','id'=>'date2']) !!}
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    {{ Form::label('status',__('Status')) }}
                                    {!! Form::select('status',[''=>__('Select Status'),'equal'=>__('Equal'),'positive'=>__('Positive'),'down'=>__('Down')],null,['class'=>'form-control','id'=>'status']) !!}
                                </fieldset>
                            </div>



                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    {{ Form::label('staffSelect2',__('Sales')) }}
                                    <div>
                                        {!! Form::select('staff_id',[''=>__('Select Staff')],null,['style'=>'width: 100%;' ,'id'=>'staffSelect2','class'=>'form-control col-md-12']) !!}
                                    </div>
                                </fieldset>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <input type="reset" class="btn btn-outline-secondary btn-md" value="{{__('Reset Form')}}">
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
                        @if(staffCan('download.comparison-consumed.excel'))
                            <a onclick="filterFunction($('#filterForm'),true)"  class="btn btn-outline-primary"><i class="ft-download"></i> {{__('Download Excel')}}</a>
                        @endif
                    </h4>
                </div>
                <div class="content-header-right col-md-6 col-xs-12 mb-2">
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
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">{{$pageTitle}}</h4>
                                </div>
                                <div class="card-body collapse in">
                                    <div class="card-block card-dashboard" id="table-data">
                                        <b style="text-align: center;">{{__('Loading...')}}</b>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!--/ Javascript sourced data -->
            </div>
        </div>
    </div>
    <!-- ////////////////////////////////////////////////////////////////////////////-->

@endsection




@section('header')

    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/extensions/pace.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/pickers/daterange/daterangepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/pickers/datetime/bootstrap-datetimepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/pickers/pickadate/pickadate.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/forms/selects/select2.min.css')}}">

@endsection

@section('footer')
    <script src="{{asset('assets/system/vendors/js/forms/select/select2.full.min.js')}}" type="text/javascript"></script>

    <!-- BEGIN PAGE VENDOR JS-->
    <script src="{{asset('assets/system/vendors/js/pickers/dateTime/moment-with-locales.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/dateTime/bootstrap-datetimepicker.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/picker.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/picker.date.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/picker.time.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/legacy.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/daterange/daterangepicker.js')}}" type="text/javascript"></script>
    <!-- END PAGE VENDOR JS-->

    {{--<script src="{{asset('assets/system/js/scripts/pickers/dateTime/picker-date-time.js')}}" type="text/javascript"></script>--}}

    <script type="text/javascript">
        staffSelect('#staffSelect2');

        $(function(){
            $('.datepicker').datetimepicker({
                viewMode: 'months',
                format: 'YYYY-MM-DD'
            });

            getDataForTable('{{\Carbon::now()->subDays(1)->format('Y-m-d')}}','{{\Carbon::now()->subDays(2)->format('Y-m-d')}}');

        });



        function getDataForTable($date1,$date2,$status = '',$sales = '',$downloadExcel = false){

            if($downloadExcel == true) {
                $url = '{{route('merchant.comparison-consumed.show',['ajax'=>'true'])}}&date1=' + $date1 + '&date2=' + $date2 + '&status=' + $status + '&sales=' + $sales + '&downloadExcel=' + $downloadExcel;
               
                window.location = $url

            }else {
                $('#table-data').html('<b style="text-align: center;">{{__('Loading...')}}</b>');

                $.get('{{route('merchant.comparison-consumed.show',['ajax'=>'true'])}}&date1=' + $date1 + '&date2=' + $date2 + '&status=' + $status + '&sales=' + $sales + '&downloadExcel=' + $downloadExcel, function ($data) {
                    $('#table-data').html($data);
                })
            }
        }




        function filterFunction($this,$downloadExcel = false){
            $('#filter-modal').modal('hide');
            getDataForTable($('#date1').val(),$('#date2').val(),$('#status').val(),$('#staffSelect2').val(),$downloadExcel);
        }


    </script>
@endsection