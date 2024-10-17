<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserFormRequest extends FormRequest
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
        $currantID = $this->segment(3);
//        if($this->parent_id == 0){
//            unset($this->parent_id);
//        }
        switch($this->method())
        {
            case 'GET':
            case 'DELETE':
            {
                return [];
            }
            case 'POST': {
                $validation = [
                    'firstname'                 =>  'required',
                    'lastname'                  =>  'required',
                    'email'                     =>  'required|email|unique:users,email',
                    'mobile'                    =>  'required|numeric|unique:users,mobile',
                    'image'                     =>  'nullable|image',
                    'gender'                    =>  'required|in:male,female',
                    'birthdate'                 =>  'required|date_format:"Y-m-d"',
                    'status'                    =>  'required|in:pending,active,in-active',
                    'nationality'               =>'required',
                    'national_id'               =>  'required|numeric|digits:14|unique:users,national_id',
                    'national_id_image_front'   =>  'nullable|image',
                    'national_id_image_back'    =>  'nullable|image',


                ];

                return $validation;


            }
            case 'PUT':
            case 'PATCH':
            {
                $validation = [
                    'firstname'                 =>  'required',

                    'lastname'                  =>  'required',
                    'email'                     =>  'required|email|unique:users,email,'.$currantID,
                    'mobile'                    =>  'required|numeric|unique:users,mobile,'.$currantID,
                    'password'                  =>  'nullable|confirmed|min:5',
                    'password_confirmation'     =>  'nullable|required_with:password',
                    'image'                     =>  'nullable|image',
                    'gender'                    =>  'required|in:male,female',
                    'national_id'               =>  'required|numeric|digits:14|unique:users,national_id,'.$currantID,
                    'birthdate'                 =>  'required|date_format:"Y-m-d"',
                    'status'                    =>  'required|in:pending,active,in-active',
                    'nationality'               =>'required',
                    'national_id_image_front'   =>  'nullable|image',
                    'national_id_image_back'    =>  'nullable|image',
                ];


                return $validation;
            }
            default:break;
        }
    }
}
