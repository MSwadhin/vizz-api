<?php

namespace App\Http\Controllers;

use App\Http\Services\MediaService;
use App\Slide;
use App\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{

    public function __construct()
    {
        parent::__construct(
            ['index','show']
        );
    }

    public function index(){

        $sliders = Slider::where('trashed',0)->orderBy('id','desc')->get();
        return response()->json( $sliders );

    }

    public function show($id){
        $slider = Slider::find($id);
        if( empty($slider) )return $this->sendFailure(404);
        $slider['slides'] = Slide::where('slider_id',$id)
                                    ->orderBy('order','asc')
                                    ->orderBy('id','desc')
                                    ->get();
        
        if( count($slider['slides'])>0 )
            foreach($slider['slides'] as $slide)
                $slide['media'] = $this->mediaService->getMediaFilteredById($slide->media_id);
        
                
        return $this->sendSuccess($slider);
    }

    public function store(Request $request){
        if( !$request->has('name') || trim($request->name)=="" )return $this->sendFailure(422,['name'=>'You must give a name to the slider']);
        $existingSlider = Slider::where('name',$request->name)->get();
        if( count($existingSlider)>0 ){
            return $this->sendFailure(422,['name'=>'Slider Name Already Exists']);
        }
        $slider = new Slider;
        $slider->name = $request->name;
        $slider->save();
        return $this->sendSuccess(['slider_id'=>$slider->id]);
    }

    public function update(Request $request,$id){
        if( !$request->has('name') )return $this->sendFailure(422,['name'=>'You must give a name to the slider']);
        $slider = Slider::find($id);
        if(empty($slider))return $this->sendFailure(404);
        $slider->name = $request->name;
        $slider->save();
        return $this->sendSuccess();
    }

    public function destroy($id){
        $slider = Slider::find($id);
        if( empty($slider) )return $this->sendFailure(404);
        $slides= $slider->slides();
        if( is_array($slides) ){
            foreach($slides as $slide)Slide::destroy($slide->id);
        }
        Slider::destroy($id);
        return $this->sendSuccess();
    }

    public function trash($id){
        $slider = Slider::find($id);
        if(empty($slider))return $this->sendFailure(404);
        $slider->trashed = 1;
        $slider->save();
        return $this->sendSuccess();
    }

    public function restore($id){
        $slider = Slider::find($id);
        if(empty($slider))return $this->sendFailure(404);
        $slider->trashed = 0;
        $slider->save();
        return $this->sendSuccess();
    }

    public function getTrash(){
        $sliders = Slider::where('trashed',1)->orderBy('id','desc')->get();
        return $this->sendSuccess($sliders);
    }

    public function clearTrash(){
        $sliders = Slider::where('trashed',1)->get();
        foreach($sliders as $slider){
            $id = $slider->id;
            $slides= $slider->slides();
            if(is_array($slides)){
                foreach($slides as $slide)Slide::destroy($slide->id);
            }
            Slider::destroy($id);
        }
        return $this->sendSuccess();
    }

}
