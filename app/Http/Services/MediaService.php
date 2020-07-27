<?php 
namespace App\Http\Services;
use App\Media;

class MediaService{
    public function getMediaFiltered(Media $media){
        if( $media->trashed ){
            $newMedia = new Media;
            $newMedia->name = 'Trashed';
            $newMedia->path = "/uploads/trashed.jpg";
            return $newMedia;
        }
        else return $media;
    }

    public function getMediaFilteredById($id){
        $media = Media::find($id);
        if( empty($media) ){
            $newMedia = new Media;
            $newMedia->name = "Deleted";
            $newMedia->path = "/uploads/deleted.jpg";
        }
        if( $media->trashed ){
            $newMedia = new Media;
            $newMedia->name = 'Trashed';
            $newMedia->path = "/uploads/trashed.jpg";
            return $newMedia;
        }
        else return $media;
    }
}