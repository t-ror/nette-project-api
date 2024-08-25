<?php declare(strict_types = 1);

namespace App\Model\Persister\Product;

use App\Api\V1\Product\RequestEntity\Product as ProductRequestEntity;
use App\Model\Entity\Product\Product;
use App\Model\Money\Price;
use Doctrine\ORM\EntityManagerInterface;
use Kdyby\DateTimeProvider\DateTimeProviderInterface;

final class ProductPersister
{

	public function __construct(
		private EntityManagerInterface $entityManager,
		private DateTimeProviderInterface $dateTimeProvider,
	)
	{
	}

	public function createFromRequestEntity(ProductRequestEntity $productRequestEntity): Product
	{
		$productOrm = new Product(
			$productRequestEntity->name,
			Price::createForProduct((string) $productRequestEntity->price),
			$this->dateTimeProvider,
		);

		$this->entityManager->persist($productOrm);

		return $productOrm;
	}

	public function updateFromRequestEntity(Product $productOrm, ProductRequestEntity $productRequestEntity): void
	{
		$productOrm->setName($productRequestEntity->name);
		$productOrm->setPrice(Price::createForProduct((string) $productRequestEntity->price));
		$productOrm->setUpdatedAt($this->dateTimeProvider->getDateTime());
	}

}
