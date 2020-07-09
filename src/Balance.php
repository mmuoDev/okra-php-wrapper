<?php
/**
 * Okra methods for the Balance Product
 * This  allows you to retrieve the real-time balance for each of a record's accounts. 
 */

namespace OKRA_PHP_WRAPPER\src;

use InvalidArgumentException;
use OKRA_PHP_WRAPPER\src\Helper\UseGuzzle;
use OKRA_PHP_WRAPPER\src\Helper\Utilities;

require_once 'Helper/UseGuzzle.php';
require_once 'endpoints.php';
require_once 'Helper/Utilities.php';

class Auth{
    const LIMIT = 10;

    public $guzzle;
    
    public function __construct($bearer_token)
    {
        $this->bearer_token = $bearer_token;
        $this->guzzle = new UseGuzzle($this->bearer_token);
    }

    //returns balance information in an array or pdf format for all customers. 
    public function getAllBalance(bool $pdf = false): array {
        $body = ['pdf' => $pdf];
        $url = HOST_URL.ALL_BALANCE;
        $response = $this->guzzle->index('post', $url, $body);
        if($response['status']){
            $res =  $response['data'];
        }else{
            $res =  $response;
        }
        return $res;
    }

      //return balance information for a cutomer using balance id
      public function getBalanceById($id = null): array {
        if(!is_null($id)){
            $body = ['id' => $id];
            $url = HOST_URL.BALANCE_BY_ID;
            $response = $this->guzzle->index('post', $url, $body);
            if($response['status']){
                $res =  $response['data'];
            }else{
                $res =  $response;
            }
            return $res;
        }else{
            throw new InvalidArgumentException("Argument needed: Balance Id!");
        }
    }

    //return balance information for a customer using customer id
    public function getBalanceByCustomerId($customer_id = null): array {
        if(!is_null($customer_id)){
            $body = ['customer' => $customer_id];
            $url = HOST_URL.BALANCE_BY_CUSTOMER_ID;
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

    //return balance information for a customer using account id
    public function getBalanceByAccountId($account_id = null): array {
        if(!is_null($account_id)){
            $body = ['account' => $account_id];
            $url = HOST_URL.BALANCE_BY_ACCOUNT_ID;
            $response = $this->guzzle->index('post', $url, $body);
            if($response['status']){
                $res =  $response['data'];
            }else{
                $res =  $response;
            }
            return $res;
        }else{
            throw new InvalidArgumentException("Argument needed: Account Id!");
        }
    }

    //return auth information based on date range they were created.
    public function getBalanceByDateRange($from, $to, bool $all_records = false, int $page = 1, int $limit = self::LIMIT): array {
        $validate_from = Utilities::validateDate($from);
        $validate_to = Utilities::validateDate($to);
        if(!($validate_from and $validate_to)){
            throw new InvalidArgumentException("Invalid Argument Either from or to date is incorrect!");
        }else{ 
            if($all_records === false){
                //per page
                $body = ['from' => $from, 'to' => $to, "page" => $page, "limit" => $limit];
                $url = HOST_URL.BALANCE_BY_DATE_RANGE;
                $response = $this->guzzle->index('post', $url, $body);
                if($response['status']){
                    $res =  $response['data'];
                }else{
                    $res =  $response;
                } 
            }else{
                //all pages
                $body = ['from' => $from, 'to' => $to, "page" => 1, "limit" => $limit];
                $url = HOST_URL.BALANCE_BY_DATE_RANGE;
                $response = $this->guzzle->index('post', $url, $body);
                if($response['status']){
                    $total_pages = $response['data']['data']['pagination']['totalPages'];
                    $next_page = $response['data']['data']['pagination']['nextPage'];
                    if($next_page > 1){
                        $array = [];
                        $i = 1;
                        while ($i <= $total_pages){
                            $body = ['from' => $from, 'to' => $to, "page" => $i, "limit" => $limit];
                            $url = HOST_URL.BALANCE_BY_DATE_RANGE;
                            $response = $this->guzzle->index('post', $url, $body);
                            $balances = $response['data']['data']['balances'];
                            $array = array_merge($array, $balances);
                            $i += 1;
                        }
                        $res = ['status' => 'success', 'data' => ["balances" => $array]]; //pagination object not returned
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

    //fetch balance info using type of balance.
    public function getBalanceByType($type = null, float $value = null, bool $all_records = false, int $page = 1, int $limit = self::LIMIT): array {
        if(is_null($type)){
            throw new InvalidArgumentException("Invalid argument for type");
        }elseif(is_null($value)){
            throw new InvalidArgumentException("Invalid argument for value");
        }else{ 
            if($all_records === false){
                //per page
                $body = ['type' => $type, 'value' => $value, "page" => $page, "limit" => $limit];
                $url = HOST_URL.BALANCE_BY_TYPE;
                $response = $this->guzzle->index('post', $url, $body);
                if($response['status']){
                    $res =  $response['data'];
                }else{
                    $res =  $response;
                } 
            }else{
                //all pages
                $body = ['type' => $type, 'value' => $value, "page" => $page, "limit" => $limit];
                $url = HOST_URL.BALANCE_BY_TYPE;
                $response = $this->guzzle->index('post', $url, $body);
                if($response['status']){
                    $total_pages = $response['data']['data']['pagination']['totalPages'];
                    $next_page = $response['data']['data']['pagination']['nextPage'];
                    if($next_page > 1){
                        $array = [];
                        $i = 1;
                        while ($i <= $total_pages){
                            $body = ['type' => $type, 'value' => $value, "page" => $page, "limit" => $limit];
                            $url = HOST_URL.BALANCE_BY_TYPE;
                            $response = $this->guzzle->index('post', $url, $body);
                            $balances = $response['data']['data']['balances'];
                            $array = array_merge($array, $balances);
                            $i += 1;
                        }
                        $res = ['status' => 'success', 'data' => ["balances" => $array]]; //pagination object not returned
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