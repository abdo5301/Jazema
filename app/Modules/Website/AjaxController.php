<?php

namespace App\Modules\Website;




use App\Models\Comment;
use App\Models\Item;
use App\Models\ItemLikes;

use App\Models\Upload;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AjaxController extends WebsiteController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function get(Request $request)
    {

        switch ($request->type) {

            case 'clear-cache':

                \Artisan::call('storage:link');
                \Artisan::call('config:clear');
                \Artisan::call('config:cache');
                \Artisan::call('cache:clear');
                \Artisan::call('route:clear');
                \Artisan::call('view:clear');
                echo 'Cache Cleared';
                break;
            case 'login' :
                Auth::loginUsingId($request->id, true);
                pda(Auth::user());
                break;
            case 'like':
             //  Auth::loginUsingId(2, false);
                if(!Auth::check())
                     return ['status'=>false,'msg'=>__('Login Required')];

                $itemID = $request->item_id;
                if(empty($itemID))
                    return ['status'=>false,'msg'=>__('Item ID required')];

                $data = ['item_id'=>$itemID,'user_id'=>Auth::id()];
                $itemLike = ItemLikes::where($data)->first();

                if(empty($itemLike)){
                    ItemLikes::create($data);
                    $count_likes = ItemLikes::where('item_id',$itemID)->count();
                    Item::find($itemID)->update(['like'=>$count_likes]);
                    return ['status'=>true,'msg'=>__('Item Liked'),'color'=>'#3aa4c1'];
                }else{
                    $itemLike->delete();
                    $count_likes = ItemLikes::where('item_id',$itemID)->count();
                    Item::find($itemID)->update(['like'=>$count_likes]);
                    return ['status'=>true,'msg'=>__('Item Unliked'),'color'=>'#cccccc'];
                }

                break;


            case 'share':
                $itemID = $request->item_id;
                if(empty($itemID))
                    return ['status'=>false,'msg'=>__('Item ID required')];

                    $item = Item::find($itemID)->toArray();

                    unset($item['id']);
                    unset($item['created_at']);
                    unset($item['updated_at']);
                    $item['owner_user_id'] = $item['user_id'];
                    $item['user_id'] = Auth::id();
                     if($newItem = Item::create($item)) {
                         $newItem->update(['slug_ar'=>create_slug($newItem->name_ar,$newItem->id),'slug_en'=>create_slug($newItem->name_en,$newItem->id)]);
                         if(!empty($item['upload'])){
                             foreach ($item['upload'] as $row){
                                 $upload [] = [
                                     'model_id'=>$newItem['id'],
                                     'model_type' => $item['model_type'],
                                     'path' => $item['path'],
                                     'title' => $item['title'],
                                     'is_default' => $item['is_default'],
                                 ];
                             }
                             Upload::create($upload);
                         }
                         $shared_item = Item::find($itemID);
                         $count_share = Item::where('owner_user_id',$shared_item->user_id)->count();
                         $shared_item->update(['share'=>$count_share]);
                         return ['status' => true, 'msg' => __('Item Shared')];
                     }
           else
                return ['status'=>false,'msg'=>__('Item Cannot share now')];

                break;

            case 'comment':

                $itemID = $request->item_id;
                $comment = $request->comment;
                $user = Auth::id();

                if(empty($itemID))
                    return ['status'=>false,'msg'=>__('Item ID required')];
                if(empty($comment))
                    return ['status'=>false,'msg'=>__('comment required')];
                if(empty($user))
                    return ['status'=>false,'msg'=>__('login required')];

                $item = Item::find($itemID)->toArray();

                if(empty($item))
                    return ['status'=>false,'msg'=>__('Item Not Exist')];
                $comment = ['user_id'=>Auth::id(),'item_id'=>$itemID,'comment'=>$comment];
                if(Comment::create($comment)){
                    $count_item_comments = Comment::where('item_id',$itemID)->count();
                    Item::find($itemID)->update(['comments'=>$count_item_comments]);
                return ['status'=>true,'msg'=>__('Comment Added')];
                }else
                    return ['status'=>false,'msg'=>__('Comment Cannot Added now')];
                break;

        }


    }


}