<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeamMemberRequest;
use App\TeamMember;
use Illuminate\Http\Request;

class TeamMemberController extends Controller
{


    public function __construct()
    {
        parent::__construct(
            ['index','show']
        );
    }


    private function getTeamMember(TeamMember &$member, TeamMemberRequest $request){
        $member->name = $request->name;
        $member->designation = $request->designation;
        $member->media_id = $request->media_id;
        $member->order = ( $request->has('order') && is_int($request->order)) ? $request->order : 100000;
        $member->facebook = ( $request->has('facebook') ) ? $request->facebook : "";
        $member->twitter = ( $request->has('twitter') ) ? $request->facebook : "";
        $member->linkedin = ( $request->has('linkedin') ) ? $request->facebook : "";
        $member->instagram = ( $request->has('instagram') ) ? $request->facebook : "";
        $member->youtube = ( $request->has('youtube') ) ? $request->facebook : "";
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $team = TeamMember::where('trashed',0)->orderBy('id','desc')->get();
        if( count($team)>0 ){
            foreach( $team as $member ){
                $member['media'] = $this->mediaService->getMediaFilteredById($member->media_id);
            }
        }
        return $this->sendSuccess($team);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TeamMemberRequest $request)
    {
        $newMember = new TeamMember;
        $this->getTeamMember($newMember,$request);
        if( TeamMember::where('name',$newMember->name)->count() > 0 ){
            return $this->sendFailure(
                422,[
                    'name' => 'Team Member Name Already Exists'
                ]
            );
        }
        $newMember->save();
        $newMember['media'] = $this->mediaService->getMediaFilteredById($newMember->media_id);
        return $this->sendSuccess($newMember);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $member = TeamMember::find($id);
        if ( empty($member) || $member==null )return $this->sendFailure(404);
        $member['media'] = $this->mediaService->getMediaFilteredById($member->media_id);
        return $this->sendSuccess($member);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TeamMemberRequest $request, $id)
    {
        
        $member = TeamMember::find($id);
        if( empty($member) || $member==null )return $this->sendFailure(404);
        $cnt = TeamMember::where('id','!=',$id)->where('name',$request->name)->count();
        if( $cnt==0 ){
            $this->getTeamMember($member,$request);
            $member->save();
            $member['media'] = $this->mediaService->getMediaFilteredById($member->media_id);
            return $this->sendSuccess($member);
        }
        return $this->sendFailure(422,[
            'name'=>'Team Member Name Already Exists!'
        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $member = TeamMember::find($id);
        if( empty($member) || $member==null )return $this->sendFailure(404);
        $member->trashed = 1;
        $member->save();
        return $this->sendSuccess();
    }
}
