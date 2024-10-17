<?php


namespace App\Libs;


use App\Models\Attribute;
use App\Models\Deal;
use App\Models\DealOptionValue;
use App\Models\Item;
use App\Models\ItemOption;
use App\Models\ItemOptionValues;
use App\Models\SelectedAttributeValues;
use App\Models\Staff;
use App\Models\TemplateOption;
use App\Models\Upload;
use App\Models\User;
use App\Models\Stage;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Expr\Array_;

class Create
{

    /*
     *
     * $data['attribute'][1]= 'value'   -> value
     * $data['attribute'][2]= 2         -> id
     * $data['attribute'][3]= [3,4]     -> multi select ids
     *
     *
     *
     */
    public function User(Array $data, $lang = 'ar')
    {

// validate user data
        $validationArray = [
            'type' => 'required|in:individual,company',
            'user_job_id' => 'required|exists:user_jobs,id',
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
            'gender' => 'required|in:male,female',
            'phone' => 'numeric',
            'mobile' => 'required|numeric|unique:users,mobile',
            'mobile2' => 'nullable|numeric',
            'mobile3' => 'nullable|numeric',
            'area_id' => 'required|exists:areas,id',
            'interisted_categories' => 'array',
            'facebook' => 'nullable|url',
            'youtube' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'google' => 'nullable|url',
            'instgram' => 'nullable|url',
            'address' => 'nullable|string',
            'lat' => 'nullable|string',
            'lng' => 'nullable|string',
        ];
        if (isset($data['type']) && $data['type'] == 'company') {
            $validationArray['company_name'] = 'required';
            $validationArray['company_business'] = 'required';
        }

        // validate user attribute
        if (!empty($data['user_job_id'])) {
            $user_job_attributes = Attribute::where('model_id', $data['user_job_id'])
                ->where('model_type', 'App\Models\UserJob')->get(['*', 'name_' . $lang . ' as name']);
//            dd($user_job_attributes->toArray());
            if (!empty($user_job_attributes)) {
                foreach ($user_job_attributes as $key => $attribute) {
//                    dd($attribute->type);
                    $type = '';
                    $required = '';

                    if ($attribute->is_required == 'yes')
                        $required = 'required';


                    if ($attribute->type == 'date')
                        $type = '|date';
                    if ($attribute->type == 'number')
                        $type = '|numeric';
                    if ($attribute->type == 'datetime')
                        $type = '|date_format:Y-m-d H:i:s';
                    if ($attribute->type == 'image')
                        $type = '|image';
                    if ($attribute->type == 'select')
                        $type = '|numeric';
                    if ($attribute->type == 'multi_select')
                        $type = '|array';

                    $validationArray['attribute[' . $attribute->id . ']'] = $required . $type;
                }
            }
        }


        $validator = Validator::make($data, $validationArray);
        if ($validator->fails()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }
//        $validator = Validator::make($data,$validationArray );
//
//
//        if($validator->errors()->any()){
//            return $this->ValidationError($validator,__('Validation Error'));
//        }

        $userData = [
            'user_job_id' => $data['user_job_id'],
            'type' => $data['type'],
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'gender' => $data['gender'],
            'mobile' => $data['mobile'],
            'address' => (isset($data['address']))?$data['address']:'',
            'lat' => (isset($data['lat']))?$data['lat']:'',
            'lng' => (isset($data['lng']))?$data['lng']:'',
            /* 'phone' => $data['phone'],
             'mobile2' => $data['mobile2'],
             'mobile3' => $data['mobile3'],
             'area_id' => $data['area_id'],
             'location' => $data['location'],
             'about' => $data['about'],
             'facebook' => $data['facebook'],
             'youtube' => $data['youtube'],
             'linkedin' => $data['linkedin'],
             'instgram' => $data['instgram'],
             'google' => $data['google'],
             'interisted_categories' => implode(',', $data['interisted_categories']),
             'address' => $data['address'],

             'staff_id' =>Auth::id(),*/
            'status' => 'active',
            'verified_at' => date('Y-m-d H:i:s')
        ];



        if ($data['type'] == 'company') {
            $userData['company_name'] = $data['company_name'];
            $userData['company_business'] = $data['company_business'];
        }
        if (!empty($data['image'])) {
            $userData['image'] = $data['image']->store('users/' . date('y') . '/' . date('m'));
        }


        if ($user = User::create($userData)) {

            // create stage
            $user->stages()->create([
                'user_id'=>$user->id,
                'name'=>'public',
                'show_to_friends'=>'yes',
                'show_to_followers'=>'yes',
                'show_to_public'=>'yes',

            ]);
            $slug = create_slug(  strstr($userData['email'], '@', true), $user->id);
            $user->update(['slug'=>$slug]);

            if (!empty($user_job_attributes)) {

                foreach ($user_job_attributes as $key => $attribute) {
                    $user_attribute = [];
                    $user_attribute['model_id'] = $user->id;
                    $user_attribute['model_type'] = 'App\Models\User';
                    if(empty($data['attribute[' . $attribute->id . ']'])){
                        continue;
                    }

                    $user_attribute['attribute_id'] = $attribute->id;
                    if (in_array($attribute->type, ['text', 'textarea', 'date', 'datetime', 'location','number'])) {
                        $user_attribute['value'] = $data['attribute[' . $attribute->id . ']'];
                        $user_attribute['model_type'] = 'App\Models\User';
                        SelectedAttributeValues::create($user_attribute);
                    } elseif ($attribute->type == 'file') {
                        if(!empty($data['attribute[' . $attribute->id . ']'])) {
                            $file_name = 'user_' . uniqid().time() . '.png'; //generating unique file name;
                            $this->file_force_contents('storage/users/' . date('y') . '/' . date('m') . '/'.$file_name , base64_decode($data['attribute[' . $attribute->id . ']']));
                            $user_attribute['value'] = 'users/'.date('y') . '/' . date('m') . '/'.$file_name;
                            //$user_attribute['value'] = $data['attribute[' . $attribute->id . ']']->store('users/' . date('y') . '/' . date('m'));
                        }
                    } elseif ($attribute->type == 'select') {
                        $user_attribute['model_id'] = $user->id;
                        $user_attribute['model_type'] = 'App\Models\User';
                        $user_attribute['attribute_value_id'] = $data['attribute[' . $attribute->id . ']'];
                        SelectedAttributeValues::create($user_attribute);
                    } elseif ($attribute->type == 'multi_select') {
                        foreach ($data['attribute[' . $attribute->id . ']'] as $key2 => $value) {
                            $user_attribute['attribute_value_id'] = $value;
                            SelectedAttributeValues::create($user_attribute);
                        }
                    }

                }
            }


            return ['status' => true, 'data' => $user, 'msg' => __('user is created')];


        }

    }

    public function EditUserProfile(Array $data, $lang = 'ar')
    {

        $old_data = Auth::user();

// validate user data
        $validationArray = [
            //  'type' => 'required|in:individual,company',
            //'user_job_id' => 'required|exists:user_jobs,id',
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|unique:users,email,' . Auth::id(),
            'password' => 'nullable|confirmed',
            'password_confirmation' => 'nullable',
            'gender' => 'nullable|in:male,female',
            'phone' => 'numeric',
            'mobile' => 'required|numeric|unique:users,mobile,' . Auth::id(),
            'mobile2' => 'nullable|numeric',
            'mobile3' => 'nullable|numeric',
            'area_id' => 'nullable|exists:areas,id',
//            'interisted_categories' => 'required',
            'facebook' => 'nullable|url',
            'youtube' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'google' => 'nullable|url',
            'instgram' => 'nullable|url',
            'about' => 'nullable',
        ];
        if ($old_data['type'] == 'company') {
            $validationArray['company_name'] = 'required';
            $validationArray['company_business'] = 'required';
        }

        // validate user attribute

        $user_job_attributes = Attribute::where('model_id', $old_data['user_job_id'])
            ->where('model_type', 'App\Models\UserJob')->get(['*', 'name_' . $lang . ' as name']);
//            dd($user_job_attributes->toArray());
        if (!empty($user_job_attributes)) {
            foreach ($user_job_attributes as $key => $attribute) {
//                    dd($attribute->type);
                $type = '';
                $required = '';

                if ($attribute->is_required == 'yes')
                    $required = 'required';


                if ($attribute->type == 'date')
                    $type = '|date';
                if ($attribute->type == 'number')
                    $type = '|numeric';
                if ($attribute->type == 'datetime')
                    $type = '|date_format:Y-m-d H:i:s';
                if ($attribute->type == 'image')
                    $type = '|image';
                if ($attribute->type == 'select')
                    $type = '|numeric';
                if ($attribute->type == 'multi_select')
                    $type = '|array';

                //$validationArray['attribute[' . $attribute->id . ']'] = $required . $type;
                $validationArray['attribute.' . $attribute->id] = $required . $type;
            }
        }


        $validator = Validator::make($data, $validationArray);
        if ($validator->fails()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }

        $userData = [
            //'user_job_id' => $data['user_job_id'],
            //        'type' => $data['type'],
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'mobile' => isset($data['mobile']) ? $data['mobile'] : $old_data['mobile'],
            'mobile2' => isset($data['mobile2']) ? $data['mobile2'] : $old_data['mobile2'],
            'mobile3' => isset($data['mobile3']) ? $data['mobile3'] : $old_data['mobile3'],
            'facebook' => isset($data['facebook']) ? $data['facebook'] : $old_data['facebook'],
            'youtube' => isset($data['youtube']) ? $data['youtube'] : $old_data['youtube'],
            'linkedin' => isset($data['linkedin']) ? $data['linkedin'] : $old_data['linkedin'],
            'instgram' => isset($data['instgram']) ? $data['instgram'] : $old_data['instgram'],
            'google' => isset($data['google']) ? $data['google'] : $old_data['google'],
            'about' => $data['about'],
            'interisted_categories' => !empty($data['interisted_categories']) ? $data['interisted_categories'] : $old_data['interisted_categories'],
            'address' => isset($data['address']) ? $data['address'] : $old_data['address'],
            'slug' => create_slug(  strstr($data['email'], '@', true), $old_data->id),

            ];



        if (!empty($data['password'])) {
            $userData['password'] = bcrypt($data['password']);
        } else {
            unset($userData['password']);
        }
        if ($old_data['type'] == 'company') {
            $userData['company_name'] = $data['company_name'];
            $userData['company_business'] = $data['company_business'];
        }
        if (!empty($data['image'])) {

            $file_name = 'user_' . uniqid().time() . '.png'; //generating unique file name;
            $this->file_force_contents('storage/users/' . date('y') . '/' . date('m') . '/'.$file_name , base64_decode($data['image']));

         //   file_put_contents('storage/users/' . date('y') . '/' . date('m') . '/' . $file_name, base64_decode($data['image']));
            $userData['image'] = 'users/'.date('y') . '/' . date('m') . '/'.$file_name;
        }
//        if (!empty($data['image'])) {
//            $userData['image'] = $data['image']->store('users/' . date('y') . '/' . date('m'));
//        }

        if ( User::where('id', $old_data['id'])->Update($userData)) {
            $user = User::find($old_data['id']);

            if (!empty($user_job_attributes)) {
                // delete old attribute

                $old_data->select_attribute()->delete();
//

                $data_attribute = $data['attribute'];
                foreach ($user_job_attributes as $key => $attribute) {
                    $user_attribute = [];
                    $user_attribute['model_id'] = $old_data['id'];
                    $user_attribute['model_type'] = 'App\Models\User';
                    if(empty($data['attribute[' . $attribute->id . ']']))
                        continue;
                    $user_attribute['attribute_id'] = $attribute->id;
                    if (in_array($attribute->type, ['text', 'textarea', 'date', 'datetime', 'location','number'])) {
                        $user_attribute['value'] = $data['attribute[' . $attribute->id . ']'];
                        SelectedAttributeValues::create($user_attribute);
                    } elseif ($attribute->type == 'file') {
                        $file_name = 'user_' . uniqid().time().$attribute->id . '.png'; //generating unique file name;
                        $this->file_force_contents('storage/users/' . date('y') . '/' . date('m') . '/'.$file_name , $data['attribute[' . $attribute->id . ']']);
                        $user_attribute['value'] = 'users/'.date('y') . '/' . date('m') . '/'.$file_name;
                    } elseif ($attribute->type == 'select') {
                        $user_attribute['attribute_value_id'] = $data['attribute[' . $attribute->id . ']'];//$data['attribute['.$attribute->id.']'];
                        SelectedAttributeValues::create($user_attribute);
                    } elseif ($attribute->type == 'multi_select') {
                        foreach ($data['attribute[' . $attribute->id . ']'] as $key2 => $value) {
                            $user_attribute['attribute_value_id'] = $value;
                            SelectedAttributeValues::create($user_attribute);
                        }
                    }

                }
            }

            return ['status' => true, 'msg' => __('user Profile is Updated')];
            //return ['status' => true, 'data' => (object)[], 'msg' => __('user Profile is Updated')];


        }

    }




    public function EditUserProfile_abdo(Array $data, $lang = 'ar')
    {

        $old_data = Auth::user();

// validate user data
        $validationArray = [
            //  'type' => 'required|in:individual,company',
            //'user_job_id' => 'required|exists:user_jobs,id',
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|unique:users,email,' . Auth::id(),
            'password' => 'nullable|confirmed',
            'password_confirmation' => 'nullable',
            'gender' => 'nullable|in:male,female',
            'phone' => 'numeric',
            'mobile' => 'required|numeric|unique:users,mobile,' . Auth::id(),
            'mobile2' => 'nullable|numeric',
            'mobile3' => 'nullable|numeric',
            'area_id' => 'nullable|exists:areas,id',
//            'interisted_categories' => 'required',
            'facebook' => 'nullable|url',
            'youtube' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'google' => 'nullable|url',
            'instgram' => 'nullable|url',
            'about' => 'nullable',
        ];
        if ($old_data['type'] == 'company') {
            $validationArray['company_name'] = 'required';
            $validationArray['company_business'] = 'required';
        }

        // validate user attribute

        $user_job_attributes = Attribute::where('model_id', $old_data['user_job_id'])
            ->where('model_type', 'App\Models\UserJob')->get(['*', 'name_' . $lang . ' as name']);
//            dd($user_job_attributes->toArray());
        if (!empty($user_job_attributes)) {
            foreach ($user_job_attributes as $key => $attribute) {
//                    dd($attribute->type);
                $type = '';
                $required = '';

                if ($attribute->is_required == 'yes')
                    $required = 'required';


                if ($attribute->type == 'date')
                    $type = '|date';
                if ($attribute->type == 'number')
                    $type = '|numeric';
                if ($attribute->type == 'datetime')
                    $type = '|date_format:Y-m-d H:i:s';
                if ($attribute->type == 'image')
                    $type = '|image';
                if ($attribute->type == 'select')
                    $type = '|numeric';
                if ($attribute->type == 'multi_select')
                    $type = '|array';

                //$validationArray['attribute[' . $attribute->id . ']'] = $required . $type;
                $validationArray['attribute.' . $attribute->id] = $required . $type;
                $ValidateNames['attribute.' . $attribute->id] = $attribute->{'name_'.getLang()};
            }
        }


        $validator = Validator::make($data, $validationArray);
        if(isset($ValidateNames)&&!empty($ValidateNames)){
            $validator->setAttributeNames($ValidateNames);
        }

        if ($validator->fails()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }

        $userData = [
            //'user_job_id' => $data['user_job_id'],
            //        'type' => $data['type'],
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'mobile' => isset($data['mobile']) ? $data['mobile'] : $old_data['mobile'],
            'mobile2' => isset($data['mobile2']) ? $data['mobile2'] : $old_data['mobile2'],
            'mobile3' => isset($data['mobile3']) ? $data['mobile3'] : $old_data['mobile3'],
            'facebook' => isset($data['facebook']) ? $data['facebook'] : $old_data['facebook'],
            'youtube' => isset($data['youtube']) ? $data['youtube'] : $old_data['youtube'],
            'linkedin' => isset($data['linkedin']) ? $data['linkedin'] : $old_data['linkedin'],
            'instgram' => isset($data['instgram']) ? $data['instgram'] : $old_data['instgram'],
            'google' => isset($data['google']) ? $data['google'] : $old_data['google'],
            'about' => $data['about'],
            'interisted_categories' => !empty($data['interisted_categories']) ? implode(',', $data['interisted_categories']) : '',
            'address' => isset($data['address']) ? $data['address'] : $old_data['address'],
            'lat' => isset($data['lat']) ? $data['lat'] : $old_data['lat'],
            'lng' => isset($data['lng']) ? $data['lng'] : $old_data['lng'],
            'slug' => create_slug(  strstr($data['email'], '@', true), $old_data->id),

        ];



        if (!empty($data['password'])) {
            $userData['password'] = bcrypt($data['password']);
        } else {
            unset($userData['password']);
        }
        if ($old_data['type'] == 'company') {
            $userData['company_name'] = $data['company_name'];
            $userData['company_business'] = $data['company_business'];
        }
//        if (!empty($data['image'])) {
//
//            $file_name = 'user_' . uniqid().time() . '.png'; //generating unique file name;
//            $this->file_force_contents('storage/users/' . date('y') . '/' . date('m') . '/'.$file_name , base64_decode($data['image']));
//            $userData['image'] = 'users/'.date('y') . '/' . date('m') . '/'.$file_name;
//        }

        if (!empty($data['image'])) {
            $userData['image'] = $data['image']->store('users/' . date('y') . '/' . date('m'));
        }

        if ( User::where('id', $old_data['id'])->Update($userData)) {
            $user = User::find($old_data['id']);

            if (!empty($user_job_attributes)) {
                // delete old attribute

                $old_data->select_attribute()->delete();
//


                foreach ($user_job_attributes as $key => $attribute) {
                    $user_attribute = [];
                    $user_attribute['model_id'] = $old_data['id'];
                    $user_attribute['model_type'] = 'App\Models\User';
                    if(empty($data['attribute'][$attribute->id]))
                        continue;
                    $user_attribute['attribute_id'] = $attribute->id;
                    if (in_array($attribute->type, ['text', 'textarea', 'date', 'datetime', 'location','number'])) {
                        $user_attribute['value'] = $data['attribute'][$attribute->id];
                        SelectedAttributeValues::create($user_attribute);
                    } elseif ($attribute->type == 'file') {
                        $file_name = 'user_' . uniqid().time().$attribute->id . '.png'; //generating unique file name;
                        $this->file_force_contents('storage/users/' . date('y') . '/' . date('m') . '/'.$file_name , $data['attribute'][$attribute->id]);
                        $user_attribute['value'] = 'users/'.date('y') . '/' . date('m') . '/'.$file_name;
                    } elseif ($attribute->type == 'select') {
                        $user_attribute['attribute_value_id'] = $data['attribute'][$attribute->id];//$data['attribute['.$attribute->id.']'];
                        SelectedAttributeValues::create($user_attribute);
                    } elseif ($attribute->type == 'multi_select') {
                        foreach ($data['attribute'][$attribute->id] as $key2 => $value) {
                            $user_attribute['attribute_value_id'] = $value;
                            SelectedAttributeValues::create($user_attribute);
                        }
                    }

                }
            }

            return ['status' => true, 'msg' => __('user Profile is Updated')];
            //return ['status' => true, 'data' => (object)[], 'msg' => __('user Profile is Updated')];


        }

    }



    public function Register(Array $data, $lang = 'ar')
    {

// validate user data
        $validationArray = [
            'type' => 'required|in:individual,company',
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
            'gender' => 'required|in:male,female',
            'phone' => 'numeric|unique:users,phone',
            'mobile' => 'required|numeric|unique:users,mobile',
            'user_job_id' => 'required|exists:user_jobs,id',
            // 'area_id'       =>  'required|exists:areas,id',
            //   'interisted_categories'       =>  'array',

        ];
        if ($data['type'] == 'company') {
            $validationArray['company_name'] = 'required';
            $validationArray['company_business'] = 'required';
        }

        // validate user attribute
        if (!empty($data['user_job_id'])) {
            $user_job_attributes = Attribute::where('model_id', $data['user_job_id'])
                ->where('model_type', 'App\Models\UserJob')->get(['*', 'name_' . $lang . ' as name']);
//            dd($user_job_attributes->toArray());
            if (!empty($user_job_attributes)) {
                foreach ($user_job_attributes as $key => $attribute) {
//                    dd($attribute->type);
                    $type = '';
                    $required = '';

                    if ($attribute->is_required == 'yes')
                        $required = 'required';


                    if ($attribute->type == 'date')
                        $type = '|date';
                    if ($attribute->type == 'number')
                        $type = '|numeric';
                    if ($attribute->type == 'datetime')
                        $type = '|date_format:Y-m-d H:i:s';
                    if ($attribute->type == 'image')
                        $type = '|image';
                    if ($attribute->type == 'select')
                        $type = '|numeric';
                    if ($attribute->type == 'multi_select')
                        $type = '|array';

                    //$validationArray['attribute[' . $attribute->id . ']'] = $required.$type;
                   $validationArray['attribute.' . $attribute->id] = $required . $type;
                   $ValidateNames['attribute.' . $attribute->id] = $attribute->{'name_'.getLang()};
                }
            }
        }

//dd($validationArray);
        $validator = Validator::make($data, $validationArray);
        if(isset($ValidateNames)&&!empty($ValidateNames)){
            $validator->setAttributeNames($ValidateNames);
        }

        if ($validator->fails()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }
//        $validator = Validator::make($data,$validationArray );
//
//
//        if($validator->errors()->any()){
//            return $this->ValidationError($validator,__('Validation Error'));
//        }

        $userData = [
            'user_job_id' => $data['user_job_id'],
            'type' => $data['type'],
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'gender' => $data['gender'],
            'phone' => $data['phone'],
            'mobile' => $data['mobile'],
            'lat' => isset($data['lat']) ? $data['lat'] : '',
            'lng' => isset($data['lng']) ? $data['lng'] : '',
//            'interisted_categories' => implode(',', $data['interisted_categories']),
            'status' => 'active',
        ];


        if ($data['type'] == 'company') {
            $userData['company_name'] = $data['company_name'];
            $userData['company_business'] = $data['company_business'];
        }
        if (!empty($data['image'])) {
            $userData['image'] = $data['image']->store('users/' . date('y') . '/' . date('m'));
        }


        if ($user = User::create($userData)) {

            //slug
            $slug = create_slug(  strstr($userData['email'], '@', true), $user->id);
            $user->update(['slug'=>$slug]);

            // create stage
            $user->stages()->create([
                'user_id'=>$user->id,
                'name'=>'public',
                'show_to_friends'=>'yes',
                'show_to_followers'=>'yes',
                'show_to_public'=>'yes',

            ]);


            $data_attribute = $data['attribute'];
            if (!empty($user_job_attributes)) {
                foreach ($user_job_attributes as $key => $attribute) {
                    $user_attribute = [];
                    $user_attribute['model_id'] = $user->id;
                    $user_attribute['model_type'] = 'App\Models\User';
                    if(empty($data['attribute'][$attribute->id]))
                        continue;
                    $user_attribute['attribute_id'] = $attribute->id;
                    if (in_array($attribute->type, ['text', 'textarea', 'date', 'datetime', 'location','number'])) {
                        $user_attribute['value'] = $data['attribute'][$attribute->id];
                        SelectedAttributeValues::create($user_attribute);
                    } elseif ($attribute->type == 'file') {
                        $file_name = 'user_' . uniqid().time().$attribute->id . '.png'; //generating unique file name;
                        $this->file_force_contents('storage/users/' . date('y') . '/' . date('m') . '/'.$file_name , $data['attribute'][$attribute->id]);
                        $user_attribute['value'] = 'users/'.date('y') . '/' . date('m') . '/'.$file_name;
                    } elseif ($attribute->type == 'select') {
                        $user_attribute['attribute_value_id'] = $data['attribute'][$attribute->id];//$data['attribute['.$attribute->id.']'];
                        SelectedAttributeValues::create($user_attribute);
                    } elseif ($attribute->type == 'multi_select') {
                        foreach ($data['attribute'][$attribute->id] as $key2 => $value) {
                            $user_attribute['attribute_value_id'] = $value;
                            SelectedAttributeValues::create($user_attribute);
                        }
                    }

                }
            }

            Auth::guard('web')->login($user);
            return ['status' => true, 'data' => $user, 'redirect'=>route('web.user.profile',[$user->slug]),'msg' => __('user is created')];


        }

    }


    public static function Staff(Array $staffArray)
    {
        $staff = false;
        \DB::transaction(function () use ($staffArray, &$staff) {
            $staff = Staff::create([
                'firstname' => $staffArray['firstname'],
                'lastname' => $staffArray['lastname'],
                //     'national_id'=> $staffArray['national_id'],
                'email' => $staffArray['email'],
                'mobile' => $staffArray['mobile'],
                'avatar' => @$staffArray['avatar'],
                'gender' => $staffArray['gender'],
                'birthdate' => $staffArray['birthdate'],
                'address' => $staffArray['address'],
                'password' => bcrypt($staffArray['password']),
                'description' => $staffArray['description'],
                'job_title' => $staffArray['job_title'],
                'status' => $staffArray['status'],
                'permission_group_id' => $staffArray['permission_group_id'],
                //  'supervisor_id'=> $staffArray['supervisor_id']
            ]);


        });

        return $staff;
    }

    public function Item(Array $data, $lang = 'ar')
    {

        //pd($data);
// validate Item data
        $validationArray = [
            'item_category_id' => 'required|exists:item_categories,id',
            'item_type_id' => 'required|exists:item_types,id',
            'user_id' => 'required|exists:users,id',
            'name_ar' => 'required',
            //'name_en' => 'required',
            'description_ar' => 'required',
            'stage_id' => 'required',
            //'description_en' => 'required',
            'price' => 'nullable|numeric',
            'quantity' => 'nullable|numeric',
            'lat' => 'nullable',
            'lng' => 'nullable',
        ];

        $ValidateNames = array(
            'item_category_id' => ('Item\'s Category'),
            'item_type_id' => __('Item\'s Type'),
            'name_ar' => __('Item\'s Name'),
            'description_ar' => __('Item\'s Description'),
            'stage_id' => __('Item\'s Stage'),
        );


// validate category attribute
        if (!empty($data['item_category_id']) && !empty($data['item_type_id'])) {
            $item_category_attributes = Attribute::where('model_id', $data['item_category_id'])
                ->where('model_type', 'App\Models\ItemCategory')
                ->where('item_type_id', $data['item_type_id'])
                ->get(['*', 'name_' . $lang . ' as name']);
            // dd($item_category_attributes);
            if (!empty($item_category_attributes)) {
                foreach ($item_category_attributes as $key => $attribute) {
                    $type = '';
                    $required = '';

                    if ($attribute->is_required == 'yes')
                        $required = 'required';

                    if ($attribute->type == 'date')
                        $type = '|date';
                    if ($attribute->type == 'number')
                        $type = '|numeric';
                    if ($attribute->type == 'datetime')
                        $type = '|date_format:Y-m-d H:i:s';
                    if ($attribute->type == 'image')
                        $type = '|image';
                    if ($attribute->type == 'select')
                        $type = '|numeric';
                    if ($attribute->type == 'multi_select')
                        $type = '|array';
                    $validationArray['attribute.' . $attribute->id] = $required . $type;
                    $ValidateNames['attribute.' . $attribute->id] = $attribute->{'name_'.getLang()};


                }
            }
        }

        if (!empty($data['option'])) {
            foreach ($data['option'] as $key => $option) {
                // $validationArray['option.' . $key . '.template_option'] = 'required|in:template,new';
                if ($option['template_option'] != 'new') {   // select from template
                    $validationArray['option[' . $key . ']']['id'] = 'required|exists:template_options,id';
                    $ValidateNames['option[' . $key . ']']['id'] = __('Option\'s Template'). $key;
                } elseif ($option['template_option'] == 'new') {
                    $validationArray['option.' . $key . '.option_name_ar'] = 'required';
                    $ValidateNames['option.' . $key . '.option_name_ar'] = __('Option\'s name').' '.__('of').' '.__('option'). $key;
                   // $validationArray['option.' . $key . '.option_name_en'] = 'required';
                    $validationArray['option.' . $key . '.option_type'] = 'required';
                    $ValidateNames['option.' . $key . '.option_type'] = __('Option\'s type').' '.__('of').' '.__('option'). $key;

                    $validationArray['option.' . $key . '.option_is_required'] = 'required';
                    $ValidateNames['option.' . $key . '.option_is_required'] = __('Option\'s required state').' '.__('of').' '.__('option'). $key;

                    if (in_array($data['option'][$key]['option_type'], ['select', 'radio', 'check'])) {
                        if (!empty($data['option'][$key]['option_value_name_ar'])){
                            foreach ($data['option'][$key]['option_value_name_ar'] as $key2 => $value) {
                                $validationArray['option.' . $key . '.option_value_name_ar.' . $key2] = 'required';
                                $ValidateNames['option.' . $key . '.option_value_name_ar.' . $key2] = __('Option Value'). $key2.__('Of').__('option'). $key ;
                              //  $validationArray['option.' . $key . '.option_value_name_en.' . $key2] = 'required';
                               // $validationArray['option.' . $key . '.option_value_price_prefix.' . $key2] = 'required';
                              //  $validationArray['option.' . $key . '.option_value_price.' . $key2] = 'required';

                            }
                    }else{
                            $validationArray['option.' . $key . '.option_value_name_ar'] = 'required';
                            $ValidateNames['option.' . $key . '.option_value_name_ar'] =__('Option\' Values').' '.__('of').' '.__('option'). $key ;
                        }

                    }
                }
            }
        }



        $validator = Validator::make($data, $validationArray);
        $validator->setAttributeNames($ValidateNames);

        if ($validator->errors()->any()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }


        if (empty($data['name_en']))
            $data['name_en'] = $data['name_ar'];
        if (empty($data['description_en']))
            $data['description_en'] = $data['description_ar'];
        $itemData = [
            'item_category_id' => $data['item_category_id'],
            'item_type_id' => $data['item_type_id'],
            'user_id' => $data['user_id'],
            'name_ar' => $data['name_ar'],
            'name_en' => $data['name_en'],
            'description_ar' => $data['description_ar'],
            'description_en' => $data['description_en'],
            'price' => $data['price'],
            'stage_id' => $data['stage_id'],
            'quantity' => $data['quantity'],
            'lat' => (isset($data['lat'])) ? $data['lat'] : '',
            'lng' => (isset($data['lng'])) ? $data['lng'] : '',
            'creatable_id' => Auth::id(),
            'creatable_type' => Auth::user()->modelPath,
        ];


        if ($item = Item::create($itemData)) {
            $slug_ar = create_slug($item->name_ar, $item->id);
            $slug_en = create_slug($item->name_en, $item->id);
            $item->update(['slug_ar' => $slug_ar, 'slug_en' => $slug_en]);

            if (isset($data['temp_id'])) {
                Upload::where('temp_id', $data['temp_id'])->update([
                    'model_type' => 'App\Models\Item',
                    'model_id' => $item->id,
                    'temp_id' => ''
                ]);
            }

            if (!empty($data['attribute']))
                $data_attribute = $data['attribute'];


            if (!empty($item_category_attributes)) {
                $item_attribute['model_id'] = $item->id;
                $item_attribute['model_type'] = 'App\Models\Item';
                foreach ($item_category_attributes as $key => $attribute) {
                    // dd($item_category_attributes);
                    $item_attribute['value'] = '';

                    $item_attribute['attribute_id'] = $attribute->id;
                    if (in_array($attribute->type, ['text', 'textarea', 'date', 'datetime', 'location','number'])) {
                        $item_attribute['value'] = $data_attribute[$attribute->id];
                        // dd($item_attribute);
                        SelectedAttributeValues::create($item_attribute);
                    } elseif ($attribute->type == 'file') {
                        $item_attribute['value'] = $data_attribute[$attribute->id]->store('users/' . date('y') . '/' . date('m'));
                    } elseif ($attribute->type == 'select') {
                        $item_attribute['attribute_value_id'] = $data_attribute[$attribute->id];
                        SelectedAttributeValues::create($item_attribute);
                    } elseif ($attribute->type == 'multi_select') {
//                        dd($attribute->id);
                        //  pd($data['attribute']);
                        foreach ($data_attribute[$attribute->id] as $key2 => $value) {
                            $item_attribute['attribute_value_id'] = $value;
                            SelectedAttributeValues::create($item_attribute);
                        }
                    }

                }
            }
            if (!empty($data['option'])) {
                foreach ($data['option'] as $key => $option) {
                    $item_option['item_id'] = $item->id;
                    $item_option['status'] = 'active';
                    $item_option['sort'] = $option['option_sort'];
                    if ($option['template_option'] == 'template') {
                        // select from template
                        $template_option = TemplateOption::find($option['id']);

                        $item_option['name_ar'] = $template_option->name_ar;
                        if(empty($item_option['name_en'])){
                            $item_option['name_en'] = $template_option->name_ar;
                        }else{
                            $item_option['name_en'] = $template_option->name_en;
                        }
                        $item_option['type'] = $template_option->type;
                        $item_option['is_required'] = $template_option->is_required;

                        if ($itemOption = ItemOption::create($item_option)) {
                            $item_option_value['item_option_id'] = $itemOption->id;
                            foreach ($template_option->values as $value) {
                                $item_option_value['name_ar'] = $value['name_ar'];
                                if(empty($item_option_value['name_en'])){
                                    $item_option_value['name_en'] = $value['name_ar'];
                                }else{
                                    $item_option_value['name_en'] = $value['name_en'];
                                }
                                $item_option_value['price_prefix'] = $value['price_prefix'];
                                $item_option_value['price'] = $value['price'];

                                ItemOptionValues::create($item_option_value);
                            }

                        }


                    } elseif ($option['template_option'] == 'new') {          // new option
                        $item_option['name_ar'] = $option['option_name_ar'];
                        if(empty( $option['option_name_en'])){
                            $item_option['name_en'] = $option['option_name_ar'];
                        }else{
                            $item_option['name_en'] = $option['option_name_en'];
                        }
                        $item_option['type'] = $option['option_type'];
                        $item_option['is_required'] = $option['option_is_required'];
                        if ($itemOption = ItemOption::create($item_option)) {
                            $item_option_value['item_option_id'] = $itemOption->id;
                            if (in_array($item_option['type'], ['select', 'radio', 'check'])) {

                                foreach ($option['option_value_name_ar'] as $key2 => $value) {

                                    $item_option_value['name_ar'] = $option['option_value_name_ar'][$key2];
                                    if(empty($option['option_value_name_en'][$key2])){
                                        $item_option_value['name_en'] = $option['option_value_name_ar'][$key2];
                                    }else{
                                        $item_option_value['name_en'] = $option['option_value_name_en'][$key2];
                                    }

                                    $item_option_value['price_prefix'] = (isset($option['option_value_price_prefix'][$key2]))? $option['option_value_price_prefix'][$key2] : '+';
                                    $item_option_value['price'] = (isset($option['option_value_price'][$key2]))? $option['option_value_price'][$key2] : 0;

                                    ItemOptionValues::create($item_option_value);
                                }
                            }

                        }

                    }
                }
            }


            return ['status' => true, 'data' => $item, 'msg' => __('Item created')];


        }


    }

    public function editItem(Array $data, $lang = 'ar')
    {


        $validationArray = [
            'id' => 'required',
            'name_ar' => 'required',
            //'name_en' => 'required',
            'description_ar' => 'required',
            //'description_en' => 'required',
            'stage_id' => 'required',
            'price' => 'nullable|numeric',
            'quantity' => 'nullable|numeric',
            'lat' => 'nullable',
            'lng' => 'nullable',
        ];

        $ValidateNames = array(
            'item_category_id' => ('Item\'s Category'),
            'item_type_id' => __('Item\'s Type'),
            'name_ar' => __('Item\'s Name'),
            'description_ar' => __('Item\'s Description'),
            'stage_id' => __('Item\'s Stage'),
        );


        $item = Auth::user()->items()->where('id', $data['id'])->first();
        if (!$item)
            return ['status' => false, 'msg' => 'Item Not Exists'];

// validate category attribute

        $item_category_attributes = Attribute::where('model_id', $item['item_category_id'])
            ->where('model_type', 'App\Models\ItemCategory')
            ->where('item_type_id', $item['item_type_id'])
            ->get(['*', 'name_' . $lang . ' as name']);
        // dd($item_category_attributes);
        if (!empty($item_category_attributes)) {
            foreach ($item_category_attributes as $key => $attribute) {
                $type = '';
                $required = '';

                if ($attribute->is_required == 'yes')
                    $required = 'required';

                if ($attribute->type == 'date')
                    $type = '|date';
                if ($attribute->type == 'number')
                    $type = '|numeric';
                if ($attribute->type == 'datetime')
                    $type = '|date_format:Y-m-d H:i:s';
                if ($attribute->type == 'image')
                    $type = '|image';
                if ($attribute->type == 'select')
                    $type = '|numeric';
                if ($attribute->type == 'multi_select')
                    $type = '|array';
                $validationArray['attribute.' . $attribute->id] = $required . $type;
                $ValidateNames['attribute.' . $attribute->id] = $attribute->{'name_'.getLang()};
            }
        }


        if (!empty($data['option'])) {
            foreach ($data['option'] as $key => $option) {
                // $validationArray['option.' . $key . '.template_option'] = 'required|in:template,new';
                if ($option['template_option'] != 'new') {   // select from template
                    $validationArray['option[' . $key . ']']['id'] = 'required|exists:template_options,id';
                    $ValidateNames['option[' . $key . ']']['id'] = __('Option\'s Template'). $key;
                } elseif ($option['template_option'] == 'new') {
                    $validationArray['option.' . $key . '.option_name_ar'] = 'required';
                    $ValidateNames['option.' . $key . '.option_name_ar'] = __('Option\'s name').' '.__('of').' '.__('option'). $key;
                    // $validationArray['option.' . $key . '.option_name_en'] = 'required';
                    $validationArray['option.' . $key . '.option_type'] = 'required';
                    $ValidateNames['option.' . $key . '.option_type'] = __('Option\'s type').' '.__('of').' '.__('option'). $key;

                    $validationArray['option.' . $key . '.option_is_required'] = 'required';
                    $ValidateNames['option.' . $key . '.option_is_required'] = __('Option\'s required state').' '.__('of').' '.__('option'). $key;

                    if (in_array($data['option'][$key]['option_type'], ['select', 'radio', 'check'])) {
                        if (!empty($data['option'][$key]['option_value_name_ar'])){
                            foreach ($data['option'][$key]['option_value_name_ar'] as $key2 => $value) {
                                $validationArray['option.' . $key . '.option_value_name_ar.' . $key2] = 'required';
                                $ValidateNames['option.' . $key . '.option_value_name_ar.' . $key2] = __('Option Value'). $key2.__('Of').__('option'). $key ;
                                //  $validationArray['option.' . $key . '.option_value_name_en.' . $key2] = 'required';
                                // $validationArray['option.' . $key . '.option_value_price_prefix.' . $key2] = 'required';
                                //  $validationArray['option.' . $key . '.option_value_price.' . $key2] = 'required';

                            }
                        }else{
                            $validationArray['option.' . $key . '.option_value_name_ar'] = 'required';
                            $ValidateNames['option.' . $key . '.option_value_name_ar'] =__('Option\' Values').' '.__('of').' '.__('option'). $key ;
                        }

                    }
                }
            }
        }

        $validator = Validator::make($data, $validationArray);
        $validator->setAttributeNames($ValidateNames);

        if ($validator->errors()->any()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }


        if (empty($data['name_en']))
            $data['name_en'] = $data['name_ar'];
        if (empty($data['description_en']))
            $data['description_en'] = $data['description_ar'];
        $itemData = [
            'name_ar' => $data['name_ar'],
            'name_en' => $data['name_en'],
            'description_ar' => $data['description_ar'],
            'description_en' => $data['description_en'],
            'price' => $data['price'],
            'stage_id' => $data['stage_id'],
            'quantity' => $data['quantity'],
            'lat' => (isset($data['lat'])) ? $data['lat'] : $item->lat,
            'lng' => (isset($data['lng'])) ? $data['lng'] : $item->lng,
            'slug_ar' => create_slug($data['name_ar'], $item->id),
            'slug_en' => create_slug($data['name_en'], $item->id)

        ];


        if ($item->update($itemData)) {

            if (isset($data['temp_id'])) {
                Upload::where('temp_id', $data['temp_id'])->update([
                    'model_type' => 'App\Models\Item',
                    'model_id' => $item->id,
                    'temp_id' => ''
                ]);
            }

            if (!empty($data['attribute']))
                $data_attribute = $data['attribute'];

            if (!empty($item_category_attributes)) {
                $item->select_attribute()->delete();
                $item_attribute['model_id'] = $item->id;
                $item_attribute['model_type'] = 'App\Models\Item';
                foreach ($item_category_attributes as $key => $attribute) {
                    $item_attribute['value'] = "";

                    $item_attribute['attribute_id'] = $attribute->id;
                    if (in_array($attribute->type, ['text', 'textarea', 'date', 'datetime', 'location','number'])) {
                        $item_attribute['value'] = $data_attribute[$attribute->id];
                        // dd($item_attribute);
                        SelectedAttributeValues::create($item_attribute);
                    } elseif ($attribute->type == 'file') {
                        $item_attribute['value'] = $data_attribute[$attribute->id]->store('users/' . date('y') . '/' . date('m'));
                    } elseif ($attribute->type == 'select') {
                        $item_attribute['attribute_value_id'] = $data_attribute[$attribute->id];
                        SelectedAttributeValues::create($item_attribute);
                    } elseif ($attribute->type == 'multi_select') {
//                        dd($attribute->id);
                        //  pd($data['attribute']);
                        foreach ($data_attribute[$attribute->id] as $key2 => $value) {
                            $item_attribute['attribute_value_id'] = $value;
                            SelectedAttributeValues::create($item_attribute);
                        }
                    }

                }
            }
            if (!empty($data['option'])) {
                $item->option()->delete();
                foreach ($data['option'] as $key => $option) {
                    $item_option['item_id'] = $item->id;
                    $item_option['status'] = 'active';
                    $item_option['sort'] = $option['option_sort'];
                    if ($option['template_option'] == 'template') {
                        // select from template
                        $template_option = TemplateOption::find($option['id']);

                        $item_option['name_ar'] = $template_option->name_ar;
                        if(empty($item_option['name_en'])){
                            $item_option['name_en'] = $template_option->name_ar;
                        }else{
                            $item_option['name_en'] = $template_option->name_en;
                        }
                        //$item_option['name_en'] = $template_option->name_en;
                        $item_option['type'] = $template_option->type;
                        $item_option['is_required'] = $template_option->is_required;

                        if ($itemOption = ItemOption::create($item_option)) {
                            $item_option_value['item_option_id'] = $itemOption->id;
                            foreach ($template_option->values as $value) {
                                $item_option_value['name_ar'] = $value['name_ar'];
                                if(empty($item_option_value['name_en'])){
                                    $item_option_value['name_en'] = $value['name_ar'];
                                }else{
                                    $item_option_value['name_en'] = $value['name_en'];
                                }
                                $item_option_value['price_prefix'] = $value['price_prefix'];
                                $item_option_value['price'] = $value['price'];

                                ItemOptionValues::create($item_option_value);
                            }

                        }


                    } elseif ($option['template_option'] == 'new') {          // new option
                        $item_option['name_ar'] = $option['option_name_ar'];
                        if(empty( $option['option_name_en'])){
                            $item_option['name_en'] = $option['option_name_ar'];
                        }else{
                            $item_option['name_en'] = $option['option_name_en'];
                        }
                        //$item_option['name_en'] = $option['option_name_en'];
                        $item_option['type'] = $option['option_type'];
                        $item_option['is_required'] = $option['option_is_required'];
                        if ($itemOption = ItemOption::create($item_option)) {
                            $item_option_value['item_option_id'] = $itemOption->id;
                            if (in_array($item_option['type'], ['select', 'radio', 'check'])) {

                                foreach ($option['option_value_name_ar'] as $key2 => $value) {

                                    $item_option_value['name_ar'] = $option['option_value_name_ar'][$key2];
                                    if(empty($option['option_value_name_en'][$key2])){
                                        $item_option_value['name_en'] = $option['option_value_name_ar'][$key2];
                                    }else{
                                        $item_option_value['name_en'] = $option['option_value_name_en'][$key2];
                                    }
                                    $item_option_value['price_prefix'] = (isset($option['option_value_price_prefix'][$key2]))? $option['option_value_price_prefix'][$key2] : '+';
                                    $item_option_value['price'] = (isset($option['option_value_price'][$key2]))? $option['option_value_price'][$key2] : 0;

                                    ItemOptionValues::create($item_option_value);
                                }
                            }

                        }

                    }
                }
            }


            return ['status' => true, 'data' => $item, 'msg' => __('Item is created')];


        }


    }

    public function Deal(Array $data)
    {

        $validationArray = [
            'item_id' => 'required|exists:items,id'
        ];

        $validator = Validator::make($data, $validationArray);

        if ($validator->errors()->any()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }

        $item = Item::find($data['item_id']);
        $options = $item->option; //array_column($item->option,'is_required','id');


        if (!empty($options)) {
            foreach ($options as $option) {
                if ($option->is_required == 'yes' && empty($data['options'][$option->id])) {
                    $validationArray['option.' . $option->id] = 'required';
                    $validationnames['option.' . $option->id] = $option->name_ar;
                }
            }
        }

        $validator = Validator::make($data, $validationArray);
        if(isset($validationnames))
        $validator->setAttributeNames($validationnames);

        if ($validator->errors()->any()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }

        if (Auth::id() == $item['user_id']) {
            return ['status' => false, 'msg' => __('You cannot Deal Your Item')];
        }

        $deal = Deal::Create([
            'item_id' => $data['item_id'],
            'item_owner_id' => $item['user_id'],
            'user_id' => Auth::id(),
            'status' => 'pending',
            'total_price' => (!empty($item->price))?$item->price:0,
            'notes' => isset($data['notes']) ? $data['notes'] : '',
        ]);

        //update count deals
        $new_count_item_deals = Deal::where('item_id',$data['item_id'])->count();
        Item::find($data['item_id'])->update(['deals'=>$new_count_item_deals]);


        if (!empty($options)) {
            foreach ($options as $option) {
                $dealOptions = [];
                if (isset($data['options'][$option->id])) {

                    if ($option->type == 'text' || $option->type == 'textarea') {
                        $dealOptions['value'] = $data['options'][$option->id];
                    } else {
                        $dealOptions['item_option_value_id'] = $data['options'][$option->id];
                    }

                    $dealOptions['item_option_id'] = $option->id;
                    $dealOptions['deal_id'] = $deal->id;
                    DealOptionValue::create($dealOptions);
                }
            }
        }

        return ['status' => true, 'data' => [], 'msg' => __('Deal is created')];


    }

    public function ValidationError($validation, $message)
    {
        $errorArray = $validation->errors()->messages();

        $data = array_column(array_map(function ($key, $val) {
            return ['key' => $key, 'val' => implode('|', $val)];
        }, array_keys($errorArray), $errorArray), 'val', 'key');

        return [
            'status' => false,
            'msg' => implode("\n", array_flatten($errorArray)),
            'data' => $data
        ];

    }

    public  function file_force_contents($dir, $contents){
        $parts = explode('/', $dir);
        $file = array_pop($parts);
        $dir = '';

        foreach($parts as $part) {
            if (! is_dir($dir .= "{$part}/")) mkdir($dir ,0777, true );
        }

        return file_put_contents("{$dir}{$file}", $contents,LOCK_EX);
    }

}



/*
public function index(Request $request,$lang = 'ar')
{


    $data = [
        'company_name' => 'jazimaa',
        'company_business' => 'social media',
        'user_job_id' => 1,
        'type' => 'company',
        'firstname' => 'sdsad',
        'lastname' => 'sadsad',
        'email' => 'amrbdreldin@yahoo.com',
        'password' => '12341234',
        'gender' => 'male',
        'phone' => '012546546',
        'mobile' => '0101477886',
        'mobile2' => '',
        'mobile3' => '',
        'area_id' => 1,
        'interisted_categories' => [1, 2, 3],
        'address' => 'sdasd',
        'facebook' => '',
        'youtube' => '',
        'linkedin' => '',
        'instgram' => 'sdf sdf ',
        'google' => 'fsdfsdf ',
        'location' => '',
        'about' => 'fdsf sd fsdf sdf sdfsd sd ',
        'image' => '',
        'attribute[1]' => 'sddsds',
        'attribute[3]' => 2,
        'attribute[4]' => [3, 4],

    ];

    $user = new Create();
    pd($user->User($data));
}*/

?>