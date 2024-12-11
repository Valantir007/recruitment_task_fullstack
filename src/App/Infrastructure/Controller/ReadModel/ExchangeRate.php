<?php

namespace App\Infrastructure\Controller\ReadModel;

use App\Domain\CurrencyRate;

class ExchangeRate
{
    /**
     * @var string
     */
    public $currencyCode;

    /**
     * @var string
     */
    public $currencyName;

    /**
     * @var string
     */
    public $todayEffectiveDate;

    /**
     * @var float|null
     */
    public $todayBuyingRate = null;

    /**
     * @var float
     */
    public $todaySellingRate;

    /**
     * @var string
     */
    public $effectiveDateByDate;

    /**
     * @var float|null
     */
    public $buyingRateByDate = null;

    /**
     * @var float
     */
    public $sellingRateByDate = null;

    public function __construct(CurrencyRate $currencyRate, CurrencyRate $rateByDate)
    {
        $this->currencyName = $currencyRate->getCurrencyName()->getName();
        $this->currencyCode = $currencyRate->getCurrencyCode()->getCode();
        $this->todayEffectiveDate = $currencyRate->getEffectiveDate()->getDateTime()->format('Y-m-d');
        $this->todayBuyingRate = $currencyRate->getBuyingRate() ? $currencyRate->getBuyingRate()->getRate() : null;
        $this->todaySellingRate = $currencyRate->getSellingRate()->getRate();

        $this->effectiveDateByDate = $rateByDate->getEffectiveDate()->getDateTime()->format('Y-m-d');
        $this->buyingRateByDate = $rateByDate->getBuyingRate() ? $rateByDate->getBuyingRate()->getRate() : null;
        $this->sellingRateByDate = $rateByDate->getSellingRate()->getRate();
    }
}