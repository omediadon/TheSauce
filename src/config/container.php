<?php

use App\Validation\JwtAuth;
use Cake\Database\Connection;
use Cake\Database\Driver\Mysql;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Log\LoggerInterface;
use Selective\Validation\Encoder\JsonEncoder;
use Selective\Validation\Middleware\ValidationExceptionMiddleware;
use Selective\Validation\Transformer\ErrorDetailsResultTransformer;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteParser;
use Slim\Views\Twig;
use System\Config\SiteSettings;
use System\Utils\Translator;

$container->set(SiteSettings::class, function(){
	return new SiteSettings();
});

$container->set(App::class, static function(ContainerInterface $container){
	AppFactory::setContainer($container);

	return AppFactory::create();
});

$container->set(SlimSession\Helper::class, function(){
	return new SlimSession\Helper();
});

// Router
$container->set(RouteParser::class, function() use ($app){
	return $app->getRouteCollector()
	           ->getRouteParser();
});

// View
$container->set(Twig::class, function(ContainerInterface $container) use ($app){
	$settings = $container->get(SiteSettings::class);

	return Twig::create($settings->storage->views, [
		'cache' => false
		/* $settings->storage->cache*/
	]);
});

// Database connection
$container->set(Connection::class, function(ContainerInterface $container){
	return new Connection((array) $container->get(SiteSettings::class)->database);
});
$container->set(PDO::class, function(ContainerInterface $container){
	/**
	 * @var Connection
	 */
	$db = $container->get(Connection::class);
	/**
	 * @var Mysql
	 */
	$driver = $db->getDriver();
	$driver->connect();

	return $driver->getConnection();
});

$container->set(ResponseFactoryInterface::class, static function(ContainerInterface $container){
	$app = $container->get(App::class);

	return $app->getResponseFactory();
});

// And add this entry

$container->set(JwtAuth::class, function(ContainerInterface $container){
	$config = $container->get(SiteSettings::class)->auth;

	$issuer     = $config->issuer;
	$lifetime   = $config->lifetime;
	$privateKey = $config->privateKey;
	$publicKey  = $config->publicKey;

	return new JwtAuth($issuer, $lifetime, $privateKey, $publicKey);
});

$container->set(ValidationExceptionMiddleware::class, function(ContainerInterface $container){
	$factory = $container->get(ResponseFactoryInterface::class);

	return new ValidationExceptionMiddleware($factory, new ErrorDetailsResultTransformer(), new JsonEncoder());
});

$container->set(Translator::class, static function(ContainerInterface $container){
	return new Translator($container->get(SiteSettings::class));
});

$container->set(LoggerInterface::class, function(ContainerInterface $c){
	$settings = $c->get(SiteSettings::class);

	$logger = new Logger($settings->logger->name);

	$processor = new UidProcessor();
	$logger->pushProcessor($processor);

	$handler = new StreamHandler($settings->storage->logs, $settings->logger->level);
	$logger->pushHandler($handler);

	return $logger;
});
