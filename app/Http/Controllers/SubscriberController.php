<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscriberRequest;
use App\Subscriber;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    public function __construct()
    {
        parent::__construct(
            ['store','send']
        );
    }

    public function store(SubscriberRequest $request){
        $subscriber = new Subscriber();
        $subscriber->email = $request->email;
        $subscriber->save();
        return $this->sendSuccess($subscriber);
    }


    public function index(){
        $all = Subscriber::where('trashed',0)->orderBy('id','desc')->get();
        return $this->sendSuccess($all);
    }

    public function trash($id){
        $sub = Subscriber::find($id);
        if( empty($sub) )return $this->sendFailure(404);
        $sub->trashed = 1;
        $sub->save();
        return $this->sendSuccess();
    }


    public function send(SubscriberRequest $request){
        
    }
}
