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
            {!! Form::open(['onsubmit'=>'filterFunction($(this));return false;']) !!}
            <div class="modal-body">

                <div class="card-body">
                    <div class="card-block">
                        <div class="row">
                            <div class="col-md-6">
                                <fieldset class="form-group">
                                    {{ Form::label('id',__('ID')) }}
                                    {!! Form::number('id',null,['class'=>'form-control','id'=>'id']) !!}
                                </fieldset>
                            </div>
                            <div class="col-md-6">
                                <fieldset class="form-group">
                                    {{ Form::label('id',__('Name')) }}
                                    {!! Form::text('name',null,['class'=>'form-control','id'=>'name']) !!}
                                </fieldset>
                            </div>
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

                <div class="content-header-left col-md-4 col-xs-12">
                    <h4>
                        {{$pageTitle}}
                        <a data-toggle="modal" data-target="#filter-modal" class="btn btn-outline-primary"><i class="ft-search"></i> {{__('Filter')}}</a>
                    </h4>
                </div>
                <div class="content-header-right col-md-8 col-xs-12 mb-2">
                    <div class=" content-header-title mb-0" style="float: right;">
                        @include('system.breadcrumb')
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Server-side processing -->
                <div class="row">
                    @if($errors->any())
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="alert alert-danger">
                                    {{__('Some fields are invalid please fix them')}}
                                    <ul>
                                        @foreach($errors->all() as $key => $value)
                                            <li>{{$key}}: {{$value}}</li>
                                        @endforeach
                                    </ul>
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
                    <section id="server-processing">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">{{$pageTitle}}</h4>
                                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                                        <div class="heading-elements">
                                            <ul class="list-inline mb-0">
                                                <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                                <li><a onclick="filterFunction(false);"><i class="ft-rotate-cw"></i></a></li>
                                                <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="card-body collapse in">
                                        <div class="card-block card-dashboard">
                                            <table style="text-align: center;" id="egpay-datatable" class="table table-striped table-bordered">
                                                <thead>
                                                <tr>
                                                    @foreach($tableColumns as $key => $value)
                                                        <th>{{$value}}</th>
                                                    @endforeach
                                                </tr>
                                                </thead>
                                                <tfoot>
                                                <tr>
                                                    @foreach($tableColumns as $key => $value)
                                                        <th>{{$value}}</th>
                                                    @endforeach
                                                </tr>
                                                </tfoot>
                                            </table>
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
    </div>

    <!-- ////////////////////////////////////////////////////////////////////////////-->

@endsection




@section('header')

    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/extensions/pace.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/pickers/daterange/daterangepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/pickers/datetime/bootstrap-datetimepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/pickers/pickadate/pickadate.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/forms/selects/select2.min.css?time='.time())}}">

@endsection;

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
        ajaxSelect2('#staff_id','staff','',"{!! route('system.ajax.get')!!}");
        function loadIframe(iframeName, url) {
            var $iframe = $('#' + iframeName);
            if ( $iframe.length ) {
                $iframe.attr('src',url);    // here you can change src
                return false;
            }
            return true;
        }
        staffSelect('#staffSelect2')
        $dataTableVar = $('#egpay-datatable').DataTable({
            "iDisplayLength": 25,
            processing: true,
            serverSide: true,
            "order": [[ 0, "desc" ]],
            "ajax": {
                "url": "{{url()->full()}}",
                "type": "GET",
                "data": function(data){
                    data.isDataTable = "true";
                }
            },
            "fnPreDrawCallback": function(oSettings) {
                for (var i = 0, iLen = oSettings.aoData.length; i < iLen; i++) {
                    if(oSettings.aoData[i]._aData[6] != ''){
                        oSettings.aoData[i].nTr.className = oSettings.aoData[i]._aData[6];
                    }
                }
            }

        });

        function filterFunction($this){
            if($this == false) {
                $url = '{{url()->full()}}?isDataTable=true';
            }else {
                $url = '{{url()->full()}}?isDataTable=true&'+$this.serialize();
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
