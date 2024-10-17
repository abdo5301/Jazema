@extends('system.layouts')
@section('header')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/forms/selects/select2.min.css')}}">
@endsection
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
            <div class="content-body">
                <!-- Server-side processing -->
                <section id="server-processing">
                    <div class="row">
                        <div class="col-xs-12">
                            @if($errors->any())
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="alert alert-danger">
                                            {{__('Some fields are invalid please fix them')}}
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
                            {!! Form::open(['route' => isset($result->id) ? ['system.item_category.update',$result->id]:'system.item_category.store','files'=>true, 'method' => isset($result->id) ?  'PATCH' : 'POST']) !!}
                                <div class="col-sm-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h2>{{__('English Data')}}</h2>
                                        </div>
                                        <div class="card-block card-dashboard">



                                            <div class="form-group col-sm-12{!! formError($errors,'name_en',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('name_en', __('Name (English)').':') !!}
                                                    {!! Form::text('name_en',isset($result->id) ? $result->name_en:old('name_en'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'name_en') !!}
                                            </div>
                                            <div class="form-group col-sm-12{!! formError($errors,'description_en',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('description_en', __('Description (English)').':') !!}
                                                    {!! Form::textarea('description_en',isset($result->id) ? $result->description_en:old('description_en'),['class'=>'form-control','id'=>'description_en']) !!}
                                                </div>
                                                {!! formError($errors,'description_en') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h2>{{__('Arabic Info')}}</h2>
                                        </div>
                                        <div class="card-block card-dashboard">



                                            <div class="form-group col-sm-12{!! formError($errors,'name_ar',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('name_ar', __('Name (Arabic)').':') !!}
                                                    {!! Form::text('name_ar',isset($result->id) ? $result->name_ar:old('name_ar'),['class'=>'form-control ar']) !!}
                                                </div>
                                                {!! formError($errors,'name_ar') !!}
                                            </div>
                                            <div class="form-group col-sm-12{!! formError($errors,'description_ar',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('description_ar', __('Description (Arabic)').':') !!}
                                                    {!! Form::textarea('description_ar',isset($result->id) ? $result->description_ar:old('description_ar'),['class'=>'form-control ar','id'=>'description_ar']) !!}
                                                </div>
                                                {!! formError($errors,'description_ar') !!}
                                            </div>



                                        </div>
                                    </div>
                                </div>


                                <div class="col-xs-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="card-block card-dashboard">

                                <div class="form-group col-sm-6{!! formError($errors,'icon',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('icon', __('Icon').':') !!}
                                                {!! Form::file('icon',['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'icon') !!}
                                        </div>

                                        <div class="form-group col-sm-6{!! formError($errors,'status',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('status', __('Status').':') !!}
                                                {!! Form::select('status',['active'=>__('Active'),'in-active'=>__('In-Active')],isset($result->id) ? $result->status:old('status'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'status') !!}
                                        </div>
                                        <div class="form-group col-sm-9{!! formError($errors,'parent_id',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('parent_id', __('Parent Category').':') !!}
                                                @if(isset($result->id))
                                                    <select name="parent_id"  class="form-control col-md-12" style="width: 100%;"> <option value="0" disabled selected>{{__('Select Parent Category')}}</option>{{getCategoryTreeSelect(0,'',$result->parent_id)}} </select>
                                                @else
                                                    <select name="parent_id"  class="form-control col-md-12" style="width: 100%;">
                                                        <option value="0" disabled selected>{{__('Select Parent Category')}}</option>
                                                        {{getCategoryTreeSelect(0,'',old('parent_id'))
                                                  }} </select>
                                                @endif
                                            </div>
                                            {!! formError($errors,'Parent') !!}
                                        </div>

                                        <div class="form-group col-sm-3{!! formError($errors,'sort',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('sort', __('Sort').':') !!}
                                                {!! Form::text('sort',isset($result->id) ? $result->sort:old('sort'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'sort') !!}
                                        </div>
                                                </div>
                                                </div>
                                                </div>
                                                </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="card">
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
@section('header')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    @endsection
@section('footer')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script src="{{asset('assets/system')}}/vendors/js/forms/select/select2.full.min.js" type="text/javascript"></script>
    <script src="{{asset('assets/system')}}/js/scripts/select2/select2.custom.js" type="text/javascript"></script>
    <script src="//maps.googleapis.com/maps/api/js?key={{env('gmap_key')}}&libraries=places&callback=initAutocomplete" type="text/javascript" async defer></script>
    <script src="{{asset('assets/system')}}/vendors/js/charts/gmaps.min.js" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            $('.nationality').select2();
        });
        ajaxSelect2('#parent_id','users');
        $('#merchant_id').change(function(){

            // Get Staff Groups
           $.getJSON('{{route('system.ajax.get')}}',{
               'type': 'getMerchantStaffGroup',
               'merchant_id': $(this).val()
           },function($data){

               $return = new Array;
               $return.push('<option value="0">{{__('Select Staff Group')}}</option>');

               $.each($data,function(key,value){
                   $return.push('<option value="'+value.id+'">'+value.title+'</option>');
               });

               $('#merchant_staff_group_id').html($return.join("\n"));
           });

           // Get Merchant Branchs

            $.getJSON('{{route('system.ajax.get')}}',{
                'type': 'getMerchantBranchs',
                'merchant_id': $(this).val()
            },function($data){
                $return = new Array;
                $return.push('<option value="0">{{__('Select Branchs')}}</option>');

                $.each($data,function(key,value){
                    $return.push('<option value="'+value.id+'">'+value.name+'</option>');
                });

                $('#branches').html($return.join("\n"));
            });



        });
    </script>
@endsection