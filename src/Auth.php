<?php
/**
 * Okra methods for the Auth Product
 * This  allows you to retrieve the bank account and routing numbers 
 * associated with a Record's current, savings, and domiciliary accounts, 
 * along with high-level account data and balances when available.
 */

namespace App;

use App\Helper\UseGuzzle;
use App\Helper\Utilities;
use InvalidArgumentException;

class Auth{
    const LIMIT = 10;

    public $guzzle;
    
    public function __construct($bearer_token)
    {
        $this->bearer_token = $bearer_token;
        $this->guzzle = new UseGuzzle($this->bearer_token);
    }

     //returns auth information in an array or pdf format for all customers. 
     public function getAllAuth(bool $pdf = false): array {
        $body = ['pdf' => $pdf];
        $url = HOST_URL.ALL_AUTH;
        $response = $this->guzzle->index('post', $url, $body);
        if($response['status']){
            $res =  $response['data'];
        }else{
            $res =  $response;
        }
        return $res;
    }

    //return auth information for a cutomer using auth id
    public function getIdentityById($id = null): array {
        if(!is_null($id)){
            $body = ['id' => $id];
            $url = HOST_URL.AUTH_BY_ID;
            $response = $this->guzzle->index('post', $url, $body);
            if($response['status']){
                $res =  $response['data'];
            }else{
                $res =  $response;
            }
            return $res;
        }else{
            throw new InvalidArgumentException("Argument needed: Auth Id!");
        }
    }

    //return auth information for a customer using customer id
    public function getAuthByCustomerId($customer_id = null): array {
        if(!is_null($customer_id)){
            $body = ['customer' => $customer_id];
            $url = HOST_URL.AUTH_BY_CUSTOMER_ID;
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

    //return auth information based on date range they were created.
    public function getAuthByDateRange($from, $to, bool $all_records = false, int $page = 1, int $limit = self::LIMIT): array {
        $validate_from = Utilities::validateDate($from);
        $validate_to = Utilities::validateDate($to);
        if(!($validate_from and $validate_to)){
            throw new InvalidArgumentException("Invalid Argument Either from or to date is incorrect!");
        }else{ 
            if($all_records === false){
                //per page
                $body = ['from' => $from, 'to' => $to, "page" => $page, "limit" => $limit];
                $url = HOST_URL.AUTH_BY_DATE_RANGE;
                $response = $this->guzzle->index('post', $url, $body);
                if($response['status']){
                    $res =  $response['data'];
                }else{
                    $res =  $response;
                } 
            }else{
                //all pages
                $body = ['from' => $from, 'to' => $to, "page" => 1, "limit" => $limit];
                $url = HOST_URL.AUTH_BY_DATE_RANGE;
                $response = $this->guzzle->index('post', $url, $body);
                if($response['status']){
                    $total_pages = $response['data']['data']['pagination']['totalPages'];
                    $next_page = $response['data']['data']['pagination']['nextPage'];
                    if($next_page > 1){
                        $array = [];
                        $i = 1;
                        while ($i <= $total_pages){
                            $body = ['from' => $from, 'to' => $to, "page" => $i, "limit" => $limit];
                            $url = HOST_URL.AUTH_BY_DATE_RANGE;
                            $response = $this->guzzle->index('post', $url, $body);
                            $auths = $response['data']['data']['auths'];
                            $array = array_merge($array, $auths);
                            $i += 1;
                        }
                        $res = ['status' => 'success', 'data' => ["auths" => $array]]; //pagination object not returned
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

     //get auth info per bank
     public function getAuthPerBank($bank_id = null, bool $all_records = false, int $page = 1, int $limit = self::LIMIT) : array{
        if(!is_null($bank_id)){
            if($all_records === false){
                //per page
                $body = ["bank" => $bank_id, "page" => $page, "limit" => $limit];
                $url = HOST_URL.AUTH_BY_BANK;
                $response = $this->guzzle->index('post', $url, $body);
                if($response['status']){
                    $res =  $response['data'];
                }else{
                    $res =  $response;
                } 
            }else{
                //all pages
                $body = ["bank" => $bank_id, "page" => 1, "limit" => $limit];
                $url = HOST_URL.AUTH_BY_BANK;
                $response = $this->guzzle->index('post', $url, $body);
                if($response['status']){
                    $total_pages = $response['data']['data']['pagination']['totalPages'];
                    $next_page = $response['data']['data']['pagination']['nextPage'];
                    if($next_page > 1){
                        $array = [];
                        $i = 1;
                        while ($i <= $total_pages){
                            $body = ["bank" => $bank_id, "page" => $i, "limit" => $limit];
                            $url = HOST_URL.AUTH_BY_BANK;
                            $response = $this->guzzle->index('post', $url, $body);
                            $auths = $response['data']['data']['auths'];
                            $array = array_merge($array, $auths);
                            $i += 1;
                        }
                        $res = ['status' => 'success', 'data' => ["auths" => $array]]; //pagination object not returned
                    }else{
                        $res =  $response['data'];
                    }
                }else{
                    $res =  $response;
                }
            }
            return $res;
        }else{
            throw new InvalidArgumentException("Argument needed: Bank Id!");
        }
    }

    //returns auth based on customer and date range
    public function getAuthPerCustomerByDateRange($from, $to, $customer_id = null, bool $all_records = false, int $page = 1, int $limit = self::LIMIT) : array {
        $validate_from = Utilities::validateDate($from);
        $validate_to = Utilities::validateDate($to);
        if(!($validate_from and $validate_to)){
            throw new InvalidArgumentException("Invalid Argument Either from or to date is incorrect!");
        }elseif(is_null($customer_id)){
            throw new InvalidArgumentException("Argument needed: Customer Id!");
        }else{ 
            if($all_records === false){
                //per page
                $body = ['from' => $from, 'to' => $to, "customer" => $customer_id, "page" => $page, "limit" => $limit];
                $url = HOST_URL.AUTH_PER_CUSTOMER_ID_BY_DATE_RANGE;
                $response = $this->guzzle->index('post', $url, $body);
                if($response['status']){
                    $res =  $response['data'];
                }else{
                    $res =  $response;
                } 
            }else{
                //all pages
                $body = ['from' => $from, 'to' => $to, "customer" => $customer_id, "page" => $page, "limit" => $limit];
                $url = HOST_URL.AUTH_PER_CUSTOMER_ID_BY_DATE_RANGE;
                $response = $this->guzzle->index('post', $url, $body);
                if($response['status']){
                    $total_pages = $response['data']['data']['pagination']['totalPages'];
                    $next_page = $response['data']['data']['pagination']['nextPage'];
                    if($next_page > 1){
                        $array = [];
                        $i = 1;
                        while ($i <= $total_pages){
                            $body = ['from' => $from, 'to' => $to, "customer" => $customer_id, "page" => $page, "limit" => $limit];
                            $url = HOST_URL.AUTH_PER_CUSTOMER_ID_BY_DATE_RANGE;
                            $response = $this->guzzle->index('post', $url, $body);
                            $auths = $response['data']['data']['auths'];
                            $array = array_merge($array, $auths);
                            $i += 1;
                        }
                        $res = ['status' => 'success', 'data' => ["auths" => $array]]; //pagination object not returned
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