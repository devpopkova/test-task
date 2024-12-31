<?php

declare(strict_types=1);

namespace App\Service;

use App\Interfaces\Factory\CollectionFactoryInterface;
use App\Interfaces\Factory\ProductInputDTOFactoryInterface;
use App\Interfaces\Service\StorageServiceInterface;
use App\Interfaces\Transformer\ProductInputDTOToProductTransformerInterface;
use InvalidArgumentException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class StorageService implements StorageServiceInterface
{
    public function __construct(
        private ProductInputDTOFactoryInterface $productInputDTOFactory,
        private CollectionFactoryInterface $collectionFactory,
        private ProductInputDTOToProductTransformerInterface $productInputDTOToProductTransformer,
        private ValidatorInterface $validator,
    ) {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function initializeFromJson(string $jsonContent): void
    {
        $products = json_decode($jsonContent, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidArgumentException('Invalid JSON format');
        }

        foreach ($products as $product) {
            $dto = $this->productInputDTOFactory->createFromArray($product);

            $violations = $this->validator->validate($dto);
            if (count($violations) > 0) {
                $errors = [];
                foreach ($violations as $violation) {
                    $errors[$violation->getPropertyPath()][] = $violation->getMessage();
                }
                throw new InvalidArgumentException(json_encode($errors, JSON_UNESCAPED_UNICODE));
            }

            $collection = $this->collectionFactory->getCollection($product['type']);
            $product = $this->productInputDTOToProductTransformer->transform($dto);
            $collection->add($product);
        }
    }
}
