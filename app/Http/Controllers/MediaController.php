<?php

namespace App\Http\Controllers;

use App\Http\Requests\BulkFileUploadRequest;
use Illuminate\Http\Request;
use App\Media;
use App\Http\Requests\FileUploadRequest;
use Urameshibr\Requests\FormRequest;

class MediaController extends Controller
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

    /* 
    *
    * Utility Functions
    *
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
    private function uploadFile($file,$type){

        $path = base_path().'/public/uploads';
        $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);;
        $media = new Media;
        $media->name = $name;
        $media->title = $name;
        $fileNewName = $name . "_" . time() . "_" .time() . "." . $file->getClientOriginalExtension();
        $media->path = '/uploads/' . $fileNewName;
        $media->type = $type;

        if( $file->move( $path,$fileNewName ) ){
            $media->save();
            return $media->id;
        }
        return false;
    }

    

    /*
    *
    * Route Methods
    *
    */
    public function index()
    {
        $allMedia = Media::where('trashed',0)->orderBy('id','desc')->get();
        return $this->utilityService->is200ResponseWithData("Success",$allMedia);
        return $this->sendSuccess($allMedia);
    }



    public function store(FileUploadRequest $request)
    {
        $file = $request['file'];
        $name = $request->has('name') ? $request->name : "";
        $title = $request->has('title') ? $request->title : "";
        $alt = $request->has('alt') ? $request->alt : "";
        $path = base_path().'/public/uploads';
        if( $name =="" or !isset($name) ){
            $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);;
        }
        $media = new Media;
        $media->name = $name;
        $media->title = $title;
        $media->alt = $alt;
        $fileNewName = $name . "_" . time() . "_" .time() . "." . $file->getClientOriginalExtension();
        $media->path = '/uploads/' . $fileNewName;
        $media->type = $this->getFileType($file->getClientOriginalExtension());
        
        if( $file->move($path,$fileNewName) ){
            if( $media->save() ){
                return $this->utilityService->is200ResponseWithData(
                    "Successfully Uploaded File!",
                    $media
                );
            }
            return $this->utilityService->is500Response("Internal Server Error!");
        }
        return $this->utilityService->is500Response("Upload Failed!");
    }


    public function show($id)
    {
        $media = Media::find($id);
        if( empty($media) )
            return $this->sendFailure(404);
        return $this->sendSuccess($media);
    }


    // takes name,titile,alt for a file
    public function update($id,Request $request)
    {
        $media = Media::find($id);
        if( !empty($media) ){
            $media->name = $request->name;
            $media->title = $request->title;
            $media->alt = $request->alt;
            $media->save();
            return $this->utilityService->is200Response("Saved!");
        }
        return $this->utilityService->is422Response("File Not Found");
    }


    
    // Moves to trash
    public function destroy($id)
    {
        $media = Media::find($id);
        if( empty($media) ){
            return $this->sendFailure(404);;
        }
        $media->trashed = 1;
        $media->save();
        return $this->sendSuccess();
    }


    // restores any media from trash
    public function restore($id){
        $media = Media::find($id);
        if( empty($media) ){
            return $this->sendFailure(404);
        }
        $media->trashed = 0;
        $media->save();
        return $this->sendSuccess();
    }


    // gets files which are in trash
    public function get_trash( Request $request ){
        $media = Media::where('trashed',1)->orderBy('id','desc')->get();
        return $this->sendSuccess($media);
    }


    public function bulkUpload(BulkFileUploadRequest $request){
        $suc = 0;
        $fail=0;
        $uploadedIds = [];
        foreach( $request->files as $file){
            $type = $this->getFileType($file->getClientOriginalExtension());
            if( !$cid = $this->uploadFile($file,$type) ){
                $fail++;
            }
            else {
                $suc++;
                array_push($uploadedIds,$cid);
            }
        }
        return $this->sendSuccess([
            'Uploaded' => $suc,
            'Failed' => $fail,
            'ids' => $uploadedIds
        ]);
    }


    // clears the trash and deletes all trashed files from uploads folder
    public function clearTrash(){
        $trashbin = Media::where('trashed',true)->get();
        $fail = 0;
        $suc = 0;
        if( !empty($trashbin) && count($trashbin)>0 ){
            foreach($trashbin as $media){
                if( unlink( $media->path ) ){
                    Media::destroy($media->id);
                    $suc++;
                }
                else $fail++;
            }
        }
        return $this->sendSuccess([
            'Falis ' => $fail
        ]);
    }
}
