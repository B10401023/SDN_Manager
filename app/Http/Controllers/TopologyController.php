<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class TopologyController extends Controller
{
    //
    public function showTopology()
    {
    	$url = 'http://localhost:8181/restconf/operational/network-topology:network-topology/';

    	$client = new Client();
        $response = $client->get($url, [
            'auth' => [
                'admin', 
                'admin'
            ]
        ]);

        $json = json_decode($response->getBody()->getContents(), true);

	$nodes = $json['network-topology']['topology']['0']['node'];
	
	return view('nodes', ['allNodes'=>$nodes]);

    }

	public function controlNode($node_id)
	{
		return view('control', ['node'=>$node_id]);
	}

}

