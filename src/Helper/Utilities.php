<?php
//helper methods

namespace App\Helper;

use DateTime;

class Utilities{
    public static function validateDate($date, $format = 'Y-m-d'){
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
    }
}