<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class FlowController extends Controller
{
    public function newFlow($node_id)
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
            $i++;
        }
	
	   return view('flowTable', ['nodes'=>$a, 'currentNode'=>$node_id]);
    }

    public function chooseMeter($node_id)
    {
    	$client = new Client();
        $response = $client->get('http://localhost:8181/restconf/config/opendaylight-inventory:nodes/node/'.$node_id, ['auth' => ['admin', 'admin']]);
        $json = json_decode($response->getBody()->getContents(), true);
        $meters = $json['node']['0']['flow-node-inventory:meter'];
        /*foreach ($meters as $meter) 
        {
            if($meter['meter-name'])
            {
                if($meter['meter-name'] == $meter_name)
                {
                    $meter_id = $meter['meter-id'];
                }
            }
        }*/
        return view('newFlow', ['meters'=>$meters, 'currentNode'=>$node_id]);
    }

    public function deleteMeter($node_id, $meter_name)
    {
        $client = new Client();
        $response = $client->get('http://localhost:8181/restconf/config/opendaylight-inventory:nodes/node/'.$node_id, ['auth' => ['admin', 'admin']]);
        $json = json_decode($response->getBody()->getContents(), true);
        $meters = $json['node']['0']['flow-node-inventory:meter'];
        foreach ($meters as $meter) 
        {
            if($meter['meter-name'])
            {
                if($meter['meter-name'] == $meter_name)
                {
                    $meter_id = $meter['meter-id'];
                }
            }
        }
        $response = $client->delete('http://localhost:8181/restconf/config/opendaylight-inventory:nodes/node/'.$node_id.'/meter/'.$meter_id, ['auth' => ['admin', 'admin']]);
        echo "<script>alert('Delete successfully!'); location.href = 'http://localhost:8000/node/openflow:1';</script>";
        //return alert("Delete successfully");
    }

    public function deleteFlowTable($node_id, $table_id)
    {
        $client = new Client();
        $response = $client->get('http://localhost:8181/restconf/config/opendaylight-inventory:nodes/node/'.$node_id.'/table/'.$table_id, ['auth' => ['admin', 'admin']]);
        if($response->getStatusCode() != 404)
        {
        	$json = json_decode($response->getBody()->getContents(), true);
	        $flows = $json['flow-node-inventory:table'];
	        foreach ($flows as $flow) 
	        {
	            if ($flow['id'] == $table_id)
	            {
	            	$flows_array = $flow['flow'];
	            }
	        }
	        return view('deleteFlow', ['node_id' => $node_id, 'flows_array' => $flows_array, 'table_id' => $table_id]);
        }
        else
        {
        	echo "<script>alert('No flows!'); location.href = 'http://localhost:8000/node/{$node_id}/deleteflowmenu';</script>";
        }
        
    }

    public function deleteFlowMenu($node_id)
    {
    	$client = new Client();
        $response = $client->get('http://localhost:8181/restconf/config/opendaylight-inventory:nodes/node/'.$node_id, ['auth' => ['admin', 'admin']]);
        $json = json_decode($response->getBody()->getContents(), true);
        $tables = $json['node']['0']['flow-node-inventory:table'];
        return view('table', ['node_id' => $node_id, 'tables' => $tables]);
    }
    
    public function deleteFlow($node_id, $table_id, $flow_name)
    {
    	$client = new Client();
        $response = $client->get('http://localhost:8181/restconf/config/opendaylight-inventory:nodes/node/'.$node_id.'/table/'.$table_id, ['auth' => ['admin', 'admin']]);
        $json = json_decode($response->getBody()->getContents(), true);
        $flows = $json['flow-node-inventory:table'][0]['flow'];
        foreach ($flows as $flow) 
        {
            if($flow['flow-name'] == $flow_name)
            {
                $flow_id = $flow['id'];
            }
        }
        $response = $client->delete('http://localhost:8181/restconf/config/opendaylight-inventory:nodes/node/'.$node_id.'/table/'.$table_id.'/flow/'.$flow_id, ['auth' => ['admin', 'admin']]);
        echo "<script>alert('Delete successfully!'); location.href = 'http://localhost:8000/node/{$node_id}/deleteflowmenu/table/{$table_id}';</script>";
    }
}
