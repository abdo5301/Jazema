<?php

namespace App\Modules\Api\User;

use App\Models\Attribute;
use App\Models\TemplateOption;
use App\Models\ItemLikes;
use App\Models\Stage;
use App\Models\SelectedAttributeValues;
use App\Models\AttributeValues;
use App\Modules\Api\Transformers\User\ItemTransformer;
use App\Libs\Create;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\ItemTypes;
use App\Models\Upload;
use Auth;
use App;
use function foo\func;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Comment;
use MongoDB\Driver\ReadConcern;

class ItemApiController extends UserApiController
{

    public function all_items_auth(Request $request)
    {

     return $this->all_items($request);

    }


    public function categoryTable(){
        $categories = ItemCategory::select(['id','name_ar','name_en','parent_id','icon'])->get();
        return  ['status' => true,
            'msg' => 'Categories',
            'code' => 200,
            'data'=>$categories];
    }

    public function categoryTree(){
          return  ['status' => true,
            'msg' => 'Categories',
            'code' => 200,
            'data'=>getCategoryTreeArray()];
    }

    public function all_items(Request $request)
    {

        $Items = Item::Actives()
            ->select(['*', 'name_' . \DataLanguage::get() . ' as name', 'description_' . \DataLanguage::get() . ' as description', 'created_at'])
            ->with(['item_category' => function ($q) {
                $q->select(['*', 'name_' . \DataLanguage::get() . ' as name', 'slug_' . \DataLanguage::get() . ' as slug']);
            }, 'item_type' => function ($q) {
                $q->select(['*', 'name_' . \DataLanguage::get() . ' as name', 'slug_' . \DataLanguage::get() . ' as slug']);
            }, 'upload' , 'user', 'owner_user']);

        $Items->orderBy('id', 'desc');

        if ($request->type_id) {
            $Items->where('item_type_id', $request->type_id);
        }
        if ($request->category_id) {
            $Items->where('item_category_id', $request->category_id);
        }
        $rows = $Items->jsonPaginate();

        if (!$rows->items())
            return $this->respondNotFound(false, __('There Are no items to display'));

        $itemTransformer = new ItemTransformer();

        return $this->respondSuccess($itemTransformer->transformCollection($rows->toArray(), [\DataLanguage::get()]), __('Items Data'));


    }


    public function subCategories(Request $request){
        $ItemCategory = ItemCategory::select('id', 'name_' . \DataLanguage::get() . ' as name','icon')->where('status', 'active');
        if((int)$request->category_id !=0){
            $ItemCategory->where('parent_id',(int)$request->category_id);
        }else{
            $ItemCategory->where('parent_id',0);
        }

        if($ItemCategory->get()->isEmpty()){
            return $this->respondWithError([], __('Data'));
        }

        return $this->respondWithoutError($ItemCategory->get(), __('Data'));

    }

    function like(Request $request)
    {
        $RequestData = $request->only(['item_id']);
        $validator = Validator::make($RequestData, [
            'item_id' => 'required|exists:items,id',
        ]);

        if ($validator->errors()->any()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }


        $itemID = $request->item_id;
        $data = ['item_id' => $itemID, 'user_id' => Auth::id()];
        $itemLike = ItemLikes::where($data)->first();

        if (empty($itemLike)) {
            ItemLikes::create($data);
            Item::where('id',$itemID)->increment('like');
            return $this->respondWithoutError([], 'Item Liked');

        } else {
            $itemLike->delete();
            Item::where('id',$itemID)->decrement('like');
            return $this->respondWithoutError([], 'Item Unliked');
        }

    }


    function share(Request $request)
    {
        $RequestData = $request->only(['item_id']);
        $validator = Validator::make($RequestData, [
            'item_id' => 'required|exists:items,id',
        ]);

        if ($validator->errors()->any()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }
        $itemID = $request->item_id;

        $item = Item::with('upload')->find($itemID)->toArray();

        unset($item['id']);
        unset($item['created_at']);
        unset($item['updated_at']);
        $item['owner_user_id'] = $item['user_id'];
        $item['user_id'] = Auth::id();
        $item['share'] = 0;
        $item['comments'] = 0;
        $item['deals'] = 0;
        $item['rank'] = 0;
        $item['like'] = 0;
        $item['views'] = 0;
        if ($newItem = Item::create($item)) {
            $newItem->update(['slug_ar' => create_slug($newItem->name_ar, $newItem->id), 'slug_en' => create_slug($newItem->name_en, $newItem->id)]);
            if (!empty($item['upload'])) {
                foreach ($item['upload'] as $row) {
                    $upload [] = [
                        'model_id' => $newItem['id'],
                        'model_type' => $row['model_type'],
                        'path' => $row['path'],
                        'title' => $row['title'],
                        'is_default' => $row['is_default'],
                    ];
                }

                Upload::insert($upload);
            }
            Item::where('id',$itemID)->increment('share');
            return $this->respondWithoutError([], 'Item Shared');

        } else
            return $this->respondWithError(false, __('Item Cannot share now'));


    }


    function comment(Request $request)
    {

        $RequestData = $request->only(['item_id', 'comment']);
        $validator = Validator::make($RequestData, [
            'item_id' => 'required|exists:items,id',
            'comment' => 'required',
        ]);

        if ($validator->errors()->any()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }

        $itemID = $request->item_id;
        $comment = $request->comment;


        $item = Item::find($itemID);

        if (empty($item))
            return ['status' => false, 'msg' => __('Item Not Exist')];
        $comment = ['user_id' => Auth::id(), 'item_id' => $itemID, 'comment' => $comment];
        if (Comment::create($comment)) {
            $item->increment('comments');
            return $this->respondWithoutError([], __('Comment Added'));
        } else {
            return $this->respondWithError(false, __('Comment Cannot Added now'));
        }
    }


    function my_items(Request $request)
    {


        $Items = Item::Actives()->where('user_id', Auth::id())
            ->select(['*', 'name_' . \DataLanguage::get() . ' as name', 'description_' . \DataLanguage::get() . ' as description', 'created_at'])
            ->with(['item_category' => function ($q) {
                $q->select(['*', 'name_' . \DataLanguage::get() . ' as name', 'slug_' . \DataLanguage::get() . ' as slug']);
            }, 'item_type' => function ($q) {
                $q->select(['*', 'name_' . \DataLanguage::get() . ' as name', 'slug_' . \DataLanguage::get() . ' as slug']);
            }, 'upload', 'user', 'owner_user']);

        if(empty($request->stage_id)){
            $public_stage_id = Auth::user()->stages()->where('show_to_public','yes')->first()->id;
            $Items->where('stage_id',$public_stage_id);
        }else{
            $user_stages = Auth::user()->stages()->where('id',$request->stage_id)->first();
            if(!empty($user_stages)) {
                $public_stage_id = $request->stage_id;
                $Items->where('stage_id',$public_stage_id);
            }else {
                return $this->respondNotFound(false, __('There Are no items to display'));
            }
        }



        if ($request->id)
            $Items->where('id', $request->id);

        $Items->orderBy('id', 'desc');
        $rows = $Items->jsonPaginate();

        if (!$rows->items())
            return $this->respondNotFound(false, __('There Are no items to display'));

        $itemTransformer = new ItemTransformer();

        return $this->respondSuccess($itemTransformer->transformCollection($rows->toArray(), [\DataLanguage::get()]), __('Items Data'));

    }

    function item_data()
    {

        $data['ItemCategory'] = ItemCategory::select('id', 'name_' . \DataLanguage::get() . ' as name','icon')->where('status', 'active')->get()->toArray();
        $data['itemType'] = ItemTypes::select('id', 'name_' . \DataLanguage::get() . ' as name','icon')->where('status', 'active')->get()->toArray();
        $data['sortBy'] = ['1'=>'Location','2'=>'Time','3'=>'Cost','4'=>'Most Like,Share,Comment','5'=>'Most Deal','6'=>'Trending'];
        return $this->respondWithoutError($data, __('Data'));

    }


    function create_item_data(Request $request)
    {

        $data['ItemCategory'] = ItemCategory::select('id', 'name_' . \DataLanguage::get() . ' as name')->where('status', 'active')->get()->toArray();
        $data['itemType'] = ItemTypes::select('id', 'name_' . \DataLanguage::get() . ' as name')->where('status', 'active')->get()->toArray();

        return $this->respondWithoutError($data, __('Data'));

    }



    function item_attributes(Request $request)
    {

        $RequestData = $request->only(['item_category_id', 'item_type_id']);
        $validator = Validator::make($RequestData, [
            'item_category_id' => 'required|exists:item_categories,id',
            'item_type_id' => 'required|exists:item_types,id',
        ]);

        if ($validator->errors()->any()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }


        $attr = Attribute::Select(['id', 'name_ar', 'name_en', 'type', 'is_required', 'sort'])
            ->where(['model_type' => 'App\Models\ItemCategory', 'model_id' => $RequestData['item_category_id'], 'item_type_id' => $RequestData['item_type_id']])
            ->with(['values' => function ($sql) {
                $sql->select(['id', 'attribute_id', 'name_ar', 'name_en', 'sort']);
            }])->get();

        $templateOptions = TemplateOption::where('item_category_id', $RequestData['item_category_id'])->orderBy('sort', 'asc')
            ->where('status', 'active')->get();
        if (empty($templateOptions))
            $data['templateOptions'] = [];
        else {

            $itemTransformer = new ItemTransformer();
            $data['templateOptions'] = $itemTransformer->transformCollection($templateOptions->toArray(), [\DataLanguage::get()], 'itemOption');
        }
        if (!$attr) {
            return $data['attribute'] = [];
        } else {
            $data['attribute'] = $attr->toArray();
        }

        $data['sort_by'] = ['created_at'=>__('Date'),'views'=>__('Views'),'comments'=>__('Comments'),'like'=>__('Likes'),'deals'=>__('Deals')];

        return $this->respondSuccess($data, __('Items Data'));


    }

    function editItem(Request $request){

        $RequestData = $request->only(['id']);
        $validator = Validator::make($RequestData, [
            'id' => 'required|exists:items,id'
        ]);

        if ($validator->errors()->any()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }


        $Item = Auth::user()->items()
        ->select('*')
            ->with([ 'select_attribute.values' => function ($q) {
                $q->orderBy('sort', 'asc')->get();
            }, 'select_attribute.Attribute.values' => function ($q) {
                $q->orderBy('sort', 'asc')->get();
            }, 'option' => function ($q) {
                $q->where('status', 'active')->orderBy('sort', 'asc')->get();
            }, 'option.values' => function ($q) {
                $q->where('status', 'active')->get();
            }, 'upload','stage','item_category','item_type'])->find($request->id);

        if(empty($Item)){
            return $this->respondNotFound([], __('There Is no Item'));
        }
        $itemTransformer = new ItemTransformer();

        $attributes = Attribute::Select(['id', 'name_ar', 'name_en', 'type', 'is_required', 'sort'])
            ->where(['model_type' => 'App\Models\ItemCategory', 'model_id' => $Item->item_category_id,
                'item_type_id' => $Item->item_type_id])
            ->with(['values' => function ($sql) {
                $sql->select(['id', 'attribute_id', 'name_ar', 'name_en', 'sort']);
            }])->get();

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
                            $value_id = $attribute->values->id;
                            $value = $attribute->values->name_ar;
                        }
                    }else {
                        $value = $attribute['value'];
                    }
                }
            }
            if($row->values->isNotEmpty()) {
                $values = $itemTransformer->transformCollection($row->values->toArray(), [\DataLanguage::get()], 'editItemAttributeValues');
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

        $editItem = $itemTransformer->editItem($Item->toArray(), [\DataLanguage::get()]);
         return $this->respondSuccess($editItem, __('Item Data'));
    }

    function editItemAction(Request $request){

        $data = $request->edit_item;
        $item_data = [];
        if(empty($data))
            return ['status'=>false,'msg'=>__('Validation ERROR')];

        $item_data =  [
            'id'=>isset($data['id'])?$data['id']:'',
            'name_ar'=>isset($data['name'])?$data['name']:'',
            'name_en'=>isset($data['name'])?$data['name']:'',
            'description_ar'=>isset($data['desc'])?$data['desc']:'',
            'description_en'=>isset($data['desc'])?$data['desc']:'',
            'quantity'=>isset($data['quantity'])?$data['quantity']:'',
            'price'=>isset($data['price'])?$data['price']:'',
            'stage_id'=>isset($data['stage_id'])?$data['stage_id']:'',
            'lat'=>(isset($data['lat']))?$data['lat']:'',
            'lng'=>(isset($data['lng']))?$data['lng']:'',
        ];

        $validationArray = [
            'id' => 'required',
            'name_ar' => 'required',
            'name_en' => 'required',
            'description_ar' => 'required',
            'stage_id' => 'required',
            'description_en' => 'required',
            'price' => 'nullable|numeric',
            'quantity' => 'nullable|numeric',
            'lat' => 'nullable',
            'lng' => 'nullable',
        ];

        if(!empty($data['image'])){
            if(!isset($data['temp_id'])) {
                $temp_id = rand(0, 999999);
                foreach ($data['image'] as $img) {

                    $file_name = 'image_' .uniqid(). time() . '.png'; //generating unique file name;
                    file_put_contents('storage/items/' . date('y') . '/' . date('m') . '/' . $file_name, base64_decode($img));
                    $image_name = 'items/' . date('y') . '/' . date('m') . '/' . $file_name;

                    Upload::where('temp_id', $data['temp_id'])->Create([
                        'temp_id' => $temp_id,
                        'path' => $image_name
                    ]);

                }
                $item_data['temp_id'] = $temp_id;
            }else{
                $item_data['temp_id'] = $data['temp_id'];
            }
        }

        if(!empty($data['attributes'])){
            $attribute_data = [];
            foreach ($data['attributes'] as $attribute){
                if($attribute['type'] == 'multi_select'){
                    $attribute_data[$attribute['id']] = explode(',',$attribute['values'][0]);
                }else{
                    $attribute_data[$attribute['id']] = $attribute['values'][0];
                }
            }
            $item_data['attribute'] = $attribute_data;
        }

        if(!empty($data['options'])){
            $option_data = [];
            foreach ($data['options'] as $key => $option){
                $new_option['template_option'] = 'new';
                $new_option['option_sort'] = $key;
                $new_option['option_name_ar'] = $option['name'];
                $new_option['option_name_en'] = $option['name'];
                $new_option['option_is_required'] = (isset($option['is_required']))?$option['is_required']:'no';
                $new_option['option_type'] = $option['type'];
                if($option['type'] == 'select' || $option['type'] == 'multi_select'){
                    foreach ($option['values'] as $key  => $value){
                        $new_option['option_value_name_ar'][$key]['option_value_name_ar'] = $value;
                        $new_option['option_value_name_en'][$key]['option_value_name_en'] = $value;
                        $new_option['option_value_price_prefix'][$key]['option_value_price_prefix'] = '+';
                        $new_option['option_value_price'][$key]['option_value_price'] = 0;
                    }

                }
                $option_data[] = $new_option;
            }
            $item_data['option'] = $option_data;
        }

        $validator = Validator::make($item_data, $validationArray);

        if ($validator->errors()->any()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }

        $create = new Create();

        return $create->editItem($item_data, \DataLanguage::get());
    }


    function create_item(Request $request)
    {

        $data = $request->create_item;



        if(empty($data))
            return ['status'=>false,'msg'=>__('Validation ERROR')];
        
        $validationArray = [
            'category' => 'required|exists:item_categories,id',
            'type' => 'required|exists:item_types,id',
            'name' => 'required',
            'desc' => 'required',
            'stage_id' => 'required',
            'price' => 'nullable|numeric',
            'qty' => 'nullable|numeric',
            'lat' => 'nullable',
            'lng' => 'nullable',
        ];

        $validator = Validator::make($data, $validationArray);

        if ($validator->errors()->any()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }

        $item_data = [
            'user_id'=>Auth::id(),
            'item_category_id'=>$data['category'],
            'item_type_id'=>$data['type'],
            'name_ar'=>$data['name'],
            'name_en'=>$data['name'],
            'description_ar'=>$data['desc'],
            'description_en'=>$data['desc'],
            'quantity'=>$data['qty'],
            'price'=>$data['price'],
            'stage_id'=>$data['stage_id'],
            'lat'=>(isset($data['lat']))?$data['lat']:'',
            'lng'=>(isset($data['lng']))?$data['lng']:'',


        ];

        if(!empty($data['images'])){
            if(!isset($data['temp_id'])) {
                $temp_id = rand(0, 999999);
                foreach ($data['images'] as $img) {


                    $file_name = 'image_' .uniqid(). time() . '.png'; //generating unique file name;
                    //file_put_contents('storage/items/' . date('y') . '/' . date('m') . '/' . $file_name, base64_decode($img));
                    $this->file_force_contents('storage/items/' . date('y') . '/' . date('m') . '/'.$file_name , base64_decode($img));

                    $image_name = 'items/' . date('y') . '/' . date('m') . '/' . $file_name;

                    Upload::where('temp_id', $temp_id)->Create([
                        'temp_id' => $temp_id,
                        'path' => $image_name
                    ]);

                }
                $item_data['temp_id'] = $temp_id;
            }else{
                $item_data['temp_id'] = $data['temp_id'];
            }
        }

 
        if(!empty($data['attributes'])){
            $attribute_data = [];
            foreach ($data['attributes'] as $attribute){
                if($attribute['type'] == 'text' || $attribute['type'] == 'textarea' || $attribute['type'] == 'number' || $attribute['type'] == 'select'
                ||  $attribute['type'] == 'date' ||  $attribute['type'] == 'datetime') {
                    $attribute_data[$attribute['id']] = $attribute['values'][0];
                }else if($attribute['type'] == 'multi_select'){
                    $attribute_data[$attribute['id']] = $attribute['values'];
                }
            }
            $item_data['attribute'] = $attribute_data;
        }

        if(!empty($data['options'])){
            $option_data = [];
            foreach ($data['options'] as $key => $option){
                $new_option['template_option'] = 'new';
                $new_option['option_sort'] = $key;
                $new_option['option_name_ar'] = $option['name'];
                $new_option['option_name_en'] = $option['name'];
                $new_option['option_is_required'] = (isset($option['is_required']))?$option['is_required']:'no';
                $new_option['option_type'] = $option['type'];
                if($option['type'] == 'select' || $option['type'] == 'multi_select'){
                    foreach ($option['values'] as $key  => $value){
                        $new_option['option_value_name_ar'][$key]['option_value_name_ar'] = $value;
                        $new_option['option_value_name_en'][$key]['option_value_name_en'] = $value;
                        $new_option['option_value_price_prefix'][$key]['option_value_price_prefix'] = '+';
                        $new_option['option_value_price'][$key]['option_value_price'] = 0;
                    }

                }
                $option_data[] = $new_option;
            }
            $item_data['option'] = $option_data;
        }

//pd($item_data);

        $create = new Create();

        return $create->Item($item_data, \DataLanguage::get());


    }


    public static function file_force_contents($dir, $contents){
        $parts = explode('/', $dir);
        $file = array_pop($parts);
        $dir = '';

        foreach($parts as $part) {
            if (! is_dir($dir .= "{$part}/")) mkdir($dir ,0777, true );
        }

        return file_put_contents("{$dir}{$file}", $contents,LOCK_EX);
    }

    function ItemDetailsAuth(Request $request){
        return $this->ItemDetails($request);
    }

    function ItemDetails(Request $request)
    {
        $RequestData = $request->only(['id']);
        $validator = Validator::make($RequestData, [
            'id' => 'required|exists:items,id'
        ]);

        if ($validator->errors()->any()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }

        $Item = Item::Actives()
            ->select(['*', 'name_' . \DataLanguage::get() . ' as name', 'description_' . \DataLanguage::get() . ' as description', 'created_at'])
            ->with(['item_category' => function ($q) {
                $q->select(['*', 'name_' . \DataLanguage::get() . ' as name', 'slug_' . \DataLanguage::get() . ' as slug']);
            }, 'item_type' => function ($q) {
                $q->select(['*', 'name_' . \DataLanguage::get() . ' as name', 'slug_' . \DataLanguage::get() . ' as slug']);
            }, 'user', 'owner_user', 'select_attribute.values' => function ($q) {
                $q->orderBy('sort', 'asc')->get();
            }, 'select_attribute.Attribute' => function ($q) {
                $q->orderBy('sort', 'asc')->get();
            }, 'option' => function ($q) {
                $q->where('status', 'active')->orderBy('sort', 'asc')->get();
            }, 'option.values' => function ($q) {
                $q->where('status', 'active')->get();
            }, 'comment.user' => function ($q) {
                $q->where('status', 'active')->limit(5)->get();
            }, 'upload'])->find($request->id);


        if(empty($Item)){
            return $this->respondNotFound([], __('There Are no Item to display'));
        }
        $itemTransformer = new ItemTransformer();
        $itemDetails = $itemTransformer->itemDetails($Item->toArray(), [\DataLanguage::get()]);

//related Items
        $related_items = Item::Actives()->select(['id'])->where(['item_category_id' => $Item->item_category_id,
            'item_type_id' => $Item->item_type_id])->limit('10')->orderBy('created_at', 'DESC')
            ->with(['upload'])->where('id','!=',$request->id)->get();
        $related_items = $itemTransformer->transformCollection($related_items->toArray(), [\DataLanguage::get()], 'relatesItems');
        $data = ['item_details' => $itemDetails, 'related_items' => $related_items];
        $Item->increment('views');
        return $this->respondSuccess($data, __('Item Data'));

    }


     

    function ItemComments(Request $request)
    {

        $RequestData = $request->only(['id']);
        $validator = Validator::make($RequestData, [
            'id' => 'required|exists:items,id'
        ]);

        if ($validator->errors()->any()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }

        $comments = Item::find($request->id)->comment()->orderBy('id', 'desc')->with('user')->jsonPaginate();


        if (!$comments->items())
            return $this->respondNotFound(['items' => []], __('There Are no Comments to display'));

        $itemTransformer = new ItemTransformer();

        return $this->respondSuccess($itemTransformer->transformCollection($comments->toArray(), [\DataLanguage::get()], 'itemComments'), __('Item Comments'));

    }


    function ItemTypes()
    {

        $types = ItemTypes::select('id', 'name_' . \DataLanguage::get() . ' as name', 'icon')->get();
        return $this->respondSuccess($types, __('Item Types'));
    }

//    function ItemData()
//    {
//
//        $data['types'] = ItemTypes::select('id', 'name_' . \DataLanguage::get() . ' as name', 'icon')->get();
//        $data['categories'] = ItemCategory::select('id', 'name_' . \DataLanguage::get() . ' as name', 'icon')->get();
//        pd($data);
//        return $this->respondSuccess($data, __('Item Data'));
//    }


    function search(Request $request)
    {

        $Items = Item::Actives()
            ->select(['*', 'name_' . \DataLanguage::get() . ' as name', 'description_' . \DataLanguage::get() . ' as description', 'created_at'])
            ->with(['item_category' => function ($q) {
                $q->select(['*', 'name_' . \DataLanguage::get() . ' as name', 'slug_' . \DataLanguage::get() . ' as slug']);
            }, 'item_type' => function ($q) {
                $q->select(['*', 'name_' . \DataLanguage::get() . ' as name', 'slug_' . \DataLanguage::get() . ' as slug']);
            }, 'upload' => function ($q) {
                $q->first();
            }, 'user', 'owner_user']);

        if($request->search) {


            $filter = $request->search;


            if (isset($filter['word'])) {
                $Items->where('name_' . \DataLanguage::get(), 'like', '%' . $filter['word'] . '%')
                    ->orWhere('description_' . \DataLanguage::get(), 'like', '%' . $filter['word'] . '%');
            }

            if (isset($filter['sort_by'])) {
                $Items->orderBy($filter['sort_by'], 'desc');
            }

            if (isset($filter['type_id'])) {
                $Items->where('item_type_id', $filter['type_id']);
            }

            if (isset($filter['category_id'])) {
                $Items->where('item_category_id', $filter['category_id']);
            }

            if (isset($filter['category_id']) && isset($filter['type_id']) && !empty($filter['attributes'])) {



                foreach ($filter['attributes'] as  $value) {
                    $Items->whereHas('select_attribute',function ($q)use($value){
                        if (!empty($value) && $value != '' && $value != null) {

                             $attr = \App\Models\Attribute::find($value['id']);
                            if (!empty($attr)) {
                                $q->where('attribute_id', $attr->id);
                                if ($attr->type == 'select') {
                                    $q->where('attribute_value_id', $value['value']);
                                } elseif ($attr->type == 'multi_select') {
//                                    foreach($value as $k => $v){
//                                        $q->where('attribute_value_id', $v);
                                    }
                                }elseif ($attr->type == 'date' || $attr->type == 'datetime' || $attr->type == 'number'){
                                    $q->where('value',$value['value']);
                                } else {
                                    $q->where('value', 'like', '%' . $value['value'] . '%');
                                }
                            }

                    });
                }


            }

        }

        if (!empty($filter['qty_from']) || !empty($filter['qty_to'])) {
            whereBetween($Items,'quantity',$filter['qty_from'],$filter['qty_to']);
        }

        if (!empty($filter['price_from']) || !empty($filter['price_to'])) {
            whereBetween($Items,'price',$filter['price_from'],$filter['price_to']);
        }


        if (!empty($filter['lat']) && !empty($filter['lng'])) {
            //3959  for miles  &  6371 for km
            $Items->selectRaw('( 6371 * acos( cos( radians('.$filter['lat'].') ) * cos( radians( `lat`) ) * cos( radians( `lng` ) - radians('.$filter['lng'].') )  + sin( radians('.$filter['lat'].') ) * sin( radians( `lat` ) )) ) AS distance ') ->havingRaw('distance < 25');
        }

        if (!empty($filter['sort_by'])) {
            $Items->orderBy('id', $filter['sort_by']);
        }else{
            $Items->orderBy('id', 'desc');
        }
        $rows = $Items->jsonPaginate();

        if (!$rows->items())
            return $this->respondNotFound(false, __('There Are no items to display'));

        $itemTransformer = new ItemTransformer();

        return $this->respondSuccess($itemTransformer->transformCollection($rows->toArray(), [\DataLanguage::get()]), __('Items Data'));


    }

    function delete(Request $request){

        $RequestData = $request->only(['id']);
        $validator = Validator::make($RequestData, [
            'id' => 'required|exists:items,id'
        ]);

        if ($validator->errors()->any()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }

        $item = Auth::user()->items()->where('id',$RequestData['id'])->first();

        if(empty($item)){
            return $this->respondNotFound(false, __('There Are no items'));
        }

        if($item->item_deals->isNotEmpty()){
            return $this->respondNotFound(false, __('Cannot Delete Item has Deal'));
        }

        $item->upload()->delete();
        $item->ranks()->delete();
        $item->comment()->delete();
        $item->likes()->delete();
        foreach ($item->select_attribute as $row){
            $row->values()->delete();
        }
        $item->select_attribute()->delete();

        foreach ($item->option as $row){
            $row->values()->delete();
        }
        $item->option()->delete();

        foreach ($item->option as $row){
            $row->values()->delete();
        }
        $item->option()->delete();

        $item->delete();

        return $this->respondWithoutError([], 'Item Deleted');
    }


}
