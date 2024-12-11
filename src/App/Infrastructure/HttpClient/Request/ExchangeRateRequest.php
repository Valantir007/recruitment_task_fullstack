<?php

namespace App\Infrastructure\HttpClient\Request;

use DateTimeInterface;

class ExchangeRateRequest extends AbstractRequest
{
    private const PATH = 'exchangerates/tables/A'; # jeśli chcielibyśmy pobierać z tabeli B, to trzeba by było ten
                                                   # parametr zawrzeć w konfiguracji - żeby wiedzieć, z której tabeli
                                                   # pobrać daną walutę: A czy B. Albo zrobić faktorię, która by decydowała
                                                   # o tym pod jaki url ma pójść request

    private $dateTime;

    public function __construct(DateTimeInterface $dateTime)
    {
        $this->dateTime = $dateTime;
    }

    public function getEndpoint(): string
    {
        return self::PATH . '/' . $this->dateTime->format('Y-m-d');
    }
}