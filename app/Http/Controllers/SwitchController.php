<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class SwitchController extends Controller
{
    //
    public function showFlow($node_id)
    {
    	$url = 'http://localhost:8181/restconf/operational/opendaylight-inventory:nodes/node/'.$node_id;

    	$client = new Client();
        $response = $client->get($url, [
            'auth' => [
                'admin', 
                'admin'
            ]
        ]);

        $json = json_decode($response->getBody()->getContents(), true);

        $nodes = $json['node']['0']['node-connector'];
        $a = array();
        $i = 0;

        foreach($nodes as $switch)
        {
            array_push($a, $nodes[$i]['flow-node-inventory:name']);
            //$a = array($json['node']['0']['node-connector'][$i]['flow-node-inventory:name']);
            $i++;
        }
	   return view('control', ['nodes'=>$a, 'currentNode'=>$node_id]);
    }

}