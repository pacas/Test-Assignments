<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ProductDetailsRequestDTO
{
    #[Assert\NotBlank]
    public ?string $productID;

    #[Assert\NotBlank]
    public ?string $taxNumber;

    public ?string $couponCode;

    #[Assert\NotBlank]
    public ?string $paymentProcessor;

    public function __construct(array $detailsRequest) {
        $this->productID = $detailsRequest['product'] ?? null;
        $this->taxNumber = $detailsRequest['taxNumber'] ?? null;
        $this->couponCode = $detailsRequest['couponCode'] ?? null;
        $this->paymentProcessor = $detailsRequest['paymentProcessor'] ?? null;
    }

}