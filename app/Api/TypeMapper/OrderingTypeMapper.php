<?php declare(strict_types = 1);

namespace App\Api\TypeMapper;

use Apitte\Core\Exception\Runtime\InvalidArgumentTypeException;
use Apitte\Core\Mapping\Parameter\ITypeMapper;
use App\Api\Enum\Ordering;

final class OrderingTypeMapper implements ITypeMapper
{

	private const TYPE = 'ordering';

	public function normalize(mixed $value): mixed
	{
		if (!is_string($value) || Ordering::tryFrom($value) === null) {
			throw new InvalidArgumentTypeException(
				self::TYPE,
				sprintf('Pass "%s" or "%s"', Ordering::ASCENDING->value, $value === Ordering::DESCENDING->value)
			);
		}

		return $value;
	}

}
