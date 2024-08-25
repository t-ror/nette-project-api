<?php declare(strict_types = 1);

namespace App\Model\Entity\Product;

use App\Model\Entity\Attributes\TId;
use App\Model\Entity\EntityOrm;
use App\Model\Money\CurrencyCode;
use App\Model\Money\Price;
use DateTimeImmutable;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Kdyby\DateTimeProvider\DateTimeProviderInterface;

#[Entity]
class Product extends EntityOrm
{

	public const NAME_MAX_LENGTH = 100;
	public const PRICE_DECIMALS = 2;

	use TId;

	#[Column(type: 'string', length: self::NAME_MAX_LENGTH, nullable: false)]
	private string $name;

	#[Column(type: 'decimal', precision: 10, scale: self::PRICE_DECIMALS, nullable: false)]
	private string $price;

	#[Column(type: 'datetime_immutable', nullable: false)]

	private DateTimeImmutable $createdAt;

	#[Column(type: 'datetime_immutable', nullable: true)]

	private ?DateTimeImmutable $updatedAt = null;

	public function __construct(string $name, Price $price, DateTimeProviderInterface $dateTimeProvider)
	{
		$this->name = $name;
		$this->price = $price->getAmount();
		$this->createdAt = $dateTimeProvider->getDateTime();
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name): void
	{
		$this->name = $name;
	}

	public function getPrice(): Price
	{
		// z důvodu zjednoduššení dávám natvrdo CZK, jinak by samozřejmě bylo nutné cenu rozdělit do nové strukturuy např. ProductPrice, kde by byla rozdělená dle měn

		return new Price(
			$this->price,
			CurrencyCode::CZK,
			self::PRICE_DECIMALS,
		);
	}

	public function setPrice(Price $price): void
	{
		$this->price = $price->getAmount();
	}

	public function getCreatedAt(): DateTimeImmutable
	{
		return $this->createdAt;
	}

	public function setCreatedAt(DateTimeImmutable $createdAt): void
	{
		$this->createdAt = $createdAt;
	}

	public function getUpdatedAt(): ?DateTimeImmutable
	{
		return $this->updatedAt;
	}

	public function setUpdatedAt(?DateTimeImmutable $updatedAt): void
	{
		$this->updatedAt = $updatedAt;
	}

}
