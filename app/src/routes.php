<?php
// Useage of model classes 
use App\Posts;
use App\Comments;

/**************************************
  Routes
 **************************************/

// Home page -> display all posts
$app->get('/', function($request, $response, $args) {
  
  // Retrieve all posts from database 
  $posts = new Posts($this->db);
  $results = $posts->getAllPosts();

  // Assign a key to the args array & store results of query
  $args['posts'] = $results;
  
  // Render results
  return $this->view->render($response, 'home.twig', $args);
});
// Detail page -> display a single post
$app->get('/detail/{id}', function($request, $response, $args) {
  
  // Retrieve specified post from database 
  $posts = new Posts($this->db);
  $results = $posts->getFullPost($args['id']);

  // Assign a key to the args array & store results of query
  $args['entry'] = $results;
  // echo "<pre>";
  // var_dump($args);
  // echo "</pre>";
  
  // Render results
  return $this->view->render($response, 'detail.twig', $args);
});

// My test route using twig-view 
$app->get('/hello/{name}', function ($request, $response, $args) {
  return $this->view->render($response, 'index.twig', [
      'name' => $args
  ]);
});

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