<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Controllers\IndexController;
use Slim\Factory\AppFactory;
use App\Helpers\PdoHelper;
use App\Services\RouterService;

// Instantiate App
global $app;
$app = AppFactory::create();

$container = $app->getContainer();
$container['db'] = PdoHelper::getPdo();

$app->any('/', IndexController::class);
$app->any('/{params:[^0-9]+}[/{id:[0-9]+}]', RouterService::class);

$app->run();
