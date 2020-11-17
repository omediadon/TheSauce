<?php
declare(strict_types=1);

// Set the absolute path to the root directory.
use DI\ContainerBuilder;
use Psr\Log\LoggerInterface;
use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;
use Slim\Http\ServerRequest;
use Slim\ResponseEmitter;
use System\Config\SiteSettings;
use System\Handlers\HttpErrorHandler;
use System\Handlers\ShutdownHandler;

error_reporting(0);
$rootPath = realpath(dirname(__DIR__));
$srcPath  = $rootPath . "/src";
$appPath  = $srcPath . "/app";

// Autoloader
require $rootPath . '/vendor/autoload.php';

// Instantiate PHP-DI ContainerBuilder
$containerBuilder = new ContainerBuilder();

if(false){ // Should be set to true in production
	$containerBuilder->enableCompilation(__DIR__ . '/../var/cache');
}

// Build PHP-DI Container instance
$container = $containerBuilder->build();

// Instantiate the app
AppFactory::setContainer($container);
$app              = AppFactory::create();
$callableResolver = $app->getCallableResolver();

// Le container qui compose nos librairies
require $srcPath . '/config/container.php';

// Appel des middlewares
require $srcPath . '/config/middlewares.php';

// Le fichier ou l'on déclare les routes
require $srcPath . '/config/routes.php';

$container = $app->getContainer();
/**
 * @var SiteSettings
 */
$settings = $container->get(SiteSettings::class);

$displayErrors   = $settings->logger->displayErrorDetails;
$logErrors       = $settings->logger->logErrors;
$logErrorDetails = $settings->logger->logErrorDetails;
$logger          = $app->getContainer()
                       ->get(LoggerInterface::class);

// Create Request object from globals
$serverRequestCreator = ServerRequestCreatorFactory::create();
$request              = $serverRequestCreator->createServerRequestFromGlobals();

$request = new ServerRequest($request);

// Create Error Handler
$responseFactory = $app->getResponseFactory();
$errorHandler    = new HttpErrorHandler($callableResolver, $responseFactory);
// Create Shutdown Handler
$shutdownHandler = new ShutdownHandler($request, $errorHandler, $displayErrors, $logErrors, $logErrorDetails);
register_shutdown_function($shutdownHandler);

// Add Routing Middleware
$app->addRoutingMiddleware();

// Add Error Middleware
$errorMiddleware = $app->addErrorMiddleware($displayErrors, $logErrors, $logErrorDetails, $logger);
$errorMiddleware->setDefaultErrorHandler($errorHandler);

// Run App & Emit Response
$response        = $app->handle($request);
$responseEmitter = new ResponseEmitter();
$responseEmitter->emit($response);

/*
// Execution de Slim
$app->run();
*/
