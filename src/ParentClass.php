<?php

namespace App;

use App\Helper\UseGuzzle;

class ParentClass {
    protected $guzzle;
    protected $bearer_token;

     
    public function __construct($bearer_token){
       $this->bearer_token = $bearer_token;
       $this->guzzle = new UseGuzzle($this->bearer_token);
    }
 }