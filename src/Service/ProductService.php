<?php

declare(strict_types=1);

namespace App\Service;

use App\Interfaces\Factory\CollectionFactoryInterface;
use App\Interfaces\Factory\FilterProductDTOFactoryInterface;
use App\Interfaces\Factory\ProductInputDTOFactoryInterface;
use App\Interfaces\Service\ProductServiceInterface;
use App\Interfaces\Transformer\ProductInputDTOToProductTransformerInterface;
use App\Repository\ProductRepository;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductService implements ProductServiceInterface
{
    public function __construct(
        private ProductInputDTOFactoryInterface $productInputDTOFactory,
        private FilterProductDTOFactoryInterface $filterProductDTOFactory,
        private ProductRepository $productRepository,
        private CollectionFactoryInterface $collectionFactory,
        private ValidatorInterface $validator,
        private ProductInputDTOToProductTransformerInterface $productInputDTOToProductTransformer,
    ) {
    }

    /**
     * throws \InvalidArgumentException
     */
    public function getProducts(string $type, Request $request): array
    {
        $filterProductDTO = $this->filterProductDTOFactory->createFromRequest($request);

        $violations = $this->validator->validate($filterProductDTO);
        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()][] = $violation->getMessage();
            }
            throw new InvalidArgumentException(json_encode($errors));
        }

        $collection = $this->collectionFactory->getCollection($type);
        $products = $this->productRepository->findByFilters($type, $filterProductDTO);
        return $collection->list($products, $filterProductDTO->unit);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function createProducts(array $productsData): void
    {
        foreach ($productsData as $productData) {
            $dto = $this->productInputDTOFactory->createFromArray($productData);

            $violations = $this->validator->validate($dto);
            if (count($violations) > 0) {
                $errors = [];
                foreach ($violations as $violation) {
                    $errors[$violation->getPropertyPath()][] = $violation->getMessage();
                }
                throw new InvalidArgumentException(json_encode($errors));
            }

            $collection = $this->collectionFactory->getCollection($productData['type']);
            $product = $this->productInputDTOToProductTransformer->transform($dto);
            $collection->add($product);
        }
    }

    public function deleteProduct(string $type, int $id): void
    {
        $collection = $this->collectionFactory->getCollection($type);
        $collection->remove($id);
    }
}
