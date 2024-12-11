<?php

declare(strict_types=1);

namespace Unit\Domain;

use App\Domain\CurrencyCode;
use App\Domain\CurrencyName;
use App\Domain\CurrencyRate;
use App\Domain\EffectiveDate;
use App\Domain\Rate;
use PHPUnit\Framework\TestCase;

/**
 * W tych testach można by było sprawdzać czy np. wartość nie jest równa konkretnej wartości.
 * Jednak to spowodowałoby, że po zmianie współczynnika kupna czy sprzedaży, musielibyśmy także zmienić testy. W ten sposób
 * jak poniżej, unikamy konieczności zmiany testów przy zmianie tych współczynników.
 */
class CurrencyRateTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     */
    public function testCreateForUSD($value): void
    {
        $currencyRate = CurrencyRate::create(
            CurrencyCode::fromString('USD'),
            CurrencyName::fromString('dolar'),
            EffectiveDate::fromDateTime(new \DateTime()),
            Rate::fromFloat($value)
        );

        $valueAsString = (string)$value;
        $usdBuyingAdjustmentAsString = (string) CurrencyRate::BUYING_RATE_USD;
        $sum = \bcsub($valueAsString, $usdBuyingAdjustmentAsString, 10);

        $usdSellingAdjustmentAsString = (string) CurrencyRate::SELLING_RATE_USD;
        $difference = \bcadd($valueAsString, $usdSellingAdjustmentAsString, 10);

        $this->assertEquals((float) $sum, $currencyRate->getBuyingRate()->getRate());
        $this->assertEquals((float) $difference, $currencyRate->getSellingRate()->getRate());
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testCreateForEUR($value): void
    {
        $currencyRate = CurrencyRate::create(
            CurrencyCode::fromString('EUR'),
            CurrencyName::fromString('euro'),
            EffectiveDate::fromDateTime(new \DateTime()),
            Rate::fromFloat($value)
        );

        $valueAsString = (string)$value;
        $usdBuyingAdjustmentAsString = (string) CurrencyRate::BUYING_RATE_EUR;
        $sum = \bcsub($valueAsString, $usdBuyingAdjustmentAsString, 10);

        $usdSellingAdjustmentAsString = (string) CurrencyRate::SELLING_RATE_EUR;
        $difference = \bcadd($valueAsString, $usdSellingAdjustmentAsString, 10);

        $this->assertEquals((float) $sum, $currencyRate->getBuyingRate()->getRate());
        $this->assertEquals((float) $difference, $currencyRate->getSellingRate()->getRate());
    }


    /**
     * @dataProvider validDataProvider
     */
    public function testCreateForRest($value): void
    {
        $currencyRate = CurrencyRate::create(
            CurrencyCode::fromString('CZK'),
            CurrencyName::fromString('korona czeska'),
            EffectiveDate::fromDateTime(new \DateTime()),
            Rate::fromFloat($value)
        );

        $valueAsString = (string)$value;

        $usdSellingAdjustmentAsString = (string) CurrencyRate::SELLING_RATE_REST;
        $difference = \bcadd($valueAsString, $usdSellingAdjustmentAsString, 10);

        $this->assertNull($currencyRate->getBuyingRate());
        $this->assertEquals((float) $difference, $currencyRate->getSellingRate()->getRate());
    }

    /**
     * @return array<int, array<int, float>>
     */
    public function validDataProvider(): array
    {
        return [
            [1.12],
            [0.02],
            [0.00],
        ];
    }
}
