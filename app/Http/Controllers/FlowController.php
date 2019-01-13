<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;

class FlowController extends Controller
{
    /*public function newFlow($node_id)
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
            array_push($a, $switch['flow-node-inventory:name']);
            $i++;
        }
		
	   return view('flowTable', ['nodes'=>$a, 'currentNode'=>$node_id]);
    }*/

    public function chooseMeter($node_id)
    {
    	$flow_id = 0;
    	$client = new Client();
        $response = $client->get('http://localhost:8181/restconf/config/opendaylight-inventory:nodes/node/'.$node_id, ['auth' => ['admin', 'admin']]);
        $json = json_decode($response->getBody()->getContents(), true);
        $meters = $json['node']['0']['flow-node-inventory:meter'];
        $url = 'http://localhost:8181/restconf/config/opendaylight-inventory:nodes/node/'.$node_id.'/table/0';
        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
        /* Get the HTML or whatever is linked in $url. */
        $response4 = curl_exec($handle);
        /* Check for 404 (file not found). */
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        if($httpCode != 404)
        {
        	$client2 = new Client();
        	$response2 = $client2->get($url, ['auth' => ['admin', 'admin']]);
        	$json2 = json_decode($response2->getBody()->getContents(), true);
        	if(isset($json2['flow-node-inventory:table']['0']['flow']))
	        {
	            $flows = $json2['flow-node-inventory:table']['0']['flow'];
	            foreach ($flows as $flow) 
	            {
	                if($flow['id'] > $flow_id)
	                {
	                    $flow_id = $flow['id'];
	                }
	            }
	        }
        }
        else
        {

        }
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
        //return $json2;
        $url3 = 'http://localhost:8181/restconf/operational/opendaylight-inventory:nodes/node/'.$node_id;

    	$client3 = new Client();
        $response3 = $client3->get($url3, [
            'auth' => [
                'admin', 
                'admin'
            ]
        ]);
        $arr = array();
        $i = 0;
        $json3 = json_decode($response3->getBody()->getContents(), true);
        $nodes = $json3['node']['0']['node-connector'];
        foreach($nodes as $switch)
        {
            if (strpos($switch['flow-node-inventory:name'], 'eth') !== false)
            {
            	$pos = strpos($switch['flow-node-inventory:name'], 'eth');
            	array_push($arr, substr($switch['flow-node-inventory:name'], $pos, 4));
            	$i++;
            }
        }
        //return $arr;
        return view('newFlow', ['meters'=>$meters, 'id'=>$node_id, 'count' => ++$flow_id, 'arr'=>$arr]);
    }

    public function submit($node_id)
    {
    	$in_port = $_GET['from'];
        $out_port = $_GET['to'];
    	$url2 = 'http://localhost:8181/restconf/operational/opendaylight-inventory:nodes/node/'.$node_id;

    	$client2 = new Client();
        $response2 = $client2->get($url2, [
            'auth' => [
                'admin', 
                'admin'
            ]
        ]);
        $in_port_num;
        $out_port_num;
        $json2 = json_decode($response2->getBody()->getContents(), true);
        $nodes = $json2['node']['0']['node-connector'];
        foreach($nodes as $switch)
        {
        	if ($switch['flow-node-inventory:name'] == 's'.substr($node_id, -1).'-'.$in_port)
        	{
        		$in_port_num = $switch['flow-node-inventory:port-number'];
        	}
        	if ($switch['flow-node-inventory:name'] == 's'.substr($node_id, -1).'-'.$out_port)
        	{
        		$out_port_num = $switch['flow-node-inventory:port-number'];
        	}
        }
        $flow_id = $_GET['flowId'];
    	$url = 'http://localhost:8181/restconf/config/opendaylight-inventory:nodes/node/'.$node_id.'/table/0/flow/'.$flow_id;
        $flowName = $_GET['flowName'];
        $meter = $_GET['meter'];
        
        $body = '{
			    "flow-node-inventory:flow": [
			        {
			            "id": "'.$flow_id.'",
			            "flow-name": "'.$flowName.'",
			            "priority": 101,
			            "table_id": 0,
			            "match": {
			                "in-port": "'.$in_port_num.'",
			                "ethernet-match": {
			                    "ethernet-type": {
			                        "type": 2048
			                    }
			                }
			            },
			            "instructions": {
			                "instruction": [
			                    {
			                        "order": 1,
			                        "meter": {
			                            "meter-id": '.$meter.'
			                        }
			                    },
			                    {
			                        "order": 0,
			                        "apply-actions": {
			                            "action": [
			                                {
			                                    "order": 0,
			                                    "output-action": {
			                                        "output-node-connector": "'.$out_port_num.'"
			                                    }
			                                }
			                            ]
			                        }
			                    }
			                ]
			            }
			        }
			    ]
			}';


        $client = new Client();

        $response = $client->put($url, [
            'auth' => [
                'admin', 
                'admin'
            ], 
            'headers' => [
                'Content-Type' => 'application/json',
            ], 
            'body' => $body
        ]);
        //return $json2;
        echo "<script>alert('Successfully!'); location.href = 'http://localhost:8000/node/{$node_id}';</script>";
    }

    public function deleteFlowTable($node_id, $table_id)
    {
    	$url = 'http://localhost:8181/restconf/config/opendaylight-inventory:nodes/node/'.$node_id.'/table/0';
        
        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
        /* Get the HTML or whatever is linked in $url. */
        $response = curl_exec($handle);
        /* Check for 404 (file not found). */
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        if($httpCode != 404)
        {
        	$flow_table = array();
        	$client = new Client();
        	$response = $client->get($url, ['auth' => ['admin', 'admin']]);
        	$json = json_decode($response->getBody()->getContents(), true);
        	if(isset($json['flow-node-inventory:table']['0']['flow']))
        	{
        		$flows = $json['flow-node-inventory:table']['0']['flow'];
		        foreach ($flows as $flow) 
		        {
		            array_push($flow_table, $flow['flow-name']);
		        }
		        return view('deleteFlow', ['node_id' => $node_id, 'flows_array' => $flow_table]);
        	}
        	else
        	{
        		echo "<script>alert('No flows!'); location.href = 'http://localhost:8000/node/{$node_id}';</script>";
        	}
        }
        else
        {
        	echo "<script>alert('No flows!'); location.href = 'http://localhost:8000/node/{$node_id}';</script>";
        }
        
    }

    public function deleteFlowMenu($node_id)
    {

    	$client = new Client();
        $response = $client->get('http://localhost:8181/restconf/config/opendaylight-inventory:nodes/node/'.$node_id, ['auth' => ['admin', 'admin']]);
        $json = json_decode($response->getBody()->getContents(), true);
        if(isset($json['flow-node-inventory:table']))
        {
        	$tables = $json['node']['0']['flow-node-inventory:table'];
        	return view('table', ['node_id' => $node_id, 'tables' => $tables]);
        }
        else
        {
        	echo "<script>alert('No flows!'); location.href = 'http://localhost:8000/node/{$node_id}';</script>";
        }
    }
    
    public function deleteFlow($node_id, $table_id, $flow_name)
    {
    	$url = 'http://localhost:8181/restconf/config/opendaylight-inventory:nodes/node/'.$node_id.'/table/0';
    	$client = new Client();
        $response = $client->get($url, ['auth' => ['admin', 'admin']]);
        $json = json_decode($response->getBody()->getContents(), true);
        $flows = $json['flow-node-inventory:table']['0']['flow'];
        foreach ($flows as $flow) 
        {
            if($flow['flow-name'] == $flow_name)
            {
                $flow_id = $flow['id'];
            }
        }
        $response = $client->delete('http://localhost:8181/restconf/config/opendaylight-inventory:nodes/node/'.$node_id.'/table/0/flow/'.$flow_id, ['auth' => ['admin', 'admin']]);
        echo "<script>alert('Delete successfully!'); location.href = 'http://localhost:8000/node/{$node_id}';</script>";
    }
}
