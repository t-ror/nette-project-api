<?php declare(strict_types = 1);

namespace App\Api\V1\TypeMapper;

use Apitte\Core\Exception\Runtime\InvalidArgumentTypeException;
use Apitte\Core\Mapping\Parameter\ITypeMapper;

final class ProductSortableColumnTypeMapper implements ITypeMapper
{

	private const TYPE = 'productSortableColumn';

	private const SORTABLE_COLUMNS = [
		'id',
		'name',
		'price',
		'createdAt',
	];

	public function normalize(mixed $value): mixed
	{
		if (!is_string($value) || !in_array($value, self::SORTABLE_COLUMNS, true)) {
			throw new InvalidArgumentTypeException(
				self::TYPE,
				sprintf('Pass one of following values %s', implode(', ', self::SORTABLE_COLUMNS)),
			);
		}

		return $value;
	}

}
