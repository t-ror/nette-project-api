<?php declare(strict_types = 1);

namespace App\Api\V1\Product\RequestEntity;

use Apitte\Core\Mapping\Request\BasicEntity;
use App\Model\Entity\Product\Product as ProductOrmEntity;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Type;

final class Product extends BasicEntity
{

	#[Type(type: 'string')]
	#[NotNull]
	#[NotBlank]
	#[Length(max: ProductOrmEntity::NAME_MAX_LENGTH)]
	public string $name;

	#[Type(type: 'numeric')]
	#[NotNull]
	#[NotBlank]
	public string|float $price;

}
