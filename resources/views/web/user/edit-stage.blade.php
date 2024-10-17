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

                    <div class="col-md-9 col-xs-12 col-sm-12" id="just_three">

                        <div class="main_content">

                            <form method="post" id="stageForm">
                                {{csrf_field()}}
                                <div id="option1" class="group task_content">

                                    <div class="inp_group">
                                        <label for="name">{{__('Stage Name')}}</label>
                                        <input type="text" class="form-control" placeholder="Name" name="name" required value="{{$stage->name}}">
                                    </div>

                                    <div class="inp_group">
                                        <label for="status" class="">{{__('Show To Friends')}}</label>
                                        <select  class="form-control" required name="show_to_friends">
                                            {{--<option disabled selected>{{__('Choose Yes/No')}}</option>--}}
                                            <option @if($stage->show_to_friends =='yes')  selected @endif value="yes">{{__('Yes')}}</option>
                                            <option @if($stage->show_to_friends =='no')  selected @endif value="no">{{__('No')}}</option>
                                        </select>
                                    </div>
                                    <div class="inp_group">
                                        <label for="status" class="">{{__('Show To Followers')}}</label>
                                        <select  class="form-control" required name="show_to_followers">
                                            {{--<option disabled selected>{{__('Choose Yes/No')}}</option>--}}
                                            <option  @if($stage->show_to_followers =='yes')  selected @endif value="yes">{{__('Yes')}}</option>
                                            <option @if($stage->show_to_followers	 =='no')  selected @endif value="no">{{__('No')}}</option>
                                        </select>
                                    </div>
                                    <div class="inp_group">
                                        <label for="status" class="">{{__('Show To Public')}}</label>
                                        <select  class="form-control" required name="show_to_public">
                                            {{--<option disabled selected>{{__('Choose Yes/No')}}</option>--}}
                                            <option @if($stage->show_to_public =='yes')  selected @endif value="yes">{{__('Yes')}}</option>
                                            <option @if($stage->show_to_public =='no')  selected @endif value="no">{{__('No')}}</option>
                                        </select>
                                    </div>
                                    <button class="btn btn-success btn-lg pull-right"
                                            type="submit">{{__('Submit')}}</button>

                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
    </div>

    </section>
    </div>
@endsection
@section('footer')
    <script>
        $('#stageForm').submit(function (e) {
            e.preventDefault();
            $.post('{{route('web.user.stage-update',$stage->id)}}', $('#stageForm').find(":input").serialize(), function (out) {
                $('.validation_error_msg').remove();
                $('.product_row').css('border-color', '#aaa');


                if (out.status == false) {
                    console.log(out);
                    $.each(out.data, function (index, value) {
                        notify('error', value);
                    });

                } else {
                    // setTimeout(location.reload,3000);
                    notify('success', 'Stage Updated Successfully');

                }
            }, 'json')
        });

    </script>
@endsection
