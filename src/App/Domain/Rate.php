<?php

namespace App\Domain;

final class Rate
{
    const PRECISION = 10;

    private $rate;

    private function __construct(float $rate)
    {
        $this->rate = $rate;
    }

    public static function fromFloat(float $rate): self
    {
        return new self($rate);
    }

    public static function fromString(string $rate): self
    {
        return new self((float) $rate);
    }

    public function add(Rate $summand): self
    {
        $rateAsString = $this->asString();
        $summandAsString = $summand->asString();

        $sum = \bcadd($rateAsString, $summandAsString, self::PRECISION);

        return $this->fromString($sum);
    }

    public function sub(Rate $subtrahend): self
    {
        $rateAsString = $this->asString();
        $subtrahendAsString = $subtrahend->asString();

        $difference = \bcsub($rateAsString, $subtrahendAsString, self::PRECISION);

        return $this->fromString($difference);
    }

    public function getRate(): float # getter byłby zbędny gdyby wersja php była wyższa - wtedy można użyć właściwości z dostępem `public readonly`
    {
        return $this->rate;
    }

    public function asString(): string
    {
        return (string) $this->rate;
    }
}