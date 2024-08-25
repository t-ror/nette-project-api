<?php declare(strict_types = 1);

namespace App\Api\V1\Product\Service;

use App\Api\V1\Product\Exception\ProductNotFoundException;
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
	 * @return array<string, mixed>
	 * @throws ProductNotFoundException
	 */
	public function getById(int $id): array
	{
		$productEntity = $this->productRepository->findById($id);
		if ($productEntity === null) {
			throw new ProductNotFoundException($id);
		}

		return $this->productMapper->mapProductOrmToArray($productEntity);
	}

	/**
	 * @return array<array<string, mixed>>
	 */
	public function list(ProductFilter $productFilter): array
	{
		$products = $this->productRepository->findByApiProductFilter($productFilter);

		$productDataResult = [];
		foreach ($products as $productEntity) {
			$productDataResult[] = $this->productMapper->mapProductOrmToArray($productEntity);
		}

		return $productDataResult;
	}

}
