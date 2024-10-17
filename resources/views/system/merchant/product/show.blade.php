@extends('system.layouts')
@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">

                <div class="content-header-left col-md-4 col-xs-12">
                    <h4>
                        @if($result->icon)
                            <img src="{{asset('storage/app/'.imageResize($result->icon,70,70))}}">
                        @endif
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
                    <div class="@if(!$result->upload->isEmpty()) col-md-4 @else col-md-12 @endif">
                        <section class="card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    {{__('Product')}}
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
                                                <td>{{__('Merchant')}}</td>
                                                <td>
                                                    <a target="_blank" href="{{route('merchant.merchant.show',$result->merchant->id)}}">
                                                        {{$result->merchant->{'name_'.\DataLanguage::get()} }}
                                                    </a>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Category')}}</td>
                                                <td>
                                                    <a target="_blank" href="{{route('merchant.category.show',$result->category->id)}}">
                                                        {{$result->category->{'name_'.\DataLanguage::get()} }}
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
                                                <td>
                                                    {!! adminDefineUserWithName($result->creatable_type,$result->creatable_id,\DataLanguage::get()) !!}
                                                </td>
                                            </tr>

                                            @if($result->approved_by_staff_id)
                                                <tr>
                                                    <td>{{__('Approved By')}}</td>
                                                    <td>
                                                        <a href="{{route('system.staff.show',$result->approved->id)}}" target="_blank">
                                                            {{__('#ID')}}:{{$result->approved->id}} <br >{{$result->approved->Fullname}}
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endif

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
                                                        <td><a href="{{url('system/download/'.$value->id)}}" title="{{$value->path}}">{{$value->title}}</a></td>
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
                                {{__('Product Options')}}
                            </h4>
                        </div>
                        <div class="card-body collapse in">
                            <div class="card-block">
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered">
                                        <thead>
                                        <th>{{__('ID')}}</th>
                                        <th>{{__('Name')}}</th>
                                        <th>{{__('Min select')}}</th>
                                        <th>{{__('Max select')}}</th>
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
                                                <td>{{$value->min_select}}</td>
                                                <td>{{$value->max_select}}</td>
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
                                        <th>{{__('Min select')}}</th>
                                        <th>{{__('Max select')}}</th>
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
                                {{__('Product Attributes')}}
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
                                        @foreach($result->attribute as $key=>$value)
                                            <tr>
                                                <td>{{$value->id}}</td>
                                                <td>{{$value->product_attribute->{'name_'.\DataLanguage::get()} }}</td>
                                                <td>{{$value->product_attribute->type }}</td>
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
                                                                    @if($value->product_attribute->type == 'multi-select')
                                                                        @php
                                                                            $attributeValue =   App\Models\MerchantProductAttributeValue::where('merchant_product_attribute_value.product_attribute_id', $value->product_attribute->id)
                                                                            ->join('product_attribute_values','merchant_product_attribute_value.product_attribute_id','=','product_attribute_values.product_attribute_id')
                                                                      ->selectRaw('*, GROUP_CONCAT(DISTINCT(`name_ar`)) as names')
                                                                      ->first()
                                                                        @endphp
                                                                        <td>{{$attributeValue->id}}</td>
                                                                        <td>{{$attributeValue->names}}</td>
                                                                        @elseif($value->product_attribute->type == 'select')
                                                                      @foreach($value->product_attribute->values as $one)
                                                                              <tr>
                                                                            <td>{{$one->id}}</td>
                                                                            <td>{{$one->{'name_'.\DataLanguage::get()} }}</td>
                                                                              </tr>
                                                                          @endforeach
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
                                        <th>{{__('Description')}}</th>
                                        <th>{{__('Value')}}</th>
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
                                {{__('Product Taxes')}}
                            </h4>
                        </div>
                        <div class="card-body collapse in">
                            <div class="card-block">
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered">
                                        <thead>
                                        <th>{{__('ID')}}</th>
                                        <th>{{__('Name')}}</th>
                                        <th>{{__('Description')}}</th>
                                        <th>{{__('Type')}}</th>
                                        <th>{{__('Rate')}}</th>
                                        <th>{{__('Created By')}}</th>
                                        <th>{{__('Show')}}</th>
                                        </thead>
                                        <tbody>
                                        @foreach($taxes as $value)
                                            <tr>
                                                <td>{{$value->id}}</td>
                                                <td>{{$value->{'name_'.\DataLanguage::get()} }}</td>
                                                @if($value->description)
                                                    <td><code>{{str_limit($value->{'description_'.\DataLanguage::get()},25)}}</code></td>
                                                @else
                                                    <td><code>--</code></td>
                                                @endif
                                                <td>{{$value->type}}</td>
                                                <td>{{$value->rate}}</td>
                                                <td><a target="_blank" href="{{route('system.staff.show',$value->staff->id)}}">{{$value->staff->Fullname}}</a></td>
                                                <td><a target="_blank" href="{{route('system.taxes.show',$value->id)}}">{{__('View')}}</a></td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                        <th>{{__('ID')}}</th>
                                        <th>{{__('Name')}}</th>
                                        <th>{{__('Description')}}</th>
                                        <th>{{__('Type')}}</th>
                                        <th>{{__('Rate')}}</th>
                                        <th>{{__('Created By')}}</th>
                                        <th>{{__('Show')}}</th>
                                        </tfoot>
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
@endsection
@section('header')
@endsection;
@section('footer')
    <script type="text/javascript" src="{{asset('assets/system/treegrid/jquery.treegrid.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/system/treegrid/jquery.treegrid.bootstrap3.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#product-list').treegrid({
                expanderExpandedClass: 'fa fa-minus',
                expanderCollapsedClass: 'fa fa-plus'
            });
        });
    </script>
@endsection
