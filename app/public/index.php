<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $file = __DIR__ . $_SERVER['REQUEST_URI'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/../vendor/autoload.php';

session_start();

// Instantiate the app
$dbSettings = $container->get('settings')['db'];
$settings = require __DIR__ . '/../src/settings.php';
$app = new \Slim\App($settings);

// my test db query
// $app->get('/posts', function(Request $request, Response $response) {
//     include __DIR__ . "/../src/dbconnection.php";
//     $sql = "SELECT * FROM posts ORDER BY id";

//     try {
// 		$results = $db->query($sql); 
// 	} catch (Exception $e) {
// 		echo $e->getMessage();
// 		return array();
// 	}
	//var_dump($results->fetchAll(PDO::FETCH_ASSOC));

    //return $response->write($results);
    //return $response;
    //return $response->getBody();
//});

// Set up dependencies
require __DIR__ . '/../src/dependencies.php';

// Register middleware
require __DIR__ . '/../src/middleware.php';

// Register routes
require __DIR__ . '/../src/routes.php';

// Run app
$app->run();