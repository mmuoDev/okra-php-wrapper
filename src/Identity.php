<?php
/**
 * Okra methods for the Identity Product
 * This allows you to retrieve various account holder information on file 
 * with the bank, including names, emails, phone numbers, and addresses
 */
namespace OKRA_PHP_WRAPPER\src;

use InvalidArgumentException;
use OKRA_PHP_WRAPPER\src\Helper\UseGuzzle;
use OKRA_PHP_WRAPPER\src\Helper\Utilities;

require_once 'Helper/UseGuzzle.php';
require_once 'endpoints.php';
require_once 'Helper/Utilities.php';

class Identity{
    const LIMIT = 10;

    public $guzzle;
    
    public function __construct($bearer_token)
    {
        $this->bearer_token = $bearer_token;
        $this->guzzle = new UseGuzzle($this->bearer_token);
    }

    //returns identities' information in an array or pdf format for all customers. 
    public function getIdentities(bool $pdf = false): array {
            $body = ['pdf' => $pdf];
            $url = HOST_URL.ALL_IDENTITY;
            $response = $this->guzzle->index('post', $url, $body);
            if($response['status']){
                $res =  $response['data'];
            }else{
                $res =  $response;
            }
            return $res;
    }

    //return identity information for a cutomer using identity id
    public function getIdentityById($id = null): array {
        if(!is_null($id)){
            $body = ['id' => $id];
            $url = HOST_URL.IDENTITY_BY_ID;
            $response = $this->guzzle->index('post', $url, $body);
            if($response['status']){
                $res =  $response['data'];
            }else{
                $res =  $response;
            }
            return $res;
        }else{
            throw new InvalidArgumentException("Argument needed: Identity Id!");
        }
    }

    //return identity information for a customer using customer id
    public function getIdentityByCustomerId($customer_id = null): array {
        if(!is_null($customer_id)){
            $body = ['customer' => $customer_id];
            $url = HOST_URL.IDENTITY_BY_CUSTOMER_ID;
            $response = $this->guzzle->index('post', $url, $body);
            if($response['status']){
                $res =  $response['data'];
            }else{
                $res =  $response;
            }
            return $res;
        }else{
            throw new InvalidArgumentException("Argument needed: Customer Id!");
        }
    }

    //return identity information based on date range they were created.
    public function getIdentityByDateRange($from, $to, bool $all_records = false, int $page = 1, int $limit = self::LIMIT): array {
        $validate_from = Utilities::validateDate($from);
        $validate_to = Utilities::validateDate($to);
        if(!($validate_from and $validate_to)){
            throw new InvalidArgumentException("Invalid Argument Either from or to date is incorrect!");
        }else{ 
            if($all_records === false){
                //per page
                $body = ['from' => $from, 'to' => $to, "page" => $page, "limit" => $limit];
                $url = HOST_URL.IDENTITY_BY_DATE_RANGE;
                $response = $this->guzzle->index('post', $url, $body);
                if($response['status']){
                    $res =  $response['data'];
                }else{
                    $res =  $response;
                } 
            }else{
                //all pages
                $body = ['from' => $from, 'to' => $to, "page" => 1, "limit" => $limit];
                $url = HOST_URL.IDENTITY_BY_DATE_RANGE;
                $response = $this->guzzle->index('post', $url, $body);
                if($response['status']){
                    $total_pages = $response['data']['data']['pagination']['totalPages'];
                    $next_page = $response['data']['data']['pagination']['nextPage'];
                    if($next_page > 1){
                        $array = [];
                        $i = 1;
                        while ($i <= $total_pages){
                            $body = ['from' => $from, 'to' => $to, "page" => $i, "limit" => $limit];
                            $url = HOST_URL.IDENTITY_BY_DATE_RANGE;
                            $response = $this->guzzle->index('post', $url, $body);
                            $identities = $response['data']['data']['identity'];
                            $array = array_merge($array, $identities);
                            $i += 1;
                        }
                        $res = ['status' => 'success', 'data' => ["identity" => $array]]; //pagination object not returned
                    }else{
                        $res =  $response['data'];
                    }
                }else{
                    $res =  $response;
                }
            }
            return $res;
        }
    }

}