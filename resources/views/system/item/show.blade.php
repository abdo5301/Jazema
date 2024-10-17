@extends('system.layouts')
@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">

                <div class="content-header-left col-md-4 col-xs-12">
                    <h4>
                        {{--@if($result->icon)--}}
                            {{--<img src="{{img($result->icon,70,70)}}">--}}
                        {{--@endif--}}
                        {{$pageTitle}}
                    </h4>
                </div>
                <div class="content-header-right col-md-8 col-xs-12 mb-2">
                    <div class=" content-header-title mb-0" style="float: right;">
                        @include('system.breadcrumb')
                    </div>
                </div>
            </div>
            <div class="content-body"><!-- Spacing -->
                <div class="row">
                    <div class="col-md-12">
                    <div class="@if(!$result->upload->isEmpty()) col-md-4 @else col-md-12 @endif">
                        <section class="card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    {{__('Item')}}
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
                                                <td>{{__('Category')}}</td>
                                                <td>
                                                    <a target="_blank" href="{{route('system.item_category.show',$result->item_category->id)}}">
                                                        {{$result->item_category->{'name_'.\DataLanguage::get()} }}
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>{{__('Type')}}</td>
                                                <td>
                                                    <a target="_blank" href="{{route('system.item_type.show',$result->item_type->id)}}">
                                                        {{$result->item_type->{'name_'.\DataLanguage::get()} }}
                                                    </a>
                                                </td>
                                            </tr>


                                            <tr>
                                                <td>{{__('Name')}} </td>
                                                <td>{{ $result->{'name_'.\DataLanguage::get()} }}</td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Description')}}</td>
                                                <td><code>{{ $result->{'description_'.\DataLanguage::get()} }}</code></td>
                                            </tr>
                                            <tr>
                                                <td>{{__('Quantity')}}</td>
                                                <td>{{$result->quantity}}</td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Price')}}</td>
                                                <td>{{$result->price}} {{__('LE')}}</td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Created By')}}</td>
                                                @if($result->creatable_id)
                                                    <td>
                                                        {!! adminDefineUserWithName($result->creatable_type,$result->creatable_id,\DataLanguage::get()) !!}
                                                    </td>
                                                @endif
                                            </tr>
                                            <tr>
                                                <td>{{__('User')}}</td>

                                                <td>
                                                    <a href="{{route('system.users.show',$result->user->id)}}" target="_blank">
                                                        {{__('#ID')}}:{{$result->user->id}} <br >{{$result->user->Fullname}}
                                                    </a>

                                                </td>
                                            </tr>

                                            @if($result->owner_user_id)
                                                <tr>
                                                    <td>{{__('Shared By')}}</td>
                                                    <td>
                                                        <a href="{{route('system.users.show',$result->owner_user->id)}}" target="_blank">
                                                            {{__('#ID')}}:{{$result->owner_user->id}} <br >{{$result->owner_user->Fullname}}
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <td>{{__('Views')}}</td>
                                                <td>{{$result->views}}</td>
                                            </tr>
                                            <tr>
                                                <td>{{__('Likes')}}</td>
                                                <td>{{$result->like}}</td>
                                            </tr>
                                            <tr>
                                                <td>{{__('Num of Likes')}}</td>
                                                <td>{{$result->likes()->count()}}</td>
                                            </tr>
                                            <tr>
                                                <td>{{__('Shares')}}</td>
                                                <td>{{$result->share}}</td>
                                            </tr>
                                            <tr>
                                                <td>{{__('Deals')}}</td>
                                                <td>{{$result->deals}}</td>
                                            </tr>
                                            <tr>
                                                <td>{{__('Ranks')}}</td>
                                                <td>{{$result->rank}}</td>
                                            </tr>
                                            <tr>
                                                <td>{{__('Status')}}</td>
                                                @if($result->status =='active')
                                                    <td><code style="color: green">{{$result->status}}</code></td>
                                                @else
                                                    <td><code style="color: red">{{$result->status}}</code></td>
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

                                            <tr>
                                                <td>{{__('Updated At')}}</td>
                                                <td>
                                                    @if($result->updated_at == null)
                                                        --
                                                    @else
                                                        {{$result->updated_at->diffForHumans()}}
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


                    @if(!$result->upload->isEmpty())
                        <div class="col-md-8">

                            <section class="card">
                                <div class="card-header">
                                    <h4 class="card-title">{{__('Files')}}</h4>
                                </div>
                                <div class="card-body collapse in">
                                    <div class="card-block">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                <tr>
                                                    <th>{{__('File')}}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($result->upload as $value)
                                                    <tr>
                                                        <td><img style="width: 70px;height: 70px;" src="{{  img($value->path)}}" alt="{{$result->{'name_'.\DataLanguage::get()} }}"></td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </section>
                        </div>
                        @endif

                                <!-- Tables Section-->
                        <div class="col-md-12">

                            <section class="card">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        {{__('Item Options')}}
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
                                                <th>{{__('Status')}}</th>
                                                <th>{{__('Sort')}}</th>
                                                <th>{{__('Values')}}</th>
                                                </thead>
                                                <tbody>
                                                @foreach($result->option()->orderBy('sort')->get() as $value)
                                                    <tr>
                                                        <td>{{$value->id}}</td>
                                                        <td>{{$value->{'name_'.\DataLanguage::get()} }}</td>
                                                        <td>{{$value->type}}</td>
                                                        <td>{{$value->status}}</td>
                                                        <td>{{$value->sort}}</td>
                                                        @if($value->values->isNotEmpty())
                                                            <td>
                                                                <div class="card-body collapse in">
                                                                    <div class="card-block">
                                                                        <div class="table-responsive">
                                                                            <table class="table table-hover table-bordered">
                                                                                <thead>
                                                                                <th>{{__('ID')}}</th>
                                                                                <th>{{__('Name')}}</th>
                                                                                <th>{{__('Price')}}</th>
                                                                                <th>{{__('Price Prefix')}}</th>
                                                                                <th>{{__('Status')}}</th>
                                                                                </thead>
                                                                                <tbody>
                                                                                @foreach($value->values()->where('status','!=','deleted')->get() as $row)
                                                                                    <tr>
                                                                                        <td>{{$row->id}}</td>
                                                                                        <td>{{$row->{'name_'.\DataLanguage::get()} }}</td>
                                                                                        <td>{{$row->price}}</td>
                                                                                        <td>{{$row->price_prefix}}</td>
                                                                                        <td>{{$row->status}}</td>
                                                                                    </tr>
                                                                                @endforeach
                                                                                </tbody>
                                                                                <tfoot>
                                                                                <th>{{__('ID')}}</th>
                                                                                <th>{{__('Name')}}</th>
                                                                                <th>{{__('Price')}}</th>
                                                                                <th>{{__('Price Prefix')}}</th>
                                                                                <th>{{__('Status')}}</th>
                                                                                </tfoot>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        @else
                                                            <td><code>--</code></td>
                                                    @endif
                                                @endforeach
                                                </tbody>
                                                <tfoot>
                                                <th>{{__('ID')}}</th>
                                                <th>{{__('Name')}}</th>
                                                <th>{{__('Type')}}</th>
                                                <th>{{__('Status')}}</th>
                                                <th>{{__('Sort')}}</th>
                                                <th>{{__('Values')}}</th>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>

                        <div class="col-md-12">
                            <section class="card">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        {{__('Item Attributes')}}
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
                        <div class="col-md-12">
                            <section id="server-processing" class="card">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        {{__('Ranks')}}
                                    </h4>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                                                <div class="heading-elements">
                                                </div>
                                            </div>

                                            <div class="card-body collapse in">
                                                <div class="card-block card-dashboard table-responsive">
                                                    <table style="text-align: center;" id="rank-datatable" class="table table-striped table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th>{{__('ID')}}</th>
                                                            <th>{{__('User')}}</th>
                                                            <th>{{__('Comment')}}</th>
                                                            <th>{{__('Created At')}}</th>

                                                        </tr>
                                                        </thead>
                                                        <tfoot>
                                                        <tr>
                                                            <th>{{__('ID')}}</th>
                                                            <th>{{__('User')}}</th>
                                                            <th>{{__('Comment')}}</th>
                                                            <th>{{__('Created At')}}</th>
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
                        <div class="col-md-12">
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
                                                <div class="heading-elements">
                                                </div>
                                            </div>

                                            <div class="card-body collapse in">
                                                <div class="card-block card-dashboard table-responsive">
                                                    <table style="text-align: center;" id="item-datatable" class="table table-striped table-bordered">
                                                        <thead>
                                                        <tr>

                                                            <th>{{__('ID')}}</th>
                                                            <th>{{__('Item')}}</th>
                                                            <th>{{__('User')}}</th>
                                                            <th>{{__('Shared From')}}</th>
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
                                                            <th>{{__('Item')}}</th>
                                                            <th>{{__('User')}}</th>
                                                            <th>{{__('Shared From')}}</th>
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
                        <div class="col-md-6">
                            <section id="server-processing" class="card">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        {{__('Comments')}}
                                    </h4>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                                                <div class="heading-elements">
                                                </div>
                                            </div>

                                            <div class="card-body collapse in">
                                                <div class="card-block card-dashboard table-responsive">
                                                    <table style="text-align: center;" id="comment-datatable" class="table table-striped table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th>{{__('ID')}}</th>
                                                            <th>{{__('User')}}</th>
                                                            <th>{{__('Comment')}}</th>
                                                            <th>{{__('Status')}}</th>
                                                            <th>{{__('Created At')}}</th>

                                                        </tr>
                                                        </thead>
                                                        <tfoot>
                                                        <tr>
                                                            <th>{{__('ID')}}</th>
                                                            <th>{{__('User')}}</th>
                                                            <th>{{__('Comment')}}</th>
                                                            <th>{{__('Status')}}</th>
                                                            <th>{{__('Created At')}}</th>
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
                        <div class="col-md-6">
                            <section id="server-processing" class="card">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        {{__('Likes')}}
                                    </h4>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                                                <div class="heading-elements">
                                                </div>
                                            </div>

                                            <div class="card-body collapse in">
                                                <div class="card-block card-dashboard table-responsive">
                                                    <table style="text-align: center;" id="like-datatable" class="table table-striped table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th>{{__('ID')}}</th>
                                                            <th>{{__('User')}}</th>
                                                            <th>{{__('Created At')}}</th>

                                                        </tr>
                                                        </thead>
                                                        <tfoot>
                                                        <tr>
                                                            <th>{{__('ID')}}</th>
                                                            <th>{{__('User')}}</th>
                                                            <th>{{__('Created At')}}</th>
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
@endsection
@section('header')
@endsection;
@section('footer')
    <script type="text/javascript" src="{{asset('assets/system/treegrid/jquery.treegrid.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/system/treegrid/jquery.treegrid.bootstrap3.js')}}"></script>
    <script type="text/javascript">
        //        $(document).ready(function(){
        //            $('#product-list').treegrid({
        //                expanderExpandedClass: 'fa fa-minus',
        //                expanderCollapsedClass: 'fa fa-plus'
        //            });
        //        });

        $dataTableVar = $('#rank-datatable').DataTable({
            "iDisplayLength": 25,
            processing: true,
            serverSide: true,
            "order": [[ 0, "desc" ]],
            "ajax": {
                "url": "{{url()->full()}}",
                "type": "GET",
                "data": function(data){
                    data.isRank = "true";
                }
            }

        });
        $dataTableVar = $('#comment-datatable').DataTable({
            "iDisplayLength": 25,
            processing: true,
            serverSide: true,
            "order": [[ 0, "desc" ]],
            "ajax": {
                "url": "{{url()->full()}}",
                "type": "GET",
                "data": function(data){
                    data.isComment = "true";
                }
            }
        });
        $dataTableVar = $('#item-datatable').DataTable({
            "iDisplayLength": 25,
            processing: true,
            serverSide: true,
            "order": [[ 0, "desc" ]],
            "ajax": {
                "url": "{{route('system.deal.index')}}?item_id={{$result->id}}",
                "type": "GET",
                "data": function(data){
                    data.isDataTable = "true";
                }
            }
        });
        $dataTableVar = $('#like-datatable').DataTable({
            "iDisplayLength": 25,
            processing: true,
            serverSide: true,
            "order": [[ 0, "desc" ]],
            "ajax": {
                "url": "{{url()->full()}}",
                "type": "GET",
                "data": function(data){
                    data.isLike = "true";
                }
            }
        });
    </script>
@endsection
