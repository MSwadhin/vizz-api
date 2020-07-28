<?php

namespace App\Http\Controllers;

use App\Client;
use App\Http\Requests\ClientRequest;
use App\Media;
use Illuminate\Http\Request;

class ClientController extends Controller
{

    public function __construct()
    {
        parent::__construct(
            ['index','show']
        );
    }

    private function withMedia(Client $client){
        $client['media'] = $this->mediaService->getMediaFilteredById($client->media_id);
        return $client;
    }


    private function getClient(ClientRequest $request){
        $client = new Client;
        $client->name = ($request->has('name')) ? $request->name : "Unnamed Client";
        $client->media_id = $request->media_id;
        $client->order = ( $request->has('order') && trim($request->order)!="" ) ? $request->order : 100000;
        $client->description = ($request->has('description')) ? $request->description : "Unnamed Client";
        return $client;
    }


    
    public function index()
    {
        $clients = Client::where('trashed',0)->orderBy('order','asc')->orderBy('id','desc')->get();
        if( count($clients)>0 )
            foreach($clients as $client)
                $client['media'] = $this->mediaService->getMediaFilteredById($client->media_id);
        return $this->sendSuccess($clients);
    }

    public function store(ClientRequest $request)
    {
        $client = $this->getClient($request);
        $client->save();
        return $this->sendSuccess($this->withMedia($client));
    }

    
    public function show($id)
    {
        $client = Client::find($id);
        if( empty($client) )return $this->sendFailure(404);
        return $this->withMedia($client);
    }

    
    /**
     * 
     * 
     * Intentionally not implemented
     * Update Not Needed in Client Module
     */
    public function update(Request $request, $id)
    {
        //
    }

   
    public function destroy($id)
    {
        $client = Client::find($id);
        if( empty($client) )return $this->sendFailure(404);
        $client->trashed = 1;
        $client->save();
        return $this->sendSuccess();
    }
}
