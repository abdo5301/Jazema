<?php

function count_ranker($model_id,$model_type){
    $rank_count = \App\Models\Rank::where(['model_id'=>$model_id,'model_type'=>$model_type])->count();
    if(!empty($rank_count)){
        return $rank_count;
    }else{
        return 0;
    }
}


function rank_calculation($model_id,$model_type){
    $rank_sum = \App\Models\Rank::where(['model_id'=>$model_id,'model_type'=>$model_type])->sum('rank');
    $rank_count = \App\Models\Rank::where(['model_id'=>$model_id,'model_type'=>$model_type])->count();

    if(!empty($rank_sum) && !empty($rank_count)){
        $rank = ($rank_sum / $rank_count);
        return $rank;
    }else{
        return 0;
    }
}


    function wish_check($item_id){
        $user_id = auth()->user()->id;
        $wish_check = \App\Models\Wishlist::where(['user_id'=>$user_id,'item_id'=>$item_id])->first();
        if(!empty($wish_check)){
            return true;
        }else{
            return false;
        }
    }


    function rank_check($model_id,$model_type){
    $user_id = auth()->user()->id;
    $rank_check = \App\Models\Rank::where(['user_id'=>$user_id,'model_id'=>$model_id,'model_type'=>$model_type])->first();
        if(!empty($rank_check)){
            return true;
        }else{
            return false;
        }
    }


 function deal_check($item_id,$item_owner_id){
     $user_id = auth()->user()->id;
    $deal_check = App\Models\Deal::where(['item_id'=>$item_id,'user_id'=>$user_id,'item_owner_id'=>$item_owner_id])->get()->first();
    if(!empty($deal_check)){
        return true;
    }else{
        return false;
    }
 }

 function is_friend($user_id){
    $check =  App\Models\Relations::where(['user_id'=>auth()->id(),'to_user_id'=>$user_id])->orWhere(['to_user_id'=> auth()->id(),'user_id'=>$user_id])
     ->where(['type' => 'friend', 'status' => 'accept'])->first();
    if(!empty($check)){
        return true;
    }else{
        return false;
    }
 }

function is_follow($user_id){
    $check =  App\Models\Relations::where(['user_id'=>auth()->id(),'to_user_id'=>$user_id])
        ->where(['type' => 'follow', 'status' => 'accept'])->first();
    if(!empty($check)){
        return true;
    }else{
        return false;
    }
}



 function friends()
{
    return App\Models\Relations::where(function ($q) {
        $q->where('user_id',auth()->id())->orWhere('to_user_id', auth()->id());
    })->where(['type' => 'friend', 'status' => 'accept'])->with('user','to_user')->get();
}


function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


function short_num( $n, $precision = 1 ) {
    if ($n < 900) {
        // 0 - 900
        $n_format = number_format($n, $precision);
        $suffix = '';
    } else if ($n < 900000) {
        // 0.9k-850k
        $n_format = number_format($n / 1000, $precision);
        $suffix = 'K';
    } else if ($n < 900000000) {
        // 0.9m-850m
        $n_format = number_format($n / 1000000, $precision);
        $suffix = 'M';
    } else if ($n < 900000000000) {
        // 0.9b-850b
        $n_format = number_format($n / 1000000000, $precision);
        $suffix = 'B';
    } else {
        // 0.9t+
        $n_format = number_format($n / 1000000000000, $precision);
        $suffix = 'T';
    }
    // Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
    // Intentionally does not affect partials, eg "1.50" -> "1.50"
    if ( $precision > 0 ) {
        $dotzero = '.' . str_repeat( '0', $precision );
        $n_format = str_replace( $dotzero, '', $n_format );
    }
    return $n_format . $suffix;
}

function EchoDrowItem($item){
    echo DrowItem($item);
}

function DrowItem($item,$profile_item = false){

    return view('web.partial.item',['item'=>$item,'profile_item'=>$profile_item])->render();
}

function img($path,$temp='items',$secure = null){
    if(!empty($path))
        return asset('storage/'.$path,$secure);
    else
        return asset('storage/'.$temp.'/temp.png');


}


function ActiveItems($limit=null,$offsit = 0,$where =[],$orWhere =[],$filter = []){

    $Items = \App\Models\Item::Actives()
//        ->where('user_id',$user_id)
        ->select(['*','name_'.\DataLanguage::get().' as name','description_'.\DataLanguage::get().' as description'])
        ->with(['item_category'=>function($q){
            $q->select(['*','name_'.\DataLanguage::get().' as name','slug_'.\DataLanguage::get().' as slug']);
        },'item_type'=>function($q){
            $q->select(['*','name_'.\DataLanguage::get().' as name','slug_'.\DataLanguage::get().' as slug']);
        }]);
//    dd($Items->toSql());
    if($limit != null)
        $Items->limit($limit);
    if($offsit != 0)
        $Items->offset($offsit);
    if(!empty($where))
        $Items->where($where);
    if(!empty($orWhere))
        $Items->orWhere($orWhere);

    //$Items->orderBy('id','desc');

    if(!empty($filter)){

         if (!empty($filter['search_word'])) {
            $Items->where('name_' . \DataLanguage::get(), 'like', '%' . $filter['search_word'] . '%');
           // ->orWhere('description_' . \DataLanguage::get(), 'like', '%' . $filter['search_word'] . '%');
        }

        if (!empty($filter['search_quantity_from']) || !empty($filter['search_quantity_to'])) {
            whereBetween($Items,'quantity',$filter['search_quantity_from'],$filter['search_quantity_to']);
        }

        if (!empty($filter['search_price_from']) || !empty($filter['search_price_to'])) {
             whereBetween($Items,'price',$filter['search_price_from'],$filter['search_price_to']);
        }

        if (!empty($filter['search_type'])) {
            $type = App\Models\ItemTypes::where('slug_'.\DataLanguage::get() , $filter['search_type'])->first();
            if(!empty($type))
            $Items->where('item_type_id', $type->id);
        }

        if (!empty($filter['search_category'])) {
            $category = App\Models\ItemCategory::where('slug_'.\DataLanguage::get() , $filter['search_category'])->first();
            if(!empty($category))
            $Items->where('item_category_id', $category->id);
        }

        if (!empty($filter['search_category']) && !empty($filter['search_type']) && !empty($filter['attribute'])) {



               foreach ($filter['attribute'] as $key => $value) {
             $Items->whereHas('select_attribute',function ($q)use($value,$key){
                   if (!empty($value) && $value != '' && $value != null) {
                       $ex_key = explode('-', $key);
                       $attr_id = $ex_key[1];
                       $attr = \App\Models\Attribute::find($attr_id);
                       if (!empty($attr)) {
                           $q->where('attribute_id', $attr->id);
                               if ($attr->type == 'select') {
                                   $q->where('attribute_value_id', $value);
                               } elseif ($attr->type == 'multi_select') {
                                   foreach($value as $k => $v){
                                       $q->where('attribute_value_id', $v);
                                   }
                               }elseif ($attr->type == 'date' || $attr->type == 'datetime' || $attr->type == 'number'){
                                   $q->where('value',$value);
                               } else {
                                   $q->where('value', 'like', '%' . $value . '%');
                               }
                       }
                   }
             });
             }





        }

        if (!empty($filter['search_lat']) && !empty($filter['search_lng'])) {
            //3959  for miles  &  6371 for km
            $Items->selectRaw('( 6371 * acos( cos( radians('.$filter['search_lat'].') ) * cos( radians( `lat`) ) * cos( radians( `lng` ) - radians('.$filter['search_lng'].') )  + sin( radians('.$filter['search_lat'].') ) * sin( radians( `lat` ) )) ) AS distance ') ->havingRaw('distance < 25');
        //->orderByRaw('distance')
        }

        if (!empty($filter['search_sort_by'])) {
            if (!empty($filter['search_sort_role'])) {
               // $Items->orderBy($filter['search_sort_by'], $filter['search_sort_role']);
                $Items->orderByRaw($filter['search_sort_by'].' '.$filter['search_sort_role']);
            }else{
                $Items->orderBy($filter['search_sort_by'], 'desc');
            }
        }else{
            if (!empty($filter['search_sort_role'])) {
                $Items->orderBy('id', $filter['search_sort_role']);
            }else{
                $Items->orderBy('id', 'desc');
            }
        }



    }else{
        $Items->orderBy('id', 'desc');
    }



    return $Items->get();


}


function ActiveItemsForUser($limit=null,$user_id,$offsit = 0,$stage_id=null,$where =[]){



    $Items = \App\Models\Item::Actives()
        ->where('user_id',$user_id)
        ->select(['*','name_'.\DataLanguage::get().' as name','description_'.\DataLanguage::get().' as description'])
        ->with(['item_category'=>function($q){
            $q->select(['*','name_'.\DataLanguage::get().' as name','slug_'.\DataLanguage::get().' as slug']);
        },'item_type'=>function($q){
            $q->select(['*','name_'.\DataLanguage::get().' as name','slug_'.\DataLanguage::get().' as slug']);
        }]);

    if ($stage_id != null){
        $Items->where('stage_id',$stage_id);
//        $limit = null;
//        $offsit = 0;
    }
    if($limit != null)
        $Items->limit($limit);
   if($offsit != 0)
       $Items->offset($offsit);
    if(!empty($where))
        $Items->where($where);



    $Items->orderBy('id','desc');
    //dd($Items->toSql());
    return $Items->get();
}

function ActiveItemTypes($limit=null){
    $Types = \App\Models\ItemTypes::Actives()->select(['*','name_'.\DataLanguage::get().' as name']);
    if($limit != null)
        $Types->limit($limit);

    return $Types->get();
}

function ActiveParentCategories($limit = null,$offsit = null,$parent_id = -1){
    $categories = \App\Models\ItemCategory::Actives()->select(['*','name_'.\DataLanguage::get().' as name']);
    if($limit != null)
        $categories->limit($limit);
    if($offsit != null)
        $categories->offset($offsit);
    if($parent_id != -1)
        $categories->where('parent_id',$parent_id);
    
    return $categories->get();
    //where('parent_id',0)->
}


function getCategoryTreeArray($parent_category_id = 0, $level = ' ', $category_id = 0) {

    $result =  \App\Models\ItemCategory::select(['id','name_'.\DataLanguage::get() .' as name', 'parent_id'])->where(['status'=>'active','parent_id'=> $parent_category_id])
        ->orderBy('sort')->get();
    $menu = [];
    if (!empty($result)) {
        foreach ($result as $key => $row) {

            $menu[$key] = ['id'=>$row -> id,'value'=>'','child'=>[]];
            $menu[$key]['value'] = $row -> name ;//$level . ' - ' . $row -> name ;

            $check = \App\Models\ItemCategory::select(['id','name_'.\DataLanguage::get() .' as name', 'parent_id'])
                ->where(['status'=>'active','parent_id'=> $row->parent_id])->orderBy('sort')->get();

            if (!empty($check)) {

                $menu[$key]['child']= getCategoryTreeArray($row -> id, $level . ' - '.$row->name  ,$category_id);
            }
        }
    }

    return $menu;

}


function getCategoryTreeSelect($parent_category_id = 0, $level = ' ', $category_id = 0) {

    $result =  \App\Models\ItemCategory::select(['id','name_'.\DataLanguage::get() .' as name', 'parent_id'])->where(['status'=>'active','parent_id'=> $parent_category_id])
        ->orderBy('sort')->get();
     $menu = '';
    if (!empty($result)) {
        foreach ($result as $row) {
            if (  $row -> id == $category_id)
                $select = 'selected="selected"';
            else
                $select = '';
//            $menu .= '<option disabled>Select Parent Category</option>';
            $menu .= '<option ' . $select . ' value="' . $row -> id . '" > ' .$level . ' - ' . $row -> name . '</option>';

            $check = \App\Models\ItemCategory::select(['id','name_'.\DataLanguage::get() .' as name', 'parent_id'])
                ->where(['status'=>'active','parent_id'=> $row->parent_id])->orderBy('sort')->get();

            if (!empty($check)) {

                $menu .= getCategoryTreeSelect($row -> id, $level . ' - '.$row->name  ,$category_id);
            }
        }
    }

    echo $menu;

}

function getTypeTreeSelect($parent_category_id = 0, $level = ' ', $category_id = 0) {
    $result =  \App\Models\ItemTypes::select(['id','name_'.\DataLanguage::get() .' as name', 'parent_id'])->where(['parent_id'=> $parent_category_id])
        ->orderBy('sort')->get();
    $menu = '';
    if (!empty($result)) {
        foreach ($result as $row) {
            if (  $row -> id == $category_id)
                $select = 'selected="selected"';
            else
                $select = '';
            $menu .= '<option ' . $select . ' value="' . $row -> id . '" > ' .$level . ' - ' . $row -> name . '</option>';

            $check = \App\Models\ItemTypes::select(['id','name_'.\DataLanguage::get() .' as name', 'parent_id'])
                ->where(['parent_id'=> $row->parent_id])->orderBy('sort')->get();

            if (!empty($check)) {

                $menu .= getTypeTreeSelect($row -> id, $level . ' - '.$row->name  ,$category_id);
            }
        }
    }

    echo $menu;

}

function passwordValidation($password){
    preg_match_all('#([a-z]+)|([A-Z]+)|([0-9]+)#',$password,$data);
    if(empty($data[0])){
        if(mb_strlen($password) >= 6){
            return true;
        }

        return false;
    }

    $chars        = $data[1];
    $upperChars   = $data[2];
    $numbers      = $data[3];

    // Chars
    foreach ($chars as $char){
        if(!empty($char)){
            $strLen = mb_strlen($char);
            if($strLen > 1 && $char == implode(range($char[0],$char[$strLen-1]))){
                return false;
            }
        }
    }

    // Upper Chars
    foreach ($upperChars as $char){
        if(!empty($char)){
            $strLen = mb_strlen($char);
            if($strLen > 1 && $char == implode(range($char[0],$char[$strLen-1]))){
                return false;
            }
        }
    }

    // Numbers
    foreach ($numbers as $number){
        if(!empty($number)){
            $nulLen = mb_strlen($number);
            if($nulLen > 1 && $number == implode(range($number[0],$number[$nulLen-1]))){
                return false;
            }
        }
    }

    return true;

}

function  exportXLS($title ,$heads, $exData,$callback){

    $return = '<table><thead><tr><th colspan="'.count($heads).'">'.$title.'</th></tr><tr>';

    foreach ($heads as $key => $value){
        $return.= '<th>'.$value.'</th>';
    }
    $return.= '</thead><tbody>';
    foreach ($exData as $key => $value){
        $return.= '<tr>';
        foreach ($callback as $k => $v){
            if(is_string($v))
                $return.= '<td>'.$value[$v].'</td>' ;
            else
                $return.= '<td>'.$v($value).'</td>';
        }
        $return.= '</tr>';
    }
    $return.= '</tbody></table>';


    \Excel::create($title, function($excel) use ($return) {
        $excel->sheet('Excel sheet', function($sheet) use ($return) {
            $sheet->loadView('system.export-to-excel')->with('return',$return);
        });

    })->export('xls');

}


function  makeTable($title ,$heads, $exData,$callback){

    $return = '<table><thead><tr><th colspan="'.count($heads).'">'.$title.'</th></tr><tr>';

    foreach ($heads as $key => $value){
        $return.= '<th>'.$value.'</th>';
    }
    $return.= '</thead><tbody>';
    foreach ($exData as $key => $value){
        $return.= '<tr>';
        foreach ($callback as $k => $v){
            if(is_string($v))
                $return.= '<td>'.$value[$v].'</td>' ;
            else
                $return.= '<td>'.$v($value).'</td>';
        }
        $return.= '</tr>';
    }
    $return.= '</tbody></table>';

    return $return;

}

function exportTable($title,$sheets){

    \Excel::create($title, function($excel) use ($sheets) {
        foreach ($sheets as $key=> $row) {
            $excel->sheet($row['title'], function ($sheet) use ($row) {

                $sheet->loadView('system.export-to-excel')->with('return', $row['table']
                );
            });
        }
    })->export('xls');
}

function exportOneTable($title,$table){

    \Excel::create($title, function($excel) use($table) {

        $excel->sheet('Excel sheet', function ($sheet) use ($table) {

            $sheet->loadView('system.export-to-excel')->with('return', $table);

        });

    })->export('xls');
}


function pda($ob)
{
    print_r($ob->toArray());
    die;
}

function pd($ob)
{
    print_r($ob);
    die;
}

function getLang(){
    return App::getLocale();
}


function json($status,$data=[]){
    return ['status'=>$status,'data'=>$data];
}

function getRealIP(){
    return env('HTTP_CF_CONNECTING_IP') ?? env('REMOTE_ADDR');
}

function databaseAmount($amount){
    $pos = strpos($amount,'.');
    if($pos === false){
        return $amount;
    }

    return substr($amount,0,$pos).substr($amount,$pos,3);
}

function distance($lat1, $lon1, $lat2, $lon2, $unit,$round) {

    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    $unit = strtoupper($unit);

    if ($unit == "K") {
        return round(($miles * 1.609344),$round);
    } else if ($unit == "N") {
        return round(($miles * 0.8684),$round);
    } else { // defult kilometer
        return round(($miles * 1.609344),$round);
    }
}


function setError($data,$model_type,$model_id,$msg = null,$type = 'error'){
    $create = \App\Models\ErrorLog::create([
        'model_type'=> $model_type,
        'model_id'=> $model_id,
        'type'=> $type,
        'data'=> $data,
        'msg'=> $msg
    ]);

    if($create){
        return true;
    }else{
        return false;
    }

}


function amount($amount,$format = false){
    if($format){
        return number_format($amount,2).' '.__('LE');
    }
    return $amount.' '.__('LE');
}

function humanStr($value){
    return __(ucwords(str_replace('_', ' ', $value)));
}

// Arrays Helpers

function arrayGetOnly(array $array,$only){
    if(empty($array)){
        return [];
    }else{
        $newData = [];
        if(is_array($only)){
            foreach ($only as $key => $value) {
                if(isset($array[$value])){
                    $newData[$value] = $array[$value];
                }
            }
        }elseif(is_string($only)){
            if(isset($array[$only])){
                $newData[$only] = $array[$only];
            }
        }else{
            return [];
        }

        return $newData;
    }
}

// Arrays Helpers




function listLangCodes(){
    return [
        'ar'=> 'العربية',
        'en'=> 'English'
    ];
}

function iif($conditions,$true = null,$false = null){
    if($conditions){
        if(is_object($true) && ($true instanceof Closure)){
            return $true();
        }else{
            return $true;
        }
    }else{
        if(is_object($false) && ($false instanceof Closure)){
            return $false();
        }else{
            return $false;
        }
    }
}


function whereBetween($eloquent,$columnName,$form,$to){
    if(!empty($form) && empty($to)){
        $eloquent->whereRaw("$columnName >= ?",[$form]);
    }elseif(empty($form) && !empty($to)){
        $eloquent->whereRaw("$columnName <= ?",[$to]);
    }elseif(!empty($form) && !empty($to)){
        $eloquent->where(function($query) use($columnName,$form,$to) {
            $query->whereRaw("$columnName BETWEEN ? AND ?",[$form,$to]);
        });
    }
}

function orWhereByLang(&$eloquent,$columnName,$value,$operator = 'like'){
    $eloquent->where(function($query) use($columnName,$value,$operator){
        $count = 0;
        foreach (listLangCodes() as $key => $langName) {

            if($count == 0){
                if($operator == 'like'){
                    $query->where("$columnName".'_'."$key",'LIKE','%'.$value.'%');
                }else{
                    $query->where("$columnName".'_'."$key",$operator,$value);
                }
            }else{
                if($operator == 'like'){
                    $query->orWhere("$columnName".'_'."$key",'LIKE','%'.$value.'%');
                }else{
                    $query->orWhere("$columnName".'_'."$key",$operator,$value);
                }
            }
            $count++;
        }
    });
}

function imageResize($imagePath,$width,$height){
    $vImagePath = $imagePath;
    $imagePath = storage_path('app/public/'.$imagePath);

    if(File::exists($imagePath) && explode('/',File::mimeType($imagePath))[0] == 'image' ){
        $resizedFileName = File::dirname($imagePath).'/'.File::name($imagePath).'_'.$width.'X'.$height.'.'.File::extension($imagePath);

        if(!Storage::exists($resizedFileName)){
            Image::make($imagePath)
                ->resize($width,$height)
                ->save($resizedFileName);
        }

        return File::dirname($vImagePath).'/'.File::name($imagePath).'_'.$width.'X'.$height.'.'.File::extension($imagePath);

//        return $resizedFileName;
    }


    return false;
}


function image($imagePath,$width,$height){
    return imageResize($imagePath,$width,$height);
}




/*
 * @ $areaID : array or int
 */

function getLastNotEmptyItem($array){
    if(empty($array) || !is_array($array)){
        return false;
    }

    $last = end($array);
    if(empty($last)){
        $last = prev($array);
    }
    return $last;
}

function contactType($row){
    return __(ucfirst(str_replace('_',' ',$row->type)));
}


function contactValue($row){
    if($row->type == 'email'){
        return '<a href="mailto:'.$row->value.'">'.$row->value.'</a>';
    }else{
        return '<a href="tel:'.$row->value.'">'.$row->value.'</a>';
    }
}

function UniqueId(){
    return md5(str_random(20).uniqid().str_random(50).(time()*rand()));
}

function Base64PngQR($var,$size=false){
    $height = ((isset($size['0']))? $size['0']:'256');
    $width = ((isset($size['1']))? $size['1']:'256');
    $renderer = new \BaconQrCode\Renderer\Image\Png();
    $renderer->setHeight($height);
    $renderer->setWidth($width);
    $writer = new \BaconQrCode\Writer($renderer);
    return $writer->outputContent($var);
}


function setting($name,$returnAll = false){
    static $data;
    if($data == null){
        $getData = App\Models\Setting::get(['name','value'])->toArray();
        $data = array_column($getData,'value','name');
    }
    if($returnAll){
        return $data;
    }elseif(isset($data[$name])){
        $unserialize = @unserialize($data[$name]);
        if(is_array($unserialize)){
            return $unserialize;
        }
        return $data[$name];
    }

    return null;
}
function country(){
    return  $countries = array(
        "AF" => "Afghanistan",
        "AL" => "Albania",
        "DZ" => "Algeria",
        "AS" => "American Samoa",
        "AD" => "Andorra",
        "AO" => "Angola",
        "AI" => "Anguilla",
        "AQ" => "Antarctica",
        "AG" => "Antigua and Barbuda",
        "AR" => "Argentina",
        "AM" => "Armenia",
        "AW" => "Aruba",
        "AU" => "Australia",
        "AT" => "Austria",
        "AZ" => "Azerbaijan",
        "BS" => "Bahamas",
        "BH" => "Bahrain",
        "BD" => "Bangladesh",
        "BB" => "Barbados",
        "BY" => "Belarus",
        "BE" => "Belgium",
        "BZ" => "Belize",
        "BJ" => "Benin",
        "BM" => "Bermuda",
        "BT" => "Bhutan",
        "BO" => "Bolivia",
        "BA" => "Bosnia and Herzegovina",
        "BW" => "Botswana",
        "BV" => "Bouvet Island",
        "BR" => "Brazil",
        "IO" => "British Indian Ocean Territory",
        "BN" => "Brunei Darussalam",
        "BG" => "Bulgaria",
        "BF" => "Burkina Faso",
        "BI" => "Burundi",
        "KH" => "Cambodia",
        "CM" => "Cameroon",
        "CA" => "Canada",
        "CV" => "Cape Verde",
        "KY" => "Cayman Islands",
        "CF" => "Central African Republic",
        "TD" => "Chad",
        "CL" => "Chile",
        "CN" => "China",
        "CX" => "Christmas Island",
        "CC" => "Cocos (Keeling) Islands",
        "CO" => "Colombia",
        "KM" => "Comoros",
        "CG" => "Congo",
        "CD" => "Congo, the Democratic Republic of the",
        "CK" => "Cook Islands",
        "CR" => "Costa Rica",
        "CI" => "Cote D'Ivoire",
        "HR" => "Croatia",
        "CU" => "Cuba",
        "CY" => "Cyprus",
        "CZ" => "Czech Republic",
        "DK" => "Denmark",
        "DJ" => "Djibouti",
        "DM" => "Dominica",
        "DO" => "Dominican Republic",
        "EC" => "Ecuador",
        "EG" => "Egypt",
        "SV" => "El Salvador",
        "GQ" => "Equatorial Guinea",
        "ER" => "Eritrea",
        "EE" => "Estonia",
        "ET" => "Ethiopia",
        "FK" => "Falkland Islands (Malvinas)",
        "FO" => "Faroe Islands",
        "FJ" => "Fiji",
        "FI" => "Finland",
        "FR" => "France",
        "GF" => "French Guiana",
        "PF" => "French Polynesia",
        "TF" => "French Southern Territories",
        "GA" => "Gabon",
        "GM" => "Gambia",
        "GE" => "Georgia",
        "DE" => "Germany",
        "GH" => "Ghana",
        "GI" => "Gibraltar",
        "GR" => "Greece",
        "GL" => "Greenland",
        "GD" => "Grenada",
        "GP" => "Guadeloupe",
        "GU" => "Guam",
        "GT" => "Guatemala",
        "GN" => "Guinea",
        "GW" => "Guinea-Bissau",
        "GY" => "Guyana",
        "HT" => "Haiti",
        "HM" => "Heard Island and Mcdonald Islands",
        "VA" => "Holy See (Vatican City State)",
        "HN" => "Honduras",
        "HK" => "Hong Kong",
        "HU" => "Hungary",
        "IS" => "Iceland",
        "IN" => "India",
        "ID" => "Indonesia",
        "IR" => "Iran, Islamic Republic of",
        "IQ" => "Iraq",
        "IE" => "Ireland",
        "IL" => "Israel",
        "IT" => "Italy",
        "JM" => "Jamaica",
        "JP" => "Japan",
        "JO" => "Jordan",
        "KZ" => "Kazakhstan",
        "KE" => "Kenya",
        "KI" => "Kiribati",
        "KP" => "Korea, Democratic People's Republic of",
        "KR" => "Korea, Republic of",
        "KW" => "Kuwait",
        "KG" => "Kyrgyzstan",
        "LA" => "Lao People's Democratic Republic",
        "LV" => "Latvia",
        "LB" => "Lebanon",
        "LS" => "Lesotho",
        "LR" => "Liberia",
        "LY" => "Libyan Arab Jamahiriya",
        "LI" => "Liechtenstein",
        "LT" => "Lithuania",
        "LU" => "Luxembourg",
        "MO" => "Macao",
        "MK" => "Macedonia, the Former Yugoslav Republic of",
        "MG" => "Madagascar",
        "MW" => "Malawi",
        "MY" => "Malaysia",
        "MV" => "Maldives",
        "ML" => "Mali",
        "MT" => "Malta",
        "MH" => "Marshall Islands",
        "MQ" => "Martinique",
        "MR" => "Mauritania",
        "MU" => "Mauritius",
        "YT" => "Mayotte",
        "MX" => "Mexico",
        "FM" => "Micronesia, Federated States of",
        "MD" => "Moldova, Republic of",
        "MC" => "Monaco",
        "MN" => "Mongolia",
        "MS" => "Montserrat",
        "MA" => "Morocco",
        "MZ" => "Mozambique",
        "MM" => "Myanmar",
        "NA" => "Namibia",
        "NR" => "Nauru",
        "NP" => "Nepal",
        "NL" => "Netherlands",
        "AN" => "Netherlands Antilles",
        "NC" => "New Caledonia",
        "NZ" => "New Zealand",
        "NI" => "Nicaragua",
        "NE" => "Niger",
        "NG" => "Nigeria",
        "NU" => "Niue",
        "NF" => "Norfolk Island",
        "MP" => "Northern Mariana Islands",
        "NO" => "Norway",
        "OM" => "Oman",
        "PK" => "Pakistan",
        "PW" => "Palau",
        "PS" => "Palestinian Territory, Occupied",
        "PA" => "Panama",
        "PG" => "Papua New Guinea",
        "PY" => "Paraguay",
        "PE" => "Peru",
        "PH" => "Philippines",
        "PN" => "Pitcairn",
        "PL" => "Poland",
        "PT" => "Portugal",
        "PR" => "Puerto Rico",
        "QA" => "Qatar",
        "RE" => "Reunion",
        "RO" => "Romania",
        "RU" => "Russian Federation",
        "RW" => "Rwanda",
        "SH" => "Saint Helena",
        "KN" => "Saint Kitts and Nevis",
        "LC" => "Saint Lucia",
        "PM" => "Saint Pierre and Miquelon",
        "VC" => "Saint Vincent and the Grenadines",
        "WS" => "Samoa",
        "SM" => "San Marino",
        "ST" => "Sao Tome and Principe",
        "SA" => "Saudi Arabia",
        "SN" => "Senegal",
        "CS" => "Serbia and Montenegro",
        "SC" => "Seychelles",
        "SL" => "Sierra Leone",
        "SG" => "Singapore",
        "SK" => "Slovakia",
        "SI" => "Slovenia",
        "SB" => "Solomon Islands",
        "SO" => "Somalia",
        "ZA" => "South Africa",
        "GS" => "South Georgia and the South Sandwich Islands",
        "ES" => "Spain",
        "LK" => "Sri Lanka",
        "SD" => "Sudan",
        "SR" => "Suriname",
        "SJ" => "Svalbard and Jan Mayen",
        "SZ" => "Swaziland",
        "SE" => "Sweden",
        "CH" => "Switzerland",
        "SY" => "Syrian Arab Republic",
        "TW" => "Taiwan, Province of China",
        "TJ" => "Tajikistan",
        "TZ" => "Tanzania, United Republic of",
        "TH" => "Thailand",
        "TL" => "Timor-Leste",
        "TG" => "Togo",
        "TK" => "Tokelau",
        "TO" => "Tonga",
        "TT" => "Trinidad and Tobago",
        "TN" => "Tunisia",
        "TR" => "Turkey",
        "TM" => "Turkmenistan",
        "TC" => "Turks and Caicos Islands",
        "TV" => "Tuvalu",
        "UG" => "Uganda",
        "UA" => "Ukraine",
        "AE" => "United Arab Emirates",
        "GB" => "United Kingdom",
        "US" => "United States",
        "UM" => "United States Minor Outlying Islands",
        "UY" => "Uruguay",
        "UZ" => "Uzbekistan",
        "VU" => "Vanuatu",
        "VE" => "Venezuela",
        "VN" => "Viet Nam",
        "VG" => "Virgin Islands, British",
        "VI" => "Virgin Islands, U.s.",
        "WF" => "Wallis and Futuna",
        "EH" => "Western Sahara",
        "YE" => "Yemen",
        "ZM" => "Zambia",
        "ZW" => "Zimbabwe"
    );
}
function countries(){
    return  $countries = array(
        "AF" => "Afghanistan",
        "AL" => "Albania",
        "DZ" => "Algeria",
        "AS" => "American Samoa",
        "AD" => "Andorra",
        "AO" => "Angola",
        "AI" => "Anguilla",
        "AQ" => "Antarctica",
        "AG" => "Antigua and Barbuda",
        "AR" => "Argentina",
        "AM" => "Armenia",
        "AW" => "Aruba",
        "AU" => "Australia",
        "AT" => "Austria",
        "AZ" => "Azerbaijan",
        "BS" => "Bahamas",
        "BH" => "Bahrain",
        "BD" => "Bangladesh",
        "BB" => "Barbados",
        "BY" => "Belarus",
        "BE" => "Belgium",
        "BZ" => "Belize",
        "BJ" => "Benin",
        "BM" => "Bermuda",
        "BT" => "Bhutan",
        "BO" => "Bolivia",
        "BA" => "Bosnia and Herzegovina",
        "BW" => "Botswana",
        "BV" => "Bouvet Island",
        "BR" => "Brazil",
        "IO" => "British Indian Ocean Territory",
        "BN" => "Brunei Darussalam",
        "BG" => "Bulgaria",
        "BF" => "Burkina Faso",
        "BI" => "Burundi",
        "KH" => "Cambodia",
        "CM" => "Cameroon",
        "CA" => "Canada",
        "CV" => "Cape Verde",
        "KY" => "Cayman Islands",
        "CF" => "Central African Republic",
        "TD" => "Chad",
        "CL" => "Chile",
        "CN" => "China",
        "CX" => "Christmas Island",
        "CC" => "Cocos (Keeling) Islands",
        "CO" => "Colombia",
        "KM" => "Comoros",
        "CG" => "Congo",
        "CD" => "Congo, the Democratic Republic of the",
        "CK" => "Cook Islands",
        "CR" => "Costa Rica",
        "CI" => "Cote D'Ivoire",
        "HR" => "Croatia",
        "CU" => "Cuba",
        "CY" => "Cyprus",
        "CZ" => "Czech Republic",
        "DK" => "Denmark",
        "DJ" => "Djibouti",
        "DM" => "Dominica",
        "DO" => "Dominican Republic",
        "EC" => "Ecuador",
        "EG" => "Egypt",
        "SV" => "El Salvador",
        "GQ" => "Equatorial Guinea",
        "ER" => "Eritrea",
        "EE" => "Estonia",
        "ET" => "Ethiopia",
        "FK" => "Falkland Islands (Malvinas)",
        "FO" => "Faroe Islands",
        "FJ" => "Fiji",
        "FI" => "Finland",
        "FR" => "France",
        "GF" => "French Guiana",
        "PF" => "French Polynesia",
        "TF" => "French Southern Territories",
        "GA" => "Gabon",
        "GM" => "Gambia",
        "GE" => "Georgia",
        "DE" => "Germany",
        "GH" => "Ghana",
        "GI" => "Gibraltar",
        "GR" => "Greece",
        "GL" => "Greenland",
        "GD" => "Grenada",
        "GP" => "Guadeloupe",
        "GU" => "Guam",
        "GT" => "Guatemala",
        "GN" => "Guinea",
        "GW" => "Guinea-Bissau",
        "GY" => "Guyana",
        "HT" => "Haiti",
        "HM" => "Heard Island and Mcdonald Islands",
        "VA" => "Holy See (Vatican City State)",
        "HN" => "Honduras",
        "HK" => "Hong Kong",
        "HU" => "Hungary",
        "IS" => "Iceland",
        "IN" => "India",
        "ID" => "Indonesia",
        "IR" => "Iran, Islamic Republic of",
        "IQ" => "Iraq",
        "IE" => "Ireland",
        "IL" => "Israel",
        "IT" => "Italy",
        "JM" => "Jamaica",
        "JP" => "Japan",
        "JO" => "Jordan",
        "KZ" => "Kazakhstan",
        "KE" => "Kenya",
        "KI" => "Kiribati",
        "KP" => "Korea, Democratic People's Republic of",
        "KR" => "Korea, Republic of",
        "KW" => "Kuwait",
        "KG" => "Kyrgyzstan",
        "LA" => "Lao People's Democratic Republic",
        "LV" => "Latvia",
        "LB" => "Lebanon",
        "LS" => "Lesotho",
        "LR" => "Liberia",
        "LY" => "Libyan Arab Jamahiriya",
        "LI" => "Liechtenstein",
        "LT" => "Lithuania",
        "LU" => "Luxembourg",
        "MO" => "Macao",
        "MK" => "Macedonia, the Former Yugoslav Republic of",
        "MG" => "Madagascar",
        "MW" => "Malawi",
        "MY" => "Malaysia",
        "MV" => "Maldives",
        "ML" => "Mali",
        "MT" => "Malta",
        "MH" => "Marshall Islands",
        "MQ" => "Martinique",
        "MR" => "Mauritania",
        "MU" => "Mauritius",
        "YT" => "Mayotte",
        "MX" => "Mexico",
        "FM" => "Micronesia, Federated States of",
        "MD" => "Moldova, Republic of",
        "MC" => "Monaco",
        "MN" => "Mongolia",
        "MS" => "Montserrat",
        "MA" => "Morocco",
        "MZ" => "Mozambique",
        "MM" => "Myanmar",
        "NA" => "Namibia",
        "NR" => "Nauru",
        "NP" => "Nepal",
        "NL" => "Netherlands",
        "AN" => "Netherlands Antilles",
        "NC" => "New Caledonia",
        "NZ" => "New Zealand",
        "NI" => "Nicaragua",
        "NE" => "Niger",
        "NG" => "Nigeria",
        "NU" => "Niue",
        "NF" => "Norfolk Island",
        "MP" => "Northern Mariana Islands",
        "NO" => "Norway",
        "OM" => "Oman",
        "PK" => "Pakistan",
        "PW" => "Palau",
        "PS" => "Palestinian Territory, Occupied",
        "PA" => "Panama",
        "PG" => "Papua New Guinea",
        "PY" => "Paraguay",
        "PE" => "Peru",
        "PH" => "Philippines",
        "PN" => "Pitcairn",
        "PL" => "Poland",
        "PT" => "Portugal",
        "PR" => "Puerto Rico",
        "QA" => "Qatar",
        "RE" => "Reunion",
        "RO" => "Romania",
        "RU" => "Russian Federation",
        "RW" => "Rwanda",
        "SH" => "Saint Helena",
        "KN" => "Saint Kitts and Nevis",
        "LC" => "Saint Lucia",
        "PM" => "Saint Pierre and Miquelon",
        "VC" => "Saint Vincent and the Grenadines",
        "WS" => "Samoa",
        "SM" => "San Marino",
        "ST" => "Sao Tome and Principe",
        "SA" => "Saudi Arabia",
        "SN" => "Senegal",
        "CS" => "Serbia and Montenegro",
        "SC" => "Seychelles",
        "SL" => "Sierra Leone",
        "SG" => "Singapore",
        "SK" => "Slovakia",
        "SI" => "Slovenia",
        "SB" => "Solomon Islands",
        "SO" => "Somalia",
        "ZA" => "South Africa",
        "GS" => "South Georgia and the South Sandwich Islands",
        "ES" => "Spain",
        "LK" => "Sri Lanka",
        "SD" => "Sudan",
        "SR" => "Suriname",
        "SJ" => "Svalbard and Jan Mayen",
        "SZ" => "Swaziland",
        "SE" => "Sweden",
        "CH" => "Switzerland",
        "SY" => "Syrian Arab Republic",
        "TW" => "Taiwan, Province of China",
        "TJ" => "Tajikistan",
        "TZ" => "Tanzania, United Republic of",
        "TH" => "Thailand",
        "TL" => "Timor-Leste",
        "TG" => "Togo",
        "TK" => "Tokelau",
        "TO" => "Tonga",
        "TT" => "Trinidad and Tobago",
        "TN" => "Tunisia",
        "TR" => "Turkey",
        "TM" => "Turkmenistan",
        "TC" => "Turks and Caicos Islands",
        "TV" => "Tuvalu",
        "UG" => "Uganda",
        "UA" => "Ukraine",
        "AE" => "United Arab Emirates",
        "GB" => "United Kingdom",
        "US" => "United States",
        "UM" => "United States Minor Outlying Islands",
        "UY" => "Uruguay",
        "UZ" => "Uzbekistan",
        "VU" => "Vanuatu",
        "VE" => "Venezuela",
        "VN" => "Viet Nam",
        "VG" => "Virgin Islands, British",
        "VI" => "Virgin Islands, U.s.",
        "WF" => "Wallis and Futuna",
        "EH" => "Western Sahara",
        "YE" => "Yemen",
        "ZM" => "Zambia",
        "ZW" => "Zimbabwe"
    );
}

function recursiveFind(array $array, $needle)
{
    $response = [];
    $iterator  = new RecursiveArrayIterator($array);
    $recursive = new RecursiveIteratorIterator(
        $iterator,
        RecursiveIteratorIterator::SELF_FIRST
    );
    foreach ($recursive as $key => $value) {
        if ($key === $needle) {
            $response[] = $value;
        }
    }
    return ((count($response)=='1')?$response:$response);
}

function response_to_object($array) {
    $obj = new stdClass;
    foreach($array as $k => $v) {
        if(strlen($k)) {
            if((is_array($v)) && count($v)) {
                $obj->{$k} = response_to_object($v); //RECURSION
            } elseif(($k == 'info') && (is_array($v))) {
                $obj->{$k} = implode("\n",$v);
            } else {
                $obj->{$k} = $v;
            }
        }
    }
    return $obj;
}

function calcDim($width,$height,$maxwidth,$maxheight) {
    if($width != $height){
        if($width > $height){
            $t_width = $maxwidth;
            $t_height = (($t_width * $height)/$width);
            //fix height
            if($t_height > $maxheight)
            {
                $t_height = $maxheight;
                $t_width = (($width * $t_height)/$height);
            }
        } else {
            $t_height = $maxheight;
            $t_width = (($width * $t_height)/$height);
            //fix width
            if($t_width > $maxwidth){
                $t_width = $maxwidth;
                $t_height = (($t_width * $height)/$width);
            }
        }
    } else
        $t_width = $t_height = min($maxheight,$maxwidth);
    return array('height'=>(int)$t_height,'width'=>(int)$t_width);
}

function PaymentParamName($param,$lang){
    $paramData = \App\Models\PaymentServiceAPIParameters::where('external_system_id','=',explode('_',$param)['1'])->first();
    return $paramData->{'name_'.$lang};
}




function PaymentParamNameNew($param,$lang){
    $paramData = \App\Models\PaymentServiceAPIParameters::where('id','=',$param)->first();
    if(!empty($paramData))
        return $paramData->{'name_'.$lang};
    else
        return '--';
}


function monitorNotification($title,$description,$url){
    if(!empty(setting('monitor_staff'))){
        $monitorStaff = Staff::whereIn('id',explode("\n",setting('monitor_staff')))
            ->get();

        foreach ($monitorStaff as $key => $value){
            $value->notify(
                (new UserNotification([
                    'title'         => $title,
                    'description'   => $description,
                    'url'           => $url
                ]))
                    ->delay(5)
            );
        }
    }
}

function create_slug($title, $id) {

    return str_replace(' ', '-', $title) . '-' . $id;
}