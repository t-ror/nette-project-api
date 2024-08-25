<?php declare(strict_types = 1);

namespace App\Api\TypeMapper;

use Apitte\Core\Exception\Runtime\InvalidArgumentTypeException;
use Apitte\Core\Mapping\Parameter\ITypeMapper;

final class IntGreaterThanZeroTypeMapper implements ITypeMapper
{

	private const TYPE = 'intGreaterThanZero';

	public function normalize(mixed $value): mixed
	{
		if (
			(!is_int($value) && !(is_string($value) && ctype_digit($value)))
			|| (int) $value < 1
		) {
			throw new InvalidArgumentTypeException(self::TYPE, 'Pass integer greater than 0');
		}

		return (int) $value;
	}

}
