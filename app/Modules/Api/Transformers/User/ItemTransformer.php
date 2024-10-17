<?php

namespace App\Modules\Api\Transformers\User;

use App\Libs\DataLanguage;
use Carbon\Carbon;
use App\Modules\Api\Transformers\Transformer;

class ItemTransformer extends Transformer
{
    public function transform($item, $opt = 'ar')
    {

        $image = (!empty($item['upload'])) ? $item['upload'][0]['path'] : 'items/temp.png';
        list($width, $height) = getimagesize(asset('storage/'.$image));

        return [
            'id' => $item['id'],
            'category_name' => $item['item_category']['name'],
            'category_icon' => $item['item_category']['icon'],
            'category_slug' => $item['item_category']['slug'],
            'type_name' => $item['item_type']['name'],
            'type_icon' => $item['item_type']['icon'],
            'type_slug' => $item['item_type']['slug'],
            'user_id' => $item['user_id'],
            'user_image' => (!empty($item['user']['image'])) ? $item['user']['image'] : 'users/temp.png',
            'user_slug' => $item['user']['slug'],
            'user_mobile' => $item['user']['mobile'],
            'user_email' => $item['user']['email'],
            'user_views' => $item['user']['views'],
            'user_about' => $item['user']['about'],
            'user_name' => $item['user']['firstname'] . ' ' . $item['user']['lastname'],
            'owner_user_id' => $item['owner_user_id'],
            'owner_user_name' => ((isset($item['owner_user_id']) ? $item['owner_user']['firstname'] . ' ' . $item['owner_user']['lastname'] : null)),
            'name' => $item['name'],
            'description' => $item['description'],
            'price' => amount($item['price'],true),
            'quantity' => $item['quantity'],
            'views' => short_num($item['views']),
            'like' => short_num($item['like']),
            'comments' => short_num($item['comments']),
            'share' => short_num($item['share']),
            'deals' => short_num($item['deals']),
            'rank' => $item['rank'],
            'created_at' => (!empty($item['created_at']))?Carbon::createFromFormat('Y-m-d H:i:s',$item['created_at'])-> diffForHumans(): '',
            'image' => $image,
            'image_width' => $width,
            'image_height' => $height,
            'lat' => (!empty($item['lat']))?$item['lat']:'',
            'lng' => (!empty($item['lng']))?$item['lng']:'',
            'is_like' => ($item['authLiked']) ? true : false,
            'is_wishlist' => ($item['authWishlist']) ? true : false,


        ];
    }

    function itemDetails($item, $opt = 'ar')
    {

        $selected_attribute = $this->transformCollection($item['select_attribute'], $opt, 'itemAttribute');
        $option = (!empty($item['option']))?$this->transformCollection($item['option'], $opt, 'itemOption'):[];
        $upload = $this->transformCollection($item['upload'], $opt, 'itemImages');
        $comments = $this->transformCollection($item['comment'], $opt, 'itemComments');

        return [

            'id' => $item['id'],
            'category_name' => $item['item_category']['name'],
            'category_icon' => $item['item_category']['icon'],
            'category_slug' => $item['item_category']['slug'],
            'type_name' => $item['item_type']['name'],
            'type_icon' => $item['item_type']['icon'],
            'type_slug' => $item['item_type']['slug'],
            'user_id' => $item['user_id'],
            'user_image' => (!empty($item['user']['image'])) ? $item['user']['image'] : 'users/temp.png',
            'user_slug' => $item['user']['slug'],
            'user_mobile' => $item['user']['mobile'],
            'user_email' => $item['user']['email'],
            'user_views' => $item['user']['views'],
            'user_about' => $item['user']['about'],
            'user_name' => $item['user']['firstname'] . ' ' . $item['user']['lastname'],
            'owner_user_id' => $item['owner_user_id'],
            'owner_user_name' => ((isset($item['owner_user_id']) ? $item['owner_user']['firstname'] . ' ' . $item['owner_user']['lastname'] : null)),
            'name' => $item['name'],
            'description' => $item['description'],
            'price' => amount($item['price'],true),
            'quantity' => $item['quantity'],
            'views' => short_num($item['views']),
            'like' => short_num($item['like']),
            'comments' => short_num($item['comments']),
            'share' => short_num($item['share']),
            'deals' => short_num($item['deals']),
            'rank' => $item['rank'],
            'created_at' => (!empty($item['created_at']))?Carbon::createFromFormat('Y-m-d H:i:s',$item['created_at'])-> diffForHumans(): '',
            // 'image' => (!empty($item['upload'])) ? $item['upload'][0]['path'] : 'items/temp.png',
            'is_like' => ($item['authLiked']) ? true : false,
            'is_wishlist' => ($item['authWishlist']) ? true : false,
            'attribute' => $selected_attribute,
            'option' => $option,
            'images' => $upload,
            'lat' => (!empty($item['lat']))?$item['lat']:'',
            'lng' => (!empty($item['lng']))?$item['lng']:'',
            'Item_comments'=>$comments

        ];
    }


    function editItemImages($image,$opt = 'ar'){

        return [
            'id'=>$image['id'],
            'path'=>$image['path'],
        ];

    }

    function editItemAttributeValues($attributeValues,$opt){

        return [
          'id'=>$attributeValues['id'],
          'name'=>$attributeValues['name_ar']
        ];
    }

    function editItemAttribute($item_attribute, $opt = 'ar')
    {
        $values = [];
        if ($item_attribute['attribute']['type'] == 'select' ) {
            $value_id = $item_attribute['values']['id'];
            $value = $item_attribute['values']['name_' . $opt];
            $values = $this->transformCollection($item_attribute['attribute']['values'],[$opt],'editItemAttributeValues');
        }else {
            $value_id = '';
            $value = $item_attribute['value'];
        }
        if (empty($opt))
            $opt = \DataLanguage::get();

        return [
            'id'=>$item_attribute['attribute']['id'],
            'name' => $item_attribute['attribute']['name_' . $opt],
            'type' => $item_attribute['attribute']['type'],
            'value_id' => $value_id,
            'value' => $value,
            'attribute_values'=>$values
        ];
    }

    function editItemOption($option,$opt){
        if (empty($opt))
            $opt = \DataLanguage::get();

        $values = [];
        if(!empty($option['values']))
            $values = $this->transformCollection($option['values'],[$opt],'itemOptionValues');


        return [
            'id'=>$option['id'],
            'name'=>$option['name_'.$opt],
            'type'=>$option['type'],
            'is_required'=>$option['is_required'],
            'values'=> $values

        ];
    }

    function editItem($item, $opt = 'ar')
    {

        $attribute = $option = $upload = [];
        $attribute = $item['selected_attributes_handled']; //$this->transformCollection($item['select_attribute'], $opt, 'editItemAttribute');
        if(!empty($item['option']))
        $option = $this->transformCollection($item['option'], $opt, 'editItemOption');
        if(!empty($item['upload']))
        $upload = $this->transformCollection($item['upload'], $opt, 'editItemImages');

        return [

            'name' => $item['name_ar'],
            'category_name' => $item['item_category']['name_ar'],
            'category_id' => $item['item_category_id'],
            'type_name' => $item['item_type']['name_ar'],
            'stage_id' => $item['stage_id'],
            'stage_name' => $item['stage']['name'],
            'type_id' => $item['item_type_id'],
            'description' => $item['description_ar'],
            'price' => $item['price'],
            'quantity' => $item['quantity'],
            'attribute' => $attribute,
            'option' => $option,
            'images' => $upload,
            'lat' => (!empty($item['lat']))?$item['lat']:'',
            'lng' => (!empty($item['lng']))?$item['lng']:'',


        ];
    }

    function itemComments($item){
        return [
            'comment'=>$item['comment'],
            'created_at'=>(!empty($item['created_at']))?Carbon::createFromFormat('Y-m-d H:i:s',$item['created_at'])-> diffForHumans(): '',
            'user_id'=>$item['user_id'],
            'user_name'=>$item['user']['firstname'].' '.$item['user']['lastname'],
            'user_image' => (!empty($item['user']['image'])) ? $item['user']['image'] : 'users/temp.png',
        ];
    }

    function itemImages($image,$opt = 'ar'){

        list($width, $height) = getimagesize(asset('storage/'.$image['path']));

        return [
            'path'=>$image['path'],
            'width'=>$width,
            'height'=>$height,
        ];

    }

    function itemAttribute($item_attribute, $opt = 'ar')
    {

        if (!empty($item_attribute['attribute_value_id'] && empty($item_attribute['value'])))
            $value = $item_attribute['values']['name_ar'];
        else
            $value = $item_attribute['value'];
        if (empty($opt))
            $opt = \DataLanguage::get();

        return [

            'name' => $item_attribute['attribute']['name_ar'],
            'value' => $value
        ];
    }

    function relatesItems($item, $opt)
    {

        $image = (!empty($item['upload'])) ? $item['upload'][0]['path'] : 'items/temp.png';
        list($width, $height) = getimagesize(asset('storage/'.$image));
        return [
            'id' => $item['id'],
            'image' => $image,
            'image_width' => $width,
            'image_height' => $height,
        ];
    }

    function itemOption($option,$opt){
        if (empty($opt))
            $opt = \DataLanguage::get();

        $values = [];
        if(!empty($option['values']))
            $values = $this->transformCollection($option['values'],[$opt],'itemOptionValues');

        return [
            'id'=>$option['id'],
            'name'=>$option['name_'.$opt],
            'type'=>$option['type'],
            'is_required'=>$option['is_required'],
            'values'=> $values

        ];
    }

    function itemOptionValues($value,$opt){
         if (empty($opt))
            $opt = \DataLanguage::get();
        return [
            'id'=>(isset($value['id']))?$value['id']:'',
            'name'=>$value['name_'.$opt],
            'price_prefix'=>$value['price_prefix'],
            'price'=>$value['price'],

        ];
    }


}