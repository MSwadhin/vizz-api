<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoryRequest;
use App\Story;
use Illuminate\Http\Request;

class StoryController extends Controller
{
    
    public function __construct()
    {
        parent::__construct(
            ['index','show']
        );
    }

    private function makeStory(Story &$story,StoryRequest $request){
        
        $request->end_year = trim($request->end_year);
        $request->start_year = trim($request->start_year);
        $request->description = trim($request->description);
        $request->title = trim($request->title);
        $request->ft_img = trim($request->ft_img);

        $story->title = $request->title;
        $story->description = $request->description;
        $story->start_year = ($request->start_year!="") ? $request->start_year : 1999;
        $story->end_year = ($request->end_year!="" ) ? $request->end_year : 2999;
        $story->ft_img = $request->ft_img;
    }

    private function withMedia(Story $story){
        $story['media'] = $this->mediaService->getMediaFilteredById($story->ft_img);
        return $story;
    }


    public function index()
    {
        $storeis = Story::where('trashed',0)
                            ->orderBy('end_year','desc')
                            ->orderBy('start_year','desc')
                            ->orderBy('title','asc')
                            ->get();
        if( count($storeis)>0 )
            foreach( $storeis as $story )
                $story = $this->withMedia($story);
        return $this->sendSuccess($storeis);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoryRequest $request)
    {
        $story = new Story;
        $this->makeStory($story,$request);
        $story->save();
        return $this->sendSuccess($this->withMedia($story));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $story = Story::find($id);
        if( empty($story) )return $this->sendFailure(404);
        return $this->sendSuccess($this->withMedia($story));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoryRequest $request, $id)
    {
        $story = Story::find($id);
        if( empty($story) )return $this->sendFailure(404);
        $this->makeStory($story,$request);
        $story->save();
        return $this->sendSuccess($this->withMedia($story));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $story = Story::find($id);
        if( empty($story) )return $this->sendFailure(404);
        $story->trashed = 1;
        $story->save();
        return $this->sendSuccess();
    }
}
