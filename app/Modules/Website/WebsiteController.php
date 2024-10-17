<?php

namespace App\Modules\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WebsiteController extends Controller{
    protected $systemLang;
    protected $viewData = [];

    public function __construct()
    {
        
       // $this->middleware(['auth']);
       // $this->viewData['systemLang'] = \DataLanguage::get();

    }
    protected function view($file,array $data = []){
        return view('web.'.$file,$data);
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
    }