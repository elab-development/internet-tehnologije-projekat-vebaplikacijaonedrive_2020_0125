<?php
namespace App\Graph;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Psr7\Utils;
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

    public function deleteFirmFolder($firmName){
        $client = new Client();
        $headers = [
            'Authorization' => 'Bearer ' . $this->authToken,
            'Content-Type' => 'application/json',
        ];
        $response = $client->delete("https://graph.microsoft.com/v1.0/me/drive/root:/". $firmName .":/", [
            'headers' => $headers,
        ]);
        return $response;
    }

    public function renameFirmFolder($firmName,$newName){
        $client = new Client();
        $headers = [
            'Authorization' => 'Bearer ' . $this->authToken,
            'Content-Type' => 'application/json',
        ];
        $body=["name"=>$newName];
        $response = $client->patch("https://graph.microsoft.com/v1.0/me/drive/root:/". $firmName .":/", [
            'headers' => $headers,
            'json' => $body,
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

    public function getAllFilesInFirm($firmName){

        $client = new Client();
        $headers = [
            'Authorization' => 'Bearer ' . $this->authToken,
            'Content-Type' => 'application/json',
        ];
        $response = $client->get("https://graph.microsoft.com/v1.0/me/drive/root:/". $firmName .":/children?select=name,createdDateTime,lastModifiedDateTime,size", [
            'headers' => $headers,
        ]);
        return $response;
    }

    public function getDownloadLinkFileInFirm($firmName,$firmItem){

        $client = new Client();
        $headers = [
            'Authorization' => 'Bearer ' . $this->authToken,
            'Content-Type' => 'application/json',
        ];
        $response = $client->get("https://graph.microsoft.com/v1.0/me/drive/root:/". $firmName ."/". $firmItem . ":?select=name,@microsoft.graph.downloadUrl", [
            'headers' => $headers,
        ]);
        return $response;
    }
    public function getDownloadContentFileInFirm($firmName,$firmItem){

        $client = new Client();
        $headers = [
            'Authorization' => 'Bearer ' . $this->authToken,
            'Content-Type' => 'application/json',
        ];
        $response = $client->get("https://graph.microsoft.com/v1.0/me/drive/root:/". $firmName ."/". $firmItem . ":/content", [
            'headers' => $headers,
        ]);
        return $response->getBody();
    }

    public function uploadFileInFirm($req,$firmName,$firmItem){
        $client = new Client();
        $headers = [
            'Authorization' => 'Bearer ' . $this->authToken,
            'Content-Type' => 'text/plain',
        ];
         $fileData = $req->getContent();
         file_put_contents('C:\Users\darek\Downloads\test.txt', $fileData);
         
        $response = $client->put("https://graph.microsoft.com/v1.0/me/drive/root:/". $firmName ."/". $firmItem . ":/content", [
            'headers' => $headers,
            'body' => $fileData,
        ]);
        $jsonData = json_decode($response->getBody(),true);
        $selectedData = [
            'createdDateTime' => $jsonData['createdDateTime'],
            'lastModifiedDateTime' => $jsonData['lastModifiedDateTime'],
            'name' => $jsonData['name'],
            'size' => $jsonData['size'],
        ];
        $selectedData=['value'=>$selectedData];
        $selectedData=json_encode($selectedData);
        $response = $response->withBody(Utils::streamFor($selectedData));
        return $response;
    }

    public function renameFileInFirm($firmName,$firmItem,$newName){
        $client = new Client();
        $headers = [
            'Authorization' => 'Bearer ' . $this->authToken,
            'Content-Type' => 'application/json',
        ];
        $body=["name"=>$newName];
        $response = $client->patch("https://graph.microsoft.com/v1.0/me/drive/root:/". $firmName ."/". $firmItem . ":/", [
            'headers' => $headers,
            'json' => $body,
        ]);
        $jsonData = json_decode($response->getBody(),true);
        $selectedData = [
            'createdDateTime' => $jsonData['createdDateTime'],
            'lastModifiedDateTime' => $jsonData['lastModifiedDateTime'],
            'name' => $jsonData['name'],
            'size' => $jsonData['size'],
        ];
        $selectedData=['value'=>$selectedData];
        $selectedData=json_encode($selectedData);
        $response = $response->withBody(Utils::streamFor($selectedData));
        return $response;
    }

    


}


?>