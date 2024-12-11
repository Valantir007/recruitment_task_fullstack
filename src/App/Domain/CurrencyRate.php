<?php

namespace App\Domain;

final class CurrencyRate
{
    const CURRENCY_EURO = 'EUR';
    const CURRENCY_USD = 'USD';

    const BUYING_RATE_EUR = 0.05;
    const SELLING_RATE_EUR = 0.07;
    const BUYING_RATE_USD = 0.05;
    const SELLING_RATE_USD = 0.07;
    const SELLING_RATE_REST = 0.15;

    private $currencyCode;

    private $currencyName;

    private $effectiveDate;

    private $buyingRate = null;

    private $sellingRate;

    private function __construct(CurrencyCode $currencyCode, CurrencyName $currencyName, EffectiveDate $effectiveDate, Rate $rate)
    {
        $this->currencyCode = $currencyCode;
        $this->currencyName = $currencyName;
        $this->effectiveDate = $effectiveDate;

        $this->buyingRate = $this->calculateBuyingRate($currencyCode, $rate);
        $this->sellingRate = $this->calculateSellingRate($currencyCode, $rate);
    }

    public static function create(CurrencyCode $currencyCode, CurrencyName $currencyName, EffectiveDate $effectiveDate, Rate $rate): self
    {
        return new self(
            $currencyCode,
            $currencyName,
            $effectiveDate,
            $rate
        );
    }

    public function getCurrencyCode(): CurrencyCode
    {
        return $this->currencyCode;
    }

    public function getCurrencyName(): CurrencyName
    {
        return $this->currencyName;
    }

    public function getEffectiveDate(): EffectiveDate
    {
        return $this->effectiveDate;
    }

    public function getBuyingRate(): ?Rate
    {
        return $this->buyingRate;
    }

    public function getSellingRate(): Rate
    {
        return $this->sellingRate;
    }

    private function calculateBuyingRate(CurrencyCode $currencyCode, Rate $rate): ?Rate
    {
        # w operacjach na kwotach należy uważać na precyzję. Pomocne są chociażby paczki typu moneyphp/money
        if ($currencyCode->equals(CurrencyCode::fromString(self::CURRENCY_EURO))) {
            return Rate::fromFloat($rate->getRate())->sub(Rate::fromFloat(self::BUYING_RATE_EUR));
        }

        if ($currencyCode->equals(CurrencyCode::fromString(self::CURRENCY_USD))) {
            return Rate::fromFloat($rate->getRate())->sub(Rate::fromFloat(self::BUYING_RATE_USD));
        }

        return null;
    }

    private function calculateSellingRate(CurrencyCode $currencyCode, Rate $rate): Rate
    {
        # w operacjach na kwotach należy uważać na precyzję. Pomocne są chociażby paczki typu moneyphp/money
        if ($currencyCode->equals(CurrencyCode::fromString(self::CURRENCY_EURO))) {
            return Rate::fromFloat($rate->getRate())->add(Rate::fromFloat(self::SELLING_RATE_EUR));
        }

        if ($currencyCode->equals(CurrencyCode::fromString(self::CURRENCY_USD))) {
            return Rate::fromFloat($rate->getRate())->add(Rate::fromFloat(self::SELLING_RATE_USD));
        }

        return Rate::fromFloat($rate->getRate())->add(Rate::fromFloat(self::SELLING_RATE_REST));
    }
}