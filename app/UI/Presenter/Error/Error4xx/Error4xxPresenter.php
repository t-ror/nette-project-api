<?php declare(strict_types = 1);

namespace App\UI\Presenter\Error\Error4xx;

use Nette\Application\Attributes\Requires;
use Nette\Application\BadRequestException;
use Nette\Application\UI\Presenter;

/**
 * Handles 4xx HTTP error responses.
 */
#[Requires(methods: '*')]
final class Error4xxPresenter extends Presenter
{

	public function renderDefault(BadRequestException $exception): void
	{
		// renders the appropriate error template based on the HTTP status code
		$code = $exception->getCode();
		$file = is_file($file = __DIR__ . '/' . $code . '.latte')
			? $file
			: __DIR__ . '/4xx.latte';

		$this->getTemplate()->httpCode = $code;
		$this->getTemplate()->setFile($file);
	}

}
