<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Service\ProductService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class ProductController extends AbstractController
{
    public function __construct(
        private readonly ProductService $productService
    ) {
    }

    #[Route('/products/{type}', methods: ['GET'])]
    public function list(string $type, Request $request): JsonResponse
    {
        try {
            $products = $this->productService->getProducts($type, $request);
            return new JsonResponse(['data' => $products], Response::HTTP_OK);
        } catch (Exception $e) {
            return new JsonResponse(
                [
                    'error' =>
                        json_decode($e->getMessage(), true)
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    #[Route('/products', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        try {
            $this->productService->createProducts(
                json_decode($request->getContent(), true)
            );
            return new JsonResponse(
                ['message' => 'Products created successfully'],
                Response::HTTP_CREATED
            );
        } catch (Exception $e) {
            return new JsonResponse(
                [
                    'error' =>
                        json_decode($e->getMessage(), true)
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    #[Route('/products/{type}/{id}', methods: ['DELETE'])]
    public function delete(string $type, int $id): JsonResponse
    {
        try {
            $this->productService->deleteProduct($type, $id);
            return new JsonResponse(
                [
                    'message' => 'Product deleted successfully.'
                ], Response::HTTP_OK
            );
        } catch (Exception $e) {
            return new JsonResponse(
                [
                    'error' => 'An error occurred while deleting the product.',
                    'details' => $e->getMessage()
                ], Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
