<?php declare(strict_types = 1);

namespace App\Model\Repository\Product;

use App\Api\V1\Product\ValueObject\ProductFilter;
use App\Model\Entity\Product\Product;
use Doctrine\ORM\EntityManagerInterface;

final class ProductRepository
{

	public function __construct(
		private EntityManagerInterface $entityManager,
	)
	{
	}

	public function findById(int $id): ?Product
	{
		return $this->entityManager->find(Product::class, $id);
	}

	/**
	 * @return array<Product>
	 */
	public function findByApiProductFilter(ProductFilter $productFilter): array
	{
		$queryBuilder = $this->entityManager->createQueryBuilder()
			->select('product')
			->from(Product::class, 'product');

		if ($productFilter->getName() !== null) {
			$queryBuilder->andWhere('product.name LIKE :name')
				->setParameter('name', sprintf('%%%s%%', $productFilter->getName()));
		}

		if ($productFilter->getPriceFrom() !== null) {
			$queryBuilder->andWhere('product.price >= :priceFrom')
				->setParameter('priceFrom', $productFilter->getPriceFrom()->getAmount());
		}

		if ($productFilter->getPriceTo() !== null) {
			$queryBuilder->andWhere('product.price <= :priceTo')
				->setParameter('priceTo', $productFilter->getPriceTo()->getAmount());
		}

		if ($productFilter->getCreatedAtFrom() !== null) {
			$queryBuilder->andWhere('product.createdAt >= :createdAtFrom')
				->setParameter('createdAtFrom', $productFilter->getCreatedAtFrom());
		}

		if ($productFilter->getCreatedAtTo() !== null) {
			$queryBuilder->andWhere('product.createdAt <= :createdAtTo')
				->setParameter('createdAtTo', $productFilter->getCreatedAtTo());
		}

		$queryBuilder->orderBy(sprintf('product.%s', $productFilter->getOrderBy()), $productFilter->getOrdering()->value);

		return $queryBuilder->getQuery()->getResult();
	}

}
