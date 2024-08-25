<?php declare(strict_types = 1);

namespace App\Command;

use Apitte\OpenApi\SchemaBuilder;
use Nette\Utils\Json;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
	name: self::NAME,
	description: 'Generates open API schema',
)]
final class OpenApiSchemaCommand extends Command
{

	private const NAME = 'app:api:schema-openapi';

	private SchemaBuilder $builder;

	public function __construct(SchemaBuilder $builder)
	{
		parent::__construct();
		$this->builder = $builder;
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$metadata = $this->builder->build();
		$data = Json::encode($metadata->toArray(), JSON_PRETTY_PRINT);
		file_put_contents('temp/openApi.json', $data);
		$output->writeln('temp/openApi.json generated');
		return 0;
	}

}
