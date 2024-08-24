<?php declare(strict_types = 1);

namespace App\Api\V1\Product\ValueObject;

use App\Api\Enum\Ordering;
use App\Model\Money\Price;
use DateTimeImmutable;

final readonly class ProductFilter
{

	private const DEFAULT_ORDER_BY = 'id';
	private const DEFAULT_ORDERING = Ordering::ASCENDING;

	public function __construct(
		private ?string $name,
		private ?string $priceFrom,
		private ?string $priceTo,
		private ?int $createdAtFrom,
		private ?int $createdAtTo,
		private ?string $orderBy,
		private ?string $ordering,
	)
	{
	}

	public function getName(): ?string
	{
		return $this->name;
	}

	public function getPriceFrom(): ?Price
	{
		return $this->priceFrom !== null
			? Price::createForProduct($this->priceFrom)
			: null;
	}

	public function getPriceTo(): ?Price
	{
		return $this->priceTo !== null
			? Price::createForProduct($this->priceTo)
			: null;
	}

	public function getCreatedAtFrom(): ?DateTimeImmutable
	{
		return $this->createdAtFrom !== null
			? (new DateTimeImmutable())->setTimestamp($this->createdAtFrom)
			: null;
	}

	public function getCreatedAtTo(): ?DateTimeImmutable
	{
		return $this->createdAtTo !== null
			? (new DateTimeImmutable())->setTimestamp($this->createdAtTo)
			: null;
	}

	public function getOrderBy(): string
	{
		return $this->orderBy !== null ? $this->orderBy : self::DEFAULT_ORDER_BY;
	}

	public function getOrdering(): Ordering
	{
		return $this->ordering !== null ? Ordering::from($this->ordering) : self::DEFAULT_ORDERING;
	}

}
