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



                {!! Form::open(['route' =>['web.user.update-profile',auth()->user()->id],'files'=>true, 'method' =>  'POST','class'=>'number-tab-steps wizard-circle','id'=>'userEditForm']) !!}
                <!-- Step 1 -->

                    <div class="col-md-9 col-xs-12 col-sm-12" id="just_three">
                        <div class="main_content">
                            <div class="stepwizard ">
                                <div class="stepwizard-row setup-panel">
                                    <div class="stepwizard-step">
                                        <a href="#step-1" type="button" class="btn btn-primary btn-circle">1</a>
                                        <p>{{__('Basic information')}}</p>
                                    </div>
                                    <div class="stepwizard-step">
                                        <a href="#step-2" type="button" class="btn btn-default btn-circle">2</a>
                                        <p>{{__('Contact Information')}}</p>
                                    </div>

                                    <div class="stepwizard-step">
                                        <a href="#step-3" type="button" class="btn btn-default btn-circle">3</a>
                                        <p>{{__('Job information')}}</p>
                                    </div>

                                    <div class="stepwizard-step">
                                        <a href="#step-4" type="button" class="btn btn-default btn-circle">4</a>
                                        <p>{{__('Social Links')}}</p>
                                    </div>


                                </div>
                            </div><br>


                            <div class="row setup-content" id="step-1">
                                <div class="col-xs-12">
                                    <div class="col-md-12">
                                        {{--<h3>{{__('Basic')}}</h3>--}}
                                        <div id="option1" class="group task_content" style="">

                                            <div class="inp_group">
                                                <label for="type">{{__('Type')}}</label>
                                                <select name="type" class="form-control" onchange="change_type()" id="type">
                                                    <option  @if(auth()->user()->type == 'individual') selected @endif value="individual">{{__('Individual')}}</option>
                                                    <option @if(auth()->user()->type == 'company') selected @endif value="company">{{__('Company')}}</option>
                                                </select>
                                            </div>

                                            <div id="selectType" @if(auth()->user()->type == 'individual') style="display: none;" @else style="display: block" @endif >
                                                <div class="inp_group">
                                                    <label for="company_business">{{__('Company Business')}}</label>
                                                    <input type="text" class="form-control" placeholder="Company Business"
                                                           name="company_business" value="{{auth()->user()->company_business}}">
                                                </div>

                                                <div class="inp_group">
                                                    <label for="company_name">{{__('Company Name')}}</label>
                                                    <input type="text" class="form-control" placeholder="Company Name"
                                                           name="company_name" value="{{auth()->user()->company_name}}">
                                                </div>
                                            </div>


                                            <div class="inp_group">
                                                <label for="interested_categories">{{__('Interested categories')}}</label>
                                                <select name="interisted_categories[]" id="interested_cats" class="form-control" multiple>
                                                    @foreach($all_categories as $key=>$cat_item)
                                                    <option  @if(!empty($interisted_categories_ids_array) && in_array($cat_item['id'] , $interisted_categories_ids_array) ) selected @endif value="{{$cat_item['id']}}">{{$cat_item['name_'.\DataLanguage::get()] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="inp_group">
                                                <label for="">{{__('First Name')}}</label>
                                                <input type="text" class="form-control" placeholder="First Name"
                                                       name="firstname" value="{{auth()->user()->firstname}}">
                                            </div>
                                            <div class="inp_group">
                                                <label for="">{{__('Last Name')}}</label>
                                                <input type="text" class="form-control" placeholder="Last Name"
                                                       name="lastname" value="{{auth()->user()->lastname}}">
                                            </div>

                                            <div class="inp_group">
                                                <label for="">{{__('Password')}}</label>
                                                <input type="password" class="form-control" placeholder="Password"
                                                       name="password">
                                            </div>
                                            <div class="inp_group">
                                                <label for="">{{__('Password Confirmation')}}</label>
                                                <input type="password" class="form-control" placeholder="password confirmation"
                                                       name="password_confirmation">
                                            </div>


                                            <div class="inp_group">
                                                <label for="gender">{{__('Gender')}}</label>
                                                <select name="gender" class="form-control">
                                                    <option  @if(auth()->user()->gender == 'male') selected @endif value="male">{{__('Male')}}</option>
                                                    <option @if(auth()->user()->gender == 'female') selected @endif value="female">{{__('Female')}}</option>
                                                </select>
                                            </div>

                                            <div class="inp_group">
                                                <label for="">{{__('Image')}}</label>
                                                <input type="file" class="form-control" placeholder="Image"
                                                       name="image">
                                            </div>

                                            <div class="inp_group">
                                                <label for="" class="stay-top">{{__('About')}}</label>
                                                <textarea name="about" class="form-control"
                                                          placeholder="About You.....">{{auth()->user()->about}}</textarea>
                                            </div>


                                        </div>

                                        <button class="btn btn-primary nextBtn btn-lg pull-right"
                                                type="button">{{__('Next')}}</button>
                                    </div>
                                </div>
                            </div>

                            <div class="row setup-content" id="step-2">
                                <div class="col-xs-12">
                                    <div class="col-md-12">


                                        <div class="inp_group">
                                            <label for="">{{__('Email')}}</label>
                                            <input type="email" class="form-control" placeholder="Email"
                                                   name="email" value="{{auth()->user()->email}}">
                                        </div>

                                        <div class="inp_group">
                                            <label for="">{{__('Phone')}}</label>
                                            <input type="text" class="form-control" placeholder="Phone"
                                                   name="phone" value="{{auth()->user()->phone}}">
                                        </div>
                                        <div class="inp_group">
                                            <label for="">{{__('Mobile')}}</label>
                                            <input type="text" class="form-control" placeholder="Mobile"
                                                   name="mobile" value="{{auth()->user()->mobile}}">
                                        </div>
                                        <div class="inp_group">
                                            <label for="">{{__('Mobile2')}}</label>
                                            <input type="text" class="form-control" placeholder="Mobile2"
                                                   name="mobile2" value="{{auth()->user()->mobile2}}">
                                        </div>
                                        <div class="inp_group">
                                            <label for="">{{__('Mobile3')}}</label>
                                            <input type="text" class="form-control" placeholder="Mobile3"
                                                   name="mobile3" value="{{auth()->user()->mobile3}}">
                                        </div>
                                        <div class="inp_group">
                                            <label for="" class="stay-top">{{__('Address')}}</label>
                                            <textarea name="address" class="form-control"
                                                      placeholder="Write Your Address Here.....">{{auth()->user()->address}}</textarea>
                                        </div>

                                        <div class="inp_group">
                                            <label for="" class="stay-top">{{__('Location')}}</label>
                                          <div id="edit_profile_map" class="form-control"></div>
                                            <input type="hidden" name="lat">
                                            <input type="hidden" name="lng">
                                        </div>


                                        <button class="btn btn-primary nextBtn btn-lg pull-right"
                                                type="button">{{__('Next')}}</button>
                                    </div>
                                </div>
                            </div>

                            <div class="row setup-content" id="step-3">
                                <div class="col-xs-12">
                                    <div class="col-md-12" >
                                       <h2 class="text-center text-success"> {{  __('Job is') . $userJob['name_'.\DataLanguage::get()]}}</h2><br>
                                        <div id="attribute_div_list_job">

                                        </div>

                                        <button class="btn btn-primary nextBtn btn-lg pull-right"
                                                type="button">{{__('Next')}}</button>
                                    </div>
                                    <div class="col-md-12">

                                    </div>
                                </div>
                            </div>

                            <div class="row setup-content" id="step-4">
                                <div class="col-xs-12">
                                    <div class="col-md-12">

                                        <div class="inp_group">
                                            <label for="facebook">{{__('Facebook')}}</label>
                                            <input type="text" class="form-control" placeholder="facebook"
                                                   name="facebook" value="{{auth()->user()->facebook}}">
                                        </div>
                                        <div class="inp_group">
                                            <label for="youtube">{{__('Youtube')}}</label>
                                            <input type="text" class="form-control" placeholder="youtube"
                                                   name="youtube" value="{{auth()->user()->youtube}}">
                                        </div>
                                        <div class="inp_group">
                                            <label for="linkedin">{{__('Linkedin')}}</label>
                                            <input type="text" class="form-control" placeholder="linkedin"
                                                   name="linkedin" value="{{auth()->user()->linkedin}}">
                                        </div>
                                        <div class="inp_group">
                                            <label for="instgram">{{__('Instgram')}}</label>
                                            <input type="text" class="form-control" placeholder="instgram"
                                                   name="instgram" value="{{auth()->user()->instgram}}">
                                        </div>
                                        <div class="inp_group">
                                            <label for="google">{{__('Google')}}</label>
                                            <input type="text" class="form-control" placeholder="google"
                                                   name="google" value="{{auth()->user()->google}}">
                                        </div>
                                        <button class="btn btn-success btn-lg pull-right"
                                                type="submit">{{__('Save')}}</button>
                                    </div>
                                </div>
                            </div>




                        </div>
                    </div>

                    {!! Form::close() !!}
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
    </style>

@endsection
@section('footer')


    <style>
        #edit_profile_map{
            height: 200px;;
        }
    </style>
    <script>
        var map3;
        var profile_marker;
        function initMap_edit_profile() {
                    @if(!empty(auth()->user()->lat) && !empty(auth()->user()->lng) && auth()->user()->lat != 0 && auth()->user()->lng != 0)
            var myLatLng = {lat: {{auth()->user()->lat}}, lng: {{auth()->user()->lng}}};
                    @else
            var myLatLng = {lat: 26.820553, lng: 30.802498};
            @endif
                map3 = new google.maps.Map(document.getElementById('edit_profile_map'), {
                center: myLatLng,
                zoom: 8
            });

            @if(!empty(auth()->user()->lat) && !empty(auth()->user()->lng) && auth()->user()->lat != 0 && auth()->user()->lng != 0)
                profile_marker = new google.maps.Marker({
                map: map3,
                title:"My Location",
                draggable: false,
                animation: google.maps.Animation.DROP,
                position: myLatLng
            });
            profile_marker.addListener('click', toggleBounce_profile);
            @endif

            google.maps.event.addListener(map3, 'click', function(event) {
                placeProfileMarker(event.latLng);
                $("input[name=lat]").val(event.latLng.lat());
                $("input[name=lng]").val(event.latLng.lng());
                console.log('lat: ' +event.latLng.lat());
                console.log('lng: ' +event.latLng.lng());
            });


        }

        function placeProfileMarker(location) {
            deleteProfileOverlays();
            profile_marker = new google.maps.Marker({
                map: map3,
                draggable: true,
                animation: google.maps.Animation.DROP,
                position: location
            });
            profile_marker.addListener('click', toggleBounce_profile);

        }

        function deleteProfileOverlays() {
            if (profile_marker) {
                profile_marker.setMap(null);
            }
        }

        function toggleBounce_profile() {
            if (profile_marker.getAnimation() !== null) {
                profile_marker.setAnimation(null);
            } else {
                profile_marker.setAnimation(google.maps.Animation.BOUNCE);
            }
        }


    </script>

    <script>

        $(document).ready(function () {

            get_old_user_attribute();

            $('#interested_cats').select2();

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



        function add_option_value(number) {
            var length = $('#option_value_list_' + number).length;
            var clonedRow = $('#option_value_temp').clone();
            clonedRow.find('.option_value_name_ar').attr('name', 'option[' + number + '][option_value_name_ar][]');
            clonedRow.find('.option_value_name_en').attr('name', 'option[' + number + '][option_value_name_en][]');
            clonedRow.find('.option_value_price_prefix').attr('name', 'option[' + number + '][option_value_price_prefix][]');
            clonedRow.find('.option_value_price').attr('name', 'option[' + number + '][option_value_price][]');
            clonedRow.find('.option_value_status').attr('name', 'option[' + number + '][option_value_status][]');
            $('#option_value_list_' + number).append(clonedRow.html());
        }

        function check_option_type(number) {
            var value = $('#option_type_' + number).val();
            if (value == 'select' || value == 'radio' || value == 'check') {
                $('#option_value_list_' + number).show();
            } else {
                $('#option_value_list_' + number).hide();
            }
        }

        var publicLength = $('#option_li_list li').length;

        function add_option() {
            var li_length = publicLength;
            var cloned_li_row = $('#option_li_temp').clone();
            var number = +li_length + 1;
            cloned_li_row.find('.option_a_row').attr('id', 'tab_' + li_length).attr('aria-controls', 'tabv_' + li_length)
                .attr('href', '#tabv_' + li_length).html('Option ' + number).attr('onclick', 'change_tab(' + li_length + ')');
            cloned_li_row.find('#remove_option').attr('id', 'remove_option_' + li_length).attr('onclick', 'remove_option(' + li_length + ')');
            cloned_li_row.find('.option_li_row').addClass('option_li_row_' + li_length);

            var clone_div_row = $('#option_div_temp').clone();

            clone_div_row.find('.option_div_row').attr('id', '#tabv_' + li_length).attr('aria-labelledby', 'tab_' + li_length)
                .addClass('option_div_row_' + li_length);
            clone_div_row.find('#new_option').attr('id', 'new_option_' + li_length);
            clone_div_row.find('#option_value_list').attr('id', 'option_value_list_' + li_length);
            clone_div_row.find('.add_option_value_button').attr('onclick', 'add_option_value(' + li_length + ')');

            clone_div_row.find('.template_option').attr('onchange', 'choose_template_new(' + li_length + ')').attr('id', 'template_option_' + li_length).attr('name', 'option[' + li_length + '][template_option]');
            clone_div_row.find('.option_sort').attr('id', 'option_sort' + li_length).attr('name', 'option[' + li_length + '][option_sort]');
            clone_div_row.find('.option_type').attr('id', 'option_type_' + li_length).attr('onchange', 'check_option_type(' + li_length + ')').attr('name', 'option[' + li_length + '][option_type]');
            clone_div_row.find('.option_name_ar').attr('name', 'option[' + li_length + '][option_name_ar]');
            clone_div_row.find('.option_name_en').attr('name', 'option[' + li_length + '][option_name_en]');
            clone_div_row.find('.option_min_select').attr('name', 'option[' + li_length + '][option_min_select]');
            clone_div_row.find('.option_max_select').attr('name', 'option[' + li_length + '][option_max_select]');
            clone_div_row.find('.option_is_required').attr('name', 'option[' + li_length + '][option_is_required]');
            clone_div_row.find('.option_status').attr('name', 'option[' + li_length + '][option_status]');


            $('#option_li_list').append(cloned_li_row.html());
            $('#option_div_list').append(clone_div_row.html());
            publicLength = +publicLength + 1;
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

        function get_old_user_attribute() {



            $.post('{{route('web.user.get-attribute-user')}}', function (out) {

                    if (out.status == false) {
                        $('#attribute_div_list_job').html('<div class="text-center text-danger">لا توجد خصائص لهذا القسم</div>');
                        //  toastr.error(out.msg, 'Error', {"closeButton": true});
                    } else {
                        //console.log(out.data);

                        $('#attribute_div_list_job').html('');
                        for (var i = 0; i < out.data.length; i++) {
                            var attribute = out.data[i];
                            var drow = $('<div>').attr('class', 'inp_group');
                            drow.append($('<label>').html(attribute.name));

                            if (attribute.type == 'text') {
                                drow.append($('<input>', {
                                    name: 'attribute[' + attribute.id + ']',
                                    class: 'form-control',
                                    id: 'attribute_' + attribute.id,
                                    value:attribute.selected_value_name
                                }));
                            } else if (attribute.type == 'file') {
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
                                    id: 'attribute_' + attribute.id,
                                    value:attribute.selected_value_name
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
                            $('#attribute_div_list_job').append(drow);


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


        $('#userEditForm').submit(function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            $("#userEditForm input[type=text]").css('border', '1px solid #ccc');
            $.ajax({
                type: "post",
                url: '{{route('web.user.update-profile',auth()->user()->id)}}',
                processData: false, contentType: false,
                data: formData,
                success: function (out) {
                    if (out.status == false) {
                        //alert(out.data[Object.keys(out.data)[0]]);
                        $.each(out.data, function (index, value) {
                            var inputName = index.split(".");
                            if (inputName[1]) {
                                //focus valid input
                                if (value == out.data[Object.keys(out.data)[0]]) {
                                    $('#attribute_' + inputName[1]).focus();
                                }
                                $('#attribute_' + inputName[1]).css('border', '1px solid #ff0909');
                            } else {
                                //focus valid input
                                if (value == out.data[Object.keys(out.data)[0]]) {
                                    $("input[name=" + index + "]").focus();
                                }
                                $("input[name=" + index + "]").css('border', '1px solid #ff0909');

                            }
                            notify('error', value);

                        });


                    } else {

                        notify('success', '{{__('Your Profile has been successfully Edited')}}');
                        $("#userEditForm input[type=text]").css('border', '1px solid #ccc');
                    }
                },
                error: function (out) {
                    console.log(out);
                    notify('error', 'Error!  please try again later.');
                }
            });
        });
        {{--$('#userEditForm').submit(function (e) {--}}
            {{--e.preventDefault();--}}
            {{--var formData = new FormData(this);--}}
            {{--e.preventDefault();--}}
            {{--$.ajax({--}}
                {{--type: "post",--}}
                {{--url: '{{ route('web.user.update-profile',auth()->user()->id) }}',--}}
                {{--processData: false,--}}
                {{--contentType: false,--}}
                {{--data: formData,--}}
                {{--success: function (out) {--}}
                    {{--alert(out);--}}
                    {{--if (out.status == true) {--}}
                        {{--notify('success', 'Your Profile has been successfully Edited');--}}
                    {{--} else {--}}
                        {{--notify('success', 'Your Profile has been successfully Edited');--}}

                    {{--}--}}
                {{--},--}}
                {{--error: function (out) {--}}
                    {{--$.each(out.data, function (index, value) {--}}
                        {{--console.log(value);--}}
                        {{--notify('error', value);--}}
                    {{--});--}}
                {{--}--}}
            {{--});--}}


        {{--});--}}

        function change_type(){
            var type= $('#type').val();
            if(type == 'company'){
                $('#selectType').show();
            }else{
                $('#selectType').hide();
            }
        }
        // $(document).ready(function () {
        //     $('.stepper').mdbStepper();
        // })

        // function someFunction21() {
        //     setTimeout(function () {
        //         $('#horizontal-stepper').nextStep();
        //     }, 2000);
        // }

    </script>



@endsection
