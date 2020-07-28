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
        $newMember->save();
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
        return $this->sendSuccess($member);
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
        $member = TeamMember::find($id);
        if( empty($member) || $member==null )return $this->sendFailure(404);
        $this->getTeamMember($member,$request);
        $member->asve();
        return $this->sendSuccess($member);
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
