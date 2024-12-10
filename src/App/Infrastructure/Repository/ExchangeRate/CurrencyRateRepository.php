<?php

namespace App\Infrastructure\Repository\ExchangeRate;

use App\Domain\CurrencyCode;
use App\Domain\CurrencyName;
use App\Domain\CurrencyRate;
use App\Domain\CurrencyRateRepository as ExchangeRateRepositoryInterface;
use App\Domain\EffectiveDate;
use App\Domain\Rate;
use \DateTimeInterface;
use App\Infrastructure\HttpClient\NbpClient;
use App\Infrastructure\HttpClient\Request\ExchangeRateRequest;

class CurrencyRateRepository implements ExchangeRateRepositoryInterface
{
    private $nbpClient;

    private $availableCurrencies;

    public function __construct(NbpClient $nbpClient, array $availableCurrencies)
    {
        $this->nbpClient = $nbpClient;
        $this->availableCurrencies = $availableCurrencies;
    }

    public function getCurrencyRateByDate(DateTimeInterface $dateTime): array
    {
        $exchangeRate = $this->nbpClient->getExchangeRate(new ExchangeRateRequest($dateTime));

        $currencyRate = []; # przy większej ilości danych można posłużyć się generatorami
        foreach ($exchangeRate->getRates() as $rate) {
            if (\in_array($rate->code, $this->availableCurrencies)) {
                $currencyRate[] = CurrencyRate::create(
                    CurrencyCode::fromString($rate->code),
                    CurrencyName::fromString($rate->currency),
                    EffectiveDate::fromDateTime($exchangeRate->getEffectiveDate()),
                    Rate::fromFloat($rate->mid)
                );
            }
        }

        return $currencyRate;
    }
}