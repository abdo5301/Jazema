<?php

namespace App\Http\Requests;

use App\Models\MerchantProduct;
use App\Models\MerchantProductAttributeValue;
use App\Models\MerchantProductOptionValue;
use App\Models\ProductAttribute;
use App\Models\Upload;
use Elasticsearch\Endpoints\Indices\Aliases\Update;
use Illuminate\Foundation\Http\FormRequest;

class MerchantProductFormRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {


        $currantID = $this->segment(4);

        switch ($this->method()) {
            case 'GET':
            case 'DELETE': {
                return [];
            }
            case 'POST': {
                $valid = [
                    'merchant_product_category_id' => 'required|exists:merchant_product_categories,id',
                    'merchant_id' => 'required|exists:merchants,id',
                    'name_ar' => 'required',
                    'name_en' => 'required',
                    'description_ar' => 'required',
                    'description_en' => 'required',
                    'status' => 'required',
                    'price' => 'required|numeric',
                ];
                if ($this->temp_id) {
                    $images = Upload::where('temp_id', $this->temp_id);
                    if (empty($images->first())) {
                        $valid['image'] = 'required';
                    }
                }

                if ($this->option) {
                    foreach ($this->option as $key => $row) {
                        $valid['option.' . $key . '.template_option'] = 'required';
                        if ($row['template_option'] == 'new') {
                            $valid['option.' . $key . '.option_name_ar'] = 'required';
                            $valid['option.' . $key . '.option_name_en'] = 'required';
                            $valid['option.' . $key . '.option_min_select'] = 'required';
                            $valid['option.' . $key . '.option_max_select'] = 'required';
                            $valid['option.' . $key . '.option_is_required'] = 'required|in:yes,no';
                            $valid['option.' . $key . '.option_type'] = 'required';
                            // $valid['option.' . $key . '.option_status'] = 'required|in:active,in-active,deleted';

                            if ($row['option_type'] == 'select' || $row['option_type'] == 'radio' || $row['option_type'] == 'check') {
                                if (!empty($row['option_value_name_ar'])) {
                                    foreach ($row['option_value_name_ar'] as $key2 => $value) {
                                        $valid['option.' . $key . '.option_value_name_ar.' . $key2] = 'required';
                                        $valid['option.' . $key . '.option_value_name_en.' . $key2] = 'required';
                                        $valid['option.' . $key . '.option_value_price_prefix.' . $key2] = 'required';
                                        $valid['option.' . $key . '.option_value_price.' . $key2] = 'required';
                                        // $valid['option.' . $key . '.option_value_status.' . $key2] = 'required|in:active,in-active,deleted';
                                    }
                                }
                            }
                        }
                    }
                }


                if ($this->attribute) {
                    $attribute = ProductAttribute::where('merchant_product_category_id', $this->merchant_product_category_id)->get();

                    foreach ($attribute as $key => $value) {
                        if ($value->is_required == 'yes') {
                            if (empty($this->attribute[$value->id]))
                                $valid['attribute.' . $value->id] = 'required';
                        }
                    }

                }


                return $valid;

            }
            case 'PUT':
            case 'PATCH': {
                $valid = [
                    'merchant_product_category_id' => 'required|exists:merchant_product_categories,id',
                    'merchant_id' => 'required|exists:merchants,id',
                    'name_ar' => 'required',
                    'name_en' => 'required',
                    'description_ar' => 'required',
                    'description_en' => 'required',
                    'status' => 'required',
                    'price' => 'required|numeric',
                ];


                if ($this->temp_id) {
                    $tempImages = Upload::where('temp_id', $this->temp_id);
                    $currentImages = Upload::where('model_type', 'App\Models\MerchantProduct')->where('model_id', $currantID);

                    if (empty($tempImages->first()) && empty($currentImages->first())) {
                        $valid['image'] = 'required';
                    }
                }

                if ($this->option) {
                    foreach ($this->option as $key => $row) {
                        $valid['option.' . $key . '.template_option'] = 'required';
                        if ($row['template_option'] == 'new') {
                            $valid['option.' . $key . '.option_name_ar'] = 'required';
                            $valid['option.' . $key . '.option_name_en'] = 'required';
                            $valid['option.' . $key . '.option_min_select'] = 'required';
                            $valid['option.' . $key . '.option_max_select'] = 'required';
                            $valid['option.' . $key . '.option_is_required'] = 'required';
                            $valid['option.' . $key . '.option_type'] = 'required';
                            //  $valid['option.' . $key . '.option_status'] = 'required|in:active,in-active,deleted';

                            if ($row['option_type'] == 'select' || $row['option_type'] == 'radio' || $row['option_type'] == 'check') {
                                if (!empty($row['option_value_name_ar'])) {
                                    foreach ($row['option_value_name_ar'] as $key2 => $value) {
                                        $valid['option.' . $key . '.option_value_name_ar.' . $key2] = 'required';
                                        $valid['option.' . $key . '.option_value_name_en.' . $key2] = 'required';
                                        $valid['option.' . $key . '.option_value_price_prefix.' . $key2] = 'required';
                                        $valid['option.' . $key . '.option_value_price.' . $key2] = 'required';
                                        //    $valid['option.' . $key . '.option_value_status.' . $key2] = 'required|in:active,in-active,deleted';
                                    }
                                }
                            }
                        }
                    }
                }


                if ($this->attribute) {
                    $attribute = ProductAttribute::where('merchant_product_category_id', $this->merchant_product_category_id)->get();
                    //  $attribute = MerchantProductAttributeValue::where('merchant_product_id',$currantID)->with('product_attribute')->get();
                    foreach ($attribute as $key => $value) {
                        if (empty($this->attribute[$value->id]) && $value->is_required == 'yes') {
                            $valid['attribute.' . $value->id] = 'required';
                        }
                    }

                }

                return $valid;
            }
            default:
                break;
        }
    }
}
