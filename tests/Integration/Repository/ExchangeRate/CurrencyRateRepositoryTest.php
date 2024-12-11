<?php

namespace Integration\Repository\ExchangeRate;

use App\Infrastructure\HttpClient\HttpNbpClient;
use App\Infrastructure\Repository\ExchangeRate\CurrencyRateRepository;
use App\Infrastructure\HttpClient\NbpClient;
use App\Infrastructure\HttpClient\Response\ExchangeRate\ExchangeRate;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpClient\Exception\ServerException;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;

class CurrencyRateRepositoryTest extends KernelTestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
    }

    public function testGetCurrencyRateByDateMethodReturnsAllReceivedData(): void
    {
        $nbpClientResponse = [
            [
                'table' => 'A',
                'no' => '240/A/NBP/2024',
                'effectiveDate' => '2024-12-11',
                'rates' => [
                    [
                        'currency' => 'euro',
                        'code' => 'EUR',
                        'mid' => 4.2661,
                    ],
                    [
                        'currency' => 'dolar amerykański',
                        'code' => 'USD',
                        'mid' => 4.0628,
                    ],
                ]
            ]
        ];
        $container = self::$container;

        $nbpClient = $this->createMock(HttpNbpClient::class);
        $nbpClient->expects($this->once())
            ->method('getExchangeRate')
            ->willReturn(ExchangeRate::createFromResponse($nbpClientResponse));

        $container->set(NbpClient::class, $nbpClient);
        /** @var $exchangeRateRepository CurrencyRateRepository*/
        $exchangeRateRepository = $container->get(CurrencyRateRepository::class);

        $data = $exchangeRateRepository->getCurrencyRateByDate(new \DateTime('2024-12-11'));
        $this->assertCount(2, $data);
    }

    public function testGetCurrencyRateByDateMethodReturnsOnlyAllowedCurrencies(): void
    {
        $nbpClientResponse = [
            [
                'table' => 'A',
                'no' => '240/A/NBP/2024',
                'effectiveDate' => '2024-12-11',
                'rates' => [
                    [
                        'currency' => 'euro',
                        'code' => 'EUR',
                        'mid' => 4.2661,
                    ],
                    [
                        'currency' => 'Testowa waluta',
                        'code' => 'Test',
                        'mid' => 4.0628,
                    ],
                ]
            ]
        ];
        $container = self::$container;

        $nbpClient = $this->createMock(HttpNbpClient::class);
        $nbpClient->expects($this->once())
            ->method('getExchangeRate')
            ->willReturn(ExchangeRate::createFromResponse($nbpClientResponse));

        $container->set(NbpClient::class, $nbpClient);
        /** @var $exchangeRateRepository CurrencyRateRepository*/
        $exchangeRateRepository = $container->get(CurrencyRateRepository::class);

        $data = $exchangeRateRepository->getCurrencyRateByDate(new \DateTime('2024-12-11'));
        $this->assertCount(1, $data);
    }

    public function testGetCurrencyRateByDateMethodThrowServerException(): void
    {
        $this->expectException(ServerExceptionInterface::class);
        $container = self::$container;

        $nbpClient = $this->createMock(HttpNbpClient::class);
        $nbpClient->expects($this->once())
            ->method('getExchangeRate')
            ->willThrowException(new ServerException(new MockResponse()));

        $container->set(NbpClient::class, $nbpClient);
        /** @var $exchangeRateRepository CurrencyRateRepository*/
        $exchangeRateRepository = $container->get(CurrencyRateRepository::class);

        $exchangeRateRepository->getCurrencyRateByDate(new \DateTime('2024-12-11'));
    }
}