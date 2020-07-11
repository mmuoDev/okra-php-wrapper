<?php

namespace App;

use App\Helper\OkraInterface;

class Client extends ParentClass implements OkraInterface {
    use Identity, Transaction, Auth, Balance;
 
    public function __construct($bearer_token){
         parent::__construct($bearer_token);
    }
 }