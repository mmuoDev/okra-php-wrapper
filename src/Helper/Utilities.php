<?php
//helper methods

namespace OKRA_PHP_WRAPPER\src\Helper;

use DateTime;

class Utilities{
    public static function validateDate($date, $format = 'Y-m-d'){
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
    }
}