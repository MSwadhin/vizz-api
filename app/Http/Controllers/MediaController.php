<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Media;
use App\Http\Services\UtilityService;
use App\Http\Requests\FileUploadRequest;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $utilityService;

    public function __construct()
    {
        parent::__construct(
            ['index','show','store']
        );
    }


    /*
        Utility Methods 
    */
    private function getFileType( $fileExtension ){
        if( $fileExtension=="jpg" )return "image";
        if( $fileExtension=="jpeg" )return "image";
        if( $fileExtension=="png" )return "image";
        if( $fileExtension=="svg" )return "image";
        if( $fileExtension=="ai" )return "image";
        if( $fileExtension=="ps" )return "image";
        return "doc";
    }


    public function index()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FileUploadRequest $request)
    {
        $file = $request->file('file');
        $name = $request->name;
        $title = $request->titile;
        $alt = $request->alt;
        $path = base_path().'/public/uploads';

        if( $name =="" or !isset($name) ){
            $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);;
        }
        if( $title="" or !isset($title) ) {
            $title = "Vizz Arch";
        }

        $media = new Media;
        $media->name = $name;
        $media->title = $name;
        $media->alt = $alt;
        $fileNewName = time() . "." . $file->getClientOriginalExtension();
        $media->path = $path . "/" . $fileNewName;
        $media->type = $this->getFileType($file->getClientOriginalExtension());
        
        if( $file->move($path,$fileNewName) ){

            if( $media->save() ){
                
                return $this->utilityService->is200ResponseWithData(
                    "Success",
                    $media
                );
            }
            return $this->utilityService->is500Response("DB Failed");
        }
        return $this->utilityService->is500Response("Upload  Failed");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
