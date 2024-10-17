@extends('system.layouts')

@section('content')

    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-md-4">
                    <section id="spacing" class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                {{__('Item Category Info')}}
                                <span style="float: right;"><a class="btn btn-outline-primary"  href="javascript:void();" onclick="urlIframe('{{route('system.item_category.edit',[$result->id])}}')"><i class="fa fa-pencil"></i> {{__('Edit')}}</a></span>
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
                                            <td>{{__('Name Ar')}}</td>
                                            <td>{{$result->name_ar}}</td>
                                        </tr>
                                        <tr>
                                            <td>{{__('Name En')}}</td>
                                            <td>{{$result->name_en}}</td>
                                        </tr>
                                        <tr>
                                            <td>{{__('Description ar')}}</td>
                                            @if($result->description_ar)
                                            <td><code>{{$result->description_ar}}</code></td>
                                                @else
                                            <td><code>--</code></td>
                                                @endif
                                        </tr>
                                        <tr>
                                            <td>{{__('Description En')}}</td>
                                            @if($result->description_en)
                                                <td><code>{{$result->description_en}}</code></td>
                                            @else
                                                <td><code>--</code></td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <td>{{__('Parent')}}</td>

                                            @if(!empty($result->parent->id))
                                                <td><code><a target="_blank" href="{{route('system.item_category.show',$result->parent->id)}}">{{$result->parent->{'name_'.\DataLanguage::get()} }}</a></code></td>
                                            @else
                                                <td><code>--</code></td>
                                            @endif

                                        </tr>
                                        <tr>
                                            <td>{{__('sort')}}</td>
                                            <td>{{$result->sort}}</td>
                                        </tr>
                                        <tr>
                                            <td>{{__('Created By')}}</td>
                                            <td>{{$result->staff->Fullname}}</td>
                                        </tr>
                                        <tr>
                                            <td>{{__('Created At')}}</td>
                                            @if($result->created_at)
                                                <td>{{$result->created_at->diffForHumans()}}</td>
                                            @else
                                                <td>--</td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <td>{{__('Updated At')}}</td>
                                            @if($result->updated_at)
                                                <td>{{$result->updated_at->diffForHumans()}}</td>
                                            @else
                                                <td>--</td>
                                            @endif
                                        </tr>
                                        </tbody>
                                    </table>


                                </div>
                            </div>
                        </div>
                    </section>

                </div>
                <div class="col-md-8">
                    <section id="server-processing" class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                {{__('Items')}}
                                {{--                                        <span style="float: right;"><a class="btn btn-outline-primary"  href="javascript:void();" onclick="urlIframe('{{route('system.relatives.create',['user_id'=>$result->id])}}')"><i class="fa fa-pencil"></i> {{__('Add User\'s Relatives')}}</a></span>--}}

                            </h4>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="card">
                                    <div class="card-header">
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
                                        <div class="card-block card-dashboard table-responsive">
                                            <table style="text-align: center;" id="egpay-datatable" class="table table-striped table-bordered">
                                                <thead>
                                                <tr>
                                                    <th>{{__('ID')}}</th>
                                                    <th>{{__('User')}}</th>
                                                    <th>{{__('Item Category')}}</th>
                                                    <th>{{__('Name')}}</th>
                                                    <th>{{__('Price')}}</th>
                                                    <th>{{__('Quantity')}}</th>
                                                    <th>{{__('Status')}}</th>
                                                    <th>{{__('Staff')}}</th>
                                                    <th>{{__('Created At')}}</th>
                                                    <th>{{__('Action')}}</th>
                                                </tr>
                                                </thead>
                                                <tfoot>
                                                <tr>
                                                    <th>{{__('ID')}}</th>
                                                    <th>{{__('User')}}</th>
                                                    <th>{{__('Item Category')}}</th>
                                                    <th>{{__('Name')}}</th>
                                                    <th>{{__('Price')}}</th>
                                                    <th>{{__('Quantity')}}</th>
                                                    <th>{{__('Status')}}</th>
                                                    <th>{{__('Staff')}}</th>
                                                    <th>{{__('Created At')}}</th>
                                                    <th>{{__('Action')}}</th>
                                                </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{--<div>--}}
                    {{--<section id="server-processing" class="card">--}}
                    {{--<div class="card-header">--}}
                    {{--<h4 class="card-title">--}}
                    {{--{{__('User Addresses')}}--}}
                    {{--<span style="float: right;"><a class="btn btn-outline-primary"  href="javascript:void();" onclick="urlIframe('{{route('system.address.create',['user_id'=>$result->id])}}')"><i class="fa fa-pencil"></i> {{__('Add User\'s Address')}}</a></span>--}}


                    {{--</h4>--}}
                    {{--</div>--}}
                    {{--<div class="row">--}}
                    {{--<div class="col-xs-12">--}}
                    {{--<div class="card">--}}
                    {{--<div class="card-header">--}}
                    {{--<a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>--}}
                    {{--<div class="heading-elements">--}}
                    {{--<ul class="list-inline mb-0">--}}
                    {{--<li><a data-action="collapse"><i class="ft-minus"></i></a></li>--}}
                    {{--<li><a onclick="filterFunction(false);"><i class="ft-rotate-cw"></i></a></li>--}}
                    {{--<li><a data-action="expand"><i class="ft-maximize"></i></a></li>--}}
                    {{--</ul>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="card-body collapse in">--}}
                    {{--<div class="card-block card-dashboard ">--}}
                    {{--<table style="text-align: center;" id="addresses-datatable" class="table table-striped table-bordered">--}}
                    {{--<thead>--}}
                    {{--<tr>--}}
                    {{--<th>{{__('ID')}}</th>--}}
                    {{--<th>{{__('Address')}}</th>--}}
                    {{--<th>{{__('Direction')}}</th>--}}
                    {{--<th>{{__('Action')}}</th>--}}
                    {{--</tr>--}}
                    {{--</thead>--}}
                    {{--<tfoot>--}}
                    {{--<tr>--}}
                    {{--<th>{{__('ID')}}</th>--}}
                    {{--<th>{{__('Address')}}</th>--}}
                    {{--<th>{{__('Direction')}}</th>--}}
                    {{--<th>{{__('Action')}}</th>--}}
                    {{--</tr>--}}
                    {{--</tfoot>--}}
                    {{--</table>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</section>--}}
                    {{--</div>--}}
                    {{--<div>--}}
                    {{--<section id="server-processing" class="card">--}}
                    {{--<div class="card-header">--}}
                    {{--<h4 class="card-title">--}}
                    {{--{{__('Payment From User')}}--}}
                    {{--<span style="float: right;"><a class="btn btn-outline-primary"  href="javascript:void();" onclick="urlIframe('{{route('system.address.create',['user_id'=>$result->id])}}')"><i class="fa fa-pencil"></i> {{__('Add User\'s Address')}}</a></span>--}}
                    {{--</h4>--}}
                    {{--</div>--}}
                    {{--<div class="row">--}}
                    {{--<div class="col-xs-12">--}}
                    {{--<div class="card">--}}
                    {{--<div class="card-header">--}}
                    {{--<a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>--}}
                    {{--<div class="heading-elements">--}}
                    {{--<ul class="list-inline mb-0">--}}
                    {{--<li><a data-action="collapse"><i class="ft-minus"></i></a></li>--}}
                    {{--<li><a onclick="filterFunction(false);"><i class="ft-rotate-cw"></i></a></li>--}}
                    {{--<li><a data-action="expand"><i class="ft-maximize"></i></a></li>--}}
                    {{--</ul>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="card-body collapse in">--}}
                    {{--<div class="card-block card-dashboard ">--}}
                    {{--<table style="text-align: center;" id="fromUser-datatable" class="table table-striped table-bordered">--}}
                    {{--<thead>--}}
                    {{--<tr>--}}
                    {{--<th>{{__('ID')}}</th>--}}
                    {{--<th>{{__('To User')}}</th>--}}
                    {{--<th>{{__('Service')}}</th>--}}
                    {{--<th>{{__('Amount')}}</th>--}}
                    {{--<th>{{__('Total Amount')}}</th>--}}
                    {{--<th>{{__('Status')}}</th>--}}
                    {{--<th>{{__('Created At')}}</th>--}}
                    {{--<th>{{__('Action')}}</th>--}}
                    {{--</tr>--}}
                    {{--</thead>--}}
                    {{--<tfoot>--}}
                    {{--<tr>--}}
                    {{--<th>{{__('ID')}}</th>--}}
                    {{--<th>{{__('To User')}}</th>--}}
                    {{--<th>{{__('Service')}}</th>--}}
                    {{--<th>{{__('Amount')}}</th>--}}
                    {{--<th>{{__('Total Amount')}}</th>--}}
                    {{--<th>{{__('Status')}}</th>--}}
                    {{--<th>{{__('Created At')}}</th>--}}
                    {{--<th>{{__('Action')}}</th>--}}
                    {{--</tr>--}}
                    {{--</tfoot>--}}
                    {{--</table>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</section>--}}
                    {{--</div>--}}
                    {{--<div>--}}
                    {{--<section id="server-processing" class="card">--}}
                    {{--<div class="card-header">--}}
                    {{--<h4 class="card-title">--}}
                    {{--{{__('Payment To User')}}--}}
                    {{--<span style="float: right;"><a class="btn btn-outline-primary"  href="javascript:void();" onclick="urlIframe('{{route('system.address.create',['user_id'=>$result->id])}}')"><i class="fa fa-pencil"></i> {{__('Add User\'s Address')}}</a></span>--}}
                    {{--</h4>--}}
                    {{--</div>--}}
                    {{--<div class="row">--}}
                    {{--<div class="col-xs-12">--}}
                    {{--<div class="card">--}}
                    {{--<div class="card-header">--}}
                    {{--<a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>--}}
                    {{--<div class="heading-elements">--}}
                    {{--<ul class="list-inline mb-0">--}}
                    {{--<li><a data-action="collapse"><i class="ft-minus"></i></a></li>--}}
                    {{--<li><a onclick="filterFunction(false);"><i class="ft-rotate-cw"></i></a></li>--}}
                    {{--<li><a data-action="expand"><i class="ft-maximize"></i></a></li>--}}
                    {{--</ul>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="card-body collapse in">--}}
                    {{--<div class="card-block card-dashboard ">--}}
                    {{--<table style="text-align: center;" id="toUser-datatable" class="table table-striped table-bordered">--}}
                    {{--<thead>--}}
                    {{--<tr>--}}
                    {{--<th>{{__('ID')}}</th>--}}
                    {{--<th>{{__('To User')}}</th>--}}
                    {{--<th>{{__('Service')}}</th>--}}
                    {{--<th>{{__('Amount')}}</th>--}}
                    {{--<th>{{__('Total Amount')}}</th>--}}
                    {{--<th>{{__('Status')}}</th>--}}
                    {{--<th>{{__('Created At')}}</th>--}}
                    {{--<th>{{__('Action')}}</th>--}}
                    {{--</tr>--}}
                    {{--</thead>--}}
                    {{--<tfoot>--}}
                    {{--<tr>--}}
                    {{--<th>{{__('ID')}}</th>--}}
                    {{--<th>{{__('To User')}}</th>--}}
                    {{--<th>{{__('Service')}}</th>--}}
                    {{--<th>{{__('Amount')}}</th>--}}
                    {{--<th>{{__('Total Amount')}}</th>--}}
                    {{--<th>{{__('Status')}}</th>--}}
                    {{--<th>{{__('Created At')}}</th>--}}
                    {{--<th>{{__('Action')}}</th>--}}
                    {{--</tr>--}}
                    {{--</tfoot>--}}
                    {{--</table>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</section>--}}
                    {{--</div>--}}
                </div>

            </div>





        </div>

    </div>
    </div>
    </div>
    <!-- ////////////////////////////////////////////////////////////////////////////-->
    <div class="modal fade" id="modal-map" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg"" role="document">
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



    <script src="//maps.googleapis.com/maps/api/js?key={{env('gmap_key')}}" type="text/javascript" async defer></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/gmaps.js/0.4.25/gmaps.min.js" type="text/javascript"></script>

    <script type="text/javascript">


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
            }

        });
        $dataTableVar = $('#toUser-datatable').DataTable({
            "iDisplayLength": 25,
            processing: true,
            serverSide: true,
            "order": [[ 0, "desc" ]],
            "ajax": {
                "url": "{{url()->full()}}",
                "type": "GET",
                "data": function(data){
                    data.isToUser = "true";
                }
            }

        });
        $dataTableVar = $('#fromUser-datatable').DataTable({
            "iDisplayLength": 25,
            processing: true,
            serverSide: true,
            "order": [[ 0, "desc" ]],
            "ajax": {
                "url": "{{url()->full()}}",
                "type": "GET",
                "data": function(data){
                    data.isUserFrom = "true";
                }
            }

        });
        $dataTableVar = $('#addresses-datatable').DataTable({
            "iDisplayLength": 25,
            processing: true,
            serverSide: true,
            "order": [[ 0, "desc" ]],
            "ajax": {
                "url": "{{url()->full()}}",
                "type": "GET",
                "data": function(data){
                    data.isAddresses = "true";
                }
            }

        });
        function setDefault($routeName,$reload =3000){
            if(!confirm("Do you want to Set This address as Default address ?")){
                return false;
            }

            if($reload == undefined){
                $reload = 3000;
            }

            $.post(
                    $routeName,
                    {
                        '_method':'POST',
                        '_token':$('meta[name="csrf-token"]').attr('content')
                    },
                    function(response){
                        if(isJSON(response)){
                            $data = response;
                            if($data.status == true){
                                toastr.success($data.msg, 'Success !', {"closeButton": true});
                                if($reload){
                                    setTimeout(function(){location.reload();},$reload);
                                }
                            }else{
                                toastr.error($data.msg, 'Error !', {"closeButton": true});
                            }
                        }
                    }
            )
        }

    </script>
@endsection
