<?php

namespace App\Http\Controllers;

use App\FAQ;
use App\Http\Requests\FAQRequest;
use Illuminate\Http\Request;

class FAQController extends Controller
{
    
    public function __construct()
    {
        parent::__construct(
            ['index','show']
        );
    }

    private function makeFAQ(FAQ &$faq,FAQRequest $request){
        $faq->ques = $request->ques;
        $faq->ans = $request->ans;
        $faq->order = ( $request->has('order') && trim($request->order)!="" ) ? $request->order : 100000;
    }

    public function index()
    {
        $faqs = FAQ::where('trashed',0)
                    ->orderBy('order','asc')
                    ->orderBy('ques','asc')
                    ->get();
        return $this->sendSuccess($faqs);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FAQRequest $request)
    {
        $faq = new FAQ;
        $this->makeFAQ($faq,$request);
        $faq->save();
        return $this->sendSuccess($faq);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $faq = FAQ::find($id);
        if( empty($faq) )return $this->sendFailure(404);
        return $this->sendSuccess($faq);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FAQRequest $request, $id)
    {
        $faq = FAQ::find($id);
        if( empty($faq) )return $this->sendFailure(404);
        $this->makeFAQ($faq,$request);
        $faq->save();
        return $this->sendSuccess($faq);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $faq = FAQ::find($id);
        if( empty($faq) )return $this->sendFailure(404);
        $faq->trashed = 1;
        $faq->save();
        return $this->sendSuccess();
    }
}
