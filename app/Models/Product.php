<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{

    protected $table = 'merchant_products';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['created_at','updated_at','deleted_at'];
    protected $fillable = array(
        'merchant_id',
        'merchant_product_category_id',
        'name_ar',
        'name_en',
        'description_ar',
        'description_en' ,
        'price',
        'tax_ids',
        'creatable_type',
        'creatable_id',
        'approved_by_staff_id',
        'approved_at',
        'status',
        'values',
        'quantity'
    );




    public function creatable()
    {
        return $this->morphTo();
    }

    public function merchant(){
        return $this->belongsTo('App\Models\Merchant','merchant_id');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\MerchantProductCategory','merchant_product_category_id');
    }

    public function uploadmodel(){
        return $this->morphMany('App\Models\Upload', 'model');
    }

    public function upload()
    {
        return $this->morphMany('App\Models\Upload', 'upload','model_type','model_id');
    }


    public function approved()
    {
        return $this->belongsTo('App\Models\Staff','approved_by_staff_id');
    }



    public function attribute(){
        return $this->hasMany('App\Models\MerchantProductAttribute','merchant_product_id');
    }


    public function option(){
        return $this->hasMany('App\Models\MerchantProductOption','merchant_product_id');
    }

    public function taxes(){
        return Tax::whereIn('id',explode(',',$this->tax_ids))
            ->get([
                'id',
                'name_ar',
                'name_en',
                'type',
                'rate'
            ]);
    }
    public static function viewData($langCode,array $additionColumn = [])
    {
        $columns = [

            'merchant_products.id',
            'merchant_products.merchant_id',
            'merchant_products.merchant_product_category_id',
            'merchant_products.name_' . $langCode . ' as name',
            'merchant_products.description_' . $langCode . ' as description',
            'merchant_products.price',
            'merchant_products.creatable_type',
            'merchant_products.creatable_id',
            'merchant_products.quantity',
            'merchant_products.status',
            'merchant_products.approved_by_staff_id',
            'merchant_products.approved_at',
            'merchant_products.created_at',
            'merchant_products.updated_at',

        ];
        $columns = array_merge($columns, $additionColumn);
        return self::select($columns);
    }

}