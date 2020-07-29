<?php

namespace App\Http\Controllers;

use App\Http\Requests\TagRequest;
use App\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    
    public function __construct()
    {
        parent::__construct(
            ['index']
        );
    }

    public function index()
    {
        $tags = Tag::where('trashed',0)->orderBy('name','asc')->get();
        return $this->sendSuccess($tags);
    }
    


    public function store(TagRequest $request)
    {
        $tag = new Tag();
        $tag->name = $request->name;
        $tag->save();
        return $this->sendSuccess($tag);
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $tag = Tag::find($id);
        if( empty($tag) )return $this->sendFailure(404);
        $cnt = Tag::where('id','!=',$id)->where('name',$request->name)->count();
        if( $cnt!=0 )return $this->sendFailure(
            ['Tag'=>'Tag Already Exists']
        );
        $tag->name = $request->name;
        $tag->save();
        return $this->sendSuccess($tag);
    }
    public function destroy($id)
    {
        $tag = Tag::find($id);
        if( empty($tag) )return $this->sendFailure(404);
        $tag->trashed = 1;
        $tag->save();
        return $this->sendSuccess();
    }
}
