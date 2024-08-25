<?php declare(strict_types = 1);

namespace App\Api\V1\Schema;

use Apitte\Core\Annotation\Controller\Id;
use Apitte\Core\Annotation\Controller\Method;
use Apitte\Core\Annotation\Controller\Path;
use Apitte\Core\Annotation\Controller\Tag;
use Apitte\Core\Http\ApiRequest;
use Apitte\Core\Http\ApiResponse;
use Apitte\OpenApi\ISchemaBuilder;
use App\Api\V1\BaseV1Controller;

#[Path('/schema')]
#[Id('schema')]
#[Tag('Schema')]
final class SchemaController extends BaseV1Controller
{

	public function __construct(
		private ISchemaBuilder $schemaBuilder,
	)
	{
	}

	#[Path('/')]
	#[Method('GET')]
	public function index(ApiRequest $request, ApiResponse $response): ApiResponse
	{
		$openApi = $this->schemaBuilder->build();

		return $response->writeJsonBody($openApi->toArray());
	}

}
