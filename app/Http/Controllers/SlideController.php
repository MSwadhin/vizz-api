<?php

namespace App\Http\Controllers;

use App\Http\Requests\SlideRequest;
use App\Http\Services\MediaService;
use App\Slide;
use Illuminate\Http\Request;

class SlideController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        parent::__construct(
            ['index','show']
        );
    }

    public function index()
    {
        $slides = Slide::orderBy('id','desc')->get();
        return $this->sendSuccess($slides);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SlideRequest $request)
    {
        $slide = new Slide;
        $slide->name = $request->name;
        $slide->slider_id = $request->slider_id;
        $slide->media_id = $request->media_id;
        $slide->save();
        return $this->sendSuccess();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $slide = Slide::find($id);
        if( empty($slide) || $slide==null ){
            return $this->sendFailure(404);
        }
        $slide['media'] = $this->mediaService->getMediaFilteredById($slide->media_id);
        return $this->sendSuccess($slide);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SlideRequest $request, $id)
    {   
        $slide = Slide::find($id);
        if( empty($slide) )return $this->sendFailure(404);
        $slide->name = $request->name;
        $slide->slider_id = $request->slider_id;
        $slide->media_id = $request->media_id;
        $slide->save();
        return $this->sendSuccess();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $slide = Slide::find($id);
        if( empty($slide) || $slide==null ){
            return $this->sendFailure(404);
        }
        Slide::destroy($id);
        return $this->sendSuccess();
    }
}
