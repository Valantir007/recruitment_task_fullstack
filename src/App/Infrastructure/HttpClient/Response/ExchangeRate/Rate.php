<?php

namespace App\Infrastructure\HttpClient\Response\ExchangeRate;

class Rate
{
    public $currency;

    public $code;

    public $mid;

    private function __construct(string $currency, string $code, float $mid)
    {
        $this->currency = $currency;
        $this->code = $code;
        $this->mid = $mid;
    }

    public static function create(string $currency, string $code, float $mid): self
    {
        return new self($currency, $code, $mid);
    }
}