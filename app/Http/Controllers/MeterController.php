<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;


class MeterController extends Controller
{
    //
    static $meter_count = 0;
    public function newMeter($node_id)
    {
        //$ch = curl_init();
 
        //傳送給 web service 的資料，並壓成json格式
        $data =  '{
    "flow-node-inventory:meter": [
        {
            "meter-id": 2, 
            "meter-name": "s3toh2", 
            "flags": "meter-kbps meter-burst", 
            "container-name": "abcd",
            "meter-band-headers": {
                "meter-band-header": [
                    {
                        "band-id": 0,
                        "meter-band-types": {
                            "flags": "ofpmbt-drop"
                        },
                        "drop-rate": 4000,
                        "drop-burst-size": 100
                    }
                ]
            }
        }
    ]
}';
                    
        //指定 header 參數與類型           
        /*$header[] = 'Authorization: Basic YWRtaW46YWRtaW4=';           
        $header[] = 'Content-Type: application/json';
        $header[] = 'Accept: application/json';
         
        curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
        curl_setopt($ch, CURLOPT_URL, "http://localhost:8181/restconf/config/opendaylight-inventory:nodes/node/openflow:2/meter/2");  //本機測試URL
        //curl_setopt($ch, CURLOPT_URL, 'https://www.ls-ecommerce.com.tw/product/inquiry'); //遠端測試URL  
        curl_setopt($ch, CURLOPT_POST, true); 
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

         
        $result = curl_exec($ch); 
         
        //顯示錯誤訊息
        if(!$result){
           print_r(curl_error($ch));
        }
            
        curl_close($ch);*/
        //$url = 'http://localhost:8181/restconf/config/opendaylight-inventory:nodes/node/openflow:2/meter/2';
            //$url = 'http://localhost:8181/restconf/config/opendaylight-inventory:nodes/node/'.$node_id.'/meter/'.$i;
        $client = new Client();
        
        $headers = ['Authorization' => 'Basic YWRtaW46YWRtaW4=', 'Content-Type' => 'application/json','Accept' => 'application/json'];
        $request = $client->put('http://localhost:8181/restconf/config/opendaylight-inventory:nodes/node/openflow:2/meter/2', ['auth' => ['admin', 'admin'], 'Headers' => $headers, 'Body' => $data]);
        //$request = $client->createRequest('PUT', '/put', ['json' => ['foo' => 'bar']]);
        //$request = new Request('PUT', 'http://localhost:8181/restconf/config/opendaylight-inventory:nodes/node/openflow:2/meter/2', $headers, $data);
        //return view('newMeter');
        
        return view('newMeter', ['id'=>$node_id]);
    }
    public function editMeter($node_id)
    {
        

        

        
    }

    public function deleteMeter($node_id, $meter_name)
    {
        $client = new Client();
        $response = $client->get('http://localhost:8181/restconf/config/opendaylight-inventory:nodes/node/'.$node_id, ['auth' => ['admin', 'admin']]);
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
        $client = new Client();
        $response = $client->get('http://localhost:8181/restconf/config/opendaylight-inventory:nodes/node/'.$node_id, ['auth' => ['admin', 'admin']]);
        $json = json_decode($response->getBody()->getContents(), true);
        $nodes = $json['node']['0']['flow-node-inventory:meter'];
        return view('deleteMeter', ['node_id' => $node_id, 'nodeList' => $nodes]);
    }
}
