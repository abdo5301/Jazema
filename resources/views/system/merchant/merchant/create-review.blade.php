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
                <div class="content-header-right col-md-8 col-xs-12 mb-2">
                    <div class=" content-header-title mb-0" style="float: right;">
                        @include('system.breadcrumb')
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Server-side processing -->
                <section id="server-processing">
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
                        {!! Form::open(['route' => isset($result->id) ? ['merchant.merchant.update-review',$result->id]:'merchant.merchant.post-review','method' => isset($result->id) ?  'PATCH' : 'POST']) !!}

                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-block card-dashboard">
                                    <a href="{{route('merchant.merchant.show',$merchant_id)}}" target="_blank" style="float: right;">View</a>
                                    <div class="form-group col-sm-12{!! formError($errors,'comment',true) !!}">
                                        <div class="controls">
                                            {!! Form::label('comment', __('Comment').':') !!}
                                            {!! Form::textarea('comment',isset($result->id) ? $result->comment:old('comment'),['class'=>'form-control']) !!}
                                        </div>
                                        {!! formError($errors,'description_en') !!}
                                    </div>
                                    {!! Form::hidden('id',isset($result->id) ? $result->comment:old('id'),['class'=>'form-control']) !!}

                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="card-header">
                                <div class="col-sm-12">

                                    <div class="card-header">
                                        <h2>{{__('Options')}}</h2>
                                    </div>
                                    <div class="card-block card-dashboard">

                                        <div class="form-group col-sm-12{!! formError($errors,'status',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('status', __('Status').':') !!}
                                                {!! Form::select('status',[''=>__('Select Status'),'approved'=>__('approved'),'disapproved'=>__('disapproved')],isset($result->id) ? $result->type:old('status'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'status') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12" style="padding-top: 20px;">
                            <div class="card-header">
                                <div class="card-body">
                                    <div class="card-block card-dashboard">
                                        {!! Form::submit(__('Save'),['class'=>'btn btn-success pull-right']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </section>
                <!--/ Javascript sourced data -->
            </div>
        </div>
    </div>

    <!-- ////////////////////////////////////////////////////////////////////////////-->

@endsection
@section('footer')
    <script src="{{asset('assets/system/js/scripts')}}/custom/CustomInputLoyaltyPrograms.js"></script>
    {{--<script type="text/javascript">--}}

    {{--$(document).ready(function(){--}}
    {{--list_type_function();--}}
    {{--});--}}

    {{--function list_type_function(){--}}
    {{--$value = $('#list_type').val();--}}
    {{--if($value == 'static'){--}}
    {{--$('#dynamic-point-div').hide();--}}
    {{--$('#static-point-div').show();--}}
    {{--}else{--}}
    {{--$('#static-point-div').hide();--}}
    {{--$('#dynamic-point-div').show();--}}
    {{--}--}}
    {{--}--}}

    {{--</script>--}}
@endsection