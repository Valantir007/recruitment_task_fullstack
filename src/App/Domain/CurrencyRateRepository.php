<?php

namespace App\Domain;

use \DateTimeInterface;

interface CurrencyRateRepository
{
    /**
     * @return CurrencyRate[]
     */
    public function getCurrencyRateByDate(DateTimeInterface $dateTime): array;
}