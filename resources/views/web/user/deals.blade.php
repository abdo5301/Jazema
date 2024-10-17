@extends('web.layouts')
@section('header')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.css">
@endsection
@section('content')

    @if(auth()->check())
        @include('web.partial.user-profile-banner')
    @endif


    <!-- =-=-=-=-=-=-= rank user Modal =-=-=-=-=-=-= -->
    <div class="modal fade modal-register" tabindex="-1" role="dialog" aria-hidden="true" id="rank-preview">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">

                    {{--<h2 class="login-head">{{__('Your Evaluation')}}</h2>--}}
                    <button id="close_rank_modal" type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span
                                class="sr-only">Close</span></button>
                    <!-- content goes here -->
                    <form id="rank_user_form" method="post">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <div id="user_rank" style="margin-left: 169px;">
                        </div><div class="counter badge" style="margin-left: 174px;"></div>
                        {{--<button class="btn btn-theme btn-lg btn-block"  type="submit">{{__('Save')}}</button>--}}
                    </form>

                </div>
            </div>
        </div>
    </div>
    <!-- =-=-=-=-=-=-= rank user Modal =-=-=-=-=-=-= -->

    <!-- =-=-=-=-=-=-= status user Modal =-=-=-=-=-=-= -->
    <div class="modal fade modal-register" tabindex="-1" role="dialog" aria-hidden="true" id="status-preview">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">

                    {{--<h2 class="login-head">{{__('Your Evaluation')}}</h2>--}}

                        <h2 class="login-head">{{__('Deal Status')}}</h2>
                        <button  type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span
                                    class="sr-only">Close</span></button>

                                {!! Form::open(['route' =>['web.user.deals'],'files'=>true, 'method' => 'POST','class'=>'updateStatusForm']) !!}
                                {{--['web.user.deal.update-status',$deal->id]--}}
                                <div class="attribute_row form-group">
                                    <input name="id" id="deal_status_id" type="hidden" >
                                    {{--<label  for="status">{{__('Status')}}</label>--}}
                                    <select  name="status" class="form-control">
                                        <option selected disabled hidden>{{__('Status')}}</option>
                                        <option value="done">{{__('Done')}}</option>
                                        <option value="pending">{{__('Pending')}}</option>
                                        <option value="inprogress">{{__('Inprogress')}}</option>
                                        <option value="pause">{{__('Pause')}}</option>
                                        <option value="stopping">{{__('Stopping')}}</option>
                                    </select>
                                </div>

                                {!! Form::submit(__('Save'),['class'=>'btn btn-success pull-right']) !!}

                        {!! Form::close() !!}


                </div>
            </div>
        </div>
    </div>
    <!-- =-=-=-=-=-=-= status user Modal =-=-=-=-=-=-= -->


    <!-- =-=-=-=-=-=-= deal options Modal =-=-=-=-=-=-= -->
    <div class="modal fade modal-register" tabindex="-1" role="dialog" aria-hidden="true" id="deal-options-preview">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">

                    <h2 class="login-head">{{__('Deal Options')}}</h2>
                    <button id="close_deal_modal" type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span
                                class="sr-only">Close</span></button>
                    <!-- content goes here -->

                        <div id="drow_options_div">

                        </div>


                    </form>

                </div>
            </div>
        </div>
    </div>
    <!-- =-=-=-=-=-=-= deal options Modal =-=-=-=-=-=-= -->



    <!-- =-=-=-=-=-=-= Main Content Area =-=-=-=-=-=-= -->
    <div class="main-content-area reset clearfix view-profile">

        <!-- =-=-=-=-=-=-= Featured Ads =-=-=-=-=-=-= -->
        <section class="custom-padding">
            <!-- Main Container -->
            <div class="container menu-list-items">
                <!-- Row -->
                <div class="row">
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
                                    <button type="button" id="close" class="close" data-dismiss="modal" aria-label="Close" style="float:right;">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    {{ Session::get('msg') }}
                                </div>
                            </div>
                        </div>
                    @endif

                    <ul class="nav nav-tabs " style="padding-left: 297px;border-bottom: 0;">

                        <li class=" col-md-4 col-sm-12 col-xs-12" style="text-align: center;"><a data-toggle="tab" href="#dealsOut">{{__('Deals In')}}</a></li>
                        <li class="active col-md-5 col-sm-12 col-xs-12" style="float: right;text-align: center;"><a data-toggle="tab" href="#dealsIn">{{__('Deals Out')}}</a></li>
                    </ul>
                        @if(auth()->check())
                            @include('web.partial.user-sidebar')
                        @endif

                    <div class="tab-content">
                        <div class="table_below tab-pane fade in active" id="dealsIn">
                            <div class="table-responsive" style="height: 485px;">
                                <table class="table table-bordered table-hover table-responsive">
                                    <thead>
                                    <tr>
                                        <th>{{__('Item')}}</th>
                                        <th>{{__('Deal Owner Name')}}</th>
                                        {{--<th>{{__('Item Owner Name')}}</th>--}}
                                        <th>{{__('Date')}}</th>
                                        <th>{{__('Price')}}</th>
                                        <th>{{__('Status')}}</th>
                                        <th>{{__('options')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($dealsIn as $deal)
                                        <tr>
                                            <td><a href="{{route('web.item.details',$deal->item->slug_ar)}}" target="_blank">{{$deal->item->name}}</a></td>
                                            <td><a href="{{route('web.user.profile',$deal->user->slug)}}" target="_blank">{{$deal->user->full_name}}</a></td>
                                            {{--<td><a href="{{route('web.user.profile',$deal->owner->slug)}}" target="_blank">{{$deal->owner->full_name}}</a></td>--}}
                                            <td>{{$deal->created_at->format('Y-m-d')}}</td>
                                            <td>{{$deal->total_price}}</td>
                                            @if($deal->status !="done")
                                                @if($deal->status == "pending")
                                                <td><b><a class="text-info" href="#status-preview" onclick="set_deal_status_id('{{$deal->id}}')" data-toggle="modal" >{{__($deal->status)}} <i class="fa fa-edit"></i></a></b></td>
                                                @elseif($deal->status == "inprogress")
                                                <td><b><a class="text-warning" href="#status-preview" onclick="set_deal_status_id('{{$deal->id}}')" data-toggle="modal" >{{__($deal->status)}} <i class="fa fa-edit"></i></a></b></td>
                                                @elseif($deal->status == "stopping")
                                                <td><b><a class="text-danger" href="#status-preview" onclick="set_deal_status_id('{{$deal->id}}')" data-toggle="modal" >{{__($deal->status)}}  <i class="fa fa-edit"></i></a></b></td>
                                                @else
                                                <td><b><a class="text-danger" href="#status-preview" onclick="set_deal_status_id('{{$deal->id}}')" data-toggle="modal" >{{__($deal->status)}}  <i class="fa fa-edit"></i></a></b></td>
                                                @endif
                                                    <!-- Change Deal  Status-->
                                            @else
                                                <td><b style="color:green;" title="This Deal is already done">{{__($deal->status)}}</b></td>
                                            @endif
                                            <td>
                                                <a onclick="drow_deal_options('{{$deal->id}}')" class="text-center" title="{{__('View Deal Options')}}" href="#deal-options-preview" data-toggle="modal"><i class="fa fa-search"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="table_below tab-pane fade in " id="dealsOut">
                            <div class="table-responsive" style="height: 485px;">
                                <table class="table table-bordered table-hover table-responsive">
                                    <thead>
                                    <tr>
                                        <th>{{__('Item')}}</th>
                                        {{--<th>{{__('User Name')}}</th>--}}
                                        <th>{{__('Owner Name')}}</th>
                                        <th>{{__('Date')}}</th>
                                        <th>{{__('Price')}}</th>
                                        <th>{{__('Status')}}</th>
                                        <th>{{__('Rate Owner')}}</th>
                                        <th>{{__('options')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($dealsOut as $deal)
                                        <tr>
                                            <td><a href="{{route('web.item.details',$deal->item->slug_ar)}}" target="_blank">{{$deal->item->name}}</a></td>
                                            {{--<td>{{$deal->user->full_name}}</td>--}}
                                            <td><a href="{{route('web.user.profile',$deal->owner->slug)}}" target="_blank">{{$deal->owner->full_name}}</a></td>
                                            <td>{{$deal->created_at->format('Y-m-d')}}</td>
                                            <td>{{$deal->total_price}}</td>

                                                @if($deal->status =="done")
                                                <td><b style="color:green;">{{__($deal->status)}}</b></td>
                                               @elseif($deal->status =="pending")
                                                <td><b class="text-info" >{{__($deal->status)}}</b></td>
                                               @elseif($deal->status =="inprogress")
                                                <td><b class="text-warning" >{{__($deal->status)}}</b></td>
                                                @else
                                                <td><b class="text-danger">{{__($deal->status)}}</b></td>
                                                @endif
                                            
                                            <td id="rank_owner_{{$deal->id}}">
                                                @if(rank_check($deal->item_owner_id,'App\Models\User'))
                                                    <b style="color:green;" title="You already rated him">  {{__('Owner Rated')}} </b>
                                                @else
                                                    @if($deal->status == 'done')
                                                        <a id="open_rank_modal" class="btn btn-warning btn-xs"  href="#rank-preview"  data-toggle="modal" onclick="public_owner_id_ranked = '{{$deal->item_owner_id}}';public_deal_id_ranked = '{{$deal->id}}';"> {{__('rate Owner')}}</a>
                                                    @else
                                                        <b class="text-danger" title="You can't rate the Owner till the deal is done">   {{__('Deal not done')}} </b>
                                                    @endif
                                                @endif</td>
                                            <td>
                                                <a onclick="drow_deal_options('{{$deal->id}}')" class="text-center" title="{{__('View Deal Options')}}" href="#deal-options-preview" data-toggle="modal"><i class="fa fa-search"></i></a>
                                            </td>
                                            {{--<td><a><i class="fa fa-times fa-2x"></i></a></td>--}}
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                    <!-- Row End -->
                </div>
            </div>
        </section>
        <!-- Main Container End -->
    </div>



@endsection

@section('footer')


    <script>

        var public_owner_id_ranked = 0;
        var public_deal_id_ranked = 0;


        $(document).ready(function() {

            $("#user_rank").rateYo({
            spacing: "10px",
            rating:0,
            multiColor: {
                "startColor": "#FF0000", //RED
                "endColor"  : "#00FF00"  //GREEN
            }, onChange: function (rating, rateYoInstance) {
                $(this).next().text(rating);
            },onSet: function (rating, rateYoInstance) {

                if(!confirm("Rating is set to: " + rating + "\nDo you want to save this rate?")){
                    return false;
                }
                rate_user(rating);

            }
        });


        });



        function rate_user(user_rank) {
            $.post('{{route('web.user.rank-user')}}',{'owner_id':public_owner_id_ranked,'deal_id':public_deal_id_ranked,'rank':user_rank}, function (out) {
                //console.log('owner id :'+public_owner_id_ranked);
                //console.log('deal id :'+public_deal_id_ranked);
                if (out.status == false) {
                    notify('error',out.msg);
                } else {
                    notify('success','{{__('Done. Thank you for rating')}}');
                    $('#close_rank_modal').click();
                    $('#rank_owner_' + public_deal_id_ranked).html('<b style="color:green;"title="You already rated him">{{__('Owner Rated')}}</b>');

                }
            });
        }



            {{--$('.updateStatusForm').submit(function (e) {--}}
                {{--e.preventDefault();--}}
                {{--// var id = 23;--}}

                {{--$.post('{{route('web.user.deals')}}', $('#updateStatusForm').find(":input").serialize(), function (test) {--}}
                    {{--console.log(test);--}}
                    {{--if (test.status == false) {--}}
                        {{--$.each(test.data, function (index, value) {--}}
                            {{--notify('error', value);--}}
                        {{--});--}}

                    {{--} else {--}}

                        {{--notify('success', test.msg);--}}
                        {{--// $('#userEditForm')[0].reset();--}}
                        {{--//  setTimeout(location.reload,3000);--}}
                    {{--}--}}
                {{--}, 'json');--}}
            {{--});--}}

             // var public_deal_id_status = 0 ;

              function set_deal_status_id(id){
                   $('#deal_status_id').val(id);
              }
            // function set_deal_options_id(id){
            //     $('#deal_options_id').val(id);
            // }

            $('.updateStatusForm').submit(function (e) {
                e.preventDefault();
                var formData = new FormData(this);
                e.preventDefault();
                $.ajax({
                    type: "post",
                    url: '{{ route('web.user.deals') }}',
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: function (out) {

                        if (out.status == true) {

                            notify('success', out.msg);
                            $('modal').hide();
                            setTimeout((function() {
                                window.location.reload();
                            }), 250);
                        } else {
                            notify('error',out.msg);

                        }
                    },
                    error: function (out) {

                        notify('error','Error !', {"closeButton": true});

                    }
                });


            });


        function drow_deal_options(deal_id){
            $.post('{{route('web.user.get-deal-options')}}',{'deal_id':deal_id}, function (out) {
                if (out.status == false) {
                    $('#drow_options_div').html('');
                    notify('error','Error !');
                } else {
                    $('#drow_options_div').html('');
                    console.log(out.data);
                    for (var i = 0; i < out.data.options.length; i++) {
                        var option_value = out.data.options[i];
                        var option = option_value.item_option;
                        var option_value_selected = option_value.item_option_values;

                        var drow = $('<div>').attr('class', 'txt-about');
                        drow.append('<h3>' + option.name_ar + '</h3>');
                        if(option.type == 'select'){
                            drow.append('<p class="well">' + option_value_selected.name_ar + '</p>');
                        }else{
                            if(option_value.value != null){
                                drow.append('<p class="well">' + option_value.value + '</p>');
                            }else{
                                drow.append('<p class="well">' + '{{__('No Answer')}}' +'</p>');
                            }

                        }




                        $('#drow_options_div').append(drow);
                    }

                    if(out.data.notes != null && out.data.notes !=''){
                        $('#drow_options_div').append($('<div>').attr('class', 'txt-about').append('<h3>' + '{{__('Notes')}}' + '</h3>').append('<p class="well">' + out.data.notes + '</p>'));
                    }

                }
            }, 'json');
        }

        // var closebtns = document.getElementById("close");
        // console.log(closebtns);
        // closebtns.addEventListener("click",function () {
        //     this.parentElement.style.display = 'none';
        // });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js"></script>

@endsection
