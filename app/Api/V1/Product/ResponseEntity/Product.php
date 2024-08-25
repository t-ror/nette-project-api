<?php declare(strict_types = 1);

namespace App\Api\V1\Product\ResponseEntity;

use Apitte\Core\Mapping\Request\BasicEntity;
use Symfony\Component\Validator\Constraints\Type;

final class Product extends BasicEntity
{

	#[Type(type: 'id')]
	public int $id;

	#[Type(type: 'string')]
	public string $name;

	#[Type(type: 'numeric')]
	public string $price;

	#[Type(type: 'int')]
	public int $createdAt;

	#[Type(type: 'int')]
	public ?int $updatedAt;

	public function __construct(
		int $id,
		string $name,
		string $price,
		int $createdAt,
		?int $updatedAt,
	)
	{
		$this->id = $id;
		$this->name = $name;
		$this->price = $price;
		$this->createdAt = $createdAt;
		$this->updatedAt = $updatedAt;
	}

}
