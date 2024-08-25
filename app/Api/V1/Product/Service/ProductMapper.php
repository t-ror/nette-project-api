<?php declare(strict_types = 1);

namespace App\Api\V1\Product\Service;

use App\Model\Entity\Product\Product;

final class ProductMapper
{

	/**
	 * @return array<string, mixed>
	 */
	public function mapProductOrmToArray(Product $product): array
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
