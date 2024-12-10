<?php

namespace App\Domain;

final class CurrencyName
{
    private $name;

    private function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function fromString(string $currencyName): self
    {
        return new self($currencyName);
    }

    public function getName(): string # getter byłby zbędny gdyby wersja php była wyższa - wtedy można użyć właściwości z dostępem `public readonly`
    {
        return $this->name;
    }
}