<?php

namespace App\Controller;

use App\Controller\Components\RequestHandlerTrait;
use App\Service\BasketService;
use App\Service\Payment\PaymentService;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BasketController extends BaseController
{
    use RequestHandlerTrait;

    // далее исходя из описания я так понял, что get запрос нужен для предварительного просмотра,
    // а post - для выполнения операции
    #[Route(self::API_PREFIX . 'price', methods: ['GET'])]
    public function getPriceInfo(Request $request, BasketService $basketService): JsonResponse
    {
        try {
            $basketDTO = $this->handleBasketRequest($request);
            $basket = $basketService->createBasket($basketDTO);
            $basketService->proceedBasket($basket);
            return new JsonResponse(
                [
                    'productName' => $basket->getProductInfo()->getProduct()->getName(),
                    'productPrice' => $basket->getFinalPrice()
                ]
            );
        } catch (BadRequestException $exception) {
            return new JsonResponse($exception->getMessage(), 400);
        }
    }

    #[Route(self::API_PREFIX . 'pay', methods: 'POST')]
    public function pay(Request $request, BasketService $basketService, PaymentService $paymentService): JsonResponse
    {
        try {
            $basketDTO = $this->handleBasketRequest($request);
            $basket = $basketService->createBasket($basketDTO);
            $basketService->proceedBasket($basket);
            $paymentOutput = $paymentService->payWith(
                $basket->getProductInfo()->getPaymentProcessor(),
                $basket->getFinalPrice()
            );
            $paymentOutput['success'] ?
                $response = new JsonResponse('Successful payment', 200) :
                $response = new JsonResponse($paymentOutput['error'], 400);
        }
        catch (BadRequestException $exception) {
            return new JsonResponse($exception->getMessage(), 400);
        }
        return $response;
    }

}