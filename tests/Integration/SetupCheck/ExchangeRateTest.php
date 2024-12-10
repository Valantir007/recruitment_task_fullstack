<?php

namespace Integration\SetupCheck;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExchangeRateTest extends WebTestCase
{
    public function testConnectivity(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/exchange-rates');
        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), TRUE);

        $this->assertIsArray($responseData);
        $this->assertNotEmpty($responseData);
        $this->assertCount(6, $responseData); //testujemy przy okazji czy jak zmienimy zmienna środowiskową to aplikacją ją podłapie
    }
}