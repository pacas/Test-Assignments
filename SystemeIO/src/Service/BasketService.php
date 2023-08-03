<?php

namespace App\Service;

use App\DTO\BasketDetailsRequestDTO;
use App\Entity\Basket;
use App\Entity\ProductPaymentInfo;
use App\Repository\CountryInfoRepository;
use App\Repository\ProductRepository;
use App\Validator\Validator;
use Doctrine\ORM\Exception\MissingIdentifierField;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class BasketService
{
    public function __construct(
        private ProductRepository $productRepository,
        private CountryInfoRepository $countryInfoRepository,
        private Validator $validator
    )
    {
    }
    public function createBasket(BasketDetailsRequestDTO $data): Basket
    {
        $basket = new Basket($data);
        // оставляем репозиторий и внешнюю логику за пределами сущностей
        $basket->getProductInfo()->setProduct($this->productRepository->find($data->productDetails->productID));
        return $basket;
    }

    public function proceedBasket(Basket $basket): void
    {
        $this->checkTaxInfo($basket);
        $this->checkIfBasketValid($basket);
        $this->calculateFinalPrice($basket);
    }

    public function checkTaxInfo(Basket $basket): void
    {
        // тут проверку на правильность номера
        $taxNum = $basket->getProductInfo()->getTaxNumber();
        $countyName = substr($taxNum, 0, 2);
        $countyMask = substr($taxNum, 2);
        $countyMask = preg_replace("#[a-zA-Z]#u", "Y", $countyMask);
        $countyMask = preg_replace("#\d#u", "X", $countyMask);
        try {
            $county = $this->countryInfoRepository->findOneBy(['name' => $countyName]);
            if (!($county->getTaxMask() === $countyMask)) {
                $basket->setValid(false);
            }
        } catch (MissingIdentifierField $e) {}
    }

    public function checkIfBasketValid(Basket $basket): void
    {
        $this->validator->validateWithException($basket);
        $this->validator->validateWithException($basket->getProductInfo());
        if ($basket->getProductInfo()->getCoupon()?->isValid() === false) {
            throw new BadRequestException('Bad coupon code');
        }
        if ($basket->isValid() === false) {
            throw new BadRequestException('Bad tax info');
        }
    }

    public function calculateFinalPrice(Basket $basket): void
    {
        $countyName = substr($basket->getProductInfo()->getTaxNumber(), 0, 2);
        $county = $this->countryInfoRepository->findOneBy(['name' => $countyName]);
        $basket->setFinalPrice($basket->getProductInfo()->getProduct()->getPrice());
        // не уверен в каком порядке считать, так что сначала купон, потом налог
        $coupon = $basket->getProductInfo()->getCoupon();
        $finalPrice = $basket->getFinalPrice();
        if ($coupon?->isValid() === true) {
            // уже проверили что только два типа
            if ($coupon->getType() === 'D') {
                $finalPrice *= ((100 - $basket->getProductInfo()->getCoupon()->getPercent()) / 100);
            } else {
                $finalPrice -= $basket->getProductInfo()->getCoupon()->getPercent();
                $finalPrice = max($finalPrice, 0);
            }

        }
        $finalPrice = round(($finalPrice * (1 + ($county->getTaxPercent()) / 100)), 2);
        $basket->setFinalPrice($finalPrice);
    }

}