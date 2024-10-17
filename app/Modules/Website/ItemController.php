<?php

namespace App\Modules\Website;

use App\Mail\SendMail;
use App\Models\Attribute;
use App\Models\Chat;
use App\Models\Rank;
use App\Models\SelectedAttributeValues;
use App\Models\AttributeValues;
use App\Models\Comment;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\ItemTypes;
use App\Models\Email;

use App\Models\User;
use App\Modules\Website\WebsiteController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Notification;
use App\Notifications\UserNotification;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Array_;

class ItemController extends WebsiteController
{


    public function index(Request $request)
    {


    }


    public function getItemAttributes(Request $request)
    {
        if (!empty($request->search_category) && !empty($request->search_type)) {
            //dd($request->search_category);
            $s_categroy = ItemCategory::where('slug_'.\DataLanguage::get(), $request->search_category)->first();
            //dd($s_categroy);
            $category_id = $s_categroy->id;
            $s_type = ItemTypes::where('slug_' . \DataLanguage::get(), $request->search_type)->first();
            $type_id = $s_type->id;
        }else{
            return ['status' => false, 'msg' => __('please select category')];
        }

        $attribute = Attribute::select(['*', 'name_' . \DataLanguage::get() . ' as name'])->orderBy('sort')
            ->where(['model_id' => $category_id, 'model_type' => 'App\Models\ItemCategory', 'item_type_id' => $type_id])
            ->with(['values' => function ($q) {
                $q->select(['*', 'name_' . \DataLanguage::get() . ' as name'])->get();
            }])->get();
//        dd($attribute->toArray());
        if (empty($attribute->toArray()))
            return ['status' => false];

        return ['status' => true, 'data' => $attribute];
    }

    public function fastSearchItem(Request $request){
        //dd($request);
        if(!empty($request->search)){
        $items = Item::Actives()
            ->select(['*','name_'.\DataLanguage::get().' as name','description_'.\DataLanguage::get().' as description'])
                ->where('name_'.\DataLanguage::get() , 'like', '%' . $request->search . '%')
                ->orWhere('description_'.\DataLanguage::get() , 'like', '%' . $request->search . '%')
            ->with(['item_category'=>function($q){
                $q->select(['*','name_'.\DataLanguage::get().' as name','slug_'.\DataLanguage::get().' as slug']);
            },'item_type'=>function($q){
                $q->select(['*','name_'.\DataLanguage::get().' as name','slug_'.\DataLanguage::get().' as slug']);
            }]) ->limit(10)->offset(0)->orderBy('id','desc')->get();
        }else{
            $items = '';
        }


        foreach ($items as $item){
            if(!empty($item->upload) && !empty($item->upload->first())){
                $item->image = img($item->upload->first()->path);
            }else{
                $item->image = img('items/temp.png');
            }
            $item->item_link = route('web.item.details',$item->{'slug_'.\DataLanguage::get()});

            if($item->rank == null)
                $item->rank = 0;
            $item->description = str_limit($item->{'description_'.\DataLanguage::get()},120);
        }

        if(!empty($items)){
            $count = count($items);
        }else{
            $count = 0;
        }

        //var_dump($items);die;

        $data = ['items' => $items,'total_count'=>$count];
        return json_encode($data);
    }

    public function getItems(Request $request)
    {

        $offset = $request->offset;
        $category = $request->category;
        $type = $request->type;
        $filter = $request->search;
        if(!empty($type)){
            $items = ActiveItems(setting('item_per_request'), $offset,['item_type_id'=>$type]);
        }elseif(!empty($category)){
            $items = ActiveItems(setting('item_per_request'), $offset,['item_category_id'=>$category]);
        }elseif(!empty($filter)){
            $items = ActiveItems(setting('item_per_request'), $offset,'','',$filter);
        }else{
            $items = ActiveItems(setting('item_per_request'), $offset);
        }
         // DB::enableQueryLog();
         // dd(DB::getQueryLog());
        //$items->toSql();

        if ($items->isNotEmpty()) {
            $drowedItems = [];

            foreach ($items as $item) {
                $drowedItems[] = DrowItem($item);
 
            $item->marker_icon = img($item->item_type->icon);
            $item->username = $item->user->FullName;

            $item->description = str_limit($item->description,180);
            if(!empty($item->upload) && !empty($item->upload->first())){
                $item->image = '<img class="img-responsive" src="'.img($item->upload->first()->path).'">';
            }else{
                $item->image = '<img class="img-responsive" src="'.img('items/temp.png').'">';
            }
            $item->profile_link = route('web.user.profile',$item->user->slug);
            $item->profile_image = '<img style="border-radius: 70%;width: 47px;height: 47px;" class="media-object" src="'.img($item->user->image,'users').'">';

            $item->date = date('Y/m/d',strtotime($item->created_at));
            $item->link = route('web.item.details',$item->{'slug_'.\DataLanguage::get()});
            $item->name = str_limit($item->name,60);
            $item->views = short_num($item->views);
            $item->type_icon = '<img width="24px"  height="24px" src="'.img($item->item_type->icon).'">';
            if(!empty($item->price)){
                $item->price = number_format($item->price,2).' '.setting('currency');
            }else{
                $item->price = '';
            }
            $item->link_tag = __('View Details');
            $item->category_link = '<a target="_blank" class="cat-first" href="'.route("web.item.category",$item->item_category->{"slug_".\DataLanguage::get()} ).'">'.$item->item_category->name.'</a>';
            if($item->item_category->parent_id)
            $item->parent_category_link = '<a target="_blank" href="'.route("web.item.category",$item->item_category->parent->{"slug_".\DataLanguage::get()} ).'">'.$item->item_category->parent->{"name_".\App\Libs\DataLanguage::get()}.'</a>';
            else
            $item->parent_category_link = '';
                if($item->AuthLiked == true){ $likedColor = '#3aa4c1';}else{ $likedColor = '#cccccc';}
            $item->like_icon = '<i id="like_icon_'.$item->id.'" style="color:'.$likedColor.'" class="fa fa-thumbs-up"></i>';
            $item->likes_num = '<span class="likes">'.short_num($item->like).'</span>';

            if($item->AuthCommented == true){ $commentedColor = '#3aa4c1';}else{ $commentedColor = '#cccccc';}
            $item->comment_icon = '<i  style="color:'.$commentedColor.'" class="fa fa-comments"></i>';
            $item->comments_num = '<span class="comments">'.short_num($item->comments).'</span>';

            $item->share_icon = '<i id="share_icon_'.$item->id.'" class="fa fa-share-alt"></i>';
            $item->share_num = '<span class="share">'.short_num($item->share).'</span>';

            if($item->AuthDealed == true){ $dealsImage = "images/deel.png";}else{ $dealsImage = "images/not-deal.png";}
            $item->deals_icon = '<img  src="'.$dealsImage.'" class="fa fa-comments">';
            $item->deals_num = '<span class="deeels">'.short_num($item->deals).'</span>';



            }

            return ['status'=>true,'data'=> $drowedItems,'items'=>$items];
        } else {
            return json(false);
        }
    }


    public function type($slug, Request $request)
    {

        $type = ItemTypes::where('slug_' . \DataLanguage::get(), $slug)->first();
        if (empty($type))
            abort(404);

        if ($request->getData) {

            $offset = $request->offset;
            $items = ActiveItems(setting('item_per_request'),  $offset,['item_type_id' => $type->id]);

            if ($items->isNotEmpty()) {
                $drowedItems = [];

                foreach ($items as $item) {

                    $drowedItems[] = DrowItem($item);

                }
                return json(true, $drowedItems);
            } else {
                return json(false);
            }
        }

        return view('web/category');


    }


    public function category($slug, Request $request)
    {

        $category = ItemCategory::where('slug_' . \DataLanguage::get(), $slug)->first();
        if (empty($category))
            abort(404);

        if ($request->getData) {

            $offset = $request->offset;
            $items = ActiveItems(setting('item_per_request'),$offset, ['item_category_id' => $category->id]);

            if ($items->isNotEmpty()) {
                $drowedItems = [];

                foreach ($items as $item) {

                    $drowedItems[] = DrowItem($item);

                }
                return json(true, $drowedItems);
            } else {
                return json(false);
            }
        }

        return view('web/category');


    }


    public function Details($slug, Request $request)
    {
        //$item = Item::where('slug_' . \DataLanguage::get(), $slug)->first();
        $Item = Item::where('slug_' . \DataLanguage::get(), $slug)
            ->select('*')
            ->with([ 'select_attribute.values' => function ($q) {
                $q->orderBy('sort', 'asc')->get();
            }, 'select_attribute.Attribute.values' => function ($q) {
                $q->orderBy('sort', 'asc')->get();
            }, 'option' => function ($q) {
                $q->where('status', 'active')->orderBy('sort', 'asc')->get();
            }, 'option.values' => function ($q) {
                $q->where('status', 'active')->get();
            }, 'upload','stage','item_category','item_type'])->first();

        if (!$Item)
            abort(404);

        $Item->update(['views'=>($Item->views + 1)]);

        $attributes = Attribute::Select(['id', 'name_ar', 'name_en', 'type', 'is_required', 'sort'])
            ->where(['model_type' => 'App\Models\ItemCategory', 'model_id' => $Item->item_category_id,
                'item_type_id' => $Item->item_type_id])
            ->with(['values' => function ($sql) {
                $sql->select(['id', 'attribute_id', 'name_ar', 'name_en', 'sort']);
            }])->get();
        $selected_attributes_handled = [];
        foreach ($attributes as $row){
            $values = [];
            $value_id=0;
            $value = '';
            foreach ($Item->select_attribute()->with('Attribute.values')->groupBy('attribute_id')->get() as $attribute){
                if($row->id == $attribute->attribute_id){
                    if (!empty($attribute->attribute_value_id) && empty($attribute->value)) {
                        if($row->type == 'multi_select'){
                            $selected_values = SelectedAttributeValues::where(['attribute_id'=>$attribute->attribute_id,
                                'model_id'=>$Item->id,'model_type'=>'App\Models\Item'])
                                ->with('values')->get(['attribute_value_id']);
                            $value_id = array_column($selected_values->toArray(),'attribute_value_id');
                            $value = implode(',',array_column(AttributeValues::whereIn('id',$value_id)->get(['name_ar'])->toArray(),'name_ar'));
                            $value_id = implode(',',$value_id);
                        }else {
                            $value_id = (!empty($attribute->values->id))? $attribute->values->id : '';
                            $value = (!empty($attribute->values->name_ar))? $attribute->values->name_ar : '';
                        }
                    }else {
                        $value = $attribute['value'];
                    }
                }
            }
            if($row->values->isNotEmpty()) {
                $values = $row->values;
            }

            $selected_attributes_handled[]=[
                'id' => $row->id,
                'type' => $row->type,
                'is_required' => $row->is_required,
                'name' => $row->name_ar,
                'selected_value_name' => (!empty($value))? $value : '',
                'selected_value' => (!empty($value_id))? (string)$value_id : '',
                'values' => $values,
            ];
        }

        $Item->selected_attributes_handled = $selected_attributes_handled;

        $this->viewData['item'] = $Item;
        $this->viewData ['related_items']= Item::where('user_id',$Item->user_id)->where('item_category_id',$Item->item_category_id)->limit(8)->get();
        $user = User::find($Item->user->id);
        //dd($user);
        $this->viewData ['categories'] = ItemCategory::whereIn('id', explode(',', $user->interisted_categories))->get();
        $this->viewData['comments'] = Comment::where('item_id',$Item->id)->with('user')->orderBy('id', 'desc')->get();
        $this->viewData['messages'] = Chat::with(['fromUser','toUser'])->orderBy('id', 'desc')->get();
        // dd($this->viewData );
        return view('web.details', $this->viewData);

    }

    public function sendMail(Request $request)
    {
        if (auth()->check()) {
            $validationArray = [
                'subject' => 'required',
                'message' => 'required',
            ];
        } else {
            $validationArray = [
                'subject' => 'required',
                'message' => 'required',
                'email' => 'required|email',
                'mobile' => 'required|numeric',
                'name' => 'required',
            ];
        }
        $validator = Validator::make($request->all(), $validationArray);
        if ($validator->fails()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }
        $data = [];

        if (auth()->check()) {
            $data['email'] = auth()->user()->email;
            $data['name'] = auth()->user()->full_name;
            $data['from_id'] = auth()->id();
        } else {
            $data['email'] = $request->email;
            $data['name'] = $request->name;
        }
        $data ['subject'] = $request->subject;
        $data ['message'] = $request->message;
        //dd($request);
        if(isset($request->item_id)){
            $item = Item::find($request->item_id);
            $to_user = $item->user;
            //dd($to_user);
        }else if(isset($request->to_user_id)){
            $to_user = User::find($request->to_user_id);
           // dd($to_user);
        }else{
            return ['status' => false ,'msg'=>'valid user'];
        }

        $data['to_id'] = $to_user->id;
        //dd($data);
        //Mail::to($to_user)->send(new SendMail($data));

        if (Email::create($data)) {
            return ['status' => true];
        }
        return ['status' => false];
    }


    public function rankItem(Request $request){

        $data['user_id'] = auth()->user()->id;
        $data['model_type'] = 'App\Models\Item';
        $data['model_id'] = $request->item_id;
        $data['rank'] = $request->rank;

        if (Rank::create($data)) {
            $new_rank = rank_calculation($data['model_id'],$data['model_type']);
            $item = Item::find($data['model_id']);

            if($item->update(['rank'=>$new_rank]))
            return ['status' => true,'new_rank'=>$new_rank];
        }

        return ['status' => false];
    }

}