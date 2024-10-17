@extends('web.layouts')

@section('content')


    <!-- =-=-=-=-=-=-= Home Banner 2 End =-=-=-=-=-=-= -->
    <!-- =-=-=-=-=-=-= Main Content Area =-=-=-=-=-=-= -->
    <div class="main-content-area clearfix" style="margin-top:180px;"  >

        <!-- =-=-=-=-=-=-= Featured Ads =-=-=-=-=-=-= -->
        <section class="custom-padding">
            <!-- Main Container -->
            <div class="container">
                <!-- Row -->
                <div class="row">
                    <!-- Middle Content Box -->
                    <div class="col-md-12 col-xs-12 col-sm-12">
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
        $(document).ready(function(){
            $('#footer').hide();
            $('#down_footer').hide();
            clear_items();
            runCategoryGetItems();

        });

        function clear_items(){
            $("#items").html('');
        }

        function runCategoryGetItems(){
            $('#no_items').hide();
            $("#loading").show();

            $.get("{{url()->full()}}",{'getData':true,'offset':$("#items .item").length}, function( items ) {
                if(items.status == true){
                    //  console.log(items);
                    for(var i =0;i<= items.data.length;i++){
                        var item = items.data[i];
                        //  console.log(item);
                        $("#items").append(item).masonry('reloadItems');;
                    }
                    $("#loading").hide();
                }else{
                    $("#loading").hide();
                    $('#no_items').show();
                   {{-- $("#loading").html("{{__("No Items remaining")}}");--}}
                }
                $("#items").masonry();
            },'json');
        }

        setTimeout(function () {
            $("#items").masonry();
        },3000);

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
                runCategoryGetItems();
                $("#items").masonry();
            }
        },500));


    </script>

@endsection