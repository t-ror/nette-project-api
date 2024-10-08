<?php declare(strict_types = 1);

namespace App\Api\V1\Product\Service;

use App\Api\V1\Product\Exception\ProductNotFoundException;
use App\Api\V1\Product\RequestEntity\Product;
use App\Api\V1\Product\ResponseEntity\Product as ProductResponseEntity;
use App\Model\Persister\Product\ProductPersister;
use App\Model\Repository\Product\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

final class ProductPersistenceManager
{

	public function __construct(
		private ProductPersister $productPersister,
		private EntityManagerInterface $entityManager,
		private ProductRepository $productRepository,
		private ProductMapper $productMapper,
	)
	{
	}

	public function create(Product $productRequestEntity): ProductResponseEntity
	{
		$productOrm = $this->productPersister->createFromRequestEntity($productRequestEntity);

		$this->entityManager->flush();

		return $this->productMapper->mapProductOrmToResponseEntity($productOrm);
	}

	public function update(int $productId, Product $productRequestEntity): ProductResponseEntity
	{
		$productOrm = $this->productRepository->findById($productId);
		if ($productOrm === null || $productOrm->isDeleted()) {
			throw new ProductNotFoundException($productId);
		}

		$this->productPersister->updateFromRequestEntity($productOrm, $productRequestEntity);

		$this->entityManager->flush();

		return $this->productMapper->mapProductOrmToResponseEntity($productOrm);
	}

	public function delete(int $productId, bool $force): void
	{
		$productOrm = $this->productRepository->findById($productId);
		if ($productOrm === null || $productOrm->isDeleted()) {
			throw new ProductNotFoundException($productId);
		}

		$this->productPersister->delete($productOrm, $force);

		$this->entityManager->flush();
	}

}
