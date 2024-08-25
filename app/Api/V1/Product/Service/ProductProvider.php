<?php declare(strict_types = 1);

namespace App\Api\V1\Product\Service;

use App\Api\V1\Product\Exception\ProductNotFoundException;
use App\Api\V1\Product\ResponseEntity\Product;
use App\Api\V1\Product\ValueObject\ProductFilter;
use App\Model\Repository\Product\ProductRepository;

final class ProductProvider
{

	public function __construct(
		private ProductRepository $productRepository,
		private ProductMapper $productMapper,
	)
	{
	}

	/**
	 * @throws ProductNotFoundException
	 */
	public function getById(int $id): Product
	{
		$productEntity = $this->productRepository->findById($id);
		if ($productEntity === null || $productEntity->isDeleted()) {
			throw new ProductNotFoundException($id);
		}

		return $this->productMapper->mapProductOrmToResponseEntity($productEntity);
	}

	/**
	 * @return array<array<string, mixed>>
	 */
	public function list(ProductFilter $productFilter): array
	{
		$products = $this->productRepository->findByApiProductFilter($productFilter);

		$productDataResult = [];
		foreach ($products as $productEntity) {
			$productDataResult[] = $this->productMapper->mapProductOrmToResponseEntity($productEntity)->toArray();
		}

		return $productDataResult;
	}

}
