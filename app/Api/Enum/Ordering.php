<?php declare(strict_types = 1);

namespace App\Api\Enum;

enum Ordering: string
{

	case ASCENDING = 'ASC';
	case DESCENDING = 'DESC';

}
