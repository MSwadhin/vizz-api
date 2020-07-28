<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Requests\CategoryRequest;
use Urameshibr\Requests\FormRequest;

class CategoryController extends Controller
{


    public function __construct()
    {
        parent::__construct(
            ['index','show']
        );
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cats = Category::where('trashed',0)->orderBy('name','asc')->get();
        return $this->sendSuccess($cats);
    }

    public function getTrash(){
        $cats = Category::where('trashed',1)->get();
        return $this->sendSuccess($cats);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {   
        if( Category::where('name',$request->name)->count() > 0 ){
            return $this->sendFailure(422,[
                'name' => 'Category Name Already Exists!'
            ]);
        }
        $category = new Category;
        $category->name = $request->name;
        $category->parent = $request->has('parent') ? $request->parent : 0;
        $category->save();
        return $this->sendSuccess($category);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::find($id);
        if( empty($category) )return $this->sendFailure(404);
        return $this->sendSuccess($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request,$id)
    {   
        
        $category = Category::find( $id );
        $cnt = Category::where('id','!=',$id)->where('name',$request->name)->count();
        if( $cnt>0 ){
            return $this->sendFailure(
                422,[
                    'name' => 'Category Name Already Exists!'
                ]
            );
        }

        if( empty($category) )return $this->sendFailure(404);
        $category->name = $request->name;
        $category->parent = $request->has('parent') ? $request->parent : 0;
        $category->save();
        return $this->sendSuccess($category);
    }

    public function trash($id)
    {   
        $category = Category::find($id);
        if( empty($category) )return $this->sendFailure(404);
        $category->trashed = 1;
        $category->save();
        return $this->sendSuccess();
    }


    public function restore($id)
    {   
        $category = Category::find($id);
        if( empty($category) )return $this->sendFailure(404);
        $category->trashed = 0;
        $category->save();
        return $this->sendSuccess();
    }

    public function destroy($id)
    {   
        $category = Category::find($id);
        if( empty($category) )return $this->sendFailure(404);
        Category::destroy($id);
        return $this->sendSuccess();
    }

    public function clearTrash(){
        $cats = Category::where('trashed',1)->orderBy('name','asc')->get();
        foreach($cats as $cat){
            Category::destroy($cat->id);
        }
        return $this->sendSuccess();
    }

}