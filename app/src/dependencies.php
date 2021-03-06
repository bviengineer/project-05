<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
// $container['renderer'] = function ($c) {
//     $settings = $c->get('settings')['renderer'];
//     return new Slim\Views\PhpRenderer($settings['template_path']);
// };

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], Monolog\Logger::DEBUG));
    return $logger;
};

// Database connection 
$container['db'] = function () {
	try { 
		$db = new PDO("sqlite:".__DIR__."/../../blog.db");
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  
	} catch (Exception $e) {
		echo $e->getMessage();
		exit;
	}
	return $db;
};

// Register view component on container
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig('../templates', []);

    // Instantiate and add Slim specific extension -> http://www.slimframework.com/docs/v3/features/templates.html
    $router = $container->get('router');
    $uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
    $view->addExtension(new \Slim\Views\TwigExtension($router, $uri));

    return $view;
};