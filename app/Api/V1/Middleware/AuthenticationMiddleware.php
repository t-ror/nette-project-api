<?php declare(strict_types = 1);

namespace App\Api\V1\Middleware;

use App\Api\V1\Service\RequestAuthenticator;
use Contributte\Middlewares\IMiddleware;
use Contributte\Psr7\Psr7Response;
use Contributte\Psr7\Psr7ServerRequest;
use InvalidArgumentException;
use Nette\Http\IResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class AuthenticationMiddleware implements IMiddleware
{

	public function __construct(
		private RequestAuthenticator $authenticator,
	)
	{
	}

	public function __invoke(
		ServerRequestInterface $request,
		ResponseInterface $response,
		callable $next
	): ResponseInterface
	{
		if (str_starts_with('/api/v1/schema', $request->getUri()->getPath()) || str_starts_with('/api/v1', $request->getUri()->getPath())) {
			return $next($request, $response);
		}

		if (!$request instanceof Psr7ServerRequest || !$response instanceof Psr7Response) {
			throw new InvalidArgumentException('This middleware can be used only with contributte/psr-7');
		}

		return $this->process($request, $response, $next);
	}

	private function process(Psr7ServerRequest $request, Psr7Response $response, callable $next): ResponseInterface
	{
		if (!$request->hasHeader(RequestAuthenticator::HEADER_AUTH)) {
			return $response
				->withStatus(IResponse::S401_Unauthorized)
				->writeJsonBody([
					'error' => 'Missing header "Authorization"',
				]);
		}

		if (!$this->authenticator->authenticate($request)) {
			return $response
				->withStatus(IResponse::S401_Unauthorized)
				->writeJsonBody([
					'error' => 'Unauthorized',
				]);
		}

		// Continue to next
		return $next($request, $response);
	}

}
