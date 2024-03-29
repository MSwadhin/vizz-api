<?php

namespace App\Http\Controllers;

use App\Http\Requests\BulkFileUploadRequest;
use App\Http\Requests\BulkTrashRequest;
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

    

    function public_path($path = null){
        return rtrim(app()->basePath('public/' . $path), '/');
    }


    /* 
    *
    * Utility Functions
    *
    */ 
    private function getFileType( $fileExtension ){
        if( strtolower($fileExtension)=="jpg" )return "image";
        if( strtolower($fileExtension)=="jpeg" )return "image";
        if( strtolower($fileExtension)=="png" )return "image";
        if( strtolower($fileExtension)=="svg" )return "image";
        if( strtolower($fileExtension)=="ai" )return "image";
        if( strtolower($fileExtension)=="ps" )return "image";
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
        $allMedia = Media::where('trashed',0)->orderBy('updated_at','desc')->get();
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
                return $this->sendSuccess($media);
            }
            return $this->sendFailure(500,"DB Failure!");
        }
        return $this->sendFailure(500,"Upload Failed");;
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
            return $this->sendSuccess($media);
        }
        return $this->sendFailure(404);
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
        $media = Media::where('trashed',1)->where('deleted',0)->orderBy('updated_at','desc')->get();
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
        $trashbin = Media::where('trashed',true)->where('deleted',0)->get();
        $fail = 0;
        $suc = 0;
        if( !empty($trashbin) && count($trashbin)>0 ){
            foreach($trashbin as $media){

                $filePath = $this->public_path($media->path);
                if( file_exists($filePath) && unlink( $filePath ) ){
                    $suc++;
                }
                else $fail++;
                $media->trashed = 1;
                $media->deleted = 1;
                $media->save();
            }
        }
        return $this->sendSuccess([
            'Falis ' => $fail
        ]);
    
    
    }


    public function bulkTrash(BulkTrashRequest $request){
        $ids = $request->items;
        $media = Media::whereIn('id',$ids)->get();
        // return $this->sendSuccess($media);
        foreach( $media as $curMedia ){
            $curMedia->trashed = 1;
            $curMedia->save();
        }
        return $this->sendSuccess("Successfully Moved to Trash");
    }
}
