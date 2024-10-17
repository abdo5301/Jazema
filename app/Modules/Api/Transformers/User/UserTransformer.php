<?php

namespace App\Modules\Api\Transformers\User;

use App\Modules\Api\Transformers\Transformer;
use Auth;

class UserTransformer extends Transformer
{
    public function transform($item, $opt = 'ar')
    {

       $stages = $item['stages'];
        $item = $item['user'];
        $user = [
            'userId' => $item['id'],
            'firstName' => ((isset($item['firstname']) ? $item['firstname'] : null)),
            'lastName' => ((isset($item['lastname']) ? $item['lastname'] : null)),
            'gender' => ((isset($item['gender'])) ? $item['gender'] : ''),
            'birthdate' => ((isset($item['birthdate'])) ? $item['birthdate'] : ''),
            'image' => self::Link($item, 'image'),
            'email' => ((isset($item['email']) ? $item['email'] : null)),
            'mobile' => ((isset($item['mobile']) ? $item['mobile'] : null)),
            'address' => ((isset($item['address']) ? $item['address'] : null)),
            'facebook' => ((isset($item['facebook']) ? $item['facebook'] : null)),
            'youtube' => ((isset($item['youtube']) ? $item['youtube'] : null)),
            'linkedin' => ((isset($item['linkedin']) ? $item['linkedin'] : null)),
            'instgram' => ((isset($item['instgram']) ? $item['instgram'] : null)),
            'google' => ((isset($item['google']) ? $item['google'] : null)),
            'interisted_categories' => ((isset($item['interisted_categories']) ? $item['interisted_categories'] : null)),
            //  'isActive'                  => self::status($item),
            'views' => $item['views'],
            'stages' => $stages,
            'about' => $item['about'],

        ];

        if (isset($item['userJob']['job_name']))
            $user['job_name'] = $item['userJob']['job_name'];
        if (isset($item['deals']))
            $user['deals'] = $item['deals'];

        return $user;
    }


   

    function UserStages($item,$opt){
        return [
          'id'=>$item['id'],
            'name'=>$item['name']
        ];
    }

    public function UserData($item, $opt)
    {

        $selected_attribute = $item['attributes'];

//        if (!empty($item['attributes']) && $item['attributes']->isNotEmpty()) {
//
//            $selected_attribute = $this->transformCollection($item['attributes']->toArray(), $opt, 'UserAttribute');
//        }

        $interisted_categories = $item['interisted_categories'];

        $stages = $this->transformCollection($item['stages']->toArray(), $opt, 'UserStages');
        $item = $item['user']->toArray();
        $user = [
            'userId' => $item['id'],
            'firstName' => ((isset($item['firstname']) ? $item['firstname'] : null)),
            'lastName' => ((isset($item['lastname']) ? $item['lastname'] : null)),
            'gender' => ((isset($item['gender'])) ? $item['gender'] : ''),
            'birthdate' => ((isset($item['birthdate'])) ? $item['birthdate'] : ''),
            'image' => self::Link($item, 'image'),
            'email' => ((isset($item['email']) ? $item['email'] : null)),
            'mobile' => ((isset($item['mobile']) ? $item['mobile'] : null)),
            'address' => ((isset($item['address']) ? $item['address'] : null)),
            'facebook' => ((isset($item['facebook']) ? $item['facebook'] : null)),
            'youtube' => ((isset($item['youtube']) ? $item['youtube'] : null)),
            'linkedin' => ((isset($item['linkedin']) ? $item['linkedin'] : null)),
            'instgram' => ((isset($item['instgram']) ? $item['instgram'] : null)),
            'google' => ((isset($item['google']) ? $item['google'] : null)),
            'rank' => ((isset($item['rank']) ? $item['rank'] : null)),
            'count_ranks' => ((isset($item['count_ranks']) ? $item['count_ranks'] : null)),
            'interisted_categories' => ((!empty($interisted_categories) ? $interisted_categories : null)),
            //  'isActive'                  => self::status($item),
            'deals_out' => ((isset($item['deals_out']) ? $item['deals_out'] : 0)),
            'deals_in' => ((isset($item['deals_in']) ? $item['deals_in'] : 0)),
            'views' => $item['views'],
            'about' => $item['about'],
            'following' => (isset($item['following']))?$item['following']:'',
            'friend' => (isset($item['friend']))?$item['friend']:'',
            'selected_attribute' => $selected_attribute,
            'stages' => $stages,


        ];

        if (isset($item['user_job'])) {
            $user['job_name'] = $item['user_job']['name_ar'];
        }
        if (isset($item['deals']))
            $user['deals'] = $item['deals'];

        return $user;
    }


    function UserAttributeSimple($user_attribute, $opt = 'ar')
    {

        if (!empty($user_attribute['attribute_value_id']) && empty($user_attribute['value'])) {
            $value = $user_attribute['values']['name_ar'];
            $value_id = $user_attribute['values']['id'];
        } else {
            $value = $user_attribute['value'];
            $value_id = 0;

        }
        if (empty($opt))
            $opt = \DataLanguage::get();

        return [
            'name' => $user_attribute['attribute']['name_ar'],
            'selected_value_name' => $value,

        ];
    }
    public function Profile($item, $opt)
    {

        $selected_attribute = [];

        if (!empty($item['attributes']) && $item['attributes']->isNotEmpty()) {

            $selected_attribute = $this->transformCollection($item['attributes']->toArray(), $opt, 'UserAttributeSimple');
        }

        $stages = $this->transformCollection($item['user']['stages']->toArray(), $opt, 'UserStages');
        $item = $item['user']->toArray();
        $user = [
            'userId' => $item['id'],
            'firstName' => ((isset($item['firstname']) ? $item['firstname'] : null)),
            'lastName' => ((isset($item['lastname']) ? $item['lastname'] : null)),
            'gender' => ((isset($item['gender'])) ? $item['gender'] : ''),
            'birthdate' => ((isset($item['birthdate'])) ? $item['birthdate'] : ''),
            'image' => self::Link($item, 'image'),
            'email' => ((isset($item['email']) ? $item['email'] : null)),
            'mobile' => ((isset($item['mobile']) ? $item['mobile'] : null)),
            'address' => ((isset($item['address']) ? $item['address'] : null)),
            'facebook' => ((isset($item['facebook']) ? $item['facebook'] : null)),
            'youtube' => ((isset($item['youtube']) ? $item['youtube'] : null)),
            'linkedin' => ((isset($item['linkedin']) ? $item['linkedin'] : null)),
            'instgram' => ((isset($item['instgram']) ? $item['instgram'] : null)),
            'google' => ((isset($item['google']) ? $item['google'] : null)),
            'rank' => ((isset($item['rank']) ? $item['rank'] : null)),
            'count_ranks' => ((isset($item['count_ranks']) ? $item['count_ranks'] : null)),
            'interisted_categories' => ((isset($item['interisted_categories']) ? $item['interisted_categories'] : null)),
            //  'isActive'                  => self::status($item),
            'deals_out' => ((isset($item['deals_out']) ? $item['deals_out'] : 0)),
            'deals_in' => ((isset($item['deals_in']) ? $item['deals_in'] : 0)),
            'views' => $item['views'],
            'about' => $item['about'],
            'following' => (isset($item['following']))?$item['following']:'',
            'friend' => (isset($item['friend']))?$item['friend']:'',
            'selected_attribute' => $selected_attribute,
            'stages' => $stages,


        ];

        if (isset($item['user_job'])) {
            $user['job_name'] = $item['user_job']['name_ar'];
        }
        if (isset($item['deals']))
            $user['deals'] = $item['deals'];

        return $user;
    }


    function UserAttributeValues($attributeValues,$opt){

        return [
            'id'=>$attributeValues['id'],
            'name'=>$attributeValues['name_ar']
        ];
    }

    function UserAttribute($user_attribute, $opt = 'ar')
    {
        $values = [];
        if (!empty($user_attribute['attribute_value_id']) && empty($user_attribute['value'])) {
            $value = $user_attribute['values']['name_ar'];
            $value_id = $user_attribute['values']['id'];
            $values = $this->transformCollection($user_attribute['attribute']['values'],[$opt],'UserAttributeValues');
        } else {
            $value = $user_attribute['value'];
            $value_id = 0;
            
        }
        if (empty($opt))
            $opt = \DataLanguage::get();

        return [

            'id' => $user_attribute['attribute']['id'],
            'type' => $user_attribute['attribute']['type'],
            'is_required' => $user_attribute['attribute']['is_required'],
            'name' => $user_attribute['attribute']['name_ar'],
            'selected_value_name' => $value,
            'selected_value' => $value_id,
            'values' => $values,
        ];
    }


    function deal($item, $opt = 'ar')
    {
        $options = [];
        if($item['options']){
        foreach ($item['options'] as $option){
            if($option['item_option']['type'] == 'text'){
                $options[] = [
                  'key' => $option['item_option']['name_ar'],
                  'value' => $option['value']
                ];
            }else{
                $options[] = [
                    'key' => $option['item_option']['name_ar'],
                    'value' => $option['item_option_values']['name_ar']
                ];
            }
        }
        }

        $data = [
            'id' => $item['id'],
            'item_id' => $item['item_id'],
            'status' => $item['status'],
            'total_price' => $item['total_price'],
            'item_name' => $item['item']['name'],
            'item_owner_id' => $item['item_owner_id'],
            'image' => (!empty($item['item']['upload'])) ? $item['item']['upload'][0]['path'] : 'items/temp.png',
            'owner_name' => $item['owner']['firstname'] . ' ' . $item['owner']['lastname'],
            'user_id' => $item['user_id'],
            'user_name' => $item['user']['firstname'] . ' ' . $item['user']['lastname'],
            'notes' => $item['notes'],
            'options' => $options,
            'rank' => ''
        ];

        return $data;
    }

    function friendRequests($item, $opt = 'ar')
    {
//pd($item);
        return [
            'id' => $item['id'],
            'created_at' => $item['created_at'],
            'user_id' => $item['user_id'],
            'user_name' => $item['user']['firstname'] . ' ' . $item['user']['lastname'],
            'image' => $item['user']['image'],
            'job' => $item['user']['user_job']['name_'.$opt],
        ];

    }

    function following($item, $opt = 'ar')
    {

        return [
            'id' => $item['id'],
            'user_id' => $item['to_user_id'],
            'user_name' => $item['to_user']['firstname'].' '.$item['to_user']['lastname'],
            'image' => $item['to_user']['image'],
            'job' => $item['to_user']['user_job']['name_ar'],
        ];

    }


    function  followers($item, $opt = 'ar')
    {

        return [
            'id' => $item['id'],
            'user_id' => $item['user_id'],
            'user_name' => $item['user']['firstname'].' '.$item['user']['lastname'],
            'image' => $item['user']['image'],
            'job' => $item['user']['user_job']['name_ar'],

        ];

    }



    function friends($item, $opt = 'ar')
    {

        if($item['user_id'] != Auth::id()) {
            return [
                'id' => $item['id'],
                'user_id' => $item['user_id'],
                'user_name' => $item['user']['firstname'].' '.$item['user']['lastname'],
                'image' => $item['user']['image'],
                'job' => $item['user']['user_job']['name_ar'],
            ];
        }else{
            return [
                'id' => $item['id'],
                'user_id' => $item['to_user_id'],
                'user_name' => $item['to_user']['firstname'].' '.$item['to_user']['lastname'],
                'image' => $item['to_user']['image'],
                'job' => $item['to_user']['user_job']['name_ar'],

            ];
        }

    }

    function inboxEmails($item,$opt){

        $data = [
            'id'=>$item['id'],
            'user_id'=>$item['from_id'],
            'user_name'=>$item['from_user']['firstname'].' '.$item['from_user']['lastname'],
            'image'=>$item['from_user']['image'],
            'subject'=>$item['subject'],
            'message'=>$item['message'],
            'date_time'=>$item['created_at'],

        ];

        if(!empty($item['item_id'])){
            $data['item_id']= $item['item_id'];
            $data['item_name']= $item['item']['name_ar'];
        }

        return $data;

    }

    function sentEmails($item,$opt){

        $data = [
            'id'=>$item['id'],
            'user_id'=>$item['to_id'],
            'user_name'=>$item['to_user']['firstname'].' '.$item['to_user']['lastname'],
            'image'=>$item['to_user']['image'],
            'subject'=>$item['subject'],
            'message'=>$item['message'],
            'date_time'=>$item['created_at'],
        ];

        if(!empty($item['item_id'])){
            $data['item_id']= $item['item_id'];
            $data['item_name']= $item['item']['name_ar'];
        }

        return $data;

    }

    function trashEmails($item,$opt){

        $data = [
            'id'=>$item['id'],
//            'to_id'=>$item['to_id'],
//            'to_user_name'=>$item['to_user']['firstname'].' '.$item['to_user']['lastname'],
//            'to_image'=>$item['to_user']['image'],
//            'from_id'=>$item['from_id'],
//            'from_user_name'=>$item['from_user']['firstname'].' '.$item['from_user']['lastname'],
//            'from_image'=>$item['to_user']['image'],
            'subject'=>$item['subject'],
            'message'=>$item['message'],
            'date_time'=>$item['created_at'],
        ];


        if( Auth::id() == $item['to_id']){
            $data['user_id'] = $item['from_id'];
            $data['user_name'] = $item['from_user']['firstname'].' '.$item['from_user']['lastname'];
            $data['image'] = $item['from_user']['image'];
        }else{
            $data['user_id'] = $item['to_id'];
            $data['user_name'] =$item['to_user']['firstname'].' '.$item['to_user']['lastname'];
            $data['image'] = $item['to_user']['image'];
        }

        if(!empty($item['item_id'])){
            $data['item_id']= $item['to_id'];
            $data['item_name']= $item['item']['name_ar'];
        }

        return $data;

    }
}