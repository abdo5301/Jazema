@extends('web.layouts')

@section('content')


    @if(auth()->check())
        @include('web.partial.user-profile-banner')
    @endif



<style>
    .badge{
        background-color: #ccc;
    }
     .fa-trash{
        color: darkred;
    }

</style>

    <!-- =-=-=-=-=-=-= Main Content Area =-=-=-=-=-=-= -->
    <div class="main-content-area reset clearfix view-profile">

        <!-- =-=-=-=-=-=-= Featured Ads =-=-=-=-=-=-= -->
        <section class="custom-padding">
            <!-- Main Container -->
            <div class="container menu-list-items">
                <!-- Row -->

                <div class="row">

                @if(auth()->check())
                        @include('web.partial.user-sidebar')
                    @endif

                    <div class="col-md-9 col-xs-12 col-sm-12 no-padding">
                        <div class="mail_body">
                            <div class="row">
                                <div class="col-md-3 links">

                                    <div class="main_links">

                                        <ul>
                                            <div class="compose">
                                                <a href="#send_mail"  data-toggle="tab"   class="btn btn-primary btn-block">{{__('Compose')}}</a>
                                            </div><br>
                                            <li class="active"><a data-toggle="tab" href="#received">{{__('Inbox')}}</a><span class="numbers badge count_received">{{count($received)}}</span></li>
                                            <li><a data-toggle="tab" href="#sent">{{__('Sent')}}</a><span class="numbers badge count_sent">{{count($sent)}}</span></li>
                                            <li><a data-toggle="tab" href="#trashed">{{__('Trash')}}</a><span class="numbers badge count_trashed">{{count($trashed)}}</span></li>
                                            <li style="display: none;"><a id="view_message_link" data-toggle="tab" href="#message_tab"></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="tab-content" >
                                <div class="col-md-9 m-body All_messages tab-pane fade in active" id="received" >
                                    <div class="table-responsive" style="height: 485px;">
                                        <table class="table table-hover table-responsive">
                                            <tbody>
                                            @if(count($received)>0)
                                            @foreach($received as $item)
                                            <tr>
                                                <td><a onclick="deleteMail('{{route('web.user.delete-mail',$item->id)}}');" href="javascript:void(0);"><i class="remove fa fa-trash "></i> </a></td>
                                                <td>{{$item->name}}</td>
                                                <td><a  onclick="view_message('{{route('web.user.view-mail',$item->id)}}');" href="javascript:void(0);" >{{$item->subject}}</a></td>
                                                <td><smal>{{$item->created_at->diffForHumans()}}</smal></td>
                                            </tr>
                                                @endforeach
                                             @else
                                                <h3 class="text-center text-danger" style="top: 70px;">{{__('The Inbox is empty')}}</h3>
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>

                                </div>



                                    <div class="col-md-9 m-body All_messages tab-pane fade" id="sent">
                                        <div class="table-responsive" style="height: 485px;">
                                            <table class="table table-hover table-responsive" >
                                                <tbody>
                                                @if(count($sent)>0)
                                                @foreach($sent as $item)
                                                    <tr>
                                                        <td><a onclick="deleteMail('{{route('web.user.delete-mail',$item->id)}}')" href="javascript:void(0);"><i class="remove fa fa-trash"></i> </a></td>
                                                        <td>{{$item->name}}</td>
                                                        <td><a onclick="view_message('{{route('web.user.view-mail',$item->id)}}');" href="javascript:void(0);">{{$item->subject}}</a></td>
                                                        <td><smal>{{$item->created_at->diffForHumans()}}</smal></td>
                                                    </tr>
                                                @endforeach
                                                @else
                                                <h3 class="text-center text-danger" style="top: 70px;">{{__('No Sent mails found')}}</h3>
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>


                                    <div class="col-md-9 m-body All_messages tab-pane fade" id="trashed">
                                        <div class="table-responsive" style="height: 485px;">
                                            <table class="table table-hover table-responsive">
                                                <tbody>
                                                @if(count($trashed)>0)
                                                @foreach($trashed as $item)
                                                    <tr>
                                                        {{--<td><a onclick="deleteMail('{{route('web.user.delete-mail',$item->id)}}')" href="javascript:void(0);"><i class="fa fa-trash"></i> </a></td>--}}
                                                        <td>{{$item['name']}}</td>
                                                        <td><a onclick="view_message('{{route('web.user.view-mail',$item['id'])}}');" href="javascript:void(0);">{{$item['subject']}}</a></td>
                                                    </tr>
                                                @endforeach
                                                 @else
                                                    <h3 class="text-center text-danger" style="top: 70px;">{{__('The Trash box is empty')}}</h3>
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>


                                    <div class="send_mail col-md-9 m-body  tab-pane fade" id="send_mail">
                                        <div class="to_cc" style="height: 445px;">
                                            <form method="post" id="mailForm">
                                                {{csrf_field()}}
                                                <div class="inp_group">
                                                <select type="" name="id" class="form-control js-example-data-ajax" style="width: 100%;"></select>
                                                </div>
                                                {{--<input type="text" name="" class="form-control" placeholder="CC">--}}
                                                <input type="text" name="subject" class="form-control" placeholder="Subject">
                                                <textarea  name="message" id="editor1" rows="12" class="form-control" placeholder=""></textarea>
                                                <input type="submit" class="btn btn-primary" value="{{__('Send')}}">
                                            </form>
                                        </div>
                                    </div>


                                    <div class="col-md-9 m-body All_messages tab-pane fade" id="message_tab">
                                        <h3 id="view_subject" style="padding-left: 18px;"></h3>
                                        <div class="well " style="background-color: #f8f8f8;">

                                        <b id="view_name"></b><span style="float: right;" id="view_date"></span><br>
                                            <small id="view_email"></small><br><br>
                                        <p id="view_message" class="text-center"></p>
                                        </div>
                                        {{--<div class="col-md-12 col-lg-12 col-xs-12  col-sm-12">--}}
                                        {{--<textarea name="editor1" id="editor1" rows="12" class="form-control" placeholder=""></textarea>--}}
                                        {{--</div>--}}
                                    </div>



                                </div>





                            </div>

                            {{--<div class="row">--}}

                                {{--<div class="col-md-3">--}}

                                {{--</div>--}}
                                {{--<div class="col-md-9 m-body All_messages ">--}}
                                    {{--<p id="view_message"></p>--}}
                                    {{--<div class="col-md-12 col-lg-12 col-xs-12  col-sm-12">--}}
                                    {{--<textarea name="editor1" id="editor1" rows="12" class="form-control" placeholder=""></textarea>--}}
                                    {{--</div>--}}
                                {{--</div>--}}

                            {{--</div>--}}


                        </div>
                    </div>


                <!-- Row End -->
            </div> </div>
            <!-- Main Container End -->
        </section>

    </div>



@endsection

@section('footer')
    <script src="{{asset('assets/system')}}/SimpleAjaxUploader.js"></script>

    <script>

        $('#mailForm').submit(function (e) {
            e.preventDefault();
            $.post('{{route('web.user.send-mail')}}', $('#mailForm').find(":input").serialize(), function (out) {
                if (out.status == false) {
                    $.each(out.data, function (index, value) {
                        notify('error', value);
                    });
                    if(out.msg.length !== 0){
                        notify('error', out.msg);
                    }

                } else {
                    notify('success', 'E-mail has been sent Successfully');
                    $('#mailForm')[0].reset();
                    $('.count_sent').html(out.sent);
                }
            }, 'json')
        });


        function view_message(routeName){

            $.post(
                routeName,
                {
                    '_method':'post',
                    '_token':$('meta[name="csrf-token"]').attr('content')
                },
                function(response){
                        if(response.status == true){
                           $('#view_message_link').click();
                           $('#view_subject').text(response.data.subject);
                           $('#view_email').text(response.data.email);
                           $('#view_name').text(response.data.name);
                           $('#view_message').text(response.data.message);
                           $('#view_date').text(response.data.created_at);
                        }else{
                            notify('error',response.msg);
                        }

                }
            )
        }

        function deleteMail(routeName,$reload){

            // if(!confirm("Do you want to delete this Mail?")){
            //     return false;
            // }

            if($reload == undefined){
                $reload = 3000;
            }

            $.post(
                routeName,
                {
                    '_method':'DELETE',
                    '_token':$('meta[name="csrf-token"]').attr('content')
                },
                function(response){
                    if(response.status == true){
                        notify('success','{{__('Mail has been deleted Successfully')}}');
                        $('.count_received').html(response.data.received);
                        $('.count_sent').html(response.data.sent);
                        $('.count_trashed').html(response.data.trashed);

                        // if($reload){
                        //     setTimeout(function(){location.reload();},$reload);
                        // }
                    }else{
                        {{--notify('success','{{__('Mail has been deleted Successfully')}}');--}}
                        notify('error','{{__('Can\'t delete this mail')}}');
                    }

                }
            )
        }


        $(".js-example-data-ajax").select2({
            ajax: {
                url: '{{route('web.user.searchUser')}}',
                dataType: 'json',
                delay: 250,
                type:'get',
                data: function (params) {
                    return {
                        search: params.term,
                        type: 'public'
                        //page: params.page
                    };
                },
                processResults: function (data, params) {
                    // parse the results into the format expected by Select2
                    // since we are using custom formatting functions we do not need to
                    // alter the remote JSON data, except to indicate that infinite
                    // scrolling can be used
                    params.page = params.page || 1;

                    return {
                        results: data.users,
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                    };
                },
                cache: true
            },
            placeholder: '{{__('Search for User')}}',
            minimumInputLength: 1,
            templateResult: formatRepo,
            templateSelection: formatRepoSelection
        });

        function formatRepo (repo) {
           // console.log(repo);
            if (repo.loading) {
                return repo.text;
            }


            var $container = $(
                "<div class='select2-result-repository row clearfix'>" +
                "<div class='select2-result-repository__avatar col-sm-2'><img style='width: 90%;height: 70px;' src='" + repo.image +"' /></div>" +
                "<div class='select2-result-repository__meta col-sm-10'>" +
                "<div class='select2-result-repository__title' style='font-family: cursive;font-size: 15px;font-weight: 850;'></div>" +
                "<div class='select2-result-repository__description' style='font-size: 12px;font-weight: 450;'></div>" +
                "<div class='select2-result-repository__statistics row' style='font-size: 10px;font-weight: 400;'>" +
                "<div class='select2-result-repository__forks col-md-3'><i class='fa fa-flash'></i> </div>" +
                "<div class='select2-result-repository__stargazers col-md-3'><i class='fa fa-star'></i> </div>" +
                "<div class='select2-result-repository__watchers col-md-4'><i class='fa fa-eye'></i> </div>" +
                "</div>" +
                "</div>" +
                "</div>"
            );

            $container.find(".select2-result-repository__title").text(repo.FullName);
            $container.find(".select2-result-repository__description").text(repo.about);
            $container.find(".select2-result-repository__forks").append(repo.items_count + " {{__('Items')}}");
            $container.find(".select2-result-repository__stargazers").append(repo.rank + " {{__('Stars')}}");
            $container.find(".select2-result-repository__watchers").append(repo.views + " {{__('Watchers')}}");

            return $container;
        }

        function formatRepoSelection (repo) {
            return repo.FullName || repo.text;
        }

    </script>
@endsection
