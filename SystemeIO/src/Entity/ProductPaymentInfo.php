<?php

namespace App\Entity;

use App\DTO\ProductDetailsRequestDTO;
use Symfony\Component\Validator\Constraints as Assert;

class ProductPaymentInfo
{
    #[Assert\NotBlank]
    private ?Product $product;

    #[Assert\NotBlank]
    private ?string $taxNumber;

    private ?Coupon $coupon;

    #[Assert\NotBlank]
    private ?string $paymentProcessor;

    public function __construct(ProductDetailsRequestDTO $data) {
        $this->taxNumber = $data->taxNumber;
        isset($data->couponCode) ? $this->coupon = new Coupon($data->couponCode) : $this->coupon = null;
        $this->paymentProcessor = $data->paymentProcessor;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): void
    {
        $this->product = $product;
    }

    public function getTaxNumber(): string
    {
        return $this->taxNumber;
    }

    public function getCoupon(): ?Coupon
    {
        return $this->coupon;
    }

    public function getPaymentProcessor(): ?string
    {
        return $this->paymentProcessor;
    }
}