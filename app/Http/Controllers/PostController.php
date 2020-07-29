<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Post;
use App\PostTag;
use App\Tag;
use Illuminate\Http\Request;

class PostController extends Controller
{

    public function __construct()
    {
        parent::__construct(
            ['index','show','byTag','byAuthor']
        );
    }
    
    public function index()
    {
        $posts = Post::where('trashed',0)->orderBy('id','desc')->get();
        return $this->sendSuccess($posts);
    }

    private function makePost(Post &$post,PostRequest $request){

        $cnt = Post::where('id','!=',$post->id)->where('title',$request->title)->count();
        if( $cnt>0 )return false;
        $post->title = $request->title;
        $post->author = $request->title;
        $post->text = $request->text;
        $post->save();

        $existingTags = PostTag::where('post_id',$post->id)->get();
        if( count($existingTags)>0 ){
            foreach($existingTags as $tag){
                PostTag::destroy($tag->id);
            }
        }

        $newTags = array();
        if( $request->has('tags') ){
            $tags = $request->tags;
            if( is_array($tags) ){
                foreach( $tags as $tag){
                    $postTag = new PostTag();
                    $postTag->post_id = $post->id;
                    $postTag->tag_id = $tag;
                    $curTag = Tag::find($tag);
                    if( $curTag->trashed==0 ){
                        $postTag->save();
                        array_push($newTags,$curTag);
                    }
                }
            }
        }
        $post['tags'] = $newTags;
        return true;
    }

    private function WithRelatedPosts($post){
        $tags = PostTag::select('tag_id')->where('post_id',$post->id)->distinct()->get();
        $postIds = PostTag::select('post_id')->where('post_id','!=',$post->id)->whereIn('tag_id',$tags)->distinct()->get();
        $posts = Post::whereIn('id',$postIds)->orderBy('id','desc')->get();
        $post['related_posts'] = $posts;
        return $post;
    }

    public function byTag($tagId){
        $postIds = PostTag::select('post_id')->where('tag_id',$tagId)->get();
        $posts = Post::whereIn('id',$postIds)->orderBy('id','desc')->get();
        return $this->sendSuccess($posts);
    }

    public function byAuthor(Request $request){
        $author = ( $request->has('author') ) ? ( trim($request->author) ) : "";
        $posts = Post::where('author',$author)->orderBy('id','desc')->get();
        return $this->sendSuccess($posts);
    }
    
    public function store(PostRequest $request)
    {
        $post = new Post;
        if( $this->makePost($post,$request) ){
            return $this->sendSuccess($post);
        }
        return $this->sendFailure(
            422,['title'=>'Post Title Already Exists']
        );
    }

   
    public function show($id)
    {
        $post = Post::find($id);
        if( empty($post) ) return $this->sendFailure(404);

        $tagIds = PostTag::select('tag_id')->where('post_id',$id)->get();

        $tags = Tag::whereIn('id',$tagIds)->get();
        $post['tags'] = $tags;
        return $this->sendSuccess( $this->WithRelatedPosts($post) );
    }

    public function update(PostRequest $request, $id)
    {
        $post = Post::find($id);
        if( empty($post) )return $this->sendFailure(404);
        if( $this->makePost($post,$request) ){
            return $this->sendSuccess($post);
        }
        return $this->sendFailure(
            422,['title'=>'Post Title Already Exists']
        );
    }

   
    public function destroy($id)
    {
        $post = Post::find($id);
        if( empty($post) )return $this->sendFailure(404);
        $post->trashed = 1;
        $post->save();
        return $this->sendSuccess();
    }
}
