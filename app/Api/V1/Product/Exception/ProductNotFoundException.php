<?php declare(strict_types = 1);

namespace App\Api\V1\Product\Exception;

use Exception;

final class ProductNotFoundException extends Exception
{

	public function __construct(
		int $productId,
	)
	{
		$message = sprintf('Product with ID "%d" not found', $productId);
		parent::__construct($message);
	}

}
