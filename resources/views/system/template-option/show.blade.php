@extends('system.layouts')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-4 col-xs-12">
                    <h4>
                        {{$pageTitle}}
                    </h4>
                </div>
                <div class="content-header-right col-md-8 col-xs-12">
                    <div class=" content-header-title mb-0" style="float: right;">
                        @include('system.breadcrumb')
                    </div>
                </div>
            </div>
            <div class="content-body"><!-- Spacing -->
                <div class="row">



                    <div class="col-md-4">
                        <section id="spacing" class="card">
                            <div class="card-header">

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
                                              <td>
                                                  {{$result->name_ar}}
                                              </td>
                                            <tr>
                                                <td>{{__('Name En')}}</td>
                                              <td>
                                                  {{$result->name_en}}
                                              </td>
                                            </tr>


                                            <tr>
                                                <td>{{__('Item Category')}}</td>

                                                <td>
                                                    <a target="_blank" href="{{route('system.item_category.show',$result->itemCategory->id)}}" target="_blank">{{$result->itemCategory->{'name_'.\DataLanguage::get()} }}</a>
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

                    <div class="col-md-8">
                        <section class="card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    {{__('Template Option Values')}}
                                </h4>
                            </div>
                            <div class="card-body collapse in">
                                <div class="card-block">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th>{{__('#')}}</th>
                                                        <th>{{__('Name')}}</th>
                                                        <th>{{__('Price Prefix')}}</th>
                                                        <th>{{__('Price ')}}</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @if(!empty($values))
                                                        @foreach($values as $key=>$value)
                                                        <tr>
                                                            <td>{{$key +=1}}</td>
                                                            <td>{{$value['name_'.\DataLanguage::get()]}}</td>
                                                            <td>{{$value['price_prefix']}}</td>
                                                            <td>{{amount($value['price'],true)}}</td>
                                                        </tr>
                                                        @endforeach
                                                        @endif
                                                    </tbody>
                                                    <tfoot>
                                                    <tr>
                                                        <th>{{__('#')}}</th>
                                                        <th>{{__('Name')}}</th>
                                                        <th>{{__('Price Prefix')}}</th>
                                                        <th>{{__('Price ')}}</th>
                                                    </tr>

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
@endsection

@section('footer')
            <script>

            </script>

@endsection
