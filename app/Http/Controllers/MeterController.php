<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;


class MeterController extends Controller
{
    public function newMeter($node_id)
    {
        $meter_id = 0;
        $url = 'http://localhost:8181/restconf/config/opendaylight-inventory:nodes/node/'.$node_id;
        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
        /* Get the HTML or whatever is linked in $url. */
        $response = curl_exec($handle);
        /* Check for 404 (file not found). */
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        if($httpCode != 404)
        {
            $client = new Client();
            $response = $client->get($url, ['auth' => ['admin', 'admin']]);
            $json = json_decode($response->getBody()->getContents(), true);
            if(isset($json['node']['0']['flow-node-inventory:meter']))
            {
                $meters = $json['node']['0']['flow-node-inventory:meter'];
                foreach ($meters as $meter) 
                {
                    if($meter['meter-id'] > $meter_id)
                    {
                        $meter_id = $meter['meter-id'];
                    }
                }
            }
            else
            {
                // do nothing
            }
        }
        else
        {
            // do nothing
        }
        //return $json;
        return view('newMeter', ['id'=>$node_id, 'count' => ++$meter_id]);
    }

    public function newMeterSubmit($node_id, $count)
    {
        $url = 'http://localhost:8181/restconf/config/opendaylight-inventory:nodes/node/'.$node_id.'/meter/'.$count;
        $meterName = $_GET['meterName'];
        $dropRate = $_GET['dropRate'];

        $body = '{
            "flow-node-inventory:meter": [
                {
                    "meter-id": '.$count.', 
                    "meter-name": "'.$meterName.'", 
                    "flags": "meter-kbps meter-burst", 
                    "container-name": "abcd",
                    "meter-band-headers": {
                        "meter-band-header": [
                            {
                                "band-id": 0,
                                "meter-band-types": {
                                    "flags": "ofpmbt-drop"
                                },
                                "drop-rate": '.$dropRate.',
                                "drop-burst-size": 100
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
        echo "<script>alert('Successfully!'); location.href = 'http://localhost:8000/node/{$node_id}';</script>";
    }
    public function editMeter($node_id)
    {
        

        

        
    }

    public function deleteMeter($node_id, $meter_name)
    {
        $url = 'http://localhost:8181/restconf/config/opendaylight-inventory:nodes/node/'.$node_id;
        $client = new Client();
        $response = $client->get($url, ['auth' => ['admin', 'admin']]);
        $json = json_decode($response->getBody()->getContents(), true);
        $meters = $json['node']['0']['flow-node-inventory:meter'];
        foreach ($meters as $meter) 
        {
            if($meter['meter-name'] == $meter_name)
            {
                $meter_id = $meter['meter-id'];
            }
        }
        $response = $client->delete('http://localhost:8181/restconf/config/opendaylight-inventory:nodes/node/'.$node_id.'/meter/'.$meter_id, ['auth' => ['admin', 'admin']]);
        echo "<script>alert('Delete successfully!'); location.href = 'http://localhost:8000/node/{$node_id}';</script>";
        //return alert("Delete successfully");
    }

    public function deleteMeterMenu($node_id)
    {
        $url = 'http://localhost:8181/restconf/config/opendaylight-inventory:nodes/node/'.$node_id;
        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
        /* Get the HTML or whatever is linked in $url. */
        $response = curl_exec($handle);
        /* Check for 404 (file not found). */
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        if($httpCode != 404)
        {
            $client = new Client();
            $response = $client->get('http://localhost:8181/restconf/config/opendaylight-inventory:nodes/node/'.$node_id, ['auth' => ['admin', 'admin']]);
            $json = json_decode($response->getBody()->getContents(), true);
            if(isset($json['node']['0']['flow-node-inventory:meter']))
            {
                $nodes = $json['node']['0']['flow-node-inventory:meter'];
                return view('deleteMeter', ['node_id' => $node_id, 'nodeList' => $nodes]);
            }
            else
            {
                echo "<script>alert('No meter!'); location.href = 'http://localhost:8000/node/{$node_id}';</script>";
            }
        }
        else
        {
            echo "<script>alert('No meters!'); location.href = 'http://localhost:8000/node/{$node_id}';</script>";
        }
    }
}
