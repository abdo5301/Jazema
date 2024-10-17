@extends('web.layouts')
@section('header')
@endsection
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

                            <div class="col-md-9 col-sm-12 col-xs-12">
                                <table class="table table-striped ">
                                    <thead>
                                    <tr>
                                        <th scope="col">{{__('Item')}}</th>
                                        <th scope="col">{{__('Image')}}</th>
                                        <th scope="col">{{__('Remove')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($items as $item)
                                        <tr id="wishlist_item_{{$item['id']}}">
                                            <td><a target="_blank" style="color: blue" href="{{route('web.item.details',$item['item']['slug'])}}">{{$item['item']['name']}}</a></td>

                                            <td>
                                                 @if(!empty($item['item']['upload']) && !empty(reset($item['item']['upload'])))
                                                    <img style="width: 70px;height: 70px;border-radius: 15px;" class="img-responsive" src="{{img(reset($item['item']['upload'])['path'])}}" alt="">
                                                @else
                                                    <img  style="width: 70px;height: 70px;border-radius: 15px;" class="img-responsive" src="{{img('items/temp.png')}}">
                                                 @endif
                                            </td>

                                            <td><a style="color: red" onclick="remove_wish('{{route('web.user.delete-wish',$item['id'])}}','{{$item['id']}}')" href="javascript:void(0);"> {{__('Remove')}}</a></td>

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="pull-left">
                                    @if(request()->input('page') !=0)
                                        Showing  <span style="color: grey">{{$items->count() * request()->input('page')}} of {{ $items->total() }}entries</span>
                                    @else
                                        Showing  <span style="color: grey">{{$items->count()}} of {{ $items->total() }}entries</span>
                                    @endif
                                </div>
                                <div class="pull-right">
                                    {{ $items->appends(request()->input())->links() }}
                                </div>
                            </div>

                </div>
            </div>
        </section>

    </div>



@endsection

@section('footer')
    <script src="{{asset('assets/system')}}/SimpleAjaxUploader.js"></script>


    <script>
        $(document).ready(function () {
            $(".page-link").attr("target", "_self");
        });
        function remove_wish(routeName,id){

            if(!confirm("Do you want to remove this item from wish list?")){
                return false;
            }

            $.post(
                routeName,
                {
                    '_method':'DELETE',
                    '_token':$('meta[name="csrf-token"]').attr('content')
                },
                function(response){
                    if(response.status === true){
                        notify('success',"{{__('Item removed')}}");
                        $("#wishlist_item_"+id).remove();
                    }else{
                        notify('error',' {{__('Can\'t delete this item now..try later!')}}');
                    }

                }
            )
        }




    </script>

@endsection
