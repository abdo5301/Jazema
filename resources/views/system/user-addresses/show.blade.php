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



                    <div class="col-md-12">
                        <section id="spacing" class="card">
                            <div class="card-header">
                                {{--<nav class="navbar navbar-light navbar-profile">--}}
                                    {{--<h4 class="card-title">--}}
                                        {{--{{__('User Info')}}--}}
                                        {{--<span style="float: right;"><a class="btn btn-outline-primary"  href="javascript:void();" onclick="urlIframe('{{route('system.address.edit',[$result->id])}}')"><i class="fa fa-pencil"></i> {{__('Edit')}}</a></span>--}}
                                    {{--</h4>--}}
                                {{--</nav>--}}

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
                                                <td>{{__('User')}}</td>
                                                <td>
                                                    {{$result->user->Fullname}}
                                                </td>
                                            </tr> <tr>
                                                <td>{{__('Type')}}</td>
                                                <td>
                                                    {{$result->type}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>{{__('Street Name')}}</td>
                                                <td>
                                                    {{$result->street_name}}

                                                </td>
                                            </tr>
                                            <tr>
                                                <td>{{__('Building Number')}}</td>
                                                <td>
                                                    {{$result->building_number}}

                                                </td>
                                            </tr>
                                            <tr>
                                                <td>{{__('Floor Number')}}</td>
                                                <td>
                                                    {{$result->floor_number}}

                                                </td>
                                            </tr>
                                            <tr>
                                                <td>{{__('Flat Number')}}</td>
                                                <td>
                                                    {{$result->floor_number}}

                                                </td>
                                            </tr>
                                            <tr>
                                                <td>{{__('Direction')}}</td>
                                                <td>
                                                  <code>{{$result->direction}}</code>

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



                </div>
            </div>
        </div>
    </div>

@endsection

@section('header')
@endsection

@section('footer')

@endsection
