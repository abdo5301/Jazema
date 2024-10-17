<?php

namespace App\Modules\Website;

use App\Libs\Create;
use App\Models\Attribute;
use App\Models\Wishlist;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\ItemTypes;
use App\Models\TemplateOption;
use App\Models\Upload;
use App\Models\User;
use App\Models\Rank;
use App\Models\Relations;
use App\Models\Stage;
use App\Models\Email;
use App\Models\UserJob;
use App\Models\SelectedAttributeValues;
use App\Models\AttributeValues;
use App\Models\Deal;
use App\Mail\SendMail;

use Illuminate\Support\Facades\Mail;
use App\Notifications\UserNotification;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

class UserController extends WebsiteController
{

    public function __construct()
    {

        $this->middleware(['auth:web'])->except(['profile', 'register', 'getAttributes', 'login']);
    }


    public function searchUser(Request $request){
        //dd($request);
        $users = User::where('firstname', 'like', '%' . $request->search . '%')
            ->orWhere('lastname', 'like', '%' . $request->search . '%')
            ->orWhere('email', 'like', '%' . $request->search . '%')
            ->get();
        //$users = $users->where('id','!=',auth()->id());
        foreach ($users as $key=>$user){
            $user->FullName = $user->firstname.' '.$user->lastname;
            $user->image = img($user->image);
            $user->items_count = Item::where(['user_id'=>$user->id,'owner_user_id'=>0])->count();
            if($user->rank == null)
             $user->rank = 0;
            $user->about = str_limit($user->about,180);
        }
        $data = ['users' => $users];
        return json_encode($data);
    }


    //Items

    public function addItems()
    {

        $ItemCategory = ItemCategory::get();
        $this->viewData['ItemCategory'] = $ItemCategory;

        $itemType = ItemTypes::get();
        $this->viewData['ItemTypes'] =  $itemType;

        if (!empty(old('temp_id'))) {
            $this->viewData['temp_id'] = old('temp_id');
            $this->viewData['temp_image'] = Upload::where('temp_id', old('temp_id'))->get();
        } else {
            $this->viewData['temp_id'] = md5(uniqid() . time() . rand() . rand(1, 999999));
        }

        $stages = Stage::where('user_id',auth()->id())->get();
        $this->viewData['stages'] =  $stages;

        $this->viewData['pageTitle'] = __('Create Item');

       // $this->viewData['user'] = Auth::user();
       // dd( $this->viewData);

        return $this->view('user.add-item', $this->viewData);
    }

    public function storeItems(Request $request)
    {
        $theRequest = $request->all();
        $theRequest['name_en'] = $request->name_ar;
        $theRequest['description_en'] = $request->description_ar;
        $theRequest['status'] = 'active';
        $create = new Create();

        return $create->Item($theRequest, \DataLanguage::get());

    }

    public function editItem(Request $request)
    {
        $RequestData = ['id'=>request()->segment(3)];
        $validator = Validator::make($RequestData, [
            'id' => 'required|exists:items,id'
        ]);

        if ($validator->errors()->any()) {
            abort(404);
        }


        $ItemCategory = ItemCategory::get();
        $this->viewData['ItemCategory'] = $ItemCategory;

        $itemType = ItemTypes::get();
        $this->viewData['ItemTypes'] =  $itemType;

        if (!empty(old('temp_id'))) {
            $this->viewData['temp_id'] = old('temp_id');
            $this->viewData['temp_image'] = Upload::where('temp_id', old('temp_id'))->get();
        } else {
            $this->viewData['temp_id'] = md5(uniqid() . time() . rand() . rand(1, 999999));
        }

        $stages = Stage::where('user_id',auth()->id())->get();
        $this->viewData['stages'] =  $stages;

        $this->viewData['pageTitle'] = __('Create Item');


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
            abort(404);
        }

        $this->viewData['item'] = $Item;
       // dd($Item);
        return $this->view('user.edit-item', $this->viewData);
    }

    public function editItemAction(Request $request){
        $data = $request->all();

        $create = new Create();

        return $create->editItem($data, \DataLanguage::get());

    }

    function get_old_item_options(Request $request){

        $Item = Auth::user()->items()
            ->select('*')
            ->with([ 'option' => function ($q) {
                $q->where('status', 'active')->orderBy('sort', 'asc')->get();
            }, 'option.values' => function ($q) {
                $q->where('status', 'active')->get();
            }])->find($request->id);

        if(empty($Item)){
            return ['status' => false,'msg'=>'No Item found'];
        }

        if(empty($Item->option))
            return ['status' => false,'msg'=>'No option found'];
        else
            //pd($Item->option);
            return ['status' => true, 'data'=>$Item->option];


    }



    function get_old_item_attributes(Request $request){

        $Item = Auth::user()->items()
            ->select('*')
            ->with([ 'select_attribute.values' => function ($q) {
                $q->orderBy('sort', 'asc')->get();
            }, 'select_attribute.Attribute.values' => function ($q) {
                $q->orderBy('sort', 'asc')->get();
            }])->find($request->id);

        if(empty($Item)){
            return ['status' => false,'msg'=>'No Item found'];
        }

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
                            $value_id = $attribute->values->id;
                            $value = $attribute->values->name_ar;
                        }
                    }else {
                        $value = $attribute['value'];
                    }
                }
                if($row->values->isNotEmpty()) {
                    $values = $row->values;
                }

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

        if(empty($selected_attributes_handled))
             return ['status' => false];
        else
            return ['status' => true, 'data'=>$selected_attributes_handled];

    }


    public function deleteItems($id)
    {
        $item = Item::findOrFail($id);
        if ($item->user_id != \auth()->id())
            abort(404);
        $item->delete();
        return ['status' => true, 'data' => []];
    }

    public function upload_image(Request $request)
    {

        $validator = Validator::make($request->toArray(), [
            'uploadfile' => 'image|required',

        ]);

        if ($validator->errors()->all()) {
            return array('success' => false, 'msg' => $validator->errors()->all());
        }

        $file = $request->uploadfile;

        $data = [
            'path' => $file->store('product'),
            'title' => '',
            'temp_id' => $request->temp_id
        ];
        $upload = Upload::create($data);

        if ($upload) {
            $path = asset('storage/' . $data['path']);
            return array('success' => true, 'data' => ['title' => $request->image_title, 'path' => $path, 'image_id' => $upload->id]);
        } else {
            return array('success' => false, 'msg' => 'ERROR');
        }
    }

    public function remove_image(Request $request)
    {
        $image = Upload::where('id', $request->id)->first();
        if ($image->delete()) {
            return ['status' => true];
        } else {
            return ['status' => false];
        }
    }

    public function destroy(Item $item, Request $request)
    {

        $item->delete();
        if ($request->ajax()) {
            return ['status' => true, 'msg' => __('Product has been deleted successfully')];
        } else {
            redirect()
                ->route('system.item.index')
                ->with('status', 'success')
                ->with('msg', __('This product has been deleted'));
        }
    }

    public function merchantOptions(Request $request)
    {
       $template_option = TemplateOption::select('id', 'name_' . \DataLanguage::get() . ' as name');
        if ($request->user_id) {
            $template_option->where('user_id', $request->user_id);
        }
        if ($request->category_id) {
            $template_option->Where('item_category_id', $request->category_id);
        }
        $template_option = $template_option->get()->toArray();
//         dd($template_option->toSql());
        $return = '<option value="template" >' . __('Select Option') . '</option>';
        $return .= '<option value="new" >' . __('New Option') . '</option>';
        if (!empty($template_option)) {
            foreach ($template_option as $key => $row) {
                $return .= '<option value="' . $row['id'] . '" >' . $row['name'] . '</option>';
            }
        }
        return $return;
    }

    public function getItemAttributes(Request $request)
    {

        if(!empty($request->category_id) && !empty($request->item_type_id)){
            $type_id = $request->item_type_id;  $category_id = $request->category_id;
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

    public function getItemTypes(Request $request)
    {
        $asd = explode("\n", setting('item_type_ids'));
        if (in_array((int)$request->item_type_id, $asd)) {
            return ['status' => true, 'data' => ''];
        } else {
            return ['status' => false, 'data' => ''];
        }
    }




    //Profile & Auth

    public function editProfile()
    {

        $user = Auth::user();
        $interisted_categories = ItemCategory::select('*','name_ar as name')->whereIn('id', explode(',', $user->interisted_categories))->get();
        $all_categories = ItemCategory::all()->toArray();


        //$this->viewData['userJobs'] = array_column(UserJob::get(['id', 'name_' . \DataLanguage::get() . ' as name'])->toArray(), 'name', 'id');
        $this->viewData['userJob'] = UserJob::find($user->user_job_id);
       // $this->viewData['user'] = $user;
        $this->viewData['interisted_categories'] = $interisted_categories;
        $this->viewData['interisted_categories_ids_array'] = explode(',', $user->interisted_categories);
        //$this->viewData['stages'] = $user->stages;
        $this->viewData['all_categories'] = $all_categories;

        // dd($this->viewData);
        return $this->view('user.edit-profile', $this->viewData);
    }

    public function get_old_user_attributes(){

        $user = Auth::user();
        $job_attribures =  $user->userJob->attributes;
        $selected_attributes = $user->select_attribute()->with(['values','Attribute.values'])->get();
        $selected_attributes_handled = [];

        foreach ($job_attribures as $row){
            $values = [];
            $value_id=0;
            $value = '';

            foreach ($selected_attributes as $attribute){
                if($row->id == $attribute->attribute_id){
                    if (!empty($attribute->attribute_value_id)) {
                        if($row->type == 'multi_select'){
                            $selected_values = SelectedAttributeValues::where(['attribute_id'=>$attribute->attribute_id,
                                'model_id'=>$user->id,'model_type'=>'App\Models\User'])
                                ->with('values')->get(['attribute_value_id']);
                            $value_id = array_column($selected_values->toArray(),'attribute_value_id');
                            $value = implode(',',array_column(AttributeValues::whereIn('id',$value_id)->get(['name_ar'])->toArray(),'name_ar'));
                            $value_id = implode(',',$value_id);
                        }else  {
                            $value_id = $attribute->attribute_value_id;
                            foreach ($attribute->attribute->values as $val ){
                                if($val->id == $attribute->attribute_value_id) {
                                    $value = $val->name_ar;
                                }
                            }
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
                'selected_value' => (!empty($value_id))? $value_id : '',
                'values' => $values,
            ];
        }

        if (empty($selected_attributes_handled))
            return ['status' => false];

        return ['status' => true, 'data' => $selected_attributes_handled];

    }

    public function updateProfile(Request $request, User $user)
    {
        $theRequest = $request->all();
       // dd($theRequest);
        $create = new Create();
        return $create->EditUserProfile_abdo($theRequest, \DataLanguage::get());
    }

    public function myItems(Request $request)
    {
//        dd(Auth::user()->notifications()->orderBy('created_at','DESC')->get());

        if ($request->getData) {

            $offset = $request->offset;
            $stage_id = null;
            if ($request->has('stage_id'))
                $stage_id = $request->stage_id;
            $items = ActiveItemsForUser(setting('item_per_request'), ['user_id' => Auth::id()], $offset, $stage_id);
            // dd($items);

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
        $categories = ItemCategory::whereIn('id', explode(',', Auth::user()->interisted_categories))->get();
        $this->viewData['categories'] = $categories;
        $this->viewData['user'] = Auth::user();
        $this->viewData['auth'] = true;
        $this->viewData['stages'] = Auth::user()->stages;
        return $this->view('user.profile', $this->viewData);

    }

    public function profile(Request $request)
    {
        $user = User::where('slug', request()->segment(3))->first();

        if (!$user)
            abort(404);

        if ($request->getData) {

            $offset = $request->offset;

            $stage_id = $request->stage_id;

            $items = ActiveItemsForUser(setting('item_per_request'),['user_id' => $user->id],$offset,$stage_id );
            $profile_items = false;
            if(auth()->check() && $user->id == auth()->user()->id){
                $profile_items = true;
            }

            if ($items->isNotEmpty()) {
                $drowedItems = [];

                foreach ($items as $item) {
                    $drowedItems[] = DrowItem($item,$profile_items);
                }
                return json(true, $drowedItems);
            } else {
                return json(false);
            }
        }


        $job_attribures =  $user->userJob->attributes;
        $selected_attributes = $user->select_attribute()->with(['values','Attribute.values'])->get();
        $selected_attributes_handled = [];

        foreach ($job_attribures as $row){
            $values = [];
            $value_id=0;
            $value = '';

            foreach ($selected_attributes as $attribute){
                if($row->id == $attribute->attribute_id){
                    if (!empty($attribute->attribute_value_id)) {
                        if($row->type == 'multi_select'){
                            $selected_values = SelectedAttributeValues::where(['attribute_id'=>$attribute->attribute_id,
                                'model_id'=>$user->id,'model_type'=>'App\Models\User'])
                                ->with('values')->get(['attribute_value_id']);
                            $value_id = array_column($selected_values->toArray(),'attribute_value_id');
                            $value = implode(',',array_column(AttributeValues::whereIn('id',$value_id)->get(['name_ar'])->toArray(),'name_ar'));
                            $value_id = implode(',',$value_id);
                        }else  {
                            $value_id = $attribute->attribute_value_id;
                            foreach ($attribute->attribute->values as $val ){
                                if($val->id == $attribute->attribute_value_id) {
                                    $value = $val->name_ar;
                                }
                            }
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
                'selected_value' => (!empty($value_id))? $value_id : '',
                'values' => $values,
            ];
        }


            $followRelation = Relations::where(['user_id'=>Auth::id(),'to_user_id'=> $user->id,'type'=> 'follow'])->first();

            $friendRelation = Relations::where(['type'=> 'friend'])->where(function ($q) use ($user) {
                $q->where(['user_id' => Auth::id(), 'to_user_id'=> $user->id]);
            })->orWhere(function ($q) use ($user) {
                $q->where(['to_user_id' => Auth::id(), 'user_id'=> $user->id]);
            })->first();

        if(auth()->check() && $user->id != auth()->user()->id) {
            $user->update(['views' => ($user->views + 1)]);
        }

        $categories = ItemCategory::whereIn('id', explode(',', $user->interisted_categories))->get();
        $this->viewData['user'] = $user;
        $this->viewData['categories'] = $categories;
        $this->viewData['auth'] = false;
        $this->viewData['job_attr'] = $selected_attributes_handled;
        $this->viewData['user_follow'] = $followRelation;
        $this->viewData['user_friend'] = $friendRelation;
        //dd($this->viewData);

        return $this->view('user.profile', $this->viewData);

    }

    public function register(Request $request){

        $theRequest = $request->all();


        $data = [];
        if(isset($theRequest)){
            $data = $theRequest;

            if(isset($theRequest['job_attributes']) && !empty($theRequest['job_attributes'])){
                unset($data['job_attributes']);
                foreach ($theRequest['job_attributes'] as $row){
                    // $data['attribute['.$row["id"].']']= $row['value'];
                    $attribute = Attribute::find($row['id']);
                    if(empty($attribute)) {
                        return ['msg' =>__('Attribute '.$row['id'].' Not Found '),'status'=>false];
                    }else{
                        if($attribute->type == 'multi_select'){
                            $data['attribute['.$row["id"].']']= explode(',',$row['value']);
                        }else {
                            $data['attribute[' . $row["id"] . ']'] = $row['value'];
                        }
                    }
                }
            }
        }

        $newUser = new Create();

        return $newUser->Register($data, \DataLanguage::get());

    }

    function getAttributes(Request $request)
    {
        if (!$request->user_job_id)
            return ['status' => false, 'msg' => __('please select user Job')];
        $attribute = Attribute::select(['*', 'name_' . \DataLanguage::get() . ' as name'])->orderBy('sort')
            ->where(['model_id' => $request->user_job_id, 'model_type' => 'App\Models\UserJob'])->with(['values' => function ($q) {
                $q->select(['*', 'name_' . \DataLanguage::get() . ' as name'])->get();
            }])->get();

        if (empty($attribute->toArray()))
            return ['status' => false];

        return ['status' => true, 'data' => $attribute];
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request->login_email)->first();
        if ((Auth::guard('web')->attempt(['email' => $request->login_email, 'password' => $request->login_password, 'status' => 'active'], $request->remember))) {
            //if successful redirect to Profile
            return ['status' => true,'redirect'=>route('web.user.profile',[auth()->user()->slug])]; //redirect()->route('web.user.myprofile');
        }
        return ['status' => false, 'msg' => __('Wrong Login Info')];
//            back()
//            ->withInput($request->only('email', 'remember'))
//            ->with('msg', __('Wrong email or password'));
    }

    protected function guard()
    {
        return Auth::guard('web');
    }

    protected function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();
        return redirect(route('web.index'));
    }



    //mails

    public function sendMail(Request $request)
    {

            $validationArray = [
                'subject' => 'required',
                'message' => 'required',
                'id' => 'required|exists:users,id',
            ];

        $validator = Validator::make($request->all(), $validationArray);
        if ($validator->fails()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }
        if(auth()->id() == $request->id){
            return ['status' => false ,'msg'=>'You can\'t mail your self by the same Email'];
        }

        $data = [];

        $data['email'] = auth()->user()->email;
        $data['name'] = auth()->user()->full_name;
        $data['from_id'] = auth()->id();
        $data ['subject'] = $request->subject;
        $data ['message'] = $request->message;
        $to_user = User::find($request->id);
        //dd($to_user);
        if(!empty($to_user)){
            $data['to_id'] = $to_user->id;
        }else{
            return ['status' => false ,'msg'=>'valid user'];
        }
       // dd($data);
       //Mail::to($to_user)->send(new SendMail($data));
           if (Email::create($data)) {
               $new_sent_count = count(Auth::user()->sentEmails()->whereNull('deleted_from')->get());
               return ['status' => true,'sent'=>$new_sent_count];
           }


        $errors[0] = 'Mail send Error Please try again later';
        return ['status' => false,'data'=>$errors];
    }


    public function viewMail(Request $request,$id){

        $email = Email::find($id);
        if(!empty($email)){
            return['status' => true,'data'=>$email];
        }else{
            return['status' => false,'msg' => 'No data found'];
        }

    }

    public function userMails()
    {
        $this->viewData['sent'] = Auth::user()->sentEmails()->whereNull('deleted_from')->get();
        $this->viewData['received'] = Auth::user()->receivedEmails()->whereNull('deleted_to')->get();

        $trashed_received = Auth::user()->receivedEmails()->whereNotNull('deleted_to')->get()->toArray();
        $trashed_sent = Auth::user()->sentEmails()->whereNotNull('deleted_from')->get()->toArray();
        $this->viewData['trashed'] = array_merge($trashed_received, $trashed_sent);
        $this->viewData ['trashed_received'] = $trashed_received;
        $this->viewData ['trashed_sent'] = $trashed_sent;
//        dd($this->viewData['trashed']);
        return $this->view('user.mail', $this->viewData);
    }

    public function deleteEmail(Request $request, Email $email)
    {
        if (Auth::id() == $email->to_id) {
            $email->update(['deleted_to' => Carbon::now()]);
        } elseif (Auth::id() == $email->from_id) {
            $email->update(['deleted_from' => Carbon::now()]);
        } else {
            return ['status' => false, 'msg' => __('Can\'t Delete This Email ')];
        }
        if ($request->ajax()) {
            $data['sent'] = count(Auth::user()->sentEmails()->whereNull('deleted_from')->get());
            $data['received'] = count(Auth::user()->receivedEmails()->whereNull('deleted_to')->get());

            $trashed_received = Auth::user()->receivedEmails()->whereNotNull('deleted_to')->get()->toArray();
            $trashed_sent = Auth::user()->sentEmails()->whereNotNull('deleted_from')->get()->toArray();
            $data['trashed'] = count(array_merge($trashed_received, $trashed_sent));

            return ['status' => true, 'data'=>$data,'msg' => __('Email has been deleted successfully')];
        } else {
            redirect()
                ->back()
                ->with('status', 'success')
                ->with('msg', __('Email has been deleted'));
        }
    }







    //deals

    public function createDeal(Request $request)
    {

        $data = $request->all();

        if (isset($data->options)) {
            foreach ($data->options as $row) {
                $data['options'][$row['id']] = $row['value'];
            }

        }

        //dd($data);
        $create = new Create();
        return $create->Deal($data);

    }

    function get_item_deal_options(Request $request){

        if(!isset($request->id))
            return ['status' => false,'msg'=>'No Item ID found'];

        $Item = Item::where('id', $request->id)
            ->select('*')
            ->with([ 'option' => function ($q) {
                $q->where('status', 'active')->orderBy('sort', 'asc')->get();
            }, 'option.values' => function ($q) {
                $q->where('status', 'active')->get();
            }])->first();

        if(empty($Item)){
            return ['status' => false,'msg'=>'No Item found'];
        }

        if(empty($Item->option))
            return ['status' => false,'msg'=>'No option found'];
        else
            //pd($Item->option);
            return ['status' => true, 'data'=>$Item->option];


    }

    public function deals(Request $request)
    {
//        dd($request->all());
        if ($request->id) {
//        dd($request->all());
            $RequestData = $request->all();
            $validator = Validator::make($RequestData, [
                'id' => 'required|exists:deals,id',
                'status' => 'required|in:pending,inprogress,stopping,pause,done',
            ]);
            if ($validator->errors()->any()) {
                return $this->ValidationError($validator, __('Validation Error'));
            }
            $deal = Auth::user()->dealIn()->find($request->id);
            if ($deal->status == 'done') {
                return ['status' => false, 'msg' => __('Cannot change Deal has been Done')];
//                return redirect()
//                    ->route('web.user.deals')
//                    ->with('status', 'danger')
//                    ->with('msg', __('Cannot change Deal has been Done'));
            }
            if ($deal->update(['status' => $request->status])) {
           $deal->user->notify(
                    (new UserNotification([
                        'title'         => __('Change Staff Password'),
                        'description'   => __(':username has been change :merchantname\'s password'),
                        'url'           => route('web.user.deals')
                    ]))
                 ->delay(5));
                return ['status' => true, 'msg' => __('Status Changed')];
            }
        }


        $this->viewData['dealsIn'] = Auth::user()->dealIn()->with(['user', 'owner', 'options' => function ($q) {
            $q->with('item_option','item_option_values')->get();
        }, 'item' => function ($q) {
            $q->with('upload')->select('*', 'name_' . \DataLanguage::get() . ' as name')->get();
        }])->get();


//        dd( $this->viewData['dealsIn']->where('id',225)->first()->options);
//        dd( $this->viewData['dealsIn']->where('id',210)->first());
        $this->viewData['dealsOut'] = Auth::user()->dealOut()->with(['user', 'owner', 'item' => function ($q) {
            $q->with('upload')->select('*', 'name_' . \DataLanguage::get() . ' as name')->get();
        }])->get();

        return $this->view('user.deals', $this->viewData);
    }

    public function updateStatus($id, Request $request)
    {
        $RequestData = $request->only(['status']);;
        $this->validate($request, [
            'status' => 'required|in:pending,inprogress,stopping,pause,done',
        ]);
//        if($validator->errors()->any()){
//            return $this->ValidationError($validator,__('Validation Error'));
//        }
        $deal = Auth::user()->dealIn()->find($id);
        if ($deal->status == 'done') {
//            return redirect()->back()->with('error',__('Cannot change Deal has been Done'));
            return redirect()
                ->route('web.user.deals')
                ->with('status', 'danger')
                ->with('msg', __('Cannot change Deal has been Done'));
        }
        if ($deal->update(['status' => $request->status])) {

            return redirect()
//                ->route('web.user.deals')
                ->back()
                ->with('status', 'success')
                ->with('msg', __('Status Changed'));
        }


    }

    public function rankUser(Request $request){

        $data['user_id'] = auth()->user()->id;
        $data['model_type'] = 'App\Models\User';
        $data['model_id'] = $request->owner_id;
        $data['deal_id'] = $request->deal_id;
        $data['rank'] = $request->rank;
        //dd($data);
        $check_old_rate = Rank::where(['model_id'=>$data['model_id'],'user_id'=>$data['user_id'],'model_type'=>$data['model_type']])
        ->first();
        if(!empty($check_old_rate)){
            return ['status' => false,'msg'=>'You already rated that Owner before'];
        }
        if (Rank::create($data)) {
            $new_rank = rank_calculation($data['model_id'],$data['model_type']);
            $user = User::find($data['model_id']);

            if($user->update(['rank'=>$new_rank]))
                return ['status' => true,'new_rank'=>$new_rank];
        }

        return ['status' => false ,'msg'=>'Error please try again!'];
    }

    public function get_deal_options(Request $request){

       $deal = Deal::where('id',$request->deal_id)->with(['options' => function ($q) {
            $q->with('item_option','item_option_values')->get();
        }])->first();
// dd($deal);
       if(!empty($deal)){
           return['status'=>true,'data'=>$deal];
       }

        return['status'=>false];


    }



    //stages

    public function userStage()
    {
        $this->viewData['stages'] = Auth::user()->stages;
        return $this->view('user.stages', $this->viewData);
    }

    public function addStage()
    {
        //$this->viewData['user'] = Auth::user();
        $categories = ItemCategory::whereIn('id', explode(',', Auth::user()->interisted_categories))->get();
        $this->viewData['categories'] = $categories;
        return $this->view('user.add-stage',$this->viewData);
    }

    public function storeStage(Request $request)
    {

        $validationArray = [
            'name' => 'required',
            'show_to_friends' => 'required|in:yes,no',
            'show_to_followers' => 'required|in:yes,no',
            'show_to_public' => 'required|in:yes,no',
        ];
        $validator = Validator::make($request->all(), $validationArray);
        if ($validator->fails()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }
        $data = $request->all();
        $data['user_id'] = \auth()->id();
        if ($stage = Stage::create($data)) {
            return ['status' => true, 'data' => $stage, 'msg' => __('stage is created')];
        } else {
            return ['status' => false, 'msg' => __('can\'t add stage')];
        }
    }

    public function deleteStage(Stage $stage, Request $request)
    {
        if ($stage->user_id != \auth()->id()) {
            abort(404);
        }
        $stage->delete();
        if ($request->ajax()) {
            return ['status' => true, 'msg' => __('Stage has been deleted successfully')];
        } else {
            redirect()
                ->back()
                ->with('status', 'success')
                ->with('msg', __('Stage has been deleted'));
        }
    }

    public function editStage($id)
    {
        $this->viewData['stage'] = Stage::find($id);
        return $this->view('user.edit-stage', $this->viewData);
    }

    public function updateStage(Request $request, Stage $stage)
    {
        $validationArray = [
            'name' => 'required',
            'show_to_friends' => 'required|in:yes,no',
            'show_to_followers' => 'required|in:yes,no',
            'show_to_public' => 'required|in:yes,no',
        ];
        $validator = Validator::make($request->all(), $validationArray);
        if ($validator->fails()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }
        $data = $request->all();
//        $data['user_id'] = \auth()->id();
        if ($stage->update($data)) {
            return ['status' => true, 'data' => $stage, 'msg' => __('stage is Updated')];
        } else {
            return ['status' => false, 'msg' => __('can\'t update stage')];
        }
    }


    //wishlist

    public function wishList(Request $request)
    {
    if(!auth()->check())
        abort(404);

        $items = wishlist::select([
            'id',
            'user_id',
            'item_id'
        ])->where('user_id',auth()->id())->with(['item' => function ($q) {
            $q->select(['*', 'name_' . \DataLanguage::get() . ' as name', 'slug_' . \DataLanguage::get() . ' as slug',
                'description_' . \DataLanguage::get() . ' as description'])->with('upload')->get();
        },])->get()->toArray();
        // Get current page form url e.x. &page=1
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        // Create a new Laravel collection from the array data
        $itemCollection = collect($items);

        // Define how many items we want to be visible in each page
        $perPage = 10;

        // Slice the collection to get the items to display in current page
        $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();


        // Create our paginator and pass it to the view
        $paginatedItems = new LengthAwarePaginator($currentPageItems, count($itemCollection), $perPage);

        // set url path for generted links
        $paginatedItems->setPath($request->url());

        //pd($paginatedItems);

        return $this->view('user.wishlist', ['items' => $paginatedItems]);
    }

    public function deleteWish($id)
    {
        $wish = Wishlist::findOrFail($id);
        if ($wish->user_id != \auth()->id())
            abort(404);
        $wish->delete();
        return ['status' => true];
    }

    public function addWish(Request $request)
    {
        $user_id = \auth()->id();
        $item_id = $request->item_id;
        if(empty($item_id) || empty($user_id))
            return ['status' => false];

        $addWish = Wishlist::create(['user_id'=>$user_id,'item_id'=>$item_id]);

       if(!empty($addWish)){
           return ['status' => true];
       }else{
           return ['status' => false];
       }
    }



    //relations

    public function addRelation(Request $request) //follow or friend
    {

        $RequestData = $request->only(['user_id', 'type']);
        $validator = Validator::make($RequestData, [
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:friend,follow',
        ]);

        if ($validator->errors()->any()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }

        if(Auth::id() == $RequestData['user_id']){
            return  ['status' => false, 'msg' => __('Cannot Make Relation with Your Self')];
        }
        $user = Auth::user();

        if ($RequestData['type'] == 'follow') {
            $check = $user->following()->where('to_user_id', $RequestData['user_id'])->first();

            if (empty($check)) {
                if (Relations::create([
                    'to_user_id' => $RequestData['user_id'],
                    'type' => $RequestData['type'],
                    'user_id' => Auth::id(),
                    'status' => 'accept',
                ])) {
                    return ['status' => true, 'msg' => __('Following')];
                } else {
                    return ['status' => false, 'msg' => __('Cannot Follow Now')];
                }
            }else{
                return ['status' => false, 'msg' => __('You are Already Following')];
            }
        } else {
            $check = Relations::where(['type'=> 'friend'])->where(function ($q) use ($RequestData) {
                $q->where(['user_id' => Auth::id(), 'to_user_id'=> $RequestData['user_id']]);
            })->orWhere(function ($q) use ($RequestData) {
                $q->where(['to_user_id' => Auth::id(), 'user_id'=> $RequestData['user_id']]);
            })->first();

            if (empty($check)) {
                if (Relations::create([
                    'to_user_id' => $RequestData['user_id'],
                    'type' => $RequestData['type'],
                    'user_id' => Auth::id(),
                    'status' => 'pending',
                ])) {
                    return ['status' => true, 'msg' => __('Friend Request Sent')];
                } else {
                    return ['status' => false, 'msg' => __('Cannot Send Request Now')];
                }
            }else{
                if($check->status == 'pending'){
                     return ['status' => false, 'msg' => __('You Request Already Sent')];
                } else {
                    return ['status' => false, 'msg' => __('You are Already Friend')];
                }
            }


        }


    }

    public function removeRelation(Request $request) //follow or friend
    {
        $RequestData = $request->only(['user_id','type']);
        $validator = Validator::make($RequestData, [
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:follow,friend',
        ]);

        if ($validator->errors()->any()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }

        $msg = '';

        if ($RequestData['type'] == 'follow') {
            $relation = Relations::where(['user_id'=>Auth::id(),'to_user_id'=> $RequestData['user_id'],'type'=> 'follow'])->first();
            $msg =  __('Successfully unfollow');
        } else {
            $relation = Relations::where(['type'=> 'friend'])->where(function ($q) use ($RequestData) {
                $q->where(['user_id' => Auth::id(), 'to_user_id'=> $RequestData['user_id']]);
            })->orWhere(function ($q) use ($RequestData) {
                $q->where(['to_user_id' => Auth::id(), 'user_id'=> $RequestData['user_id']]);
            })->first();
            if(!empty($relation) && $relation->status == 'pending'){
                $msg =  __('friendship request canceled');
            }else{
                $msg =  __('Friendship canceled');
            }

        }

        //dd($relation);

        $relation->forceDelete();

        return ['status' => true,'type'=>$RequestData['type'] ,'msg' => $msg];

    }

    public function unFriend($id, Request $request)
    {
        $relation = Relations::where(['type' => 'friend'])->where(function ($q) use ($id) {
            $q->where('user_id', auth()->id())->where('to_user_id', $id)
                ->orWhere('user_id', $id)->where('to_user_id', \auth()->id());
        })->first();

//         =  Relations::where(function ($q)use($id) {
//            $q->where('user_id', $id)->orWhere('to_user_id', $id);
//        })->where(['type' => 'friend', 'status' => 'accept']);
//        $relation->update(['status' => 'cancel']);
        $relation->forceDelete();
        if ($request->ajax()) {
            return ['status' => true, 'msg' => __('Friend has been Removed successfully')];
        } else {
            redirect()
                ->back()
                ->with('status', 'success')
                ->with('msg', __('Friend has been Removed successfully'));
        }

    }

    public function myFriends(Request $request)
    {
        $friendsOfMine = auth()->user()->friendsOfMine;
//        $this->viewData['friends'] =  $friendsOfMine->merge(auth()->user()->friendsOf);
        $friends = $friendsOfMine->merge(auth()->user()->friendsOf);
        $friends = $friends->toArray();

        $relations = Relations::select([
            'id',
            'user_id',
            'to_user_id',
            'type',
            'status'
        ])->where(function ($q) {
            $q->where('to_user_id', Auth::id());
        })->where(['type' => 'friend', 'status' => 'pending'])->with(['user' => function ($q) {
            $q->select(['id', 'firstname', 'lastname', 'image', 'slug']);
        }])->get()->toArray();
        // Get current page form url e.x. &page=1
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        // Create a new Laravel collection from the array data
        $itemCollection = collect($friends);
        $itemCollection2 = collect($relations);
        // Define how many items we want to be visible in each page
        $perPage = 10;

        // Slice the collection to get the items to display in current page
        $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
        $currentPageItems2 = $itemCollection2->slice(($currentPage * $perPage) - $perPage, $perPage)->all();

        // Create our paginator and pass it to the view
        $paginatedItems = new LengthAwarePaginator($currentPageItems, count($itemCollection), $perPage);
        $requests = new LengthAwarePaginator($currentPageItems2, count($itemCollection2), $perPage);

        // set url path for generted links
        $paginatedItems->setPath($request->url());
        return $this->view('user.friends', ['friends' => $paginatedItems,'requests'=>$requests]);
    }

//    public function friendRequest(Request $request)
//    {
//        $relations = Relations::select([
//            'id',
//            'user_id',
//            'to_user_id',
//            'type',
//            'status'
//        ])->where(function ($q) {
//            $q->where('to_user_id', Auth::id());
//        })->where(['type' => 'friend', 'status' => 'pending'])->with(['user' => function ($q) {
//            $q->select(['id', 'firstname', 'lastname', 'image', 'slug']);
//        }])->get()->toArray();
//
//
//
//        $currentPage = LengthAwarePaginator::resolveCurrentPage();
//
//        // Create a new Laravel collection from the array data
//        $itemCollection = collect($relations);
//
//        // Define how many items we want to be visible in each page
//        $perPage = 10;
//
//        // Slice the collection to get the items to display in current page
//        $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
//
//        // Create our paginator and pass it to the view
//        $paginatedItems = new LengthAwarePaginator($currentPageItems, count($itemCollection), $perPage);
//
//
//        // set url path for generted links
//        $paginatedItems->setPath($request->url());
//        return $this->view('user.friend-requests', ['friends' => $paginatedItems]);
//    }

    public function friendRequestAction(Request $request, $id, $type)
    {

        $relation = Relations::find($id)->update(['status' => $type]);
        // dd($relation);
        // ->update(['status'=>$type])
        if ($request->ajax()) {
            return ['status' => true, 'msg' => __('Friend Request has been Updated successfully')];
        } else {
            redirect()
                ->back()
                ->with('status', 'success')
                ->with('msg', __('Friend Request has been Updated successfully'));
        }
    }

    public function followers(Request $request)
    {
        $followers = Relations::where(['to_user_id' => Auth::id(), 'type' => 'follow'])->with('user')->get()->toArray();
        $following = Relations::where(['user_id' => Auth::id(), 'type' => 'follow'])->where('status','!=','cancel')->with('to_user')->get()->toArray();

//        dd($followers,$following);

        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        // Create a new Laravel collection from the array data
        $itemCollection = collect($followers);
        $itemCollection2 = collect($following);

        // Define how many items we want to be visible in each page
        $perPage = 10;

        // Slice the collection to get the items to display in current page
        $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
        $currentPageItems2 = $itemCollection2->slice(($currentPage * $perPage) - $perPage, $perPage)->all();

        // Create our paginator and pass it to the view
        $paginatedItems = new LengthAwarePaginator($currentPageItems, count($itemCollection), $perPage);
        $following = new LengthAwarePaginator($currentPageItems2, count($itemCollection2), $perPage);

        // set url path for generted links
        $paginatedItems->setPath($request->url());
        return $this->view('user.following', ['followers' => $paginatedItems,'following'=>$following]);
    }

    public function followingAction($id,Request $request)
    {
        $relation = Relations::find($id)->update(['status' => 'cancel']);
        // dd($relation);
        // ->update(['status'=>$type])
        if ($request->ajax()) {
            return ['status' => true, 'msg' => __('Following List has been Updated successfully')];
        } else {
            redirect()
                ->back()
                ->with('status', 'success')
                ->with('msg', __('Following List has been Updated successfully'));
        }
    }


}