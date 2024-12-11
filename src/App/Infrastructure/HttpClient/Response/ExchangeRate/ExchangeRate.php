<?php

namespace App\Infrastructure\HttpClient\Response\ExchangeRate;

use DateTimeImmutable;

class ExchangeRate
{
    private $table;

    private $number;

    private $effectiveDate;

    /**
     * @var Rate[]
     */
    private $rates = [];

    private function __construct(string $table, string $number, DateTimeImmutable $effectiveDate, array $rates)
    {
        $this->table = $table;
        $this->number = $number;
        $this->effectiveDate = $effectiveDate;
        $this->rates = $rates;
    }

    public static function createFromResponse(array $response): self
    {
        $response = \array_pop($response);
        $rates = \array_map(function ($item) {
            return Rate::create($item['currency'], $item['code'], $item['mid']);
        }, $response['rates']);

        $effectiveDate = DateTimeImmutable::createFromFormat('Y-m-d', $response['effectiveDate'])->setTime(0,0);

        return new self($response['table'], $response['no'], $effectiveDate, $rates);
    }

    public function getTable(): string
    {
        return $this->table;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function getEffectiveDate(): DateTimeImmutable
    {
        return $this->effectiveDate;
    }

    public function getRates(): array
    {
        return $this->rates;
    }
}