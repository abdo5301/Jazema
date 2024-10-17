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
                            {!! Form::open(['route' => isset($result->id) ? ['system.attributes.update',$result->id]:'system.attributes.store','method' => isset($result->id) ?  'PATCH' : 'POST','files' => true,'id' => isset($result->id) ?  'editForm' : 'addForm']) !!}


                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h2>{{__('Attribute Data')}}</h2>
                                    </div>
                                    <div class="card-block card-dashboard">
                                        @if(isset($att_type) && $att_type== 'job' || (isset($result->id) &&$result->model_type == 'App\Models\UserJob'))
                                        <div class="form-group col-sm-12{!! formError($errors,'user_job_id',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('user_job_id', __('user Job').':') !!}
                                                {!! Form::select('user_job_id',array_merge([0=>__('select')],$userJobs),isset($result->id) ? $result->model_id:old('user_job_id'),['class'=>'form-control'])  !!}
                                                    <input type="hidden" name="attr_type" value="job">
                                            </div>
                                            {!! formError($errors,'user_job_id') !!}
                                        </div>
                                                    @elseif(isset($att_type) && $att_type== 'item' || isset($result->item_type_id))
                                                    <div class="form-group col-sm-12{!! formError($errors,'item_category_id',true) !!}">
                                                        <div class="controls">
                                                    {!! Form::label('item_category_id', __('Item Category').':') !!}
                                                    {!! Form::select('item_category_id',array_merge([0=>__('select')],$itemCategories),isset($result->id) ? $result->model_id:old('item_category_id'),['class'=>'form-control item_category_id'])  !!}
                                                            <input type="hidden" name="attr_type" value="item">
                                            </div>
                                            {!! formError($errors,'item_category_id') !!}
                                        </div>
                                                    <div class="form-group col-sm-12{!! formError($errors,'item_type_id',true) !!}">
                                                        <div class="controls">
                                                    {!! Form::label('item_type_id', __('Item Type').':') !!}
                                                    {!! Form::select('item_type_id',array_merge([0=>__('select')],$itemTypes),isset($result->id) ? $result->item_type_id:old('item_type_id'),['class'=>'form-control item_type_id']) !!}
                                            </div>
                                            {!! formError($errors,'item_type_id') !!}
                                        </div>
                                @endif


                                        <div class="form-group col-sm-6{!! formError($errors,'name_en',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('name_en', __('Name (English)').':') !!}
                                                {!! Form::text('name_en',isset($result->id) ? $result->name_en:old('name_en'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'name_en') !!}
                                        </div>
                                        <div class="form-group col-sm-6{!! formError($errors,'name_ar',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('name_ar', __('Name (Arabic)').':') !!}
                                                {!! Form::text('name_ar',isset($result->id) ? $result->name_ar:old('name_ar'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'name_ar') !!}
                                        </div>
                                        <div class="form-group col-sm-6{!! formError($errors,'is_required',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('option_is_required', __('Is Required').':') !!}
                                                <div class="input-group input-group-lg">
                                                    {!! Form::select('is_required',['yes'=>__('Yes'),'no'=>__('No')],isset($result->id) ? $result->is_required:old('is_required'),['class'=>'form-control is_required']) !!}
                                                </div>
                                            </div>
                                            {!! formError($errors,'is_required') !!}
                                        </div>
                                        <div class="form-group col-sm-6{!! formError($errors,'name_ar',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('type', __('Type').':') !!}
                                                {!! Form::select('type',[''=>'Select Type','text'=>__('Text'),'number'=>__('Number'),'textarea'=>__('textarea'),'select'=>__('Select'),'multi_select'=>__('Multi Select'),'date'=>__('Date'),'datetime'=>__('datetime'),'location'=>__('location'),'file'=>__('File')],isset($result->id) ? $result->type:old('type'),['onchange'=>'check_option_type()','class'=>'form-control ','id'=>'option_type']) !!}
                                            </div>
                                            {!! formError($errors,'type') !!}
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="card">

                                    <div class="card-block card-dashboard">


                                        <div id="option_value_list"   style="display: none" >


                                            <div style="float: right;"
                                                 class="col-sm-6">
                                                <button type="button"
                                                        class="btn btn-primary fa fa-plus add_option_value_button"
                                                        onclick="add_option_value()">
                                                    <span>{{__('Add Value')}}</span>
                                                </button>
                                            </div>

                                            @if(isset($result->id) && $result->values->isNotEmpty())
                                                @foreach($result->values as $key=>$value)
                                                    <div class="option_value_row" style="width: 100%;display: table;">
                                                        <div class="form-group col-sm-4{!! formError($errors,'option_value_name_ar',true) !!}">
                                                            <div class="controls">
                                                                {!! Form::label('option_value_name_ar', __('Name Ar').':') !!}
                                                                <div class="input-group input-group-lg">
                                                                    {!! Form::text('option['.$value["id"].'][option_value_name_ar]',$value['name_ar'],['class'=>'form-control option_value_name_ar']) !!}
                                                                </div>
                                                            </div>
                                                            {!! formError($errors,'option_value_name_ar') !!}
                                                        </div>
                                                        <div class="form-group col-sm-4{!! formError($errors,'option_value_name_en',true) !!}">
                                                            <div class="controls">
                                                                {!! Form::label('option_value_name_en', __('Name En').':') !!}
                                                                <div class="input-group input-group-lg">
                                                                    {!! Form::text('option['.$value["id"].'][option_value_name_en]',$value['name_en'],['class'=>'form-control option_value_name_en']) !!}
                                                                </div>
                                                            </div>
                                                            {!! formError($errors,'option_value_name_en') !!}
                                                        </div>

                                                        <div class="form-group col-sm-2{!! formError($errors,'option_value_sort',true) !!}">
                                                            <div class="controls">
                                                                {!! Form::label('option_value_price', __('Sort').':') !!}
                                                                <div class="input-group input-group-lg">
                                                                    {!! Form::number('option['.$value["id"].'][option_value_sort]',$value['sort'],['class'=>'form-control option_value_sort']) !!}
                                                                </div>
                                                            </div>
                                                            {!! formError($errors,'option_value_sort') !!}
                                                        </div>
                                                        {{--<div class="form-group col-sm-2">--}}
                                                            {{--<a href="javascript:void(0);"--}}
                                                               {{--onclick="$(this).closest('.option_value_row').remove();"--}}
                                                               {{--class="text-danger">--}}
                                                                {{--<i class="fa fa-lg fa-trash mt-3"></i>--}}
                                                            {{--</a>--}}
                                                        {{--</div>--}}
                                                    </div>

                                                @endforeach
                                            @elseif(old('option_value_name_ar') && old('option_value_name_en') && old('option_value_price_prefix') && old('option_value_price'))
                                                @foreach(old('option_value_name_ar')  as $key=>$value)

                                                    <div class="option_value_row" style="width: 100%;display: table;">
                                                        <div class="form-group col-sm-4{!! formError($errors,'option_value_name_ar',true) !!}">
                                                            <div class="controls">

                                                                {!! Form::label('option_value_name_ar', __('Name Ar').':') !!}
                                                                <div class="input-group input-group-lg">
                                                                    {!! Form::text('option_value_name_ar[]',old('option_value_name_ar')[$key],['class'=>'form-control option_value_name_ar']) !!}
                                                                </div>
                                                            </div>
                                                            {!! formError($errors,'option_value_name_ar') !!}
                                                        </div>
                                                        <div class="form-group col-sm-4{!! formError($errors,'option_value_name_en',true) !!}">
                                                            <div class="controls">
                                                                {!! Form::label('option_value_name_en', __('Name En').':') !!}
                                                                <div class="input-group input-group-lg">

                                                                    {!! Form::text('option_value_name_en[]',old('option_value_name_en')[$key],['class'=>'form-control option_value_name_en']) !!}
                                                                </div>
                                                            </div>
                                                            {!! formError($errors,'option_value_name_en') !!}
                                                        </div>
                                                        <div class="form-group col-sm-2{!! formError($errors,'option_value_sort',true) !!}">
                                                            <div class="controls">
                                                                {!! Form::label('option_value_price', __('Sort').':') !!}
                                                                <div class="input-group input-group-lg">
                                                                    {!! Form::number('option_value_sort[]',$value['sort'],['class'=>'form-control option_value_sort']) !!}
                                                                </div>
                                                            </div>
                                                            {!! formError($errors,'option_value_sort') !!}
                                                        </div>
                                                        <div class="form-group col-sm-2">
                                                            <a href="javascript:void(0);"
                                                               onclick="$(this).closest('.option_value_row').remove();"
                                                               class="text-danger">
                                                                <i class="fa fa-lg fa-trash mt-3"></i>
                                                            </a>
                                                        </div>

                                                    </div>
                                                @endforeach

                                            @endif

                                            <div id="option_value_temp" style="display: none">
                                                <div class="option_value_row" style="width: 100%;display: table;">
                                                    <div class="form-group col-sm-4{!! formError($errors,'option_value_name_ar',true) !!}">
                                                        <div class="controls">
                                                            {!! Form::label('option_value_name_ar', __('Name Ar').':') !!}
                                                            <div class="input-group input-group-lg">
                                                                {!! Form::text('','',['class'=>'form-control option_value_name_ar']) !!}
                                                            </div>
                                                        </div>
                                                        {!! formError($errors,'option_value_name_ar') !!}
                                                    </div>
                                                    <div class="form-group col-sm-4{!! formError($errors,'option_value_name_en',true) !!}">
                                                        <div class="controls">
                                                            {!! Form::label('option_value_name_en', __('Name En').':') !!}
                                                            <div class="input-group input-group-lg">
                                                                {!! Form::text('','',['class'=>'form-control option_value_name_en']) !!}
                                                            </div>
                                                        </div>
                                                        {!! formError($errors,'option_value_name_en') !!}
                                                    </div>
                                                    <div class="form-group col-sm-2{!! formError($errors,'option_value_sort',true) !!}">
                                                        <div class="controls">
                                                            {!! Form::label('option_value_price', __('Sort').':') !!}
                                                            <div class="input-group input-group-lg">
                                                                {!! Form::number('','',['class'=>'form-control option_value_sort']) !!}
                                                            </div>
                                                        </div>
                                                        {!! formError($errors,'option_value_sort') !!}
                                                    </div>
                                                    <div class="form-group col-sm-2">
                                                        <a href="javascript:void(0);"
                                                           onclick="$(this).closest('.option_value_row').remove();"
                                                           class="text-danger">
                                                            <i class="fa fa-lg fa-trash mt-3"></i>
                                                        </a>
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
@section('footer')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/extensions/pace.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/pickers/pickadate/pickadate.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/forms/selects/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/pickers/daterange/daterangepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/pickers/datetime/bootstrap-datetimepicker.css')}}">
    <script src="{{asset('assets/system')}}/vendors/js/forms/select/select2.full.min.js" type="text/javascript"></script>

    <!-- BEGIN PAGE VENDOR JS-->
    <script src="{{asset('assets/system/vendors/js/pickers/dateTime/moment-with-locales.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/dateTime/bootstrap-datetimepicker.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/picker.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/picker.date.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/picker.time.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/legacy.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/daterange/daterangepicker.js')}}" type="text/javascript"></script>

    <script type="text/javascript">
        ajaxSelect2('#merchant_id','merchant','',"{{route('system.ajax.get')}}");
        $(document).ready(function() {
//             add_option_value();
            check_option_type();
        });
        function add_option_value() {
            var clonedRow = $('#option_value_temp').clone();
            clonedRow.find('.option_value_name_ar').attr('name', 'new_option_name_ar[]');
            clonedRow.find('.option_value_name_en').attr('name', 'new_option_name_en[]');
            clonedRow.find('.option_value_sort').attr('name', 'new_option_sort[]');
            $('#option_value_list').append(clonedRow.html());
        }

        function check_option_type() {
            var value = $('#option_type').val();
            if (value == 'select' || value == 'multi_select') {
                $('#option_value_list' ).show();
            } else {
                $('#option_value_list' ).hide();
            }
        }


        $('#addForm').submit(function (e) {
            e.preventDefault();

            $.post('{{route('system.attributes.store')}}', $('#addForm').find(":input").serialize(), function (out) {
                $('.validation_error_msg').remove();
                $('.product_row').css('border-color', '#aaa');


                if (out.status == false) {
                    toastr.error(out.msg, 'Error', {"closeButton": true});
                    if (out.data) {
                        $.each(out.data, function (index, value) {
                            $('[for="' + index + '"]').html($('[for="' + index + '"]').html() + '  <p style="color:red;display: inline-block;" class="validation_error_msg" id="error_msg_' + index + '" >' + value + '</p>');

                        });
                    }
                }else {
                    toastr.success(out.msg, 'Success', {"closeButton": true});
                    $('#addForm')[0].reset();
                         setTimeout(function() {
                location.reload();
            }, 3000);
                }
            }, 'json')


        });
        @if (isset($result->id))
        $('#editForm').submit(function (e) {
            e.preventDefault();

            $.post('{{route('system.attributes.update',$result->id)}}', $('#editForm').find(":input").serialize(), function (out) {
                $('.validation_error_msg').remove();
                $('.product_row').css('border-color', '#aaa');


                if (out.status == false) {
                    toastr.error(out.msg, 'Error', {"closeButton": true});
                    if (out.data) {
                        $.each(out.data, function (index, value) {
                            $('[for="' + index + '"]').html($('[for="' + index + '"]').html() + '  <p style="color:red;display: inline-block;" class="validation_error_msg" id="error_msg_' + index + '" >' + value + '</p>');

                        });
                    }
                }else {
                    setTimeout(function() {
                        location.reload();
                    }, 3000);
                    toastr.success(out.msg, 'Success', {"closeButton": true});
                   // $('#editForm')[0].reset();
                    // setTimeout(location.reload,3000);
                }
            }, 'json')
        });
        @endif
    </script>
@endsection