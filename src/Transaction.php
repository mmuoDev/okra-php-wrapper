<?php
/**
 * Okra methods for the Transaction Product. 
 * This allows developers to receive customer-authorized transaction 
 * data for current, savings, and domiciliary Accounts.
 */
namespace OKRA_PHP_WRAPPER\src;

use InvalidArgumentException;
use OKRA_PHP_WRAPPER\src\Helper\UseGuzzle;
use OKRA_PHP_WRAPPER\src\Helper\Utilities;

require_once 'Helper/UseGuzzle.php';
require_once 'endpoints.php';
require_once 'Helper/Utilities.php';

class Transaction{
    const LIMIT = 10;

    public $guzzle;
    
    public function __construct($bearer_token)
    {
        $this->bearer_token = $bearer_token;
        $this->guzzle = new UseGuzzle($this->bearer_token);
    }

    //Returns a summarized info on the transactions  in an array or pdf format.
    public function getTransactions(bool $pdf = false){
        $body = ['pdf' => $pdf];
        $url = HOST_URL.ALL_TRANSACTION;
        $response = $this->guzzle->index('post', $url, $body);
        if($response['status']){
            $res =  $response['data'];
        }else{
            $res =  $response;
        }
        return $res;
    }

    //Returns transactions per customer
    public function getTransactionsPerCustomer($customer_id = null, bool $all_records = false, int $page = 1, int $limit = self::LIMIT) : array{
        if(!is_null($customer_id)){
            if($all_records === false){
                //per page
                $body = ["customer" => $customer_id, "page" => $page, "limit" => $limit];
                $url = HOST_URL.TRANSACTION_BY_CUSTOMER_ID;
                $response = $this->guzzle->index('post', $url, $body);
                if($response['status']){
                    $res =  $response['data'];
                }else{
                    $res =  $response;
                } 
            }else{
                //all pages
                $body = ["customer" => $customer_id, "page" => 1, "limit" => $limit];
                $url = HOST_URL.TRANSACTION_BY_CUSTOMER_ID;
                $response = $this->guzzle->index('post', $url, $body);
                if($response['status']){
                    $total_pages = $response['data']['data']['pagination']['totalPages'];
                    $next_page = $response['data']['data']['pagination']['nextPage'];
                    if($next_page > 1){
                        $array = [];
                        $i = 1;
                        while ($i <= $total_pages){
                            $body = ["customer" => $customer_id, "page" => $i, "limit" => $limit];
                            $url = HOST_URL.TRANSACTION_BY_CUSTOMER_ID;
                            $response = $this->guzzle->index('post', $url, $body);
                            $transactions = $response['data']['data']['transaction'];
                            $array = array_merge($array, $transactions);
                            $i += 1;
                        }
                        $res = ['status' => 'success', 'data' => ["transaction" => $array]]; //pagination object not supplied
                    }else{
                        $res =  $response['data'];
                    }
                }else{
                    $res =  $response;
                }
            }
            return $res;
        }else{
            throw new InvalidArgumentException("Argument needed: Customer Id!");
        }
    }

    //Returns transactions per account id
    public function getTransactionsPerAccountId($account_id = null, bool $all_records = false, int $page = 1, int $limit = self::LIMIT) : array {
        if(!is_null($account_id)){
            if($all_records === false){
                //per page
                $body = ["account" => $account_id, "page" => $page, "limit" => $limit];
                $url = HOST_URL.TRANSACTION_BY_ACCOUNT_ID;
                $response = $this->guzzle->index('post', $url, $body);
                if($response['status']){
                    $res =  $response['data'];
                }else{
                    $res =  $response;
                } 
            }else{
                //all pages
                $body = ["account" => $account_id, "page" => 1, "limit" => $limit];
                $url = HOST_URL.TRANSACTION_BY_ACCOUNT_ID;
                $response = $this->guzzle->index('post', $url, $body);
                if($response['status']){
                    $total_pages = $response['data']['data']['pagination']['totalPages'];
                    $next_page = $response['data']['data']['pagination']['nextPage'];
                    if($next_page > 1){
                        $array = [];
                        $i = 1;
                        while ($i <= $total_pages){
                            $body = ["account" => $account_id, "page" => $i, "limit" => $limit];
                            $url = HOST_URL.TRANSACTION_BY_ACCOUNT_ID;
                            $response = $this->guzzle->index('post', $url, $body);
                            $transactions = $response['data']['data']['transaction'];
                            $array = array_merge($array, $transactions);
                            $i += 1;
                        }
                        $res = ['status' => 'success', 'data' => ["transaction" => $array]]; //pagination object not returned
                    }else{
                        $res =  $response['data'];
                    }
                }else{
                    $res =  $response;
                }
            }
            return $res;
        }else{
            throw new InvalidArgumentException("Argument needed: Account Id!");
        }
    }

    //get transactions by date range
    public function getTransactionsByDateRange($from, $to, bool $all_records = false, int $page = 1, int $limit = self::LIMIT): array {
        $validate_from = Utilities::validateDate($from);
        $validate_to = Utilities::validateDate($to);
        if(!($validate_from and $validate_to)){
            throw new InvalidArgumentException("Invalid Argument Either from or to date is incorrect!");
        }else{ 
            if($all_records === false){
                //per page
                $body = ['from' => $from, 'to' => $to, "page" => $page, "limit" => $limit];
                $url = HOST_URL.TRANSACTION_BY_DATE_RANGE;
                $response = $this->guzzle->index('post', $url, $body);
                if($response['status']){
                    $res =  $response['data'];
                }else{
                    $res =  $response;
                } 
            }else{
                //all pages
                $body = ['from' => $from, 'to' => $to, "page" => 1, "limit" => $limit];
                $url = HOST_URL.TRANSACTION_BY_DATE_RANGE;
                $response = $this->guzzle->index('post', $url, $body);
                if($response['status']){
                    $total_pages = $response['data']['data']['pagination']['totalPages'];
                    $next_page = $response['data']['data']['pagination']['nextPage'];
                    if($next_page > 1){
                        $array = [];
                        $i = 1;
                        while ($i <= $total_pages){
                            $body = ['from' => $from, 'to' => $to, "page" => $i, "limit" => $limit];
                            $url = HOST_URL.TRANSACTION_BY_DATE_RANGE;
                            $response = $this->guzzle->index('post', $url, $body);
                            $transactions = $response['data']['data']['identity'];
                            $array = array_merge($array, $transactions);
                            $i += 1;
                        }
                        $res = ['status' => 'success', 'data' => ["transaction" => $array]]; //pagination object not returned
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

    //get transactions per bank
    public function getTransactionsPerBank($bank_id = null, bool $all_records = false, int $page = 1, int $limit = self::LIMIT) : array{
        if(!is_null($bank_id)){
            if($all_records === false){
                //per page
                $body = ["bank" => $bank_id, "page" => $page, "limit" => $limit];
                $url = HOST_URL.TRANSACTION_BY_BANK;
                $response = $this->guzzle->index('post', $url, $body);
                if($response['status']){
                    $res =  $response['data'];
                }else{
                    $res =  $response;
                } 
            }else{
                //all pages
                $body = ["bank" => $bank_id, "page" => 1, "limit" => $limit];
                $url = HOST_URL.TRANSACTION_BY_BANK;
                $response = $this->guzzle->index('post', $url, $body);
                if($response['status']){
                    $total_pages = $response['data']['data']['pagination']['totalPages'];
                    $next_page = $response['data']['data']['pagination']['nextPage'];
                    if($next_page > 1){
                        $array = [];
                        $i = 1;
                        while ($i <= $total_pages){
                            $body = ["bank" => $bank_id, "page" => $i, "limit" => $limit];
                            $url = HOST_URL.TRANSACTION_BY_BANK;
                            $response = $this->guzzle->index('post', $url, $body);
                            $transactions = $response['data']['data']['transaction'];
                            $array = array_merge($array, $transactions);
                            $i += 1;
                        }
                        $res = ['status' => 'success', 'data' => ["transaction" => $array]]; //pagination object not returned
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

    //returns spending pattern of a customer
    public function getSpendingPatternPerCustomer($customer_id = null){
        if(!is_null($customer_id)){
            $body = ['customer_id' => $customer_id];
            $url = HOST_URL.SPENDING_PATTERN_BY_CUSTOMER_ID;
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

    //returns transactions based on customer and date range
    public function getTransactionsPerCustomerByDateRange($from, $to, $customer_id = null, bool $all_records = false, int $page = 1, int $limit = self::LIMIT) : array {
        $validate_from = Utilities::validateDate($from);
        $validate_to = Utilities::validateDate($to);
        if(!($validate_from and $validate_to)){
            throw new InvalidArgumentException("Invalid Argument Either from or to date is incorrect!");
        }elseif(is_null($customer_id)){
            throw new InvalidArgumentException("Argument needed: Customer Id!");
        }else{ 
            if($all_records === false){
                //per page
                $body = ['from' => $from, 'to' => $to, "customer_id" => $customer_id, "page" => $page, "limit" => $limit];
                $url = HOST_URL.TRANSACTION_PER_CUSTOMER_ID_BY_DATE_RANGE;
                $response = $this->guzzle->index('post', $url, $body);
                if($response['status']){
                    $res =  $response['data'];
                }else{
                    $res =  $response;
                } 
            }else{
                //all pages
                $body = ['from' => $from, 'to' => $to, "customer_id" => $customer_id, "page" => $page, "limit" => $limit];
                $url = HOST_URL.TRANSACTION_PER_CUSTOMER_ID_BY_DATE_RANGE;
                $response = $this->guzzle->index('post', $url, $body);
                if($response['status']){
                    $total_pages = $response['data']['data']['pagination']['totalPages'];
                    $next_page = $response['data']['data']['pagination']['nextPage'];
                    if($next_page > 1){
                        $array = [];
                        $i = 1;
                        while ($i <= $total_pages){
                            $body = ['from' => $from, 'to' => $to, "customer_id" => $customer_id, "page" => $page, "limit" => $limit];
                            $url = HOST_URL.TRANSACTION_PER_CUSTOMER_ID_BY_DATE_RANGE;
                            $response = $this->guzzle->index('post', $url, $body);
                            $transactions = $response['data']['data']['transaction'];
                            $array = array_merge($array, $transactions);
                            $i += 1;
                        }
                        $res = ['status' => 'success', 'data' => ["transaction" => $array]]; //pagination object not returned
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