<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubServiceRequest;
use App\SubService;
use Illuminate\Http\Request;

class SubServiceController extends Controller
{

    public function __construct()
    {
        parent::__construct([]);
    }

    private function makeSubService( SubService &$service,SubServiceRequest $request ){
        $request->title = trim($request->title);
        $request->description = trim($request->description);
        $request->icon = trim($request->icon);
        $request->service_id = trim($request->service_id);
        $service->title = $request->title;
        $service->description = $request->description;
        $service->icon = $request->icon;
        $service->service_id = $request->service_id;
        return $service;
    }


    public function index()
    {
        // Intentinally Left Blank
        // No Need to Implement For The Current Version
    }

  
    public function store(SubServiceRequest $request)
    {
        $service = new SubService();
        $this->makeSubService($service,$request);
        $service->save();
        return $this->sendSuccess();
    }

    public function show($id)
    {
        $service = SubService::find($id);
        if( empty($service) )return $this->sendFailure(404);
        $iconId = $service->icon;
        $service->icon = $this->mediaService->getMediaFilteredById($iconId);
        return $this->sendSuccess($service);
    }

    public function update(SubServiceRequest $request, $id)
    {
        $service = SubService::find($id);
        if( empty($service) )return $this->sendFailure(404);
        $this->makeSubService($service,$request);
        $service->save();
        return $this->sendSuccess();
    }
    public function destroy($id)
    {
        $service = SubService::find($id);
        if( empty($service) )return $this->sendFailure(404);
        $service->trashed = 1;
        $service->save();
        return $this->sendSuccess();
    }
}
