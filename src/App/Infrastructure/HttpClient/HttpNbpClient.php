<?php

namespace App\Infrastructure\HttpClient;

use App\Infrastructure\HttpClient\Request\ExchangeRateRequest;
use App\Infrastructure\HttpClient\Response\ExchangeRate\ExchangeRate;
use App\Infrastructure\Json\JsonDecoder;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HttpNbpClient implements NbpClient
{
    private $nbpClient;

    private $jsonDecoder;

    public function __construct(HttpClientInterface $nbpClient, JsonDecoder $jsonDecoder)
    {
        $this->nbpClient = $nbpClient;
        $this->jsonDecoder = $jsonDecoder;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getExchangeRate(ExchangeRateRequest $currencyRateRequest): ExchangeRate
    {
        $response = $this->nbpClient->request($currencyRateRequest->getMethod(), $currencyRateRequest->getEndpoint());
        $content = $response->getContent();
        # można użyć paczki symfony/serializer-pack ale nie ma jej w zależnościach więc użyta została funkcja \json_decode

        return ExchangeRate::createFromResponse($this->jsonDecoder->decode($content));
    }

}