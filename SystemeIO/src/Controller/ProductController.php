<?php

namespace App\Controller;

use App\Controller\Components\RequestHandlerTrait;
use App\Entity\Product;
use App\Forms\ProductForm;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends BaseController
{
    use RequestHandlerTrait;

    #[Route(self::API_PREFIX . 'product/{product}', methods: 'GET')]
    public function findProduct(string $product, ProductRepository $repository): JsonResponse
    {
        return new JsonResponse($repository->find($product)?->getProductInfo());
    }

    // демонстрация запрошенных форм
    #[Route(self::API_PREFIX . 'product', methods: ['POST'])]
    public function addProduct(Request $request, ProductRepository $productRepository): Response
    {
        $productForm = $this->createForm(ProductForm::class, new Product());
        $productForm->submit(json_decode($request->getContent(), true));
        if (!$productForm->isValid()) {
            return new Response('Invalid product data', 400);
        }
        $productRepository->save($productForm->getData());
        return new Response('Success');
    }

}