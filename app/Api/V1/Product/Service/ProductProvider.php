<?php declare(strict_types = 1);

namespace App\Api\V1\Product\Service;

use App\Api\V1\Product\Exception\ProductNotFoundException;
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
	 * @return array<string, mixed>
	 */
	private function mapProductToArray(Product $product): array
	{
		return [
			'id' => $product->getId(),
			'name' => $product->getName(),
			'price' => $product->getPrice()->getAmount(),
			'createdAt' => $product->getCreatedAt()->getTimestamp(),
			'updatedAt' => $product->getUpdatedAt()?->getTimestamp(),
		];
	}

}
