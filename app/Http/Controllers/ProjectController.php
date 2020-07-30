<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\ProjectRequest;
use App\Media;
use App\Project;
use App\ProjectCategory;
use Illuminate\Http\Request;

class ProjectController extends Controller
{

    public function __construct()
    {
        parent::__construct(
            ['index','show']
        );
    }


    private function thumbWithMedia(Project $project){
        
        $project['ft-media'] = $this->mediaService->getMediaFilteredById($project->ft_img);
        $catIds = ProjectCategory::select('category_id')->where('project_id',$project->id)->get();
        $cats = Category::whereIn('id',$catIds)->where('trashed',0)->get();
        $project['cats'] = $cats;
        return $project;
    }
    private function withMedia(Project $project){
        $project['bg-media'] = $this->mediaService->getMediaFilteredById($project->bg_img);;
        $project = $this->thumbWithMedia($project);
        $gallery = $project->gallery;
        $galleryMedia = Media::whereIn('id',$gallery)->where('trashed',0)->get();
        $project['gallery-media'] = $galleryMedia;
        return $project;
    }
    private function makeProject(Project &$project,ProjectRequest $request){
        
        $project->name = $request->name;
        $project->description = $request->description;
        $project->client = $request->client;
        $project->cd = $request->cd;
        $project->date = $request->date;
        $project->gallery = ($request->gallery);
        $project->ft_img = intval($request->ft_img);
        $project->bg_img = intval($request->bg_img);
        $project->facebook = ($request->has('facebook')) ? $request->facebook : "#";
        $project->twitter = ($request->has('twitter')) ? $request->twitter : "#";
        $project->linkedin = ($request->has('linkedin')) ? $request->linkedin : "#";
        $project->youtube = ($request->has('youtube')) ? $request->youtube : "#";
        $project->instagram = ($request->has('instagram')) ? $request->instagram : "#";
        $project->save();


        $prevCats = ProjectCategory::where('project_id',$project->id)->get();
        if(count($prevCats)>0){
            foreach($prevCats as $cat){
                ProjectCategory::destroy($cat->id);
            }
        }
        foreach( $request->cats as $cat){
            $pc = new ProjectCategory;
            $pc->category_id = $cat;
            $pc->project_id = $project->id;
            $pc->save();
        }

    }
    
    public function index()
    {
        $projects = Project::where('trashed',0)->orderBy('date','desc')->orderBy('id','desc')->get();
        if( count($projects)>0 ){
            foreach($projects as $project){
                $project = $this->thumbWithMedia($project);
            }
        }
        return $this->sendSuccess($projects);
    }



   
    public function store(ProjectRequest $request)
    {
        $project = new Project;
        $cnt = Project::where('name',$request->name)->count();
        if( $cnt>0 )return $this->sendFailure(422,[
            'name' => 'Project Name Already Exists'
        ]);
        $this->makeProject($project,$request);
        return $this->sendSuccess( $this->withMedia($project) );
    }

   
    public function show($id)
    {
        $project = Project::find($id);
        if( empty($project) )return $this->sendFailure(404);
        return $this->sendSuccess($this->withMedia($project));
    }

    
    public function update(ProjectRequest $request, $id)
    {
        $project = Project::find($id);
        if( empty($project) )return $this->sendFailure(404);
        $cnt = Project::where('id','!=',$id)->where('name',$request->name)->count();
        if( $cnt>0 )return $this->sendFailure(422,[
            'name' => 'Project Name Already Exists'
        ]);
        $this->makeProject($project,$request);
        return $this->sendSuccess( $this->withMedia($project) );
    }

    
    public function destroy($id)
    {
        $project = Project::find($id);
        if( empty($project) )return $this->sendFailure(404);
        $project->trashed = 1;
        $project->save();
        return $this->sendSuccess();
    }
}
