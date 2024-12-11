<?php

namespace App\Domain;

final class CurrencyCode
{
    private $code;

    private function __construct(string $code)
    {
        $this->code = $code;
    }

    public static function fromString(string $currencyCode): self
    {
        return new self($currencyCode);
    }

    public function equals(self $currencyCode): bool
    {
        return $this->code === $currencyCode->code;
    }

    public function getCode(): string # getter byłby zbędny gdyby wersja php była wyższa - wtedy można użyć właściwości z dostępem `public readonly`
    {
        return $this->code;
    }
}