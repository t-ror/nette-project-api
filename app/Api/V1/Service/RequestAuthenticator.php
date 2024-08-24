<?php declare(strict_types = 1);

namespace App\Api\V1\Service;

use Contributte\Psr7\Psr7ServerRequest;
use Nette\Utils\Strings;

final class RequestAuthenticator
{

	public const HEADER_AUTH = 'Authorization';

	/**
	 * @param array<string, mixed> $apiConfiguration
	 */
	public function __construct(
		private array $apiConfiguration,
	)
	{
	}

	public function authenticate(Psr7ServerRequest $request): bool
	{
		$apiKey = $this->apiConfiguration['apiKey'];
		$bearerKey = Strings::replace($request->getHeader(self::HEADER_AUTH)[0], '#Bearer #');

		return hash_equals($apiKey, $bearerKey);
	}

}
