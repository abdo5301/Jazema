<?php

namespace App\Modules\Website;

use App\Models\Item;
use App\Models\Page;
use App\Models\Service;
use App\Libs\DataLanguage;
use App\Models\User;
use App\Models\ContactUs;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Notification;
use App\Notifications\UserNotification;
use Illuminate\Http\Request;

class HomeController extends WebsiteController
{


    public function index(Request $request, $lang = 'ar')
    {

        $start = strtotime("10 September 2017");

//End point of our date range.
        $end = strtotime("22 July 2019");


        for ($x = 0; $x <= 2000; $x++) {
            //Custom range.
            $timestamp = mt_rand($start, $end);


//    Comment::Insert([
//        'comment' => str_random(10),
//        'user_id' => rand(1,5),
//        'item_id' => rand(1,500),
//        'created_at' => date("Y-m-d", $timestamp),
//        'updated_at' => date("Y-m-d", $timestamp),
//    ]);
        }

        if ($lang == 'en') {
            \App::setLocale('en');
            \DataLanguage::set('en');
        } elseif ($lang == 'ar' || empty($lang)) {
            \App::setLocale('ar');
            \DataLanguage::set('ar');
        } else {
            abort(404);
        }

//        if ($request->getData) {
//            $offset = $request->offset;
//
//            $items = ActiveItems(setting('item_per_request'), [], $offset);
//
//            if ($items->isNotEmpty()) {
//                $drowedItems = [];
//
//                foreach ($items as $item) {
//
//                    $drowedItems[] = DrowItem($item);
//
//                }
//                return json(true, $drowedItems);
//            } else {
//                return json(false);
//            }
//        }
        //$this->viewData['items'] = $items = ActiveItems(10);

        return view('web/home');

    }

    public function category(Request $request)
    {

    }

    public function getItems(Request $request)
    {


    }

    public function Details(Request $request)
    {

    }

    public function aboutUs()
    {
        $this->viewData['topRankItems'] = Item::OrderBy('rank', 'Desc')->limit(10)->get([
            'name_' . \DataLanguage::get() . ' as name',
            'id',
            'rank',
            'slug_'. \DataLanguage::get() . ' as slug'
        ]);
        $this->viewData['topRankUsers'] = User::OrderBy('rank', 'Desc')->limit(10)->get(['firstname', 'lastname', 'id', 'rank', 'image','slug']);
        $this->viewData['about_us'] = Page::where('id', 1)->select([
            'id',
            'name_' . \DataLanguage::get() . ' as name',
            'content_' . \DataLanguage::get() . ' as content',
            'image'
        ])->first();
        $this->viewData['services'] = Service::select([
            'id',
            'name_' . \DataLanguage::get() . ' as name',
            'description_' . \DataLanguage::get() . ' as description',
            'image'
        ])->get();
//        dd( $this->viewData['topRankUsers'] );
        return view('web/about-us', $this->viewData);
    }



    public function page(Request $request)
    {

        if (!empty($request->slug)){
            $this->viewData['page'] = Page::where('slug_' . \DataLanguage::get(), $request->slug)->select([
                'id',
                'name_' . \DataLanguage::get() . ' as name',
                'content_' . \DataLanguage::get() . ' as content',
                'image'
            ])->first();
        }else{
            abort(404);
        }
       // dd( $this->viewData['page'] );
        return view('web/page', $this->viewData);
    }

    public function contactUs(Request $request)
    {

        $validationArray = [
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required',
            'mobile' => 'required',
        ];
        $validator = Validator::make($request->all(), $validationArray);
        if ($validator->fails()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }
        if (ContactUs::create($request->all())) {
            return ['status' => true];
        }
        return ['status' => false];


    }


}