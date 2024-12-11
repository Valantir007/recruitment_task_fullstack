<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Domain\CurrencyRateRepository;
use App\Infrastructure\Controller\ReadModel\ExchangeRate;
use App\Infrastructure\Validator\DateTimeValidator;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ExchangeRateController extends AbstractController
{
    public function __invoke(
        Request $request,
        CurrencyRateRepository $currencyRateRepository,
        DateTimeValidator $dateTimeValidator
    ): Response {
        # Normalnie użyłbym mapowania requestu na obiekt, a cały obiekt wrzucił do walidatora z paczki symfony/validator
        # przez co całość mogłaby wyglądać mniej więcej tak: $validator->validate($filterObject);
        # Należałoby jeszcze sprawdzić czy dzień jest sobotą czy niedzielą - wspomniałem o tym w pliku Explanations.md
        $dateFromRequest = $request->query->get('date');
        $date = $dateTimeValidator->hasCorrectFormat($dateFromRequest) ? new DateTime($dateFromRequest) : new DateTime();

        $ratesByDate = $currencyRateRepository->getCurrencyRateByDate($date);
        $currencyRates = $currencyRateRepository->getCurrencyRateByDate(new DateTime());

        $readModels = [];
        foreach ($currencyRates as $currencyRate) {
            foreach ($ratesByDate as $rateByDate) {
                if ($rateByDate->getCurrencyCode()->equals($currencyRate->getCurrencyCode())) {
                    $readModels[] = new ExchangeRate($currencyRate, $rateByDate);
                }
            }
        }

        return new JsonResponse($readModels);
    }
}
