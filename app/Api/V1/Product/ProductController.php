<?php declare(strict_types = 1);

namespace App\Api\V1\Product;

use Apitte\Core\Annotation\Controller\Id;
use Apitte\Core\Annotation\Controller\Method;
use Apitte\Core\Annotation\Controller\Path;
use Apitte\Core\Annotation\Controller\RequestBody;
use Apitte\Core\Annotation\Controller\RequestParameter;
use Apitte\Core\Http\ApiRequest;
use Apitte\Core\Http\ApiResponse;
use App\Api\V1\BaseV1Controller;
use App\Api\V1\Product\Exception\ProductNotFoundException;
use App\Api\V1\Product\RequestEntity\Product;
use App\Api\V1\Product\Service\ProductPersistenceManager;
use App\Api\V1\Product\Service\ProductProvider;
use App\Api\V1\Product\ValueObject\ProductFilter;
use Nette\Http\IResponse;

#[Path('/product')]
#[Id('product')]
final class ProductController extends BaseV1Controller
{

	public function __construct(
		private ProductProvider $productProvider,
		private ProductPersistenceManager $productPersistenceManager,
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

	#[Path('/list')]
	#[Method('GET')]
	#[RequestParameter(name: 'name', type: 'string', in: 'query', required: false, description: 'Product name')]
	#[RequestParameter(name: 'priceFrom', type: 'numeric', in: 'query', required: false, description: 'Product price from')]
	#[RequestParameter(name: 'priceTo', type: 'numeric', in: 'query', required: false, description: 'Product price to')]
	#[RequestParameter(name: 'createdAtFrom', type: 'int', in: 'query', required: false, description: 'Product timestamp of date created from')]
	#[RequestParameter(name: 'createdAtTo', type: 'int', in: 'query', required: false, description: 'Product timestamp of date created to')]
	#[RequestParameter(name: 'orderBy', type: 'productSortableColumn', in: 'query', required: false, description: 'Column by which list will be ordered by')]
	#[RequestParameter(name: 'ordering', type: 'ordering', in: 'query', required: false, description: 'Ordering of the list ASC or DESC')]
	public function list(ApiRequest $request, ApiResponse $response): ApiResponse
	{
		$productFilter = new ProductFilter(
			$request->hasQueryParam('name') ? $request->getQueryParam('name') : null,
			$request->hasQueryParam('priceFrom') ? $request->getQueryParam('priceFrom') : null,
			$request->hasQueryParam('priceTo') ? $request->getQueryParam('priceTo') : null,
			$request->hasQueryParam('createdAtFrom') ? (int) $request->getQueryParam('createdAtFrom') : null,
			$request->hasQueryParam('createdAtTo') ? (int) $request->getQueryParam('createdAtTo') : null,
			$request->hasQueryParam('orderBy') ? $request->getQueryParam('orderBy') : null,
			$request->hasQueryParam('ordering') ? $request->getQueryParam('ordering') : null,
		);

		$productDataResult = $this->productProvider->list($productFilter);

		return $response
			->writeJsonBody($productDataResult)
			->withHeader('Content-Type', 'application/json');
	}

	#[Path('/create')]
	#[Method('POST')]
	#[RequestBody(entity: Product::class)]
	public function create(ApiRequest $request, ApiResponse $response): ApiResponse
	{
		/** @var Product $productRequestEntity */
		$productRequestEntity = $request->getEntity();

		$productId = $this->productPersistenceManager->create($productRequestEntity);

		return $response
			->writeJsonBody([
				'message' => sprintf('Product was successfully created'),
				'productId' => $productId,
			])
			->withHeader('Content-Type', 'application/json');
	}

	#[Path('/update/{id}')]
	#[Method('PUT')]
	#[RequestParameter(name: 'id', type: 'int', in: 'path', required: true, description: 'Product ID')]
	#[RequestBody(entity: Product::class)]
	public function update(ApiRequest $request, ApiResponse $response): ApiResponse
	{
		/** @var Product $productRequestEntity */
		$productRequestEntity = $request->getEntity();

		$productId = $request->getParameter('id');

		try {
			$productData = $this->productPersistenceManager->update($productId, $productRequestEntity);
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
