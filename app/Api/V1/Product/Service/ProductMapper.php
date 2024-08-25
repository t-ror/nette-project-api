<?php declare(strict_types = 1);

namespace App\Api\V1\Product\Service;

use App\Api\V1\Product\ResponseEntity\Product;
use App\Model\Entity\Product\Product as ProductOrm;

final class ProductMapper
{

	public function mapProductOrmToResponseEntity(ProductOrm $productOrm): Product
	{
		return new Product(
			$productOrm->getId(),
			$productOrm->getName(),
			$productOrm->getPrice()->getAmount(),
			$productOrm->getCreatedAt()->getTimestamp(),
			$productOrm->getUpdatedAt()?->getTimestamp(),
		);
	}

}
