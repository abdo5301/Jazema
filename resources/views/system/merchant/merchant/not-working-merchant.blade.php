@extends('system.layouts')
<div class="modal fade text-xs-left" id="filter-modal"  role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <label class="modal-title text-text-bold-600" id="myModalLabel33">{{__('Filter for Stopped Working Merchants')}}</label>
            </div>
            {{--{!! Form::open(['id'=>'filterForm','onsubmit'=>'filterFunction($(this));return false;']) !!}--}}

            <div class="modal-body">

                <div class="card-body">
                    <div class="card-block">
                        <div class="row">
                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    {{ Form::label('created_at1',__('Period  From')) }}
                                    {!! Form::text('created_at1',null,['class'=>'form-control datepicker','id'=>'created_at1']) !!}
                                </fieldset>
                            </div>
                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    {{ Form::label('staffSelect2',__('Created By')) }}
                                    <div>
                                        {!! Form::select('staff_id',[''=>__('Select Staff')],null,['dddd'=>request('staff_id').'ssss','style'=>'width: 100%;' ,'id'=>'staffSelect2','class'=>'form-control col-md-12']) !!}
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <input type="reset" class="btn btn-outline-secondary btn-md" value="{{__('Reset Form')}}">
                <input type="button" id="filter" class="btn btn-outline-primary btn-md" value="{{__('Filter')}}">
            </div>
            {{--{!! Form::close() !!}--}}
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
                        <a data-toggle="modal"  data-target="#filter-modal" class="btn btn-outline-primary"><i class="ft-search"></i> {{__('Filter')}}</a>
                    </h4>
                </div>
                <div class="content-header-right col-md-8 col-xs-12">
                    <div class=" content-header-title mb-0" style="float: right;">
                        @include('system.breadcrumb')
                    </div>
                </div>
            </div>


            <nav class="navbar navbar-default">
                <div class="container-fluid">
                    <ul class="nav nav-tabs navbar-nav">
                        <li class="nav-item"><a href="#stop-working" class="nav-link active" data-toggle="tab">{{__("Stop Transaction Merchant ")}}</a></li>
                        <li class="nav-item"><a href="#not-working" class="nav-link "  data-toggle="tab">{{__("No Transaction Merchant ")}}</a></li>
                    </ul>
                </div>
            </nav>

            <div class="content-body"><!-- Spacing -->
                <div class="row">
                    <div class="tab-content" id="content">
                        <!--Stop working Merchant -->
                        <div class="col-md-12 tab-pane fade-in active" id="stop-working">
                            <h4>{{__('Stop working Merchant')}} ({{$stopWorkingCount}})</h4>
                            <section id="spacing" class="card">
                                <div class="card-header">

                                </div>
                                <div class="card-body collapse in">
                                    <div class="card-block">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                <tr>
                                                    <td>{{__('ID')}}</td>
                                                    <td>{{__('Merchant Name')}}</td>
                                                    <td>{{__('Seller')}}</td>
                                                    <td>{{__('Created At')}}</td>
                                                    <td>{{__('Wallet Trans')}}</td>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                @foreach($stopWorkingMerchant as $value)
                                                    @if($value->walletowner)

                                                        <tr>
                                                            <td>{{$value->walletowner->id}}</td>
                                                            <td><a target="_blank" href = "{{route('merchant.merchant.show',$value->walletowner->id)}}"> {{$value->walletowner->{'name_'.\DataLanguage::get()} }}</a></td>
                                                            <td><a target="_blank" href = "{{route('system.staff.show',$value->walletowner->staff->id)}}">{{$value->walletowner->staff->Fullname}}</a></td>
                                                            <td>{{$value->walletowner->created_at->diffForHumans()}}</td>
                                                            <td> <button class="btn btn-primary" type="button" onclick='location = "{{route('system.wallet.show',$value->id)}}"'><i class="ft-eye"></i></button></td>

                                                        </tr>
                                                    @endif
                                                @endforeach

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </section>

                        </div>


                        <div class="col-md-12 tab-pane fade-in "  id="not-working">
                            <h4>{{__('Not working Merchants')}} ({{$notWorkingCount}})</h4>
                            <section id="spacing" class="card">
                                <div class="card-header">
                                </div>
                                <div class="card-body collapse in">
                                    <div class="card-block">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                <tr>
                                                    <td>{{__('ID')}}</td>
                                                    <td>{{__('Merchant Name')}}</td>
                                                    <td>{{__('Seller')}}</td>
                                                    <td>{{__('Created At')}}</td>
                                                    <td>{{__('Wallet Trans')}}</td>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                @foreach($result as $value)

                                                    @if($value->walletowner)

                                                        <tr>
                                                            <td>{{$value->walletowner->id}}</td>
                                                            <td><a target="_blank" href="{{route('merchant.merchant.show',$value->walletowner->id)}}"> {{$value->walletowner->{'name_'.\DataLanguage::get()} }}</a></td>
                                                            <td><a target="_blank" href = "{{route('system.staff.show',$value->walletowner->staff->id)}}">{{$value->walletowner->staff->Fullname}}</a></td>
                                                            <td>{{$value->walletowner->created_at->diffForHumans()}}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach

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

@endsection

@section('header')


    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/extensions/pace.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/pickers/daterange/daterangepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/pickers/datetime/bootstrap-datetimepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/pickers/pickadate/pickadate.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/forms/selects/select2.min.css')}}">

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


    <script type="text/javascript">
        staffSelect('#staffSelect2');
        $('#merchant-table').DataTable({
            "iDisplayLength": 25,
            processing: true,
            serverSide: true,
            "order": [[ 0, "desc" ]],
            "ajax": {
                "url": "{{url()->full()}}",
                "type": "GET",
                "data": function(data){
                    data.isMerchant = "true";
                }
            }
        });

        $(function(){
            $('.datepicker').datetimepicker({
                viewMode: 'months',
                format: 'YYYY-MM-DD'
            });
        });
        $(function () {
            $('#filter').click(function () {
                var created_at1 = $('#created_at1').val();
                var staff_id = $('#staffSelect2').val();
                $.ajax({
                    type : 'get',
                    dataType : 'html',
                    url : '{{route('merchant.merchant.not-working-ajax')}}',
                    data : "created_at1=" + created_at1 + "& staff_id=" + staff_id,
                    success: function (response) {
                        console.log(response);
                        $('#content').html(response);
                        $('#filter-modal').modal('hide');
                    }
                });
            });
        });

    </script>
@endsection
