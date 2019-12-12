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

// Test route for only posts
$app->get('/posts', function() {
  $posts = new Posts($this->db);
  $results = $posts->getAllPosts();
  
  //$results = $this->db->query('SELECT * FROM posts')->fetchAll(PDO::FETCH_OBJ); //this works
	var_dump($results);
});

// my test db query
$app->get('/comments', function() {
	$comm = new Comments($this->db);
	$results = $comm->getComments();
	var_dump($results);
});

// Test route for all posts
$app->get('/pm', function() {
  $fullPost = new Posts($this->db);  
  $results = $fullPost->getFullPost();
	echo "<pre>";	
	var_dump($results);
	echo "</pre>";
});