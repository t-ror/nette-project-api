<?php declare(strict_types = 1);

namespace App\Api\V1\Product;

use Apitte\Core\Annotation\Controller\Id;
use Apitte\Core\Annotation\Controller\Method;
use Apitte\Core\Annotation\Controller\Path;
use Apitte\Core\Annotation\Controller\RequestParameter;
use Apitte\Core\Http\ApiRequest;
use Apitte\Core\Http\ApiResponse;
use App\Api\V1\BaseV1Controller;
use App\Api\V1\Product\Exception\ProductNotFoundException;
use App\Api\V1\Product\Service\ProductProvider;
use Nette\Http\IResponse;

#[Path('/product')]
#[Id('product')]
final class ProductController extends BaseV1Controller
{

	public function __construct(
		private ProductProvider $productProvider,
	)
	{
	}

	#[Path('/{id}')]
	#[Method('GET')]
	#[RequestParameter(name: 'id', type: 'int', in: 'path', required: true, description: 'Product ID')]
	public function get(ApiRequest $request, ApiResponse $response): ApiResponse
	{
		if (!$request->hasParameter('id')) {
			return $response
				->withStatus(IResponse::S400_BadRequest)
				->writeBody('Missing "id" parameter')
				->withHeader('Content-Type', 'application/json');
		}

		try {
			$productData = $this->productProvider->getById($request->getParameter('id'));
		} catch (ProductNotFoundException $exception) {
			return $response
				->withStatus(IResponse::S400_BadRequest)
				->writeBody($exception->getMessage())
				->withHeader('Content-Type', 'application/json');
		}

		return $response
			->writeJsonBody($productData)
			->withHeader('Content-Type', 'application/json');
	}

}
