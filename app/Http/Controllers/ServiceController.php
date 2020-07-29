<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceRequest;
use App\Service;
use App\SubService;
use Illuminate\Http\Request;

class ServiceController extends Controller
{

    public function __construct()
    {
        parent::__construct(
            ['index','show']
        );
    }

    private function getService( ServiceRequest $request,Service &$service=null ){

        $request->title = trim($request->title);
        $request->description = trim($request->description);
        $request->ft_img = trim($request->ft_img);
        $request->button_link = trim($request->button_link);
        if( $request->has('order') )
            $request->order = trim($request->order);
        if( $service==null )$service = new Service;
        $service->title = $request->title;
        $service->description = $request->description;
        $service->ft_img = $request->ft_img;
        $service->button_link = ($request->has('button_link')) ? $request->button_link : '#';
        $service->order = ( $request->has('order') && $request->order!="" ) ? $request->order : 100000;
        return $service;
    }

    private function withMedia($service){
        $service['media'] = $this->mediaService->getMediaFilteredById($service->ft_img);
        $subs = SubService::where('service_id',$service->id)->where('trashed',0)->orderBy('title','asc')->get();
        if( count($subs)>0 ){
            foreach( $subs as $sub ){
                $sub['media'] = $this->mediaService->getMediaFilteredById($sub->icon);
                // array_push($service['subservices'],$sub);
            }
        }
        $service['sub_services'] = $subs;
        return $service;
    }
    
    public function index()
    {
        $services = Service::where('trashed',0)->orderBy('order','asc')->orderBy('title','asc')->get();
        foreach($services as $service){
            $service = $this->withMedia($service);
        }
        return $this->sendSuccess($services);
    }

    public function store(ServiceRequest $request)
    {
        $service = $this->getService($request);
        $service->save();
        return $this->sendSuccess($this->withMedia($service));
    }

    public function show($id)
    {
        $service = Service::find($id);
        if( empty($service) )return $this->sendFailure(404);
        return $this->sendSuccess($this->withMedia($service));
    }

    public function update(ServiceRequest $request, $id)
    {
        $cnt = Service::where('id',$id)->count();
        if( $cnt!=1 )return $this->sendFailure(404);
        $service = Service::find($id);
        $service = $this->getService($request,$service);
        $service->id = $id;
        $service->save();
        return $this->sendSuccess($this->withMedia($service));
    }

    public function destroy($id)
    {
        $service = Service::find($id);
        if( empty($service) )return $this->sendFailure(404);
        $service->trashed = 1;
        $service->save();
        return $this->sendSuccess();
    }
}
