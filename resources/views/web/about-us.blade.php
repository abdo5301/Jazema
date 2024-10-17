@extends('web.layouts')

@section('content')


    <!-- start carousel -->
    {{--<div class="background-rotator def-margin">--}}
        {{--<!-- slider start-->--}}
        {{--<div class="owl-carousel owl-theme background-rotator-slider">--}}
            {{--<!-- Slide -->--}}
            {{--<div class="item linear-overlay"> <img src="{{img($about_us->image)}}"  alt=""></div>--}}
            {{--<!-- Slide -->--}}
            {{--<div class="item linear-overlay"><img  src="images/slider/2.jpg" alt=""></div>--}}

        {{--</div>--}}
    {{--</div>--}}


    <!-- start about banner -->
    <section id="home-content" style="margin-top: 237px;">
        <div class="container">
            <div class="about-sec" style="margin-top: 80px;">
                <div class="row">
                    <div class="col-md-10 col-md-offset-1 col-sm-12 about">
                        <div class="l-shadow"></div>
                        <div class="r-shadow"></div>
                        <div class="col-md-6 col-sm-6 about-content">
                            <img src="icons/logo.png" alt="">
                            <p class="line-clamp">{{strip_tags($about_us->content)}}</p>
                            {{--<a href="#" class="btn btn-primary btn-more">Know More</a>--}}
                        </div>
                        <div class="col-md-6 col-sm-6 hidden-xs">
                            <div class="r-img">
                                <img src="{{img($about_us->image)}}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="services">
                <div class="ser-heading text-center">
                    <span class="before-head"></span>
                    <h2>services</h2>
                </div>
                <div class="ser-body">
                    <div class="row">

                        @foreach($services as $service)
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="ser-item text-center" style="width: 360px;height: 286px;">
                                <img src="{{img($service->image)}}" alt="" style="width: 100px;height: 100px;">
                                <h3>{{$service->name}}</h3>
                                {{--<h6>full width & boxed version</h6>--}}
                                <p class="line2-clamp">{{strip_tags($service->description)}}</p>
                            </div>
                        </div>
                            @endforeach


                    </div>
                </div>
            </div>
        </div>

    </section>
    <section id="high_rank">
        <div class="container">
            <div class="hr-items">
                <div class="text-center">
                    <span class="before-head"></span>
                    <h2>{{__('High Rank Items')}}</h2>
                </div>
                <!-- Middle Content Box -->
                <div class="col-md-12 col-xs-12 col-sm-12">
                    <div class="row">
                        <div class="featured-slider owl-carousel owl-theme">
                            @foreach($topRankItems as $topRankItem)
                            <div class="item">
                                <div class="col-md-12 col-xs-12 col-sm-12 clearfix">
                                    <!-- Ad Box -->
                                    <div class="category-grid-box">
                                        <!-- Ad Img -->
                                        <div class="category-grid-img">
                                            @if($topRankItem->upload->isNotEmpty())
                                            <img class="img-responsive" style="width: 313px;height: 234px;" alt="" src="{{img($topRankItem->upload->first()->path)}}">
                                                @else
                                                <img class="img-responsive" alt="" src="images/posting/car-3.jpg">
                                            @endif
                                        </div>
                                        <!-- Ad Img End -->
                                        <div class="short-description">
                                            <h3><a target="_blank" title="" href="{{route('web.item.details',$topRankItem->slug)}}">{{$topRankItem->name}}</a>
                                                <span class="user-r">Rank <num>#{{$topRankItem->rank}}</num></span>
                                            </h3>
                                        </div>
                                    </div>
                                    <!-- Ad Box End -->
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- Middle Content Box End -->
            </div>
            <div class="clearfix"></div>
            <!-- start high rank users -->

            <div class="hr-users">
                <div class="text-center">
                    <span class="before-head"></span>
                    <h2>{{__('High Rank Users')}}</h2>
                </div>
                <!-- Middle Content Box -->
                <div class="col-md-12 col-xs-12 col-sm-12 marg-top">
                    <div class="row">
                        <div class="high-rank-user owl-carousel owl-theme">
                            @foreach($topRankUsers as $topRankUser)
                            <div class="item">
                                <div class="col-md-12 col-xs-12 col-sm-12 clearfix">
                                    <!-- Ad Box -->
                                    <div class="category-grid-box">
                                        <!-- Ad Img -->
                                        <div class="category-grid-img">
                                            @if(!empty($topRankUser->image))
                                            <img class="img-responsive" alt="" style="width: 313px;height: 234px;" src="{{img($topRankUser->image)}}">
                                                @else
                                                <img class="img-responsive" alt="" src="images/posting/car-3.jpg">
                                            @endif
                                        </div>
                                        <!-- Ad Img End -->
                                        <div class="short-description">
                                            <h3><a target="_blank" title="" href="{{route('web.user.profile',$topRankUser->slug)}}">{{$topRankUser->full_name}}</a>
                                                <span class="user-r">{{__('Rank')}} <num>#{{$topRankUser->rank}}</num></span>
                                            </h3>
                                        </div>
                                    </div>
                                    <!-- Ad Box End -->
                                </div>
                            </div>
                                @endforeach
                        </div>
                    </div>
                </div>
                <!-- Middle Content Box End -->
            </div>
        </div>
    </section>
    <section id="contact-sec">
        <div class="row">
            <div class="col-md-6">
                <div class="cont-map" id="about_map">

                </div>
            </div>
            <div class="col-md-6">
                <div class="cont-details">
                    <h2>contacts</h2>
                    <ol class="list-unstyled">
                        @if(!empty(setting('address'))) <li>
                            <span><i class="fa fa-map-marker"></i></span>
                            {{setting('address')}}
                        </li>@endif
                            @if(!empty(setting('email'))) <li>
                            <span><i class="fa fa-envelope"></i></span>
                            {{setting('email')}}
                        </li>@endif
                            @if(!empty(setting('mobile1'))) <li>
                            <span><i class="fa fa-phone"></i></span>
                            {{setting('mobile1')}}
                        </li>@endif
                            @if(!empty(setting('mobile2'))) <li>
                                <span><i class="fa fa-phone"></i></span>
                                {{setting('mobile2')}}
                            </li>@endif
                    </ol>

                    <form method="post" action="{{route('web.contact-us')}}" id="contact">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12">
                                <div class="form-group">
                                    <input type="text" placeholder="Name" id="name" name="name" class="form-control" required="">
                                </div>
                                <div class="form-group">
                                    <input type="email" placeholder="Email" id="email" name="email" class="form-control" required="">
                                </div>
                                <div class="form-group">
                                    <input type="number" placeholder="Mobile" id="name" name="mobile" class="form-control" required="">
                                </div>
                                <div class="form-group">
                                    <textarea cols="12" rows="7" placeholder="Message..." id="message" name="message" class="form-control" required=""></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <button class="btn btn-theme" type="submit">{{__('Send Message')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>



@endsection
@section('footer')

    <style>
        #about_map{
            height: 553px;
        }
    </style>
    <script>
        var map11;
        var about_marker;
        function initMap_about() {
                    @if(!empty(setting('lat')) && !empty(setting('lng')) && setting('lat') != 0 && setting('lng') != 0)
            var myLatLng = {lat: {{setting('lat')}}, lng: {{setting('lng')}}};
                    @else
            var myLatLng = {lat: 26.820553, lng: 30.802498}; // lat & lng of Cairo Egypt
            @endif
                map11 = new google.maps.Map(document.getElementById('about_map'), {
                center: myLatLng,
                zoom: 8
            });

                about_marker = new google.maps.Marker({
                map: map11,
                title:"Our Location",
                draggable: false,
                animation: google.maps.Animation.DROP,
                position: myLatLng
            });
            about_marker.addListener('click', toggleBounce_about);


        }

        function toggleBounce_about() {
            if (about_marker.getAnimation() !== null) {
                about_marker.setAnimation(null);
            } else {
                about_marker.setAnimation(google.maps.Animation.BOUNCE);
            }
        }


    </script>

    <script>
        $('#contact').submit(function (e) {
            e.preventDefault();
            $.post('{{route('web.contact-us')}}', $('#contact').find(":input").serialize(), function (out) {
                $('.validation_error_msg').remove();
                $('.product_row').css('border-color', '#aaa');
                if (out.status == false) {
                    $.each(out.data, function (index, value) {
                        notify('error',value);
                        // $('[for="' + index + '"]').html($('[for="' + index + '"]').html() + '  <p style="color:red;display: inline-block;" class="validation_error_msg" id="error_msg_' + index + '" >' + value + '</p>');
                    });

                }else {
                    notify('success','Message Sent Successfully');
                    $('#contact')[0].reset();
                    //  setTimeout(location.reload,3000);
                }
            }, 'json')


        });
    </script>
    @endsection

