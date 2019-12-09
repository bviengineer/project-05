<?php
// Routes

$app->get('/[{name}]', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
}); 

// my test route 
$app->get('/hello/[{name}]', function(Request $request, Response $response) {
    $name = $request->getAttribute('name');
    //$name = $request->getQueryParams(['name']);
    $response->getBody()->write("Hello World, $name");
    //echo "<pre>";
    return $response;
    //echo "</pre>";
});

// my test route 
$app->get('/test/{method}', function(Request $request, Response $response) {
    $method = $request->getMethod();
    return $method;
});