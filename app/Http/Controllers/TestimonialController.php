<?php

namespace App\Http\Controllers;

use App\Http\Requests\TestimonialRequest;
use App\Media;
use App\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{

    public function __construct()
    {
        parent::__construct(
            ['index','show']
        );
    }

    private function makeTestimonial(Testimonial &$testimonial,TestimonialRequest $request){
        $testimonial->name = $request->name;
        $testimonial->designation = $request->designation;
        $testimonial->text = $request->text;
        $testimonial->photo = $request->photo;
        $testimonial->order = ( $request->has('order') && is_int( trim( $request->order ) ) ) 
                                ? $request->order
                                : 100000;
        $testimonial->save();
    }


    private function withMedia(Testimonial $testimonial){
        $photo = Media::find($testimonial->photo);
        $testimonial['media'] = $photo;
        return $testimonial;
    }
    
    public function index()
    {
        $testimonials = Testimonial::where('trashed',0)->orderBy('order','asc')->orderBy('id','asc')->get();
        return $this->sendSuccess($testimonials);
    }

    public function store(TestimonialRequest $request)
    {
        $testimonial = new Testimonial;
        $this->makeTestimonial($testimonial,$request);
        return $this->sendSuccess($this->withMedia($testimonial));
    }

    
    public function show($id)
    {
        $testimonial = Testimonial::find($id);
        if( empty($testimonial) )return $this->sendFailure(404);
        return $this->sendSuccess($this->withMedia($testimonial));
    }

   
    public function update(TestimonialRequest $request, $id)
    {
        $testimonial = Testimonial::find($id);
        if( empty($testimonial) )return $this->sendFailure(404);
        $this->makeTestimonial($testimonial,$request);
        return $this->sendSuccess($this->withMedia($testimonial));
    }

   
    public function destroy($id)
    {
        $testimonial = Testimonial::find($id);
        if( empty($testimonial) )return $this->sendFailure(404);
        $testimonial->trashed = 1;
        $testimonial->save();
        return $this->sendSuccess();
    }
}
