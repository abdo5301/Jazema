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
                                    @if($result->image)
                                    <div class="media-left pl-2 pt-2">
                                        <a href="jaascript:void(0);" class="profile-image">
                                            <img title="{{$result->firstname}} {{$result->lastname}}" src="{{asset('storage/'.imageResize($result->image,70,70))}}"  class="rounded-circle img-border height-100"  />
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
                                            {{--<li class="nav-item active">--}}
                                                {{--<a class="nav-link"  href="javascript:void();" onclick="urlIframe('{{route('system.users.edit',$result->id)}}')"><i class="fa-line-chart"></i> {{__('Edit User info')}} <span class="sr-only">(current)</span></a>--}}
                                            {{--</li>--}}
                                            {{--<li class="nav-item active">--}}
                                                {{--<a class="nav-link"  href="javascript:void();" onclick="urlIframe('{{route('system.address.create',['user_id'=>$result->id])}}')"><i class="fa-line-chart"></i> {{__('Add User\'s Address')}} <span class="sr-only">(current)</span></a>--}}
                                            {{--</li>--}}
                                        </ul>
                                    </div>
                                </nav>
                            </div>
                        </div>
                    </div>

                    {!! Form::hidden('id',isset($result->id) ? $result->comment:old('id'),['class'=>'form-control']) !!}

                    <div class="row">
                        <div class="col-md-4">
                            <section id="spacing" class="card">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        {{__('User Info')}}
                                        <span style="float: right;"><a class="btn btn-outline-primary"  href="javascript:void();" onclick="urlIframe('{{route('system.users.edit',[$result->id])}}')"><i class="fa fa-pencil"></i> {{__('Edit')}}</a></span>
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
                                                    {{--<td>{{$result->firstname}} {{$result->firstname}}</td>--}}
                                                    <td>{{$result->Fullname}}</td>
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
                                                    <td>{{__('National ID')}}</td>
                                                    <td>
                                                        {{$result->national_id}}
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>{{__('Birthdate')}}</td>
                                                    <td>
                                                        {{$result->birthdate}}
                                                    </td>
                                                </tr>


                                                <tr>
                                                    <td>{{__('Gender')}}</td>
                                                    <td>
                                                        {{$result->gender}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>{{__('Nationality')}}</td>
                                                    <td>
                                                    <code>{{$result->nationality}}</code>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>{{__('Status')}}</td>
                                                    <td>
                                                        @if($result->status == 'active')
                                                            <b style="color: green;">Active</b>
                                                        @else
                                                            <b style="color: red;">In-Active</b>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>{{__('National ID Image Front')}}</td>
                                                    @if($result->national_id_image_front)
                                                    <td><a target="_blank" href="{{asset('storage/'.$result->national_id_image_front)}}">{{__('view')}}</a></td>
                                                        @else
                                                    <td>--</td>
                                                        @endif
                                                </tr>
                                                <tr>
                                                    <td>{{__('National ID Image Back')}}</td>
                                                    @if($result->national_id_image_back)
                                                        <td><a target="_blank" href="{{asset('storage/'.$result->national_id_image_back)}}">{{__('view')}}</a></td>
                                                    @else
                                                        <td>--</td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <td>{{__('Last Login')}}</td>
                                                    <td>
                                                        @if($result->lastlogin == null)
                                                            --
                                                        @else
                                                            {{$result->lastlogin->diffForHumans()}}
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
                        <div class="col-md-8">
                            <section id="server-processing" class="card">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        {{__('User Relatives')}}
                                        <span style="float: right;"><a class="btn btn-outline-primary"  href="javascript:void();" onclick="urlIframe('{{route('system.relatives.create',['user_id'=>$result->id])}}')"><i class="fa fa-pencil"></i> {{__('Add User\'s Relatives')}}</a></span>

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
                                                <div class="card-block card-dashboard">
                                                    <table style="text-align: center;" id="egpay-datatable" class="table table-striped table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th>{{__('ID')}}</th>
                                                            <th>{{__('Relative User')}}</th>
                                                            {{--<th>{{__('User Relative Relation')}}</th>--}}
                                                            <th>{{__('Name')}}</th>
                                                            <th>{{__('Mobile')}}</th>
                                                            <th>{{__('Created At')}}</th>
                                                            <th>{{__('Action')}}</th>
                                                        </tr>
                                                        </thead>
                                                        <tfoot>
                                                        <tr>
                                                            <th>{{__('ID')}}</th>
                                                            <th>{{__('Relative User')}}</th>
                                                            {{--<th>{{__('User Relative Relation')}}</th>--}}
                                                            <th>{{__('Name')}}</th>
                                                            <th>{{__('Mobile')}}</th>
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

                            <div>
                                <section id="server-processing" class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">
                                            {{__('User Addresses')}}
                                            <span style="float: right;"><a class="btn btn-outline-primary"  href="javascript:void();" onclick="urlIframe('{{route('system.address.create',['user_id'=>$result->id])}}')"><i class="fa fa-pencil"></i> {{__('Add User\'s Address')}}</a></span>


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
                                                    <div class="card-block card-dashboard ">
                                                        <table style="text-align: center;" id="addresses-datatable" class="table table-striped table-bordered">
                                                            <thead>
                                                            <tr>
                                                                <th>{{__('ID')}}</th>
                                                                <th>{{__('Address')}}</th>
                                                                <th>{{__('Direction')}}</th>
                                                                <th>{{__('Action')}}</th>
                                                            </tr>
                                                            </thead>
                                                            <tfoot>
                                                            <tr>
                                                                <th>{{__('ID')}}</th>
                                                                <th>{{__('Address')}}</th>
                                                                <th>{{__('Direction')}}</th>
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
                            </div>
                            <div>
                                <section id="server-processing" class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">
                                            {{__('Payment From User')}}
                                            {{--<span style="float: right;"><a class="btn btn-outline-primary"  href="javascript:void();" onclick="urlIframe('{{route('system.address.create',['user_id'=>$result->id])}}')"><i class="fa fa-pencil"></i> {{__('Add User\'s Address')}}</a></span>--}}
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
                                                    <div class="card-block card-dashboard ">
                                                        <table style="text-align: center;" id="fromUser-datatable" class="table table-striped table-bordered">
                                                            <thead>
                                                            <tr>
                                                                <th>{{__('ID')}}</th>
                                                                <th>{{__('To User')}}</th>
                                                                <th>{{__('Service')}}</th>
                                                                <th>{{__('Amount')}}</th>
                                                                <th>{{__('Total Amount')}}</th>
                                                                <th>{{__('Status')}}</th>
                                                                <th>{{__('Created At')}}</th>
                                                                <th>{{__('Action')}}</th>
                                                            </tr>
                                                            </thead>
                                                            <tfoot>
                                                            <tr>
                                                                <th>{{__('ID')}}</th>
                                                                <th>{{__('To User')}}</th>
                                                                <th>{{__('Service')}}</th>
                                                                <th>{{__('Amount')}}</th>
                                                                <th>{{__('Total Amount')}}</th>
                                                                <th>{{__('Status')}}</th>
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
                            </div>
                            <div>
                                <section id="server-processing" class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">
                                            {{__('Payment To User')}}
                                            {{--<span style="float: right;"><a class="btn btn-outline-primary"  href="javascript:void();" onclick="urlIframe('{{route('system.address.create',['user_id'=>$result->id])}}')"><i class="fa fa-pencil"></i> {{__('Add User\'s Address')}}</a></span>--}}
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
                                                    <div class="card-block card-dashboard ">
                                                        <table style="text-align: center;" id="toUser-datatable" class="table table-striped table-bordered">
                                                            <thead>
                                                            <tr>
                                                                <th>{{__('ID')}}</th>
                                                                <th>{{__('To User')}}</th>
                                                                <th>{{__('Service')}}</th>
                                                                <th>{{__('Amount')}}</th>
                                                                <th>{{__('Total Amount')}}</th>
                                                                <th>{{__('Status')}}</th>
                                                                <th>{{__('Created At')}}</th>
                                                                <th>{{__('Action')}}</th>
                                                            </tr>
                                                            </thead>
                                                            <tfoot>
                                                            <tr>
                                                                <th>{{__('ID')}}</th>
                                                                <th>{{__('To User')}}</th>
                                                                <th>{{__('Service')}}</th>
                                                                <th>{{__('Amount')}}</th>
                                                                <th>{{__('Total Amount')}}</th>
                                                                <th>{{__('Status')}}</th>
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
                            </div>
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
                    data.isRelative = "true";
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
