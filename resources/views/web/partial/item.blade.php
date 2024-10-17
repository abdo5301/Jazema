

<div class="item col-md grid-sizer grid-item" id="item_row_{{$item->id}}">
    <div class="clearfix">
        <div class="media">
            @if(!$profile_item)
            <div class="media-left">
                <a href="{{route('web.user.profile',$item->user->slug)}}" target="_blank">
                    <img class="media-object" src="{{img($item->user->image,'users')}}" alt="{{$item->user->FullName}}">
                </a>
            </div>
            <div class="media-body">
                <h6 class="media-heading logo-name">{{$item->user->FullName}}</h6>
                <p class="date">{{date('Y/m/d',strtotime($item->created_at))}}
                    {{--<span class="time" style="margin-left: 10px;">23:00</span>--}}
                </p>
            </div>
            @endif
        </div>
        <h5 class="item-title">
            <a href="{{route('web.item.details',$item->{'slug_'.\DataLanguage::get()})}}">{{str_limit($item->name,60)}}</a>
@if($profile_item)
            <ul  style="float: right;" title="{{__('Control menu')}}">
                <li class="dropdown" >
                    <a href="#" class="dropdown-toggle open-catg" data-toggle="dropdown" role="button"
                       aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-ellipsis-v"></i>
                    </a>
                    <ul style="min-width: auto;" class="dropdown-menu drop-down-multilevel" id="urgent">
                        <li> <a  title="{{__('Edit')}}"  href="{{route('web.user.edit-item',['id'=>$item->id])}}" ><i class="fa fa-pencil "></i></a></li>
                        <li> <a  title="{{__('Delete')}}"  href="javascript:void(0);" onclick="deleteItem('{{route('web.user.delete-items',$item->id)}}','{{$item->id}}')"><i class="fa fa-trash "></i></a></li>
                    </ul>
                </li>

            </ul>
    @endif
        </h5>
        <!-- Ad Box -->
        <div class="category-grid-box">
            <!-- Ad Img -->
            <div class="category-grid-img">
                <div class="itm-overlay-top"></div>
                <div class="itm-overlay-bott"></div>
                @php
                    if($item->upload->isNotEmpty())
                    $image = $item->upload->first()->path;
                else
                $image = 'items/temp.png';
                @endphp
                <img class="img-responsive" alt="{{$item->name}}" src="{{img($image)}}">

                <span class="itm-views"><i class="fa fa-eye"></i>{{short_num($item->views)}}</span>
                <span class="itm-from"><img width="24px" height="24px" src="{{img($item->item_type->icon)}}" alt="{{$item->name}}"></span>
                @if(!empty($item->price))
                <span class="itm-price">{{number_format($item->price,2)}} {{setting('currency')}}</span>
                @endif
                <a class="view-details" title="{{__('Details')}}" href="{{route('web.item.details',$item->{'slug_'.\DataLanguage::get()})}}" style="width: 100%;bottom: 50px;top: 0;" ></a>
                @if(!$profile_item)
                   @if(!empty($item->lat) && !empty($item->lng) && $item->lat !=0 && $item->lng != 0)
                        <a style="width: 50%;right: 18px;" onclick="focus_marker('{{$item->lat}}','{{$item->lng}}','{{$item->id}}')"  class="view-details" >{{__('Location')}}</a>
                    @endif
                @endif
            </div>
            <!-- Ad Img End -->
            <div class="short-description">
                <!-- Ad Category -->
                <div class="category-title">
                                     <span>

                                       <a target="_blank" href="{{route('web.item.category',$item->item_category->{'slug_'.\DataLanguage::get()} )}}" class="cat-first">{{$item->item_category->name}}</a>
                                         @if($item->item_category->parent_id)
                                       <a target="_blank" href="{{route('web.item.category',$item->item_category->parent->{'slug_'.\DataLanguage::get()} )}}">{{$item->item_category->parent->{'name_'.\App\Libs\DataLanguage::get()} }}</a>
                                             @endif
                                     </span>
                </div>
                <!-- no of social interactions -->
                <ul class="list-unstyled social-rate">
                    <li>
                        <a href="javascript:;" onclick="like('{{$item->id}}')">
                        <i id="like_icon_{{$item->id}}" style=" @if($item->AuthLiked == true) color:#3aa4c1;  @else color:#cccccc; @endif" class="fa fa-thumbs-up"></i>
                        <span class="likes">{{short_num($item->like)}}</span>
                        </a>
                    </li>
                    <li>
                        <i style=" @if($item->AuthCommented) color:#3aa4c1;  @else color:#cccccc; @endif" class="fa fa-comments"></i>
                        <span class="comments">{{short_num($item->comments)}}</span>
                    </li>
                    <li>
                        <a href="#share-now" data-toggle="modal">
                            <i  id="share_icon_{{$item->id}}" class="fa fa-share-alt"></i>
                            <span class="share">{{short_num($item->share)}}</span>
                        </a>
                    </li>
                    <li>
                        <a href="#deal-now" data-toggle="modal">
                        <img  @if($item->AuthDealed)src="images/deel.png" @else src="images/not-deal.png" @endif alt="">
                        <span class="deeels">{{short_num($item->deals)}}</span>
                        </a>
                    </li>
                </ul>
            </div>
        @if(!$profile_item)
            <!-- short description -->
          <div class="itm-desc line-clamp">
                <p class="">
                    {{str_limit($item->description,100)}}
                </p>
            </div>
        @endif
            <!-- Addition Info -->
            {{--<div class="ad-btns">--}}
                {{--<ul>--}}
                    {{--<li>--}}
                        {{--<a href="#"><img src="images/pub.png" alt="">{{__('Publish')}}</a>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                        {{--<a href="#"><img src="images/sav.png" alt="">{{__('Save')}}</a>--}}
                    {{--</li>--}}
                {{--</ul>--}}
            {{--</div>--}}

            @if(!$profile_item)
            <div class="input-group post-comment">
                                  <span class="input-group-addon" id="basic-addon1">
                                    <a href="javascript:;"  onclick="commentByBtn('{{$item->id}}')" class="submit-comment"><i class="fa fa-comments"></i></a>
                                  </span>
                <input type="text"  onkeydown="commentByEnter('{{$item->id}}')" class="form-control"  id="comment_input_{{$item->id}}" placeholder="{{__('Comment')}}" aria-describedby="basic-addon1">
            </div>
            @endif
        </div>

    </div>
</div>

<!-- =-=-=-=-=-=-= share Modal =-=-=-=-=-=-= -->
<div class="modal fade modal-share" tabindex="-1" role="dialog" aria-hidden="true" id="share-now">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <!-- content goes here -->
                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <div class="lefty">
                            <h6><i class="fa fa-share-alt"></i>{{__('Share item to website now')}}</h6>

                            <button onclick="share('{{$item->id}}')" class="btn btn-blue margin-bottom-10" type="button">{{__('share item now')}}</button>

                            <img src="images/share.png" alt="">
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="lefty shares">
                            <h6><i class="fa fa-share-alt"></i>{{__('Share item to social media')}}</h6>
                            <button onclick="window.open('https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('web.item.details',$item->{'slug_'.\DataLanguage::get()})) }}')" class="btn btn-blue margin-bottom-10 fb" type="button">{{__('facebook')}}</button>
                            <button onclick="window.open('https://twitter.com/intent/tweet?text={{$item->{"name_".\DataLanguage::get()} }}&amp;url={{ urlencode(route('web.item.details',$item->{'slug_'.\DataLanguage::get()})) }}')" class="btn btn-blue margin-bottom-10 tw" type="button">{{__('twitter')}}</button>
                            {{--<button onclick="window.open('https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('web.item.details',$item->{'slug_'.\DataLanguage::get()})) }}')" class="btn btn-blue margin-bottom-10 inst" type="button">{{__('instagram')}}</button>--}}
                            <button onclick="window.open('http://pinterest.com/pin/create/bookmarklet/?url={{ urlencode(route('web.item.details',$item->{'slug_'.\DataLanguage::get()})) }}&is_video=false&description={{$item->{"name_".\DataLanguage::get()} }}&media={{img($image)}}')" class="btn btn-blue margin-bottom-10 pint" type="button">{{__('pinterest')}}</button>

                            {{--Request::fullUrl()--}}
                            {{--https://www.facebook.com/sharer/sharer.php?u={{ urlencode(Request::fullUrl()) }}--}}
                            {{--https://twitter.com/intent/tweet?url={{ urlencode(Request::fullUrl()) }}--}}
                           {{--https://plus.google.com/share?url={{ urlencode(Request::fullUrl()) }}--}}



                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- =-=-=-=-=-=-= share Modal =-=-=-=-=-=-= -->