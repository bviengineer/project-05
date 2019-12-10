<?php
// Routes


// $app->get('/[{name}]', function ($request, $response, $args) {
//     // Sample log message
//     $this->logger->info("Slim-Skeleton '/' route");

//     // Render index view
//     return $this->renderer->render($response, 'index.phtml', $args);
// }); 

// my test route
$app->get('/', function($request, $response, $args) {
  $response->getBody()->write("Hello World");
  return $response;
});

// my test route 
$app->get('/hello/[{name}]', function($request, $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello World, $name");
    return $response;
});

// my test route 
$app->get('/test/{method}', function($request, $response) {
    $method = $request->getMethod();
    return $method;
});

// my test db query
$app->get('/posts', function() {
	include "Posts.php";
  $posts = new Posts;
	$results = $posts->getPosts();
	var_dump($results);
});

// my test db query
$app->get('/comments', function() {
	include "Comments.php";
	$comm = new Comments;
	$results = $comm->getComments();
	var_dump($results);
});

// my test db query
$app->get('/pm', function() {
	include "Posts.php"; 
  $fullPost = new Posts;  
  $results = $fullPost->getFullPosts();
	var_dump($results);
});