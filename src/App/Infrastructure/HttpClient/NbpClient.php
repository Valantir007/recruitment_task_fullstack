<?php

namespace App\Infrastructure\HttpClient;

use App\Infrastructure\HttpClient\Request\ExchangeRateRequest;
use App\Infrastructure\HttpClient\Response\ExchangeRate\ExchangeRate;

interface NbpClient
{
    public function getExchangeRate(ExchangeRateRequest $currencyRateRequest): ExchangeRate;
}