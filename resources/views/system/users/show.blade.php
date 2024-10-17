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
                                    {{--@if($result->image)--}}
                                    {{--<div class="media-left pl-2 pt-2">--}}
                                        {{--<a href="jaascript:void(0);" class="profile-image">--}}
                                            {{--<img title="{{$result->firstname}} {{$result->lastname}}" src="{{asset('storage/'.imageResize($result->image,70,70))}}"  class="rounded-circle img-border height-100"  />--}}
                                        {{--</a>--}}
                                    {{--</div>--}}
                                    {{--@endif--}}
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
                                                    <td>{{__('Phone')}}</td>
                                                    <td>
                                                        <a href="tel:{{$result->phone}}">{{$result->phone}}</a>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>{{__('Type')}}</td>
                                                    <td>
                                                        {{$result->type}}
                                                    </td>
                                                </tr>


                                                <tr>
                                                    <td>{{__('Gender')}}</td>
                                                    <td>
                                                        {{$result->gender}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>{{__('Area')}}</td>
                                                    <td><code>{{ implode(' -> ',\App\Libs\AreasData::getAreasUp($result->area_id,true,\DataLanguage::get())) }}</code></td>
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
                                                {{--<tr>--}}
                                                    {{--<td>{{__('National ID Image Front')}}</td>--}}
                                                    {{--@if($result->national_id_image_front)--}}
                                                    {{--<td><a target="_blank" href="{{asset('storage/'.$result->national_id_image_front)}}">{{__('view')}}</a></td>--}}
                                                        {{--@else--}}
                                                    {{--<td>--</td>--}}
                                                        {{--@endif--}}
                                                {{--</tr>--}}
                                                <tr>
                                                    <td>{{__('Company Name')}}</td>
                                                    @if($result->company_name)
                                                        <td><code>{{$result->company_name}}</code></td>
                                                    @else
                                                        <td><code>--</code></td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <td>{{__('Company Business')}}</td>
                                                    @if($result->company_name)
                                                        <td><code>{{$result->company_business}}</code></td>
                                                    @else
                                                        <td><code>--</code></td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <td>{{__('Created By')}}</td>
                                                    @if(!empty($result->staff_id))
                                                    <td><a target="_blank" href="{{route('system.staff.show',$result->staff->id)}}">{{$result->staff->Fullname}}</a></td>
                                                        @else
                                                    <td>--</td>
                                                        @endif
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



                        <div class="col-md-8">
                            <section id="server-processing" class="card">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        {{__('Interseted Item Categories')}}
                                        {{--<span style="float: right;"><a class="btn btn-outline-primary"  href="javascript:void();" onclick="urlIframe('{{route('system.relatives.create',['user_id'=>$result->id])}}')"><i class="fa fa-pencil"></i> {{__('Add User\'s Relatives')}}</a></span>--}}
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
                                                            <th>{{__('Icon')}}</th>
                                                            <th>{{__('Name')}}</th>
                                                            <th>{{__('Description')}}</th>
                                                            <th>{{__('Staff')}}</th>
                                                            <th>{{__('Created At')}}</th>
                                                            <th>{{__('Action')}}</th>
                                                        </tr>
                                                        </thead>
                                                        <tfoot>
                                                        <tr>
                                                            <th>{{__('ID')}}</th>
                                                            <th>{{__('Icon')}}</th>
                                                            <th>{{__('Name')}}</th>
                                                            <th>{{__('Description')}}</th>
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

                            <div>
                                <section class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">
                                            {{__('User Attributes')}}
                                        </h4>
                                    </div>
                                    <div class="card-body collapse in">
                                        <div class="card-block">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-bordered">
                                                    <thead>
                                                    <th>{{__('ID')}}</th>
                                                    <th>{{__('Name')}}</th>
                                                    <th>{{__('Type')}}</th>
                                                    <th>{{__('Value')}}</th>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($result->select_attribute as $key=>$value)
                                                        <tr>
                                                            <td>{{$value->id}}</td>
                                                            <td>{{$value->attribute->{'name_'.\DataLanguage::get()} }}</td>
                                                            <td>{{$value->attribute->type }}</td>
                                                            @if(!empty($value->value))
                                                                <td>{{$value->value}}</td>
                                                            @else
                                                                <td>
                                                                    <div class="card-body collapse in">
                                                                        <div class="card-block">
                                                                            <div class="table-responsive">
                                                                                <table class="table table-hover table-bordered">
                                                                                    <thead>
                                                                                    <th>{{__('ID')}}</th>
                                                                                    <th>{{__('Name')}}</th>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                    @if($value->attribute->type == 'multi-select')
                                                                                        @php
                                                                                            $attributeValue =   App\Models\AttributeValues::where('attribute_values.attribute_id', $value->attribute->id)
                                                                                            ->join('selected_attribute_values','selected_attribute_values.attribute_id','=','attribute_values.attribute_id')
                                                                                            ->selectRaw('*, GROUP_CONCAT(DISTINCT(`name_ar`)) as names')
                                                                                            ->first();
                                                                                        @endphp
                                                                                        <td>{{$attributeValue->id}}</td>
                                                                                        <td>{{$attributeValue->names}}</td>
                                                                                    @elseif($value->attribute->type == 'select')

                                                                                        <tr>
                                                                                            <td>{{$value->values->id}}</td>
                                                                                            <td>{{$value->values{'name_'.\DataLanguage::get()} }}</td>
                                                                                        </tr>

                                                                                    @endif
                                                                                    </tbody>
                                                                                    <tfoot>
                                                                                    <th>{{__('ID')}}</th>
                                                                                    <th>{{__('Name')}}</th>
                                                                                    </tfoot>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            @endif
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                    <th>{{__('ID')}}</th>
                                                    <th>{{__('Name')}}</th>
                                                    <th>{{__('Type')}}</th>
                                                    <th>{{__('Value')}}</th>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>

                            <div>
                                <section id="server-processing" class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">
                                            {{__('Items')}}
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
                                                        <table style="text-align: center;" id="item-datatable" class="table table-striped table-bordered">
                                                            <thead>
                                                            <tr>
                                                                <th>{{__('ID')}}</th>
                                                                <th>{{__('Item Category')}}</th>
                                                                <th>{{__('Item Type')}}</th>
                                                                <th>{{__('Price')}}</th>
                                                                <th>{{__('Quantity')}}</th>
                                                                <th>{{__('Status')}}</th>
                                                                <th>{{__('Created At')}}</th>
                                                                <th>{{__('Action')}}</th>
                                                            </tr>
                                                            </thead>
                                                            <tfoot>
                                                            <tr>
                                                                <th>{{__('ID')}}</th>
                                                                <th>{{__('Item Category')}}</th>
                                                                <th>{{__('Item Type')}}</th>
                                                                <th>{{__('Price')}}</th>
                                                                <th>{{__('Quantity')}}</th>
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
                                            {{__('Deals')}}
                                        </h4>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                                                </div>
                                                <div class="card-body collapse in">
                                                    <div class="card-block card-dashboard table-responsive">
                                                        <table style="text-align: center;" id="deal-datatable" class="table table-striped table-bordered">
                                                            <thead>
                                                            <tr>
                                                                <th>{{__('ID')}}</th>
                                                                <th>{{__('Item ')}}</th>
                                                                <th>{{__('Item Owner')}}</th>
                                                                <th>{{__('Total Price')}}</th>
                                                                <th>{{__('Status')}}</th>
                                                                <th>{{__('Created By')}}</th>
                                                                <th>{{__('Created At')}}</th>
                                                                <th>{{__('Action')}}</th>
                                                            </tr>
                                                            </thead>
                                                            <tfoot>
                                                            <tr>
                                                                <th>{{__('ID')}}</th>
                                                                <th>{{__('Item ')}}</th>
                                                                <th>{{__('Item Owner')}}</th>
                                                                <th>{{__('Total Price')}}</th>
                                                                <th>{{__('Status')}}</th>
                                                                <th>{{__('Created By')}}</th>
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

@endsection

@section('header')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/css/core/menu/menu-types/vertical-menu.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/css/core/menu/menu-types/vertical-overlay-menu.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/css/pages/users.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/css/pages/timeline.css')}}">

    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/treegrid/jquery.treegrid.css')}}">
@endsection

@section('footer')

    <script type="text/javascript" src="{{asset('assets/system/treegrid/jquery.treegrid.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/system/treegrid/jquery.treegrid.bootstrap3.js')}}"></script>


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
        $dataTableVar = $('#deal-datatable').DataTable({
            "iDisplayLength": 25,
            processing: true,
            serverSide: true,
            "order": [[ 0, "desc" ]],
            "ajax": {
                "url": "{{url()->full()}}",
                "type": "GET",
                "data": function(data){
                    data.isDeal = "true";
                }
            }

        });
        {{--$dataTableVar = $('#deal-datatable').DataTable({--}}
            {{--"iDisplayLength": 25,--}}
            {{--processing: true,--}}
            {{--serverSide: true,--}}
            {{--"order": [[ 0, "desc" ]],--}}
            {{--"ajax": {--}}
                {{--"url": "{{route('system.deal.index')}}?user_id={{$result->id}}",--}}
                {{--"type": "GET",--}}
                {{--"data": function(data){--}}
                    {{--data.isDataTable = "true";--}}
                {{--}--}}
            {{--}--}}
        {{--});--}}

        $dataTableVar = $('#item-datatable').DataTable({
            "iDisplayLength": 25,
            processing: true,
            serverSide: true,
            "order": [[ 0, "desc" ]],
            "ajax": {
                "url": "{{url()->full()}}",
                "type": "GET",
                "data": function(data){
                    data.isItem = "true";
                }
            }

        });Item

    </script>
@endsection
