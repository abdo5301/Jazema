@extends('web.layouts')
@section('header')

@endsection
@section('content')
<style>
    .gm-style .gm-style-iw-d{
        max-height: 315px !important;
        height: auto;
    }
    .gm-style .gm-style-iw-c{
        max-height: 315px !important;
        height: auto;
    }


    #items_map{
        height: 250px;
        width: 100%;
        border: 1px solid #ccc;
        display: none;
    }
    #map-container{
        position: fixed;
        right: 0;
        top: 0;
        left: 0;
        z-index: 1111;
        resize:vertical;
        overflow:hidden;
    }

    #toggle-map-container{
        position: fixed;
        bottom: 0px;
        right: 0px;
        top: 0px;
        margin-top: 310px;
        height: 60px;
        z-index: 1111;
    }

</style>



    <!-- =-=-=-=-=-=-= Home Banner 2 End =-=-=-=-=-=-=  -->
    <!-- =-=-=-=-=-=-= Main Content Area =-=-=-=-=-=-= -->
    <div class="main-content-area clearfix" style="margin-top:180px; "  >
    {{--<div class="main-content-area ">--}}
       <div id="toggle-map-container" >
        <input  id="toggle-map"  type="checkbox"  data-toggle="toggle" data-on="Map On" data-off="Map Off" data-onstyle="info" data-offstyle="danger">
{{-- {{$_GET["type"]}}--}}
       </div>

        <div  id="map-container">
            <div id="items_map"></div>
        </div>
        <!-- =-=-=-=-=-=-= Featured Ads =-=-=-=-=-=-= -->

        <section class="custom-padding">
            <!-- Main Container -->
            <div class="container">
                <!-- Row -->


                <div class="row">
                    <!-- Middle Content Box -->

                    <div class="col-md-12 col-xs-12 col-sm-12" >
                        <div class="rows grid" id="items"></div>
                    </div>

                    <div class="col-md-12 col-xs-12 col-sm-12" id="loading"  >
                        <img src="{{img('loading.gif')}}" alt="{{__('loading')}}...">
                    </div>
                    <div class="col-md-12 col-xs-12 col-sm-12 text-danger" style="display: none;" id="no_items">
                        {{__('No Items Remaining')}}
                    </div>

                    <!-- Middle Content Box End -->
                </div>


                <!-- Row End -->
            </div>
            <!-- Main Container End -->
        </section>

    </div>
    <!-- Main Content Area End -->

    <!-- Back To Top -->
    <a href="#0" class="cd-top">Top</a>



@endsection


@section('footer')



    <script>

        window.onload = initMap;
        var items_map;
        var item_marker = [];
        function initMap_items() {
            var myLatLng = {lat: 26.820553, lng: 30.802498};

            var roadAtlasStyles = [
                {
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#1d2c4d"
                        }
                    ]
                },
                {
                    "elementType": "labels.text.fill",
                    "stylers": [
                        {
                            "color": "#8ec3b9"
                        }
                    ]
                },
                {
                    "elementType": "labels.text.stroke",
                    "stylers": [
                        {
                            "color": "#1a3646"
                        }
                    ]
                },
                {
                    "featureType": "administrative.country",
                    "elementType": "geometry.stroke",
                    "stylers": [
                        {
                            "color": "#4b6878"
                        }
                    ]
                },
                {
                    "featureType": "administrative.land_parcel",
                    "elementType": "labels.text.fill",
                    "stylers": [
                        {
                            "color": "#64779e"
                        }
                    ]
                },
                {
                    "featureType": "administrative.province",
                    "elementType": "geometry.stroke",
                    "stylers": [
                        {
                            "color": "#4b6878"
                        }
                    ]
                },
                {
                    "featureType": "landscape.man_made",
                    "elementType": "geometry.stroke",
                    "stylers": [
                        {
                            "color": "#334e87"
                        }
                    ]
                },
                {
                    "featureType": "landscape.natural",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#023e58"
                        }
                    ]
                },
                {
                    "featureType": "poi",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#283d6a"
                        }
                    ]
                },
                {
                    "featureType": "poi",
                    "elementType": "labels.text.fill",
                    "stylers": [
                        {
                            "color": "#6f9ba5"
                        }
                    ]
                },
                {
                    "featureType": "poi",
                    "elementType": "labels.text.stroke",
                    "stylers": [
                        {
                            "color": "#1d2c4d"
                        }
                    ]
                },
                {
                    "featureType": "poi.park",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "color": "#023e58"
                        }
                    ]
                },
                {
                    "featureType": "poi.park",
                    "elementType": "labels.text.fill",
                    "stylers": [
                        {
                            "color": "#3C7680"
                        }
                    ]
                },
                {
                    "featureType": "road",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#304a7d"
                        }
                    ]
                },
                {
                    "featureType": "road",
                    "elementType": "labels.text.fill",
                    "stylers": [
                        {
                            "color": "#98a5be"
                        }
                    ]
                },
                {
                    "featureType": "road",
                    "elementType": "labels.text.stroke",
                    "stylers": [
                        {
                            "color": "#1d2c4d"
                        }
                    ]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#2c6675"
                        }
                    ]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "geometry.stroke",
                    "stylers": [
                        {
                            "color": "#255763"
                        }
                    ]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "labels.text.fill",
                    "stylers": [
                        {
                            "color": "#b0d5ce"
                        }
                    ]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "labels.text.stroke",
                    "stylers": [
                        {
                            "color": "#023e58"
                        }
                    ]
                },
                {
                    "featureType": "transit",
                    "elementType": "labels.text.fill",
                    "stylers": [
                        {
                            "color": "#98a5be"
                        }
                    ]
                },
                {
                    "featureType": "transit",
                    "elementType": "labels.text.stroke",
                    "stylers": [
                        {
                            "color": "#1d2c4d"
                        }
                    ]
                },
                {
                    "featureType": "transit.line",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "color": "#283d6a"
                        }
                    ]
                },
                {
                    "featureType": "transit.station",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#3a4762"
                        }
                    ]
                },
                {
                    "featureType": "water",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#0e1626"
                        }
                    ]
                },
                {
                    "featureType": "water",
                    "elementType": "labels.text.fill",
                    "stylers": [
                        {
                            "color": "#4e6d70"
                        }
                    ]
                }
            ];

            var mapOptions = {
                zoom: 10,
                center: myLatLng,
                mapTypeControlOptions: {
                    mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'usroadatlas']
                }
            };

            items_map = new google.maps.Map(document.getElementById('items_map'),
                mapOptions);

            var styledMapOptions = {
                name: 'وضع عرض العناصر'
            };

            var usRoadMapType = new google.maps.StyledMapType(
                roadAtlasStyles, styledMapOptions);


            items_map.mapTypes.set('usroadatlas', usRoadMapType);
            items_map.setMapTypeId('usroadatlas');


          @if(!empty($_GET['search_lat']) && !empty($_GET['search_lng']))
            var focus_search_marker = new google.maps.LatLng(parseFloat('{{$_GET['search_lat']}}'),parseFloat('{{$_GET['search_lng']}}'));
            var search_marker = new google.maps.Marker({
                map: items_map,
                draggable: false,
                animation: google.maps.Animation.DROP,
                position: focus_search_marker,
                title:'{{__('Search Location')}}',
                index:'search',
                icon:'https://developers.google.com/maps/documentation/javascript/examples/full/images/library_maps.png'
            });
            items_map.setCenter(focus_search_marker);
            item_marker.push(search_marker);
           // console.log(item_marker);
            @endif

        }


        function placeItemsMarkers(location,itemContent,title,icon,index) {
           // deleteOverlays_items();
            var image = {
                url: icon,
                size: new google.maps.Size(25, 25),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(17, 34),
                scaledSize: new google.maps.Size(25, 25)
                //style:'background-color:#27aae1;'
            };

            var marker = new google.maps.Marker({
                map: items_map,
                draggable: false,
                animation: google.maps.Animation.DROP,
                position: location,
                title: title,
                icon:image,
                index: index
            });
            var infowindow = new google.maps.InfoWindow({
                content: itemContent
            });


            marker.addListener('click', function() {
                infowindow.open(items_map, marker);
                if (marker.getAnimation() !== null) {
                    marker.setAnimation(null);
                    //infowindow.open(items_map, marker);
                } else {
                   // marker.setAnimation(google.maps.Animation.BOUNCE);
                }

            });

            marker.addListener('dblclick', function() {
                items_map.setZoom(20);
                items_map.setCenter(marker.getPosition());
                window.setTimeout(function() {
                    items_map.panTo(marker.getPosition());
                }, 3000);
            });
            // if(location && items_map){
            //     items_map.setCenter(location);
            // }

            item_marker.push(marker);

        }


        function deleteOverlays_items() {
            if (item_marker) {
                for (i in item_marker) {
                    item_marker[i].setMap(null);
                }
                item_marker.length = 0;
            }
        }

        function focus_marker(lat,lng,index){
            var focus_location = new google.maps.LatLng(parseFloat(lat),parseFloat(lng));
            if($('#toggle-map').prop('checked') !== true){
                $('#toggle-map').prop('checked', true).change();
                $('#items_map').slideDown();
                //$('#toggle-map').change();
            }
            items_map.setCenter(focus_location);
            //console.log(index);
            //console.log(item_marker);
            if (item_marker) {
                for (i in item_marker) {
                    if(item_marker[i].index === parseFloat(index)){
                        item_marker[i].setAnimation(google.maps.Animation.BOUNCE);
                    }
                    if(item_marker[i].index !== parseFloat(index)){
                        item_marker[i].setAnimation(null);
                    }
                }
            }
        }



        $(document).ready(function(){
            $('.cd-top').click();
            $('#footer').hide();
            $('#down_footer').hide();

                runHomeGetItems();
                arrange_items();
                loading();

            $("#toggle-map-container .toggle .btn").click(function () {
                $('#items_map').slideToggle();
            });



        });

        var items_offest = $("#items .item").length;
        @if(isset($_GET["type"]))
        var items_type = '{{$_GET["type"]}}';
        @else
        var items_type;
        @endif

        @if(isset($_GET["category"]))
        var items_category = '{{$_GET["category"]}}';
        @else
        var items_category;
        @endif


        var search = {
            'search_word': '',
            'search_type': '',
            'search_category': '',
            'search_sort_by':'',
            'search_sort_role':'',
            'search_lat':'',
            'search_lng':''
            };
        @if(!empty($_GET["search_word"]))
            search['search_word'] = '{{$_GET["search_word"]}}';
        @endif
        @if(!empty($_GET["search_type"]))
            search['search_type'] = '{{$_GET["search_type"]}}';
        @endif
        @if(!empty($_GET["search_category"]))
            search['search_category'] = '{{$_GET["search_category"]}}';
        @endif
        @if(!empty($_GET["search_sort_by"]))
            search['search_sort_by'] = '{{$_GET["search_sort_by"]}}';
        @endif
        @if(!empty($_GET["search_sort_role"]))
            search['search_sort_role'] = '{{$_GET["search_sort_role"]}}';
        @endif
        @if(!empty($_GET["search_lat"]))
            search['search_lat'] = '{{$_GET["search_lat"]}}';
        @endif
        @if(!empty($_GET["search_lng"]))
            search['search_lng'] = '{{$_GET["search_lng"]}}';
        @endif

        function new_items(id,section){
            window.history.pushState("html","title",'{{route('web.index')}}');
            $('.cd-top').click();
            $("#items").empty();
            $("#loading").focus();
            items_offest = $("#items .item").length;
            deleteOverlays_items();
            if(section && id && section === 'type'){
                items_type = id;
            }
            if(section && id && section === 'category'){
                items_category = id;
            }
        }

        function runHomeGetItems(){
            //console.log(urlParams);
            $('#no_items').hide();
            $("#loading").show();
            //new_items();
            $.post("{{route('web.item.get-items')}}",{'offset':items_offest,'type':items_type,'category':items_category,'search':(urlParams) ? urlParams : ''}, _.debounce(function( out ) {
           if(out.status == true){
            for(var i =0;i<= out.data.length;i++){
                var drow = out.data[i];
                $("#items").append(drow).masonry('reloadItems');

            }
               arrange_items();
               items_offest = $("#items .item").length;
               //$("#items").masonry();
            //console.log(out.items);
               var locations = [];
               for(var d =0; d<= out.items.length; d++){
                   var item = out.items[d];
                   if(item){
                   if(item.lat !== null  && item.lng !== null && item.lat.length !==0 && item.lng.length !==0 ){
                       //console.log('lat :'+item.lat);
                       //console.log('lng :'+item.lng);
                       var location = new google.maps.LatLng(parseFloat(item.lat),parseFloat(item.lng));
                       //locations.push(location);
                       var title = item.name;

                           var itemContent =
                          // '<section class="custom-padding">'+
                           '<div class="container" style="width: auto;">'+
                           '<div class="item col-md grid-sizer grid-item" style="width: 235px;" id="item_row_'+item.id+'" >'+
                           '<div class="clearfix">'+
                           '<div class="media">'+
                           '<div class="media-left">'+
                           '<a href="'+item.profile_link+'" target="_blank">'+
                            item.profile_image+
                           '</a>'+
                           '</div>'+
                           '<div class="media-body">'+
                           '<h5 class="media-heading logo-name">'+item.username+'</h5>'+
                           '<p class="date"><small>'+item.date+'</small></p>'+
                           '</div>'+
                           '</div>'+
                           '<h5 class="item-title">'+
                           '<a href="'+item.link+'">'+item.name+'</a>'+
                           '</h5>'+
                           '<div class="category-grid-box">'+
                           '<div class="category-grid-img">'+
                           '<div class="itm-overlay-top"></div>'+
                           '<div class="itm-overlay-bott"></div>'+item.image+
                           '<span class="itm-views"><i class="fa fa-eye"></i>'+item.views+'</span>'+
                           '<span class="itm-from">'+item.type_icon+'</span>'+
                           '<a href="'+item.link+'" target="_blank" class="view-details">'+item.link_tag+'</a>'+
                           '</div>'+
                           '<div class="short-description">'+
                           '<div class="category-title">'+
                           '<span>'+ item.category_link + item.parent_category_link +'</span>'+
                           '</div>'+
                           '<ul class="list-unstyled social-rate">'+
                           '<li>'+
                           '<a href="javascript:;" onclick="like('+item.id+')">'+
                           item.like_icon +
                           item.likes_num +
                           '</a>'+
                           '</li>'+
                           '<li>'+
                           item.comment_icon+
                           item.comments_num+
                           '</li>'+
                           '<li>'+
                           '<a href="#share-now" data-toggle="modal">'+
                           item.share_icon+
                           item.share_num+
                           '</a>'+
                           '</li>'+
                           '<li>'+
                           item.deals_icon+
                           item.deals_num+
                           '</li>'+
                           '</ul>'+
                           '</div>'+
                           '<div class="itm-desc line-clamp">'+
                           '<p class="">'+item.description+'</p>'+
                           '</div>'+
                           // '<div class="input-group post-comment">'+
                           // '<span class="input-group-addon" id="basic-addon1">'+
                           // '<a href="javascript:;"  onclick="commentByBtn('+item.id+')" class="submit-comment"><i class="fa fa-comments"></i></a>'+
                           // '</span>'+
                           // '<input type="text" onkeydown="commentByEnter('+item.id+')" class="form-control"  id="comment_input_'+item.id+'"  aria-describedby="basic-addon1">'+
                           // '</div>'+
                               '</div>'+

                               '</div>'+
                               '</div>'+
                               '</div>';
                          // '</section>';

                       setTimeout(placeItemsMarkers, d*400, location,itemContent,title,item.marker_icon,item.id);


                   }
                   }
               }
               //console.log(locations);
               //drop(locations);

               $("#loading").hide();
           }else{
               $("#loading").hide();
               if(items_offest > 0){
                $('#no_items').html('<b>{{__('No Items Remaining')}}</b>');
               }else{
                $('#no_items').html('<b>{{__('No Items Found')}}</b>');
               }
               $('#no_items').show();
               {{--$("#loading").html("{{__("No More Items")}}");--}}
           }
                $("#items").masonry();

            },500),'json');

        }

        function arrange_items() {
            setTimeout(function () {
            $("#items").masonry();
            },3000);
        }
        function loading() {
            $("#items").masonry();
        }



        // // extension:
        // $.fn.scrollEnd = function(callback, timeout) {
        //     $(this).scroll(function(){
        //         var $this = $(this);
        //         if ($this.data('scrollTimeout')) {
        //             clearTimeout($this.data('scrollTimeout'));
        //         }
        //         $this.data('scrollTimeout', setTimeout(callback,timeout));
        //     });
        // };

        // how to call it (with a 1000ms timeout):
        // $(window).scrollEnd(function(){
        //     runGetItems();
        //     $("#items").masonry();
        // }, 1000);

        function getDocHeight() {
            var D = document;
            return Math.max(
                D.body.scrollHeight, D.documentElement.scrollHeight,
                D.body.offsetHeight, D.documentElement.offsetHeight,
                D.body.clientHeight, D.documentElement.clientHeight
            );
        }


        $(window).scroll(_.debounce(function(){
            if($(window).scrollTop() + $(window).height()  >  getDocHeight()- 100) {
                runHomeGetItems();
                $("#items").masonry();
            }
        },1000));

        $(window).scroll(_.debounce(function(){
            $("#items").masonry();
        },200));

        // $(window).scroll(function(){
        //     // $("#items").masonry();
        // });


    </script>

    @endsection