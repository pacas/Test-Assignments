<?php

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BasketControllerTest extends WebTestCase
{
    public function testCalculationFine(): void
    {
        $client = static::createClient();
        $crawler = $client->jsonRequest('GET', '/api/price',
        [
            "product" => "1",
            "taxNumber" => "DE123456789",
            "couponCode" => "D15",
            "paymentProcessor" => "paypal"
        ]);
        $this->assertResponseIsSuccessful();
    }

    public function testCalculationFineNoCoupon(): void
    {
        $client = static::createClient();
        $crawler = $client->jsonRequest('GET', '/api/price',
            [
                "product" => "1",
                "taxNumber" => "DE123456789",
                "paymentProcessor" => "paypal"
            ]);
        $this->assertResponseIsSuccessful();
    }

    public function testCalculationBadRequest(): void
    {
        $client = static::createClient();
        $crawler = $client->jsonRequest('GET', '/api/price',
            [
                "taxNumber" => "DE123456789",
                "couponCode" => "D15"
            ]);
        $this->assertResponseIsSuccessful();
    }

    public function testCalculationBadProduct(): void
    {
        $client = static::createClient();
        $crawler = $client->jsonRequest('GET', '/api/price',
            [
                "product" => "99",
                "taxNumber" => "DE123456789",
                "couponCode" => "D15",
                "paymentProcessor" => "paypal"
            ]);
        $this->assertResponseIsSuccessful();
    }

    public function testCalculationBadTax(): void
    {
        $client = static::createClient();
        $crawler = $client->jsonRequest('GET', '/api/price',
            [
                "product" => "1",
                "taxNumber" => "DE123451246789",
                "couponCode" => "D15",
                "paymentProcessor" => "paypal"
            ]);
        $this->assertResponseIsSuccessful();
    }

    // далее только проверка самой оплаты и важные для него проблемы

    public function testPayFine(): void
    {
        $client = static::createClient();
        $crawler = $client->jsonRequest('POST', '/api/pay',
            [
                "product" => "1",
                "taxNumber" => "DE123456789",
                "couponCode" => "D30",
                "paymentProcessor" => "paypal"
            ]);
        $this->assertResponseIsSuccessful();
    }

    public function testPayBadProcessor(): void
    {
        $client = static::createClient();
        $crawler = $client->jsonRequest('POST', '/api/pay',
            [
                "product" => "1",
                "taxNumber" => "DE123456789",
                "couponCode" => "D15",
                "paymentProcessor" => "paypal1"
            ]);
        $this->assertResponseIsSuccessful();
    }

    public function testPayPaypalFail(): void
    {
        $client = static::createClient();
        $crawler = $client->jsonRequest('POST', '/api/pay',
            [
                "product" => "1",
                "taxNumber" => "DE123456789",
                "paymentProcessor" => "paypal"
            ]);
        $this->assertResponseIsSuccessful();
    }

    public function testPayStripeFail(): void
    {
        $client = static::createClient();
        $crawler = $client->jsonRequest('POST', '/api/pay',
            [
                "product" => "1",
                "taxNumber" => "DE123456789",
                "couponCode" => "F99",
                "paymentProcessor" => "ыекшзу"
            ]);
        $this->assertResponseIsSuccessful();
    }
}