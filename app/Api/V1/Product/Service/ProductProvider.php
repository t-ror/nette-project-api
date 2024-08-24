<?php declare(strict_types = 1);

namespace App\Api\V1\Product\Service;

use App\Api\V1\Product\Exception\ProductNotFoundException;
use App\Api\V1\Product\ValueObject\ProductFilter;
use App\Model\Entity\Product\Product;
use App\Model\Repository\Product\ProductRepository;

final class ProductProvider
{

	public function __construct(
		private ProductRepository $productRepository,
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

		return $this->mapProductToArray($productEntity);
	}

	/**
	 * @return array<array<string, mixed>>
	 */
	public function list(ProductFilter $productFilter): array
	{
		$products = $this->productRepository->findByApiProductFilter($productFilter);

		$productDataResult = [];
		foreach ($products as $productEntity) {
			$productDataResult[] = $this->mapProductToArray($productEntity);
		}

		return $productDataResult;
	}

	/**
	 * @return array<string, mixed>
	 */
	private function mapProductToArray(Product $product): array
	{
		return [
			'id' => $product->getId(),
			'name' => $product->getName(),
			'price' => $product->getPrice()->getAmount(),
			'createdAt' => $product->getCreatedAt()->getTimestamp(),
			'createdAtF' => $product->getCreatedAt(),
			'updatedAt' => $product->getUpdatedAt()?->getTimestamp(),
		];
	}

}
