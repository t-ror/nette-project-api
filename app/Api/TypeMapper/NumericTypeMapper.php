<?php declare(strict_types = 1);

namespace App\Api\TypeMapper;

use Apitte\Core\Exception\Runtime\InvalidArgumentTypeException;
use Apitte\Core\Mapping\Parameter\ITypeMapper;

final class NumericTypeMapper implements ITypeMapper
{

	private const TYPE = 'numeric';

	public function normalize(mixed $value): mixed
	{
		if (!is_numeric($value)) {
			throw new InvalidArgumentTypeException(self::TYPE);
		}

		return $value;
	}

}
