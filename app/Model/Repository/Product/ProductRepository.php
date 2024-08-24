<?php declare(strict_types = 1);

namespace App\Model\Repository\Product;

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

}
