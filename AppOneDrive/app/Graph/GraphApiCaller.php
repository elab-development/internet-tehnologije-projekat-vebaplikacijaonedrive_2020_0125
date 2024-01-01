<?php
namespace App\Graph;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use stdClass;

class GraphApiCaller{

    private $authToken;

    public function initGraphApiCaller($token){
        $this->authToken=$token;
    }

    public function createFirmFolder($firmName){
        $client = new Client();
        $headers = [
            'Authorization' => 'Bearer ' . $this->authToken,
            'Content-Type' => 'application/json',
        ];
        $body = [
            'name' => $firmName,
            'folder' => new stdClass(), // reprezent empty class
        ];
        $response = $client->post('https://graph.microsoft.com/v1.0/me/drive/root/children', [
            'headers' => $headers,
            'json' => $body,
        ]);
        return $response;
    }

    public function deleteFirmFolder($itemName){
        $client = new Client();
        $headers = [
            'Authorization' => 'Bearer ' . $this->authToken,
            'Content-Type' => 'application/json',
        ];
        $response = $client->delete("https://graph.microsoft.com/v1.0/me/drive/root:/". $itemName .":/", [
            'headers' => $headers,
        ]);
        return $response;
    }

    public function deleteItemInFirm($firmName,$firmItem){
        $client = new Client();
        $headers = [
            'Authorization' => 'Bearer ' . $this->authToken,
            'Content-Type' => 'application/json',
        ];
        $response = $client->delete("https://graph.microsoft.com/v1.0/me/drive/root:/". $firmName ."/". $firmItem . ":/", [
            'headers' => $headers,
        ]);
        return $response;
    }


}


?>