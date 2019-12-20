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
// Route to the new entry/post page 
$app->get('/post/new', function($request, $response) {  
  // Render page to add a post 
  return $this->view->render($response, 'new.twig');
});
// Add a post
$app->post('/post/new', function($request, $response, $args) {
  // Getting form data 
  $args = array_merge($args, $request->getParsedBody());

  // echo "<pre>";
  // var_dump($args);
  // echo "</pre>";
  if (!empty($args['title']) && !empty($args['entry'])) { //validate date as well?
      // Add post to database 
      $post = new Posts($this->db);
      $results = $post->addPost($args['title'], $args['date'], $args['entry']);
  }

  // Redirect to home page 
  return $this->response->withStatus(200)->withHeader('Location', '/');
  //return $this->view->render($response, 'new.twig', $args)
});
// Route to edit a post page
$app->get('/edit/{id}', function($request, $response, $args) {  

  // $this->logger->info("Slim-Skeleton '/edit/{id}' route");
  echo "<pre>";
  var_dump($args);
  echo "</pre>";
  
  // $args = $request->getParsedBody();
  // if (isset($_args['id'])) {
  //   $args = array_merge($args, $request->getParsedBody());
  // }
  // echo "<pre>";
  // var_dump($args);
  // echo "</pre>";
  // Render page to add a post 
  return $this->view->render($response, 'edit.twig', $args);
});
// Update a post
$app->post('/edit/{id}', function($request, $response, $args) {
  // Getting form data 
  $args = array_merge($args, $request->getParsedBody());

  echo "<pre>";
  var_dump($args);
  echo "</pre>";
  //if (!empty($args['title']) && !empty($args['entry'])) { //validate date as well?
      // Add post to database 
    //  $post = new Posts($this->db);
      // $results = $post->updatePost($args['id'], $args['title'], $args['date'], $args['entry']);
  //}

// Redirect to home page 
//return $this->response->withStatus(200)->withHeader('Location', '/');
return $this->view->render($response, 'edit.twig', $args);
});
// Detail page -> display a single post
$app->get('/post/{id}', function($request, $response, $args) {

  // Retrieve specified post from database 
  $post = new Posts($this->db);
  $results = $post->getFullPost($args['id']);

  $comm = new Comments($this->db);
  $postComm = $comm->getComments($args['id']);

  // Assign a key to the args array & store results of query
  $args['post'] = $results;
  $args['comments'] = $postComm;

  // echo "<pre>";
  // var_dump($args['comments']);
  // echo "</pre>";
  //var_dump($args['id']);
  // $args['post'] = $args['post'][0];
  // echo "<pre>";
  // var_dump($args['post']);
  // echo "</pre>";
  // echo "<pre>";
  // var_dump($args);
  // echo "</pre>";
  
  // Render results
  return $this->view->render($response, 'post.twig', $args);
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