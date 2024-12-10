<?php

namespace App\Domain;

use DateTimeInterface;

final class EffectiveDate
{
    private $datetime;

    private function __construct(DateTimeInterface $datetime)
    {
        $this->datetime = $datetime;
    }

    public static function fromDateTime(DateTimeInterface $datetime): self
    {
        return new self($datetime);
    }

    public function getDateTime(): DateTimeInterface # getter byłby zbędny gdyby wersja php była wyższa - wtedy można użyć właściwości z dostępem `public readonly`
    {
        return $this->datetime;
    }
}