<?php

declare(strict_types=1);

use Contributte\Middlewares\Application\IApplication as ApiApplication;
use App\Bootstrap;
use Nette\Application\Application as UIApplication;

require __DIR__ . '/../vendor/autoload.php';

$configurator = Bootstrap::boot();
$container = $configurator->createContainer();


$isApi = str_starts_with($_SERVER['REQUEST_URI'], '/api');
$container = Bootstrap::boot()->createContainer();

if ($isApi) {
	// Apitte application
	$applicationApi = $container->getByType(ApiApplication::class);
	$applicationApi->run();
} else {
	// Nette application
	$applicationUi = $container->getByType(UIApplication::class);
	$applicationUi->run();
}