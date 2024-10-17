<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class AttributesFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch($this->method())
        {
            case 'GET':
            case 'DELETE':
            {
                return [];
            }
            case 'POST': {
                $validation = [
                    'name_ar'                    =>  'required',
                    'name_en'                    =>  'required',
                    'type'                       =>  'required|in:text,textarea,select,multi_select,date,datetime,location,file',
                    'is_required'                =>  'required|in:yes,no',
                   // 'sort'                       =>  'nullable|numeric'
                ];
                if($this->attr_type =='job'){
                    $validation['user_job_id'] = 'required|exists:user_jobs,id';
                }elseif($this->attr_type = 'item'){
                    $validation['item_category_id'] = 'required|exists:item_categories,id';
                    $validation['item_type_id'] = 'required|exists:item_types,id';
                }
                if ($this->type == 'select'||  $this->type == 'multi_select') {
                    $validation['option_value_name_ar'] = 'array';
                    $validation['option_value_name_ar.*'] = 'required';
                    $validation['option_value_name_en'] = 'array';
                    $validation['option_value_name_en.*'] = 'required';
                }

                return $validation;


            }
            case 'PUT':
            case 'PATCH': {
            $validation = [
                'name_ar' => 'required',
                'name_en' => 'required',
                'type' => 'required|in:text,textarea,select,multi_select,date,datetime,location,file',
                'is_required' => 'required|in:yes,no',
                // 'sort'                       =>  'nullable|numeric'
            ];
            if ($this->attr_type == 'job') {
                $validation['user_job_id'] = 'required|exists:user_jobs,id';
            }elseif ($this->attr_type == 'item') {
                $validation['item_category_id'] = 'required|exists:item_categories,id';
                $validation['item_type_id'] = 'required|exists:item_types,id';
            }
            if ($this->type == 'select' || $this->type == 'multi_select') {
                $validation['option_value_name_ar'] = 'array';
                $validation['option_value_name_ar.*'] = 'required';
                $validation['option_value_name_en'] = 'array';
                $validation['option_value_name_en.*'] = 'required';
            }
            return $validation;
        }
            default:break;
        }
    }
}
