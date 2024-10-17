<?php

namespace App\Http\Requests;

use App\Models\Merchant;
use Illuminate\Foundation\Http\FormRequest;

class TemplateOptionFormRequest extends FormRequest
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
                    'name_ar'                        =>  'required',
                    'name_en'                        =>  'required',
                    'type'                           =>  'required|in:text,textarea,select,radio,check',
                    'is_required'                    =>  'required|in:yes,no',
                    'item_category_id'               => 'required|exists:item_categories,id',
                    'status'                         =>'required|in:active,in-active'
                ];
                if ($this->type == 'select'||  $this->type  == 'radio'|| $this->type == 'check'){
                    $validation['option_value_name_ar'] = 'array';
                    $validation['option_value_name_ar.*'] = 'required';
                    $validation['option_value_name_en'] = 'array';
                    $validation['option_value_name_en.*'] = 'required';
                    $validation['option_value_price_prefix'] = 'array';
                    $validation['option_value_price_prefix.*'] = 'required|in:-,+';
                    $validation['option_value_price'] = 'array';
                    $validation['option_value_price.*'] = 'required';
                }
                return $validation;
            }
            case 'PUT':
            case 'PATCH':
        {
            $validation = [
                'name_ar'                        =>  'required',
                'name_en'                        =>  'required',
                'type'                           =>  'required|in:text,textarea,select,radio,check',
                'is_required'                    =>  'required|in:yes,no',
                'item_category_id'                    => 'required|exists:item_categories,id',
                'status'                         =>'required|in"active,in-active'
            ];
            if ($this->type == 'select'||  $this->type  == 'radio'|| $this->type == 'check'){
                $validation['option_value_name_ar'] = 'array';
                $validation['option_value_name_ar.*'] = 'required';
                $validation['option_value_name_en'] = 'array';
                $validation['option_value_name_en.*'] = 'required';
                $validation['option_value_price_prefix'] = 'array';
                $validation['option_value_price_prefix.*'] = 'required|in:-,+';
                $validation['option_value_price'] = 'array';
                $validation['option_value_price.*'] = 'required';
            }

            return $validation;
        }
            default:break;
        }
    }
}
