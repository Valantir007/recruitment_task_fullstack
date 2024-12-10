<?php

namespace App\Infrastructure\Validator;

use DateTime;

class DateTimeValidator
{
    public function hasCorrectFormat($date = null, $format = 'Y-m-d'): bool
    {
        if (is_null($date)) {
            return false;
        }

        $d = DateTime::createFromFormat($format, $date);

        return $d && $d->format($format) === $date;
    }
}