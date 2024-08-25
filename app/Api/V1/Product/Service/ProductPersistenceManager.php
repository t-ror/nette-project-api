<?php declare(strict_types = 1);

namespace App\Api\V1\Product\Service;

use App\Api\V1\Product\RequestEntity\Product;
use App\Model\Persister\Product\ProductPersister;
use Doctrine\ORM\EntityManagerInterface;

final class ProductPersistenceManager
{

	public function __construct(
		private ProductPersister $productPersister,
		private EntityManagerInterface $entityManager,
	)
	{
	}

	public function create(Product $productRequestEntity): int
	{
		$productOrm = $this->productPersister->createFromRequestEntity($productRequestEntity);

		$this->entityManager->flush();

		return $productOrm->getId();
	}

}
