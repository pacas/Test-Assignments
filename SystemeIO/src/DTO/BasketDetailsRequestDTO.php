<?php

namespace App\DTO;

class BasketDetailsRequestDTO
{
    public ProductDetailsRequestDTO $productDetails;

    public function __construct(array $detailsRequest) {
        // здесь бы создавался полноценный DTO корзины, но товар в примере может быть только 1, так что предаём дальше
        $this->productDetails = new ProductDetailsRequestDTO($detailsRequest);
    }
}