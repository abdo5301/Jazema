@extends('web.layouts')

@section('content')


    @if(auth()->check())
        @include('web.partial.user-profile-banner')
    @endif

    <!-- =-=-=-=-=-=-= Main Content Area =-=-=-=-=-=-= -->
    <div class="main-content-area reset clearfix view-profile">

        <!-- =-=-=-=-=-=-= Featured Ads =-=-=-=-=-=-= -->
        <section class="custom-padding">
            <!-- Main Container -->
            <div class="container menu-list-items">
                <!-- Row -->
                <div class="row">
                    <!-- Middle Content Box -->
                @if(auth()->check())
                    @include('web.partial.user-sidebar')
                @endif


                {!! Form::open(['route' =>  ['web.user.item-edit-action',$item->id],'files'=>true, 'method' =>  'POST' ,'class'=>'number-tab-steps wizard-circle','id'=> 'itemEditForm' ]) !!}
                <!-- Step 1 -->
                       <input type="hidden" name="id" value="{{$item->id}}">

                    <div class="col-md-9 col-xs-12 col-sm-12" id="just_three">
                        <div class="main_content">
                            {{--<h4>--}}
                            {{--<span>{{__('item data')}}</span>--}}
                            {{--</h4>--}}


                            <div class="stepwizard ">
                                <div class="stepwizard-row setup-panel">
                                    <div class="stepwizard-step">
                                        <a href="#step-1" type="button" class="btn btn-primary btn-circle">1</a>
                                        <p>{{__('Basic')}}</p>
                                    </div>
                                    <div class="stepwizard-step">
                                        <a href="#step-2" type="button" class="btn btn-default btn-circle">2</a>
                                        <p>{{__('Images')}}</p>
                                    </div>
                                    <div class="stepwizard-step">
                                        <a href="#step-3" type="button" class="btn btn-default btn-circle">3</a>
                                        <p>{{__('Attributes')}}</p>
                                    </div>
                                    <div class="stepwizard-step">
                                        <a href="#step-4" type="button" class="btn btn-default btn-circle" >4</a>
                                        <p>{{__('Deal Options')}}</p>
                                    </div>

                                </div>
                            </div>


                            <div class="row setup-content" id="step-1">
                                <div class="col-xs-12">
                                    <div class="col-md-12">
                                        {{--<h3>{{__('Basic')}}</h3>--}}
                                        <div id="option1" class="group task_content" style="">
                                            {{--<input type="hidden" name="user_id" id="user_id" value="{{auth()->id()}}">--}}


                                            <div class="inp_group {!! formError($errors,'item_category_id',true) !!}">
                                                <label for="item_category_id">Item Category</label>
                                                <select class="form-control" id="item_category_id"
                                                        name="item_category_id">
                                                    <option disabled selected hidden> {{__('Select Category')}} </option>
                                                    @foreach($ItemCategory as $cat)
                                                        @if($item->item_category->id == $cat->id)
                                                            <option value="{{$cat->id}}" selected>{{$cat->{'name_' . \DataLanguage::get()} }}</option>
                                                        @else
                                                            <option value="{{$cat->id}}">{{$cat->{'name_' . \DataLanguage::get()} }}</option>
                                                        @endif

                                                    @endforeach
                                                </select>
                                            </div>


                                            <div class="inp_group {!! formError($errors,'item_type_id',true) !!}">
                                                <label for="item_type_id">{{__('Item Type')}}</label>
                                                <select class="form-control" name="item_type_id" id="item_type_id">
                                                    <option disabled selected hidden> {{__('Select Type')}} </option>
                                                    @foreach($ItemTypes as $type)
                                                        @if($item->item_type->id == $type->id)
                                                        <option value="{{$type->id}}" selected>{{$type->{'name_' . \DataLanguage::get()} }}</option>
                                                        @else
                                                            <option value="{{$type->id}}">{{$type->{'name_' . \DataLanguage::get()} }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>

                                            </div>
                                            <div class="inp_group {!! formError($errors,'stage_id',true) !!}">
                                                <label for="stage_id">{{__('Stage')}}</label>
                                                <select class="form-control" name="stage_id" id="stage_id" required>
                                                    <option disabled selected hidden> {{__('Select Stage')}} </option>
                                                    @foreach($stages as $stage)
                                                        @if($item->stage->id == $stage->id)
                                                        <option value="{{$stage->id}}" selected>{{$stage->name}}</option>
                                                        @else
                                                            <option value="{{$stage->id}}">{{$stage->name}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>

                                            </div>
                                            {!! formError($errors,'stage_id') !!}

                                            <div class="inp_group">
                                                <label for="">{{__('Name')}}</label>
                                                <input type="text" class="form-control" value="{{$item->name_ar}}" placeholder="Name"
                                                       name="name_ar">
                                            </div>
                                            <div class="inp_group">
                                                <label for="" class="stay-top">{{__('Description')}}</label>
                                                <textarea name="description_ar" class="form-control"
                                                          placeholder="Description">{{$item->description_ar}}</textarea>
                                            </div>

                                            <div style="display: none" id="item_type_div">
                                                <div class="inp_group">
                                                    <label for="price">{{__('Price')}}</label>
                                                    <input type="number" value="{{$item->price}}" class="form-control" placeholder="Price"
                                                           name="price">
                                                </div>
                                                <div class="inp_group">
                                                    <label for="quantity"> {{__('Quantity')}}</label>
                                                    <input type="number" value="{{$item->quantity}}" class="form-control" placeholder="Quantity"
                                                           name="quantity">
                                                </div>
                                            </div>

                                            <div class="inp_group">
                                                <label for="" class="stay-top">{{__('Location')}}</label>
                                               <div id="edit_item_map" class="form-control"></div>
                                                <input id="add_item_lat" name="lat" type="hidden">
                                                <input id="add_item_lng" name="lng" type="hidden">
                                            </div>
                                        </div>

                                        <button class="btn btn-primary nextBtn btn-lg pull-right"
                                                type="button">{{__('Next')}}</button>
                                    </div>
                                </div>
                            </div>
                            <div class="row setup-content" id="step-2">
                                <div class="col-xs-12 ">
                                    <div class="col-md-12">
                                        {{--<h3>{{__('Images')}}</h3>--}}

                                        <div class="col-md-10 col-xs-10 col-sm-10">
                                            <div class="card">
                                                <div class="card-header">
                                                    <div class="col-sm-6">
                                                        <h2>{{__('Images')}}</h2>
                                                        @if(formError($errors,'image',true))
                                                            <p class="text-xs-left">
                                                                <small class="danger text-muted">{{__('Error File Upload')}}</small>
                                                            </p>
                                                        @endif
                                                    </div>

                                                </div>
                                                <div class="card-block card-dashboard">
                                                    <div class="uploaddata">

                                                        @if(isset($item->upload))

                                                            @foreach($item->upload as $key=> $row)
                                                                <div class="image_row_{{$key}}" style="display: table;">

                                                                    <img width="200px" style=""
                                                                         class="image_tag form-group col-sm-6"
                                                                         src="{{asset('storage/'.$row->path)}}" alt=""/>

                                                                    <a class="delete_image_btn"
                                                                       onclick="remove_image('{{$key}}','{{$row->id}}')">{{__('Delete')}}
                                                                        <i class="fa fa-trash"></i></a>
                                                                </div>
                                                            @endforeach


                                                        @endif


                                                        <div class="row" style="padding-top:10px;">

                                                            <div class="col-xs-2">
                                                                <button id="uploadBtn"
                                                                        class="btn btn-large btn-primary">
                                                                   {{__('Choose File')}}

                                                                </button>
                                                            </div>

                                                            <div class="col-xs-10">
                                                                <div id="progressOuter"
                                                                     class="progress progress-striped active"
                                                                     style="display:none;">
                                                                    <div id="progressBar"
                                                                         class="progress-bar progress-bar-success"
                                                                         role="progressbar" aria-valuenow="45"
                                                                         aria-valuemin="0"
                                                                         aria-valuemax="100" style="width: 0%">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row" style="padding-top:10px;">
                                                            <div class="col-xs-10">
                                                                <div id="msgBox">
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div id="image_list">
                                                            <input type="hidden"
                                                                   @if(!empty(old('temp_id'))) value="{{old('temp_id')}}"
                                                                   @else value="{{$temp_id}}" @endif name="temp_id"
                                                                   id="temp_id">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="image_temp_tag" style="display: none">
                                            <div class="image_row" style="display: table;">
                                                <img width="200px" class="image_tag form-group col-sm-6" src="" alt=""/>

                                                <a class="delete_image_btn">{{__('Delete')}} <i class="fa fa-trash"></i></a>
                                            </div>
                                        </div>
                                        <div id="image_temp" style="display: none">
                                            <div class="image_row" style="display: table;width: 100%">
                                                <div class="form-group col-sm-6{!! formError($errors,'image',true) !!}">
                                                    <div class="controls">
                                                        {!! Form::label('image', __('Image')) !!}
                                                        {!! Form::file('',['class'=>'form-control image']) !!}
                                                    </div>
                                                    {!! formError($errors,'image') !!}
                                                </div>
                                                <div class="form-group col-sm-4{!! formError($errors,'image_title',true) !!}">
                                                    <div class="controls">
                                                        {!! Form::label('image_title', __('Title')) !!}
                                                        {!! Form::text('','',['class'=>'form-control image_title']) !!}
                                                    </div>
                                                    {!! formError($errors,'image_title') !!}
                                                </div>
                                                <div class="col-sm-2 form-group">
                                                    <a href="javascript:void(0);"
                                                       onclick="$(this).closest('.image_row').remove();"
                                                       class="text-danger">
                                                        <i class="fa fa-lg fa-trash mt-3"></i>
                                                    </a>
                                                </div>

                                            </div>
                                        </div>

                                        <br>
                                        <button class="btn btn-primary nextBtn btn-lg pull-right"
                                                type="button">{{__('Next')}}</button>
                                    </div>
                                </div>
                            </div>
                            <div class="row setup-content" id="step-3">
                                <div class="col-xs-12">
                                    <div class="col-md-12">
                                        {{--<h3> {{__('Attributes')}}</h3>--}}
                                        <div id="attribute_div_list_item" class="inp_group">
                                        </div>

                                        <button class="btn btn-primary nextBtn btn-lg pull-right"
                                                type="button">{{__('Next')}}</button>
                                    </div>
                                </div>
                            </div>

                            <div class="row setup-content" id="step-4">
                                <div class="col-xs-12">
                                    <div class="col-md-12">

                                        <ul id="option_li_list" class="nav nav-pills">
                                            <div id="option_li_temp">
                                                <li  class="option_li_row">
                                                    <a class="option_a_row" ></a>
                                                </li>
                                            </div>
                                        </ul>

                                        <div id="option_div_list" class="tab-content">


                                            <div id="option_div_temp">
                                                <div class="option_div_row">
                                                    <a id ="remove_option"></a>
                                                    <div id="new_option" style="display: none;">
                                                        <div class="inp_group">

                                                            <input type="hidden" value="new" class="template_option">

                                                            <label>{{__('Option type')}}</label>
                                                            <select class="form-control option_type">
                                                                {{--<option selected disabled hidden>{{__('Select option type')}}</option>--}}
                                                                <option value="text" selected>Text</option>
                                                                <option value="select">Select</option>
                                                            </select>
                                                        </div>

                                                        <div class="inp_group">
                                                            <label>{{__('Option sort')}}</label>
                                                            <input type="number" placeholder="{{__('Option Sort')}}" class="form-control option_sort">
                                                        </div>
                                                        <div class="inp_group">
                                                            <label>{{__('Option name')}}</label>
                                                            <input type="text" placeholder="{{__('Option name')}}" class="form-control option_name_ar">
                                                        </div>

                                                        <div class="inp_group">
                                                            <label> {{__('required')}} </label>
                                                            <div class="form-control">
                                                                <label class="radio-inline"><input class="option_is_required yes_check" type="radio" name="option_is_required" value="yes">{{__('yes')}}</label>
                                                                <label class="radio-inline"><input  class="option_is_required no_check" type="radio" name="option_is_required" value="no">{{__('no')}}</label>
                                                            </div>
                                                        </div>



                                                    </div>

                                                    <div id="option_value_list" style="display: none;margin-left: 123px;" >


                                                    </div>
                                                    <a class="add_option_value_button" style="display: none;margin-left: 123px;"></a>


                                                </div>
                                            </div>

                                        </div>

                                        <a class="btn btn-primary btn-lg" href="javascript:;" onclick="add_option()" title="{{__('Add new option')}}"><i class="fa fa-plus fa-lg fa-fw"></i> {{__('New Option')}}</a>


                                        <button class="btn btn-success btn-lg pull-right" type="submit">{{__('Save')}} <i class="fa fa-check fa-lg fa-fw"></i></button>
                                    </div>
                                </div>
                            </div>



                        </div>
                    </div>
                    <!-- Middle Content Box End -->
                </div>


                <!-- Row End -->
            </div>
            <!-- Main Container End -->
        </section>

    </div>



@endsection

@section('header')
    <style>
        .stepwizard-step p {
            margin-top: 10px;
        }

        .stepwizard-row {
            display: table-row;
        }

        .stepwizard {
            display: table;
            width: 100%;
            position: relative;
        }

        .stepwizard-step button[disabled] {
            opacity: 1 !important;
            filter: alpha(opacity=100) !important;
        }

        .stepwizard-row:before {
            top: 14px;
            bottom: 0;
            position: absolute;
            content: " ";
            width: 100%;
            height: 1px;
            background-color: #ccc;
            z-order: 0;
        }

        .stepwizard-step {
            display: table-cell;
            text-align: center;
            position: relative;
        }

        .btn-circle {
            width: 30px;
            height: 30px;
            text-align: center;
            padding: 6px 0;
            font-size: 12px;
            line-height: 1.428571429;
            border-radius: 15px;
        }

        .uploaddata{
            padding-top: 50px;
        }
    </style>

@endsection

@section('footer')
    <style>
        #edit_item_map{
            height: 200px;;
        }
    </style>
    <script>
        var map4;
        var edit_item_marker;
        function initMap_edit_item() {
                    @if(!empty($item->lat) && !empty($item->lng) && $item->lat != 0 && $item->lng != 0)
            var myLatLng = {lat: {{$item->lat}}, lng: {{$item->lng}}};
                    @else
            var myLatLng = {lat: 26.820553, lng: 30.802498}; // lat & lng of EGYPT
            @endif
                map4 = new google.maps.Map(document.getElementById('edit_item_map'), {
                center: myLatLng,
                zoom: 8
            });

            @if(!empty($item->lat) && !empty($item->lng) && $item->lat != 0 && $item->lng != 0)
                edit_item_marker = new google.maps.Marker({
                map: map4,
                title:"Current Location",
                draggable: false,
                animation: google.maps.Animation.DROP,
                position: myLatLng
            });
            edit_item_marker.addListener('click', toggleBounce_editItem);
            @endif

            google.maps.event.addListener(map4, 'click', function(event) {
                placeEditItemMarker(event.latLng);
                $("input[name=lat]").val(event.latLng.lat());
                $("input[name=lng]").val(event.latLng.lng());
                console.log('lat: ' +event.latLng.lat());
                console.log('lng: ' +event.latLng.lng());
            });


        }

        function placeEditItemMarker(location) {
            deleteEditItemOverlays();
            edit_item_marker = new google.maps.Marker({
                map: map4,
                draggable: true,
                animation: google.maps.Animation.DROP,
                position: location
            });
            edit_item_marker.addListener('click', toggleBounce_editItem);

        }

        function deleteEditItemOverlays() {
            if (edit_item_marker) {
                edit_item_marker.setMap(null);
            }
        }

        function toggleBounce_editItem() {
            if (edit_item_marker.getAnimation() !== null) {
                edit_item_marker.setAnimation(null);
            } else {
                edit_item_marker.setAnimation(google.maps.Animation.BOUNCE);
            }
        }


    </script>

    <script>

        $(document).ready(function () {
            get_old_item_attribute();
            drow_old_options();



            $('#item_category_id').focus(function() {
                cat_val = $(this).val();
            }).change(function() {
                $(this).blur() // Firefox fix as suggested by AgDude
                var success = confirm("IF you chang Item\'s type or category you will lose your old inserted data in the attributes section . do this action ?");
                if(success)
                {
                    get_item_attribute();
                    return true;
                    //alert('changed');
                }
                else
                {
                    $(this).val(cat_val);
                    //alert('unchanged');
                    return false;
                }
            });


            $('#item_type_id').focus(function() {
                type_val = $(this).val();
            }).change(function() {
                $(this).blur() // Firefox fix as suggested by AgDude
                var success = confirm("IF you chang Item\'s type or category you will lose your old inserted data in the attributes section . do this action ?");
                if(success)
                {
                    get_item_attribute();
                    get_item_type();
                    return true;
                    //alert('changed');
                }
                else
                {
                    $(this).val(type_val);
                    //alert('unchanged');
                    return false;
                }
            });


            var navListItems = $('div.setup-panel div a'),
                allWells = $('.setup-content'),
                allNextBtn = $('.nextBtn');

            allWells.hide();

            navListItems.click(function (e) {
                e.preventDefault();
                var $target = $($(this).attr('href')),
                    $item = $(this);

                if (!$item.hasClass('disabled')) {
                    navListItems.removeClass('btn-primary').addClass('btn-default');
                    $item.addClass('btn-primary');
                    allWells.hide();
                    $target.show();
                    $target.find('input:eq(0)').focus();
                }
            });

            allNextBtn.click(function () {
                var curStep = $(this).closest(".setup-content"),
                    curStepBtn = curStep.attr("id"),
                    nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
                    curInputs = curStep.find("input[type='text'],input[type='url']"),
                    isValid = true;

                $(".form-group").removeClass("has-error");
                for (var i = 0; i < curInputs.length; i++) {
                    if (!curInputs[i].validity.valid) {
                        isValid = false;
                        $(curInputs[i]).closest(".form-group").addClass("has-error");
                    }
                }

                if (isValid)
                    nextStepWizard.removeAttr('disabled').trigger('click');
            });

            $('div.setup-panel div a.btn-primary').trigger('click');
        });


        $(document).ready(function () {

            var btn = document.getElementById('uploadBtn'),
                progressBar = document.getElementById('progressBar'),
                progressOuter = document.getElementById('progressOuter'),
                msgBox = document.getElementById('msgBox');


            var uploader = new ss.SimpleUpload({
                button: btn,
                url: '{{route('web.upload-temp-image')}}',
                name: 'uploadfile',
                multipart: true,
                hoverClass: 'hover',
                focusClass: 'focus',
                responseType: 'json',
                data: {
                    '_token': $('[name=csrf-token]').attr('content'),
                    'temp_id': '@if(!empty(old('temp_id'))) {{old('temp_id')}} @else {{$temp_id}} @endif'
                },
                startXHR: function () {
                    progressOuter.style.display = 'block'; // make progress bar visible
                    this.setProgressBar(progressBar);
                },
                onSubmit: function () {
                    msgBox.innerHTML = ''; // empty the message box
                    btn.innerHTML = 'Uploading...'; // change button text to "Uploading..."
                },
                onComplete: function (filename, response) {
                    btn.innerHTML = 'Choose Another File';
                    progressOuter.style.display = 'none'; // hide progress bar when upload is completed

                    if (!response) {
                        msgBox.innerHTML = '';//'Unable to upload file';
                        return;
                    }

                    if (response.success === true) {
                        if (response.data) {
                            var length = $('#image_list .image_row').length;
                            var img = $('#image_temp_tag').clone();
                            img.find('.image_row').addClass('image_row_' + length);
                            img.find('.image_tag').attr('src', response.data.path);
                            //img.find('.image_id').attr('name',response.data.image_id).attr('id','image_id_'+length);
                            img.find('.delete_image_btn').attr('onclick', 'remove_image(' + length + ',' + response.data.image_id + ')');
                            $('#image_list').append(img.html());
                        }
                        msgBox.innerHTML = '<strong>' + escapeTags(filename) + '</strong>' + ' successfully uploaded.';
                    } else {
                        if (response.msg) {
                            msgBox.innerHTML = escapeTags(response.msg);
                        } else {
                            msgBox.innerHTML = 'An error occurred and the upload failed.';
                        }
                    }
                },
                onError: function () {
                    progressOuter.style.display = 'none';
                    msgBox.innerHTML = '';//'Unable to upload file';
                }
            });

        });


        function remove_image(number, id) {

            $.post('{{route('web.item-remove-image')}}', {'id': id}, function (out) {
                if (out.status == true)
                    $('.image_row_' + number).remove();
            }, 'json');
        }


        function get_merchant_template_option() {
            var value = $('#user_id').val();
            var category_id = $('#item_category_id').val();
            if (value || category_id) {
                $.get('{{route('web.item-template-option')}}', {
                    'user_id': value,
                    'category_id': category_id
                }, function (out) {
                    $('#template_option').html(out);
                });

            }
        }


        function add_option_value(number) {
            var list_length = $('#option_value_list_' + number +'  input').length + 1;
            //alert(list_length);
            //var clonedRow = $('#option_value_temp').clone();
            //clonedRow.find('.option_value_name_ar').attr('name', 'option[' + number + '][option_value_name_ar][]');
            //clonedRow.find('.option_value_name_en').attr('name', 'option[' + number + '][option_value_name_en][]');
            //clonedRow.find('.option_value_price_prefix').attr('name', 'option[' + number + '][option_value_price_prefix][]');
            //clonedRow.find('.option_value_price').attr('name', 'option[' + number + '][option_value_price][]');
            //clonedRow.find('.option_value_status').attr('name', 'option[' + number + '][option_value_status][]');
            var inp_group_option_value = $('<div>').attr({'class': 'inp_group option_value_name_ar','id':'option_value_name_ar_' + list_length});
            inp_group_option_value.append($('<input>', {
                name: 'option[' + number + '][option_value_name_ar][]',
                class: 'form-control ',
                placeholder:'{{__('Option value')}}',
                width:'95%'
            }));
            inp_group_option_value.append( $('<a>', {
                class: 'btn btn-circle  remove_option_value_button',
                title: '{{__('Remove this value')}}',
                onclick: 'remove_option_value('+ list_length +')'
            }).html('<i class="fa fa-remove fa-lg"></i>').css({'color':'red'}));

            $('#option_value_list_' + number).append(inp_group_option_value);
        }

        function remove_option_value(number) {
            $('#option_value_name_ar_' + number).remove();
        }

        function check_option_type(number) {
            var value = $('#option_type_' + number).val();
            if (value == 'select' || value == 'radio' || value == 'check') {
                $('#option_value_list_' + number).show();
                $('#add_option_value_button_' + number).show();
            } else {
                $('#option_value_list_' + number).hide();
                $('#add_option_value_button_' + number).hide();
            }
        }

        var publicLength = $('#option_li_list li').length;

        function remove_option(number) {
            $('.option_div_row_' + number).remove();
            if ( $('.option_li_row_' + number).prev().length &&
                $('.option_li_row_' + number).prev().find('.option_a_row').attr('id') ) {
                $('.option_li_row_' + number).prev().find('.option_a_row').click();
                $('.option_li_row_' + number).remove();
            }else{
                $('.option_li_row_' + number).remove();
                $("[class=option_a_row]:eq(1)").click();
            }
            // $('.option_li_row_' + number).prev().find('.option_a_row').click();
            // $('.option_li_row_' + number).remove();
            // $('.option_div_row_' + number).remove();
            // rearrange_options();


        }

        function drow_old_options(){
            $.post('{{route('web.user.get-option-item')}}',{'id':'{{$item->id}}'}, function (out) {

                if (out.status == false) {
                    notify('error',out.msg);
                    //  toastr.error(out.msg, 'Error', {"closeButton": true});
                } else {
                   // console.log(out.data);
                for (var i = 0; i < out.data.length; i++) {
                    var li_length = publicLength;
                    var option = out.data[i];
                var cloned_li_row = $('#option_li_temp').clone();
                var clone_div_row = $('#option_div_temp').clone();
                cloned_li_row.find('.option_li_row').addClass('option_li_row_' + li_length);
                cloned_li_row.find('.option_a_row').attr('id', 'tab_' + li_length).attr('href', '#tabv_' + li_length).attr('data-toggle', 'pill')
                    .attr('href', '#tabv_' + li_length).html('Option ' + li_length);//attr('onclick', 'change_tab(' + li_length + ')');

                clone_div_row.find('.option_div_row').attr('id', 'tabv_' + li_length).attr('aria-labelledby', 'tab_' + li_length)
                    .addClass('option_div_row_' + li_length + '  tab-pane fade  well');
                clone_div_row.find('#new_option').attr('id', 'new_option_' + li_length).css({
                    'display': 'block',
                    'margin-top': '40px'
                });
                clone_div_row.find('#option_value_list').attr('id', 'option_value_list_' + li_length);
                clone_div_row.find('.add_option_value_button').attr({
                    'id': 'add_option_value_button_' + li_length,
                    'onclick': 'add_option_value(' + li_length + ')'
                }).addClass('btn btn-info btn-xs').html('<i class="fa fa-bars fa-fw"></i> Add Option Values');

                clone_div_row.find('#remove_option').attr('id', 'remove_option_' + li_length).attr('onclick', 'remove_option(' + li_length + ')').attr('title', '{{__('Remove this option')}}')
                    .html('<i class="fa fa-remove fa-lg"></i>').css({'color': 'red', 'float': 'right'});
                clone_div_row.find('.template_option').attr('name', 'option[' + li_length + '][template_option]');
                clone_div_row.find('.option_sort').attr('id', 'option_sort' + li_length).attr('name', 'option[' + li_length + '][option_sort]').attr('value',option.sort);
                clone_div_row.find('.option_name_ar').attr('name', 'option[' + li_length + '][option_name_ar]').attr('value',option.name_ar);

                clone_div_row.find('.option_is_required').attr('name', 'option[' + li_length + '][option_is_required]');

               clone_div_row.find('.option_type').attr('id', 'option_type_' + li_length).attr('onchange', 'check_option_type(' + li_length + ')').attr('name', 'option[' + li_length + '][option_type]');


                $('#option_li_list').append(cloned_li_row.html());
                $('#option_div_list').append(clone_div_row.html());
                $('#option_type_' + li_length).val(option.type);
                $("input[name='option[" + li_length + "][option_is_required]'][value="+option.is_required+"]").prop('checked', true);



                    check_option_type(li_length);

                    if (option.values) {
                        var list_length = $('#option_value_list_' + li_length +'  input').length + 1;
                        for (var x = 0; x < option.values.length; x++) {
                            var option_value = option.values[x];
                            var inp_group_option_value = $('<div>').attr({'class': 'inp_group option_value_name_ar','id':'option_value_name_ar_' + list_length});
                            inp_group_option_value.append($('<input>', {
                                name: 'option[' + li_length + '][option_value_name_ar][]',
                                class: 'form-control ',
                                placeholder:'{{__('Option value')}}',
                                width:'95%',
                                value:option_value.name_ar
                            }));
                            inp_group_option_value.append( $('<a>', {
                                class: 'btn btn-circle  remove_option_value_button',
                                title: '{{__('Remove this value')}}',
                                onclick: 'remove_option_value('+ list_length +')'
                            }).html('<i class="fa fa-remove fa-lg"></i>').css({'color':'red'}));

                            $('#option_value_list_' + li_length).append(inp_group_option_value);
                            list_length = +list_length+1;

                        }
                    }
                if(i == 0){
                    $('#tab_' + li_length).click();
                }
                    publicLength = +publicLength + 1;
            }
            }
        }, 'json');
        }

        function add_option() {
            //alert(publicLength);
            var li_length = publicLength;
            var cloned_li_row = $('#option_li_temp').clone();
            var number = +li_length + 1;
            cloned_li_row.find('.option_li_row').addClass('option_li_row_' + li_length);
            //cloned_li_row.find('.option_a_row').attr('id', 'tab_' + li_length).attr('aria-controls', 'tabv_' + li_length)
            cloned_li_row.find('.option_a_row').attr('id', 'tab_' + li_length).attr('href', '#tabv_' + li_length).attr('data-toggle','pill')
                .attr('href', '#tabv_' + li_length).html('Option ' + li_length);//attr('onclick', 'change_tab(' + li_length + ')');



            var clone_div_row = $('#option_div_temp').clone();

            // clone_div_row.find('.option_div_row').attr('id', '#tabv_' + li_length).attr('aria-labelledby', 'tab_' + li_length)
            //     .addClass('option_div_row_' + li_length);
            clone_div_row.find('.option_div_row').attr('id', 'tabv_' + li_length).attr('aria-labelledby', 'tab_' + li_length)
                .addClass('option_div_row_' + li_length+'  tab-pane fade  well');

            clone_div_row.find('#new_option').attr('id', 'new_option_' + li_length).css({'display':'block','margin-top':'40px'});
            clone_div_row.find('#option_value_list').attr('id', 'option_value_list_' + li_length);
            clone_div_row.find('.add_option_value_button').attr({'id':'add_option_value_button_' + li_length,'onclick': 'add_option_value(' + li_length + ')'}).addClass('btn btn-info btn-xs').html('<i class="fa fa-bars fa-fw"></i> Add Option Values');

            clone_div_row.find('#remove_option').attr('id', 'remove_option_' + li_length).attr('onclick', 'remove_option(' + li_length + ')').attr('title', '{{__('Remove this option')}}')
                .html('<i class="fa fa-remove fa-lg"></i>').css({'color':'red','float': 'right'});
            clone_div_row.find('.template_option').attr('name', 'option[' + li_length + '][template_option]');
            // clone_div_row.find('.template_option').attr('onchange', 'choose_template_new(' + li_length + ')').attr('id', 'template_option_' + li_length).attr('name', 'option[' + li_length + '][template_option]');
            clone_div_row.find('.option_sort').attr('id', 'option_sort' + li_length).attr('name', 'option[' + li_length + '][option_sort]');
            clone_div_row.find('.option_type').attr('id', 'option_type_' + li_length).attr('onchange', 'check_option_type(' + li_length + ')').attr('name', 'option[' + li_length + '][option_type]');
            clone_div_row.find('.option_name_ar').attr('name', 'option[' + li_length + '][option_name_ar]');
            clone_div_row.find('.option_is_required').attr('name', 'option[' + li_length + '][option_is_required]');
            //clone_div_row.find('.option_name_en').attr('name', 'option[' + li_length + '][option_name_en]');
            //clone_div_row.find('.option_min_select').attr('name', 'option[' + li_length + '][option_min_select]');
            //clone_div_row.find('.option_max_select').attr('name', 'option[' + li_length + '][option_max_select]');
            //clone_div_row.find('.option_status').attr('name', 'option[' + li_length + '][option_status]');





            $('#option_li_list').append(cloned_li_row.html());
            $('#option_div_list').append(clone_div_row.html());
            $('#tab_' + li_length).click();
            publicLength = +publicLength + 1;
        }

        function rearrange_options() {
            var arrangeLength = $('#option_li_list li').length -1;
            $("#option_li_list > *:not('#option_li_temp')").remove();
            $("#option_div_list > *:not('#option_div_temp')").remove();
            for (var i = 0; i < arrangeLength; i++){
                var li_length = i+1;
                var cloned_li_row = $('#option_li_temp').clone();
                var clone_div_row = $('#option_div_temp').clone();
                cloned_li_row.find('.option_li_row').addClass('option_li_row_' + li_length);
                cloned_li_row.find('.option_a_row').attr('id', 'tab_' + li_length).attr('href', '#tabv_' + li_length).attr('data-toggle','pill')
                    .attr('href', '#tabv_' + li_length).html('Option ' + li_length);//attr('onclick', 'change_tab(' + li_length + ')');

                clone_div_row.find('.option_div_row').attr('id', 'tabv_' + li_length).attr('aria-labelledby', 'tab_' + li_length)
                    .addClass('option_div_row_' + li_length+'  tab-pane fade  well');
                clone_div_row.find('#new_option').attr('id', 'new_option_' + li_length).css({'display':'block','margin-top':'40px'});
                clone_div_row.find('#option_value_list').attr('id', 'option_value_list_' + li_length);
                clone_div_row.find('.add_option_value_button').attr({'id':'add_option_value_button_' + li_length,'onclick': 'add_option_value(' + li_length + ')'}).addClass('btn btn-info btn-xs').html('<i class="fa fa-bars fa-fw"></i> Add Option Values');

                clone_div_row.find('#remove_option').attr('id', 'remove_option_' + li_length).attr('onclick', 'remove_option(' + li_length + ')').attr('title', '{{__('Remove this option')}}')
                    .html('<i class="fa fa-remove fa-lg"></i>').css({'color':'red','float': 'right'});
                clone_div_row.find('.template_option').attr('name', 'option[' + li_length + '][template_option]');
                clone_div_row.find('.option_sort').attr('id', 'option_sort' + li_length).attr('name', 'option[' + li_length + '][option_sort]');
                clone_div_row.find('.option_type').attr('id', 'option_type_' + li_length).attr('onchange', 'check_option_type(' + li_length + ')').attr('name', 'option[' + li_length + '][option_type]');
                clone_div_row.find('.option_name_ar').attr('name', 'option[' + li_length + '][option_name_ar]');
                clone_div_row.find('.option_is_required').attr('name', 'option[' + li_length + '][option_is_required]');
                $('#option_li_list').append(cloned_li_row.html());
                $('#option_div_list').append(clone_div_row.html());
                // if(i+1 == arrangeLength){
                //     $('#tab_' + li_length).click();
                // }


            }

            publicLength = $('#option_li_list li').length;


        }

        function add_image() {
            var length = $('#image_list').length;
            var clonedRow = $('#image_temp').clone();
            clonedRow.find('.image').attr('id', 'image_' + length).attr('name', 'image[]');
            clonedRow.find('.image_title').attr('id', 'image_title_' + length).attr('name', 'image_title[]');
            $('#image_list').append(clonedRow.html());
        }


        function remove_attribute(number) {
            $('.attribute_li_row_' + number).remove();
            $('.attribute_div_row' + number).remove();
        }


        function choose_attribute_template_new(number) {
            var value = $('#template_attribute_' + number).val();
            if (value == 'new') {
                $('#new_attribute_' + number).show();
            } else {
                $('#new_attribute_' + number).hide();
            }
        }

        function change_attribute_tab(number) {
            $('.attribute_div_row').css('display', 'none');
            $('.attribute_div_row_' + number).css('display', 'block');
            $('.attribute_a_row').removeClass('active');
            $('#attribute_tab_' + number).addClass('active');
        }

        function confirm_change_attributes(){
            if(!confirm("IF you chang Item\'s type or category you will lose your old inserted data in the attributes section . do this action ?")){
                    $('#item_type_id').blur();
                    $('#item_category_id').blur();
                    $('#item_type_id').val({{$item->type_id}});
                    $('#item_category_id').val({{$item->category_id}});
                return false;
            }else{
                return true;
            }
        }

        function get_item_type() {
            var item_type_id = $('#item_type_id').val();
            $.post('{{route('web.user.check-item-type')}}', {'item_type_id': item_type_id}, function (out) {
                if (out.status == true) {
                    $('#item_type_div').show();
                } else {
                    $('#item_type_div').hide();
                }


            });
        }

        function get_item_attribute() {
            {{--if(!confirm("IF you chang Item\'s type or category you will lose your inserted data in the attributes section . do this action ?")){--}}
                {{--$('#item_type_id').val({{$item->type_id}});--}}
                {{--$('#item_category_id').val({{$item->category_id}});--}}
                {{--return false;--}}
            {{--}--}}
            var item_type_id = $('#item_type_id').val();
            var category_id = $('#item_category_id').val();
            if (item_type_id && category_id) {
                $.post('{{route('web.attribute.get-attribute')}}', {
                    'category_id': category_id,
                    'item_type_id': item_type_id
                }, function (out) {

                    if (out.status == false) {
                        $('#attribute_div_list_item').html('<br><h2 class="text-center text-danger">لا توجد خصائص لهذا القسم</h2>');
                        //  toastr.error(out.msg, 'Error', {"closeButton": true});
                    } else {
                        $('#attribute_div_list').html('');
                        $('#attribute_div_list_item').html('');
                        for (var i = 0; i < out.data.length; i++) {
                            var attribute = out.data[i];
                            var drow = $('<div>').attr('class', 'attribute_row inp_group');
                            if(attribute.type == 'textarea'){
                                drow.append($('<label>',{class:'stay-top',for: 'attribute[' + attribute.id + ']'}).html(attribute.name));
                            }else{
                                drow.append($('<label>',{for: 'attribute[' + attribute.id + ']'}).html(attribute.name));
                            }

                            if (attribute.type == 'text') {
                                drow.append($('<input>', {
                                    name: 'attribute[' + attribute.id + ']',
                                    class: 'form-control',
                                    id: 'attribute_' + attribute.id
                                }));
                            } else if (attribute.type == 'number') {
                                drow.append($('<input>', {
                                    name: 'attribute[' + attribute.id + ']',
                                    class: 'form-control',
                                    type: 'number',
                                    id: 'attribute_' + attribute.id
                                }));
                            } else if (attribute.type == 'date') {
                                drow.append($('<input>', {
                                    name: 'attribute[' + attribute.id + ']',
                                    class: 'form-control',
                                    type: 'text',
                                    placeholder: 'd-m-y',
                                    id: 'attribute_' + attribute.id
                                }));
                            }  else if (attribute.type == 'datetime') {
                                drow.append($('<input>', {
                                    name: 'attribute[' + attribute.id + ']',
                                    type: 'text',
                                    class: 'form-control',
                                    placeholder: 'd-m-y H:i:s',
                                    id: 'attribute_' + attribute.id
                                }));
                            } else if (attribute.type == 'file') {
                                drow.append($('<input>', {
                                    name: 'attribute[' + attribute.id + ']',
                                    class: 'form-control',
                                    type: 'file',
                                    id: 'attribute_' + attribute.id
                                }));
                            } else if (attribute.type == 'textarea') {
                                drow.append($('<textarea>', {
                                    name: 'attribute[' + attribute.id + ']',
                                    class: 'form-control',
                                    id: 'attribute_' + attribute.id
                                }));
                            } else if (attribute.type == 'select') {
                                var select = $('<select >', {
                                    name: 'attribute[' + attribute.id + ']',
                                    style: 'width:84%;',
                                    class: 'form-control',
                                    id: 'attribute_' + attribute.id
                                });
                                if (attribute.values) {
                                    var options = [];
                                    for (var x = 0; x < attribute.values.length; x++) {
                                        var attr_value = attribute.values[x];
                                        options.push($('<option>', {value: attr_value.id}).html(attr_value.name));
                                    }

                                    drow.append(select.html(options));
                                }
                            }else if (attribute.type == 'multi_select') {
                                var select = $('<select >', {
                                    name: 'attribute[' + attribute.id + '][]',
                                    style: 'width:84%;',
                                    class: 'form-control',
                                    multiple:'',
                                    id: 'attribute_' + attribute.id
                                });
                                if (attribute.values) {
                                    var options = [];
                                    for (var x = 0; x < attribute.values.length; x++) {
                                        var attr_value = attribute.values[x];
                                        options.push($('<option>', {value: attr_value.id}).html(attr_value.name));
                                    }

                                    drow.append(select.html(options));
                                }
                            }
                            //drow.append('</div>');
                            $('#attribute_div_list_item').append(drow);
                            if(attribute.type == 'multi_select' || attribute.type == 'select'){
                                $('#attribute_' + attribute.id).select2();
                            }
                            if(attribute.type == 'date'){
                                $( '#attribute_' + attribute.id ).datetimepicker({
                                    timepicker: false,
                                    format: 'Y-m-d',
                                });
                            }
                            if(attribute.type == 'datetime'){
                                $( '#attribute_' + attribute.id ).datetimepicker({
                                    format: 'Y-m-d H:i:s'
                                });
                            }
                        }
                    }

                }, 'json');
            }
        }



        function get_old_item_attribute() {

            $.post('{{route('web.attribute.get-attribute-item')}}',{'id':'{{$item->id}}'}, function (out) {

                if (out.status == false) {
                    $('#attribute_div_list_item').html('<div class="text-center text-danger">لا توجد خصائص لهذا القسم</div>');
                    //  toastr.error(out.msg, 'Error', {"closeButton": true});
                } else {
                    //console.log(out.data);

                    $('#attribute_div_list_item').html('');
                    for (var i = 0; i < out.data.length; i++) {
                        var attribute = out.data[i];
                        var drow = $('<div>').attr('class', 'attribute_row inp_group');
                        if(attribute.type == 'textarea'){
                            drow.append($('<label>',{class:'stay-top',for: 'attribute[' + attribute.id + ']'}).html(attribute.name));
                        }else{
                            drow.append($('<label>',{for: 'attribute[' + attribute.id + ']'}).html(attribute.name));
                        }
                        if (attribute.type == 'text') {
                            drow.append($('<input>', {
                                name: 'attribute[' + attribute.id + ']',
                                class: 'form-control',
                                id: 'attribute_' + attribute.id,
                                value:attribute.selected_value_name
                            }));
                        }else if (attribute.type == 'number') {
                            drow.append($('<input>', {
                                name: 'attribute[' + attribute.id + ']',
                                class: 'form-control',
                                type: 'number',
                                id: 'attribute_' + attribute.id,
                                value:attribute.selected_value_name
                            }));
                        }  else if (attribute.type == 'file') {
                            drow.append($('<input>', {
                                name: 'attribute[' + attribute.id + ']',
                                type: 'file',
                                class: 'form-control',
                                id: 'attribute_' + attribute.id

                            }));
                        } else if (attribute.type == 'date') {
                            drow.append($('<input>', {
                                name: 'attribute[' + attribute.id + ']',
                                type: 'text',
                                class: 'form-control',
                                id: 'attribute_' + attribute.id,
                                value:attribute.selected_value_name
                            }));
                        } else if (attribute.type == 'datetime') {
                            drow.append($('<input>', {
                                name: 'attribute[' + attribute.id + ']',
                                type: 'text',
                                class: 'form-control',
                                id: 'attribute_' + attribute.id,
                                value:attribute.selected_value_name
                            }));
                        } else if (attribute.type == 'textarea') {
                            drow.append($('<textarea>', {
                                name: 'attribute[' + attribute.id + ']',
                                class: 'form-control',
                                id: 'attribute_' + attribute.id
                            }));
                        } else if (attribute.type == 'select') {
                            var select = $('<select >', {
                                name: 'attribute[' + attribute.id + ']',
                                style: 'width:84%;',
                                class: 'form-control',
                                id: 'attribute_' + attribute.id
                            });
                            if (attribute.values) {
                                var options = [];
                                for (var x = 0; x < attribute.values.length; x++) {
                                    var attr_value = attribute.values[x];
                                    if(attr_value.id == attribute.selected_value){
                                        options.push($('<option>', {value: attr_value.id ,selected:"selected"}).html(attr_value.name_ar));
                                    }else{
                                        options.push($('<option>', {value: attr_value.id}).html(attr_value.name_ar));
                                    }

                                }

                                drow.append(select.html(options));
                            }
                        }else if (attribute.type == 'multi_select') {
                            var select = $('<select >', {
                                name: 'attribute[' + attribute.id + '][]',
                                style: 'width:84%;',
                                class: 'form-control',
                                multiple:'',
                                id: 'attribute_' + attribute.id
                            });
                            if (attribute.values) {
                                var options = [];
                                for (var x = 0; x < attribute.values.length; x++) {
                                    var attr_value = attribute.values[x];

                                    var namesString = attribute.selected_value_name;
                                    var namesArray = namesString.split(',');

                                    if(jQuery.inArray(attr_value.name_ar,namesArray) !== -1) {
                                        options.push($('<option>', {value: attr_value.id,selected:"selected"}).html(attr_value.name_ar));
                                    }else{
                                        options.push($('<option>', {value: attr_value.id}).html(attr_value.name_ar));
                                    }

                                }

                                drow.append(select.html(options));
                            }
                        }
                        drow.append('</div>');
                        $('#attribute_div_list_item').append(drow);


                        if(attribute.type == 'multi_select' || attribute.type == 'select'){
                            $('#attribute_' + attribute.id).select2();
                        }
                        if(attribute.type == 'textarea'){
                            $('#attribute_' + attribute.id).text(attribute.selected_value_name);
                        }
                        if(attribute.type == 'date'){
                            $( '#attribute_' + attribute.id ).datetimepicker({
                                timepicker: false,
                                format: 'Y-m-d',
                            });
                        }
                        if(attribute.type == 'datetime'){
                            $( '#attribute_' + attribute.id ).datetimepicker({
                                format: 'Y-m-d H:i:s'
                            });
                        }
                    }
                }

            }, 'json');

        }


        $('#itemEditForm').submit(function (e) {
            e.preventDefault();
            $.post('{{route('web.user.item-edit-action')}}',$('#itemEditForm').find(":input").serialize(), function (out) {
              //  alert('we are here');
                $('.validation_error_msg').remove();
                $('.product_row').css('border-color', '#aaa');


                if (out.status == false) {
                    $.each(out.data, function (index, value) {
                        notify('error', value);
                    });

                } else {
                    notify('success', 'Item Updated Successfully');

                }
            }, 'json')
        });



    </script>


    <script src="{{asset('assets/system')}}/SimpleAjaxUploader.js"></script>
@endsection