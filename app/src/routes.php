<?php
use App\Posts;
use App\Comments;

// Routes
// $app->get('/[{name}]', function ($request, $response, $args) {
//     // Sample log message
//     $this->logger->info("Slim-Skeleton '/' route");

//     // Render index view
//     return $this->renderer->render($response, 'index.phtml', $args);
// }); 

// Home page -> display all posts
$app->get('/', function($request, $response, $args) {
  $posts = new Posts($this->db);
  $results = $posts->getAllPosts();

  //var_dump($results);
  $args = $results;
  //$data = implode(" ", $args);
  
  // Render index view
  //return $this->renderer->render($response, 'home.php', $args);
  return $this->view->render($response, 'home.twig', array('name' => $args[0]["title"]));
  // echo "<pre>";
  // var_dump($results);
  // echo "</pre>";
});

// My test route using twig-view 
$app->get('/hello/{name}', function ($request, $response, $args) {
  return $this->view->render($response, 'index.twig', [
      'name' => $args['name']
  ]);
});

// my test route
// $app->get('/', function($request, $response, $args) {
//   $response->getBody()->write("Hello World");
//   return $response;
// });

// my test route 
// $app->get('/hello/[{name}]', function($request, $response, $args) {
//     $name = $args['name'];
//     $response->getBody()->write("Hello World, $name");
//     return $response;
// });

// my test route 
$app->get('/test/{method}', function($request, $response) {
    $method = $request->getMethod();
    return $method;
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