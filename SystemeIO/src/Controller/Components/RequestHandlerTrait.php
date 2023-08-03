<?php

namespace App\Controller\Components;

use App\DTO\BasketDetailsRequestDTO;
use App\Validator\Validator;
use Symfony\Component\HttpFoundation\Request;

trait RequestHandlerTrait
{
    public function __construct(
        protected Validator $validator
    )
    {
    }

    protected function handleBasketRequest(Request $request): BasketDetailsRequestDTO
    {
        $basketDTO = new BasketDetailsRequestDTO($request->toArray());
        $this->validator->validateWithException($basketDTO->productDetails);
        return $basketDTO;
    }
}