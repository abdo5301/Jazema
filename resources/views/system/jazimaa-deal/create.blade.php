@extends('system.layouts')
@section('header')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/forms/selects/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/css/plugins/forms/wizard.css')}}">
@endsection
<div class="modal fade text-xs-left" id="option-modal" role="dialog" aria-labelledby="myModalLabel33"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <label class="modal-title text-text-bold-600" id="myModalLabel33">{{__('Item Options')}}</label>
            </div>
            {!! Form::open(['id'=>'ItemForm','onsubmit'=>'checkOption($(this));return false;']) !!}
            <div class="modal-body">
                <div class="card-body">
                    <div class="card-block">

                        <div class="row form-group" id="option_list">


                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="button" onclick="$('#option-modal').modal('hide')" class="btn btn-outline-secondary btn-md"
                       value="{{__('close')}}">
                <input type="submit" class="btn btn-outline-primary btn-md" value="{{__('Save')}}">
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

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
                                            {{--{{print_r($errors->all())}}--}}
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
                        {!! Form::open(['route' => isset($order->id) ? ['system.deal.update',$order->id]:'system.deal.store','files'=>true, 'method' => isset($order->id) ?  'PATCH' : 'POST','class'=>'number-tab-steps wizard-circle','id'=>isset($order->id) ?'dealEditForm':'dealForm']) !!}
                        <!-- Step 1 -->
                            <h6>{{__('Information')}}</h6>
                            <fieldset style="padding: 0px;">
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h2>{{__('Information')}}</h2>
                                        </div>
                                        <div class="card-block card-dashboard">

                                            <div class="form-group col-sm-6{!! formError($errors,'item_owner_id',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('item_owner_id', __('User From').':') !!}
                                                    {!! Form::select('item_owner_id',[],old('item_owner_id'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'item_owner_id') !!}
                                            </div>


                                            <div class="form-group col-sm-6{!! formError($errors,'user_id',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('user_id', __('User').':') !!}
                                                    {!! Form::select('user_id',[],old('user_id'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'user_id') !!}
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </fieldset>

                            <h6>{{__('Items')}}</h6>
                            <fieldset style="padding: 0px;">

                                <div class="form-group col-sm-8{!! formError($errors,'item_id',true) !!}">
                                    <div class="controls">
                                        {!! Form::label('item_id', __('Items').':') !!}
                                        {!! Form::select('item_id',[],old('item_id'),['class'=>'form-control','style'=>'width:100%']) !!}
                                    </div>
                                    {!! formError($errors,'item_id') !!}
                                </div>
                                <div style="text-align: right;" class="col-sm-4">
                                    <button type="button" class="btn btn-primary fa fa-plus " onclick="add_product()">
                                        <span>{{__('Add Item')}}</span>
                                    </button>
                                </div>


                                <div id="product_list" class="table-responsive">
                                    <table class="table table-hover table-bordered">
                                        <thead>
                                        <tr>
                                            <th>image</th>
                                            <th>name</th>
                                            <th>price</th>
                                            <th>options</th>
                                            <th>delete</th>
                                        </tr>
                                        </thead>
                                        <tbody id="product_list_table">
                                        <tr><td colspan="5">  {!! Form::label('item',' ') !!}</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div id="product_temp" style="display: none">
                                    <div class="product_row">
                                        <img width="100px" src="" alt="" class="col-sm-3 product_image_temp">
                                        <p class="col-sm-3 product_name_temp"></p>
                                        <div class="col-sm-6 option_div_temp">

                                        </div>
                                        <hr/>
                                    </div>
                                </div>

                            </fieldset>


                    {!! Form::close() !!}
                </section>
                <!--/ Javascript sourced data -->
            </div>
        </div>

    </div>
    <!-- ////////////////////////////////////////////////////////////////////////////-->
@endsection
@section('footer')
    <script src="{{asset('assets/system/js/scripts')}}/custom/custominput.js"></script>
    <script src="{{asset('assets/system')}}/vendors/js/forms/select/select2.full.min.js"
            type="text/javascript"></script>

    <script src="{{asset('assets/system')}}/vendors/js/extensions/jquery.steps.min.js" type="text/javascript"></script>
    <script src="{{asset('assets/system')}}/js/scripts/forms/wizard-steps.js" type="text/javascript"></script>

    <script>

        function checkOption($this) {

            $.post('{{route('system.check-product-option')}}', $this.serialize(), function (out) {

                $('.option_msg').html('');

                if (out.status == false) {
                    for (var i = 0; i < out.data.errors.length; i++) {
                        var field = out.data.errors[i];
                        var field_msg = out.data.error_msg[i];

                        $('#option_msg_' + field).html(field_msg);
                        toastr.error(out.msg, 'Error', {"closeButton": true});
                    }
                } else {

                    var product_data = $('<tr>', {class: 'product_row', id: 'product_row_' + out.data.product_id});
                    var DivName = $('<div>', {id: 'product_name_' + out.data.product_id});
                    product_data.append($('<td>').html($('<img>', {src: out.data.img, width: '100px'})));
                    product_data.append($('<td>').append([$('<h4>').append(out.data.name), $('<small>').append('Quantity:'),
                        $('<input>', {
                            value: '1',
                            type: 'number',
                            placeholder: 'Quantity',
                            class: 'form-control',
                            style: 'width:75%',
                            name: 'product[' + out.data.product_id + '][quantity]'
                        })]));
                    DivName.append($('<input>', {
                        type: 'hidden',
                        name: 'product[' + out.data.product_id + '][id]',
                        value: out.data.product_id
                    }));

                    var priceTD = $('<td>');
                    priceTD.append($('<p>').html('Price : ' + out.data.price));
                    DivName.append($('<input>', {
                        type: 'hidden',
                        name: 'product[' + out.data.product_id + '][price]',
                        value: out.data.price
                    }));

                    if (out.data.taxes) {
                        var taxP = $('<p>').append('Taxes : ');
                        for (var x = 0; x < out.data.taxes.length; x++) {
                            var tax = out.data.taxes[x];
                            if (tax.type == 'percentage')
                                taxP.append($('<p>').html(tax.name + ' : ' + tax.price + ' (' + tax.rate + ' %)'))
                            else
                                taxP.append($('<p>').html(tax.name + ' : ' + tax.price))

                            priceTD.append(taxP);
                        }
                    }

                    product_data.append(priceTD);
                    var allOptions = out.data.option;
                    var options = [];
                    for (var y = 0; y < allOptions.length; y++) {

                        var field = out.data.option[y];
                        if (field.type == 'check') {
                            var optionValues = [];
                            var pp = $('<p>').append(field.name + ' : ');

                            for (var z = 0; z < field.value.length; z++) {
                                optionValues.push($('<p>').append(field.value[z] + ' | Price : ' + field.price_prefix[z] + field.price[z]))
                                pp.append(optionValues);
                                options.push(pp);
                                DivName.append($('<input>', {
                                    type: 'hidden',
                                    name: 'product[' + out.data.product_id + '][option][' + field.id + '][]',
                                    value: field.value_id[z]
                                }));
                            }
                        } else if (field.type == 'select' || field.type == 'radio') {
                            options.push($('<p>').html(field.name + ' : ' + field.value + ' | Price : ' + field.price_prefix + field.price));
                            DivName.append($('<input>', {
                                type: 'hidden',
                                name: 'product[' + out.data.product_id + '][option][' + field.id + ']',
                                value: field.value_id
                            }));
                        } else {
                            options.push($('<p>').html(field.name + ' : ' + field.value + ' | Price : ' + field.price_prefix + field.price));
                            DivName.append($('<input>', {
                                type: 'hidden',
                                name: 'product[' + out.data.product_id + '][option][' + field.id + ']',
                                value: field.value
                            }));

                        }
                    }
                    options.push(DivName)
                    product_data.append($('<td>').append(options));
                    product_data.append($('<td>').html('<a href="javascript:void(0);" onclick="$(this).closest(\'.product_row\').remove();" class="text-danger"> <i class="fa fa-lg fa-trash mt-3"></i></a>'));

                    $('#product_list_table').append(product_data);
                    $('#option-modal').modal('hide');
                    toastr.success('Produed is added', 'Success', {"closeButton": true});
                }
            }, 'json');

        }

        function add_product() {
            var item_id = $('#item_id').val();
            if (item_id) {
                $.post('{{route('system.product-option')}}', {'item_id': item_id}, function (out) {
                    $('#option_list').html('');
                    if (out.status == false) {
                        toastr.error(out.msg, 'This item Does not Have Any Options', {"closeButton": true});
                    }
                    if (out.status == true) {

                        // options
                        var drow = $('<div>', {class: 'form-group col-sm-12'});
                        var optionDiv = '';
                        drow.append($('<input>', {type: 'hidden', name: 'item_id', value: item_id}));
                        for (var i = 0; i < out.data.options.length; i++) {
                            var option = out.data.options[i];
                            optionDiv = $('<div>', {class: 'form-group col-sm-12'});
                            optionDiv.append($('<lable>').html(option.name)).append($('<p>', {
                                style: 'padding: inherit;display: inline;color:red',
                                id: 'option_msg_' + option.id,
                                class: 'option_msg'
                            }));
                            if (option.type == 'text') {
                                optionDiv.append($('<input>', {
                                    class: 'form-control option',
                                    name: 'option[' + option.id + ']',
                                }));
                            }
                            else if (option.type == 'textarea') {
                                optionDiv.append($('<textarea>', {
                                    class: 'form-control option',
                                    name: 'option[' + option.id + ']',
                                }));
                            }
                            else if (option.type == 'select') {
                                var values = [];

                                for (var x = 0; x < option.values.length; x++) {
                                    var value = option.values[x];

                                    values.push($('<option>', {value: value.id}).html(value.name_ar + ' ' + value.price_prefix + ' ' + value.price))
                                }

                                optionDiv.append(
                                    $('<select>', {
                                        class: 'form-control option',
                                        name: 'option[' + option.id + ']',
                                    })
                                        .append(values)
                                );
                            } else if (option.type == 'radio') {
                                var values = [];
                                for (var x = 0; x < option.values.length; x++) {
                                    var value = option.values[x];
                                    values.push($('<p>').html(value.name_ar + ' ' + value.price_prefix + ' ' + value.price))
                                    values.push($('<input>', {
                                        type: 'radio',
                                        class: 'form-control option',
                                        name: 'option[' + option.id + ']',
                                        value: value.id
                                    }));
                                }
                                optionDiv.append(values);
                            } else if (option.type == 'check') {

                                var values = [];
                                for (var x = 0; x < option.values.length; x++) {
                                    var value = option.values[x];
                                    values.push($('<p>', {for: 'option' + option.id})
                                        .append($('<input>', {
                                            type: 'checkbox',
                                            class: 'checkbox option',
                                            id: 'option' + option.id,
                                            name: 'option[' + option.id + '][]',
                                            value: value.id
                                        }))
                                        .append(value.name_ar + ' ' + value.price_prefix + ' ' + value.price));
                                }
                                optionDiv.append(values);
                            }
                            drow.append(optionDiv)
                            $('#option_list').append(drow);


                        }

                        $('#option-modal').modal('show');
                    }
                }, 'json');
            }
        }


        $('#item_owner_id').on('select2:select', function (e) {
            var param = e.params.data;
           // console.log(param);
            $.getJSON('{{route('system.ajax.get')}}', {
                type: 'UserItem',
                item_owner_id: param.id
            }, function (data) {
                $.each(data, function (i, val) {
                    $('#item_id').append($('<option>', {value: val.id, text: val.value}));
                    $('#item_id').select2({}).trigger('change');
                });
            });
            $.getJSON('{{route('system.ajax.get')}}', {
                type: 'UserItem',
                item_owner_id: param.id
            }, function (data) {
                $.each(data, function (i, val) {
                    $('#item_id').append($('<option>', {value: val.id, text: val.name}));
                    $('#item_id').select2({}).trigger('change');
                });
            });
        });

        ajaxSelect2('#item_owner_id', 'user');
        ajaxSelect2('#user_id', 'user');






        $('#item_id').select2({

            placeholder: '{{__('Select Item')}}'
        });



        $('#dealForm').submit(function (e) {
            e.preventDefault();
            $.post('{{route('system.deal.store')}}', $('#dealForm').find(":input").serialize(), function (out) {
                $('.validation_error_msg').remove();
                $('.product_row').css('border-color', '#aaa');

                if (out.status == false) {
                    toastr.error(out.msg, 'Error', {"closeButton": true});
                    if (out.data.errors) {
                        $.each(out.data.errors, function (index, value) {
                            if (index == 'items') {
                                $.each(value, function (index2, value2) {
                                    $('#product_row_' + index2).css('border-color', 'red');
                                });
                            } else {
                                $('[for="' + index + '"]').html($('[for="' + index + '"]').html() + '  <p style="color:red;display: inline-block;" class="validation_error_msg" id="error_msg_' + index + '" >' + value + '</p>');
                            }
                        });
                    }
                }else {
                    toastr.success(out.msg, 'Success', {"closeButton": true});
                    $('#dealForm')[0].reset();
                    $('.product_row').remove();
                    $('#user_id').html('');
                    $('#item_id').html('');
                }
            }, 'json')


        });

        $(function () {
            var observer = new MutationObserver(function (mutations) {

                if ($('#map-events').is(':visible')) {
                    if (!mapInitilized) {
                        google.maps.event.trigger(map, 'resize');
                        mapInitilized = true;
                    }
                }
            });

            observer.observe(document.querySelector('#dealForm-t-3'), {
                attributes: true
            });
        });

    </script>
@endsection