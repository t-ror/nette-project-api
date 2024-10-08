<?php declare(strict_types = 1);

namespace App\Api\V1\Product;

use Apitte\Core\Annotation\Controller\Id;
use Apitte\Core\Annotation\Controller\Method;
use Apitte\Core\Annotation\Controller\OpenApi;
use Apitte\Core\Annotation\Controller\Path;
use Apitte\Core\Annotation\Controller\RequestBody;
use Apitte\Core\Annotation\Controller\RequestParameter;
use Apitte\Core\Annotation\Controller\Response;
use Apitte\Core\Annotation\Controller\Tag;
use Apitte\Core\Http\ApiRequest;
use Apitte\Core\Http\ApiResponse;
use App\Api\V1\BaseV1Controller;
use App\Api\V1\Product\Exception\ProductNotFoundException;
use App\Api\V1\Product\RequestEntity\Product;
use App\Api\V1\Product\ResponseEntity\Product as ProductResponseEntity;
use App\Api\V1\Product\Service\ProductPersistenceManager;
use App\Api\V1\Product\Service\ProductProvider;
use App\Api\V1\Product\ValueObject\ProductFilter;
use Nette\Http\IResponse;

#[Path('/product')]
#[Id('product')]
#[Tag('Product')]
#[OpenApi('
	"openapi": "3.0.2"
	"info":
		"title": "Nette API"
		"version": "1.0.0"
		"description": "Product API based on an assigment from CloudSailor"
	"security":
		- "bearerAuth": []
	"components":
		"securitySchemes":
			"bearerAuth":
				"type": http
				"scheme": bearer
				"bearerFormat": JWT
')]
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
	#[Response(description: 'Returns single product data', entity: ProductResponseEntity::class)]
	#[OpenApi('
		"summary": "Get single product"
	')]
	public function get(ApiRequest $request, ApiResponse $response): ApiResponse
	{
		try {
			$productData = $this->productProvider->getById($request->getParameter('id'));
		} catch (ProductNotFoundException $exception) {
			return $response
				->withStatus(IResponse::S400_BadRequest)
				->writeBody($exception->getMessage())
				->withHeader('Content-Type', 'application/json');
		}

		return $response
			->writeJsonBody($productData->toArray())
			->withHeader('Content-Type', 'application/json');
	}

	#[Path('/list')]
	#[Method('GET')]
	#[RequestParameter(name: 'name', type: 'string', in: 'query', required: false, description: 'Product name')]
	#[RequestParameter(name: 'priceFrom', type: 'numeric', in: 'query', required: false, description: 'Product price from')]
	#[RequestParameter(name: 'priceTo', type: 'numeric', in: 'query', required: false, description: 'Product price to')]
	#[RequestParameter(name: 'createdAtFrom', type: 'int', in: 'query', required: false, description: 'Timestamp of date created from of a product')]
	#[RequestParameter(name: 'createdAtTo', type: 'int', in: 'query', required: false, description: 'Timestamp of date created to of a product')]
	#[RequestParameter(name: 'orderBy', type: 'productSortableColumn', in: 'query', required: false, description: 'Column by which list will be ordered by (default "id")')]
	#[RequestParameter(name: 'ordering', type: 'ordering', in: 'query', required: false, description: 'Ordering of the list ASC or DESC (default ASC)')]
	#[RequestParameter(name: 'page', type: 'intGreaterThanZero', in: 'query', required: false, description: 'Page of the list (default 1)')]
	#[RequestParameter(name: 'limit', type: 'intGreaterThanZero', in: 'query', required: false, description: 'Page limit of queried items (default 100)')]
	#[Response(description: 'Returns paginated list of product data')]
	#[OpenApi('
		"summary": "Get list of products"
	')]
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
			$request->hasQueryParam('page') ? (int) $request->getQueryParam('page') : null,
			$request->hasQueryParam('limit') ? (int) $request->getQueryParam('limit') : null,
		);

		$productDataResult = $this->productProvider->list($productFilter);

		return $response
			->writeJsonBody($productDataResult)
			->withHeader('Content-Type', 'application/json');
	}

	#[Path('/create')]
	#[Method('POST')]
	#[RequestBody(entity: Product::class)]
	#[Response(description: 'Returns created product data', entity: ProductResponseEntity::class)]
	#[OpenApi('
		"summary": "Create product"
	')]
	public function create(ApiRequest $request, ApiResponse $response): ApiResponse
	{
		/** @var Product $productRequestEntity */
		$productRequestEntity = $request->getEntity();

		$productData = $this->productPersistenceManager->create($productRequestEntity);

		return $response
			->writeJsonBody($productData->toArray())
			->withHeader('Content-Type', 'application/json');
	}

	#[Path('/update/{id}')]
	#[Method('PUT')]
	#[RequestParameter(name: 'id', type: 'int', in: 'path', required: true, description: 'Product ID')]
	#[RequestBody(entity: Product::class)]
	#[Response(description: 'Returns updated product data', entity: ProductResponseEntity::class)]
	#[OpenApi('
		"summary": "Update product"
	')]
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
			->writeJsonBody($productData->toArray())
			->withHeader('Content-Type', 'application/json');
	}

	#[Path('/delete/{id}')]
	#[Method('DELETE')]
	#[RequestParameter(name: 'id', type: 'int', in: 'path', required: true, description: 'Product ID')]
	#[RequestParameter(name: 'force', type: 'bool', in: 'query', required: false, description: 'Force delete so it actually completely removes product from database (default = false)')]
	#[Response(description: 'Returns simple message that product has been deleted')]
	#[OpenApi('
		"summary": "Delete product"
	')]
	public function delete(ApiRequest $request, ApiResponse $response): ApiResponse
	{
		$productId = $request->getParameter('id');
		$force = $request->hasQueryParam('force') ? $request->getParameter('force') : false;

		try {
			$this->productPersistenceManager->delete($productId, $force);
		} catch (ProductNotFoundException $exception) {
			return $response
				->withStatus(IResponse::S400_BadRequest)
				->writeBody($exception->getMessage())
				->withHeader('Content-Type', 'application/json');
		}

		return $response
			->writeJsonBody([
				'message' => 'Product was successfully deleted',
			])
			->withHeader('Content-Type', 'application/json');
	}

}
