<?php
namespace OKRA_PHP_WRAPPER\src\Helper;

use Exception;
use GuzzleHttp\Client;

require __DIR__ . '../../../vendor/autoload.php';

class UseGuzzle{
    
    public function __construct($bearer_token)
    {
        $this->client = new Client();
        $this->bearer_token = $bearer_token;
    }

    public function index($method = null, $url = null, $body = null){
     
        $headers = [
            'content-type' => 'application/json',
            'Authorization' =>  'Bearer '.$this->bearer_token
        ];
        switch($method){
            case 'post':
                try{
                    $call = $this->client->post($url, ["headers" => $headers, "body" => json_encode($body)]);   
                    $status_code = $call->getStatusCode();
                    if ($status_code == 200){
                        $response = json_decode($call->getBody()->getContents(), true);
                        $array = ['status' => true, 'data' => $response];
                    }else{
                        $array = ['status' => false, 'error' => $call->getBody()->getContents()];
                    }
                }catch(Exception $e){
                    $code = $e->getCode();
                    if($code == 404){
                        $error = "Invalid bearer token";
                    }else{
                        $error = $e->getMessage();
                    }
                    $array = ['status' => false, 'error' => $error];
                }
                break;
            default:
        }
        return $array;
    }
}