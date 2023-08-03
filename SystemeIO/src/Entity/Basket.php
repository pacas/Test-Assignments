<?php

namespace App\Entity;

use App\DTO\BasketDetailsRequestDTO;
use Symfony\Component\Validator\Constraints as Assert;

class Basket
{
//    Если продуктов становится несколько, в задаче фигурирует один
//    /* @var $productIDs ProductPaymentInfo[] */
//    public array $productInfo;
    #[Assert\NotBlank]
    private ProductPaymentInfo $productInfo;

    #[Assert\IsTrue]
    private bool $valid = true;

    private float $finalPrice;

    public function __construct(BasketDetailsRequestDTO $data) {
        $this->productInfo = new ProductPaymentInfo($data->productDetails);
    }

    public function getProductInfo(): ProductPaymentInfo
    {
        return $this->productInfo;
    }

    public function setProductInfo(ProductPaymentInfo $productInfo): void
    {
        $this->productInfo = $productInfo;
    }

    public function getFinalPrice(): float
    {
        return $this->finalPrice;
    }

    public function setFinalPrice(float $finalPrice): void
    {
        $this->finalPrice = $finalPrice;
    }

    public function isValid(): bool
    {
        return $this->valid;
    }
    public function setValid(bool $state): void
    {
        $this->valid = $state;
    }

}