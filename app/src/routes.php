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

  // Render results to home page
  return $this->view->render($response, 'home.twig', $args);
});

// Display the blank new entry/post form
$app->get('/post/new', function($request, $response) {  
  return $this->view->render($response, 'new.twig');
});

// Add a new post
$app->post('/post/new', function($request, $response, $args) {
  // Getting form data with post details  
  $args = array_merge($args, $request->getParsedBody());

  if (!empty($args['title']) && !empty($args['entry'])) { //validate date as well?
    // Add post to database 
    $post = new Posts($this->db);
    $results = $post->addPost($args['title'], $args['date'], $args['entry']);
  }
  // Redirect to home page 
  return $this->response->withStatus(200)->withHeader('Location', '/');
});

// View post to be edited in edit mode (field values can be changed)
$app->get('/edit/{id}', function($request, $response, $args) {
  // retrieve post to be edited 
  $post = new Posts($this->db);
  $results = $post->getFullPost($args['id']);

  // Store the results of returned post
  $args['post'] = $results;
  
  // Display the post
  return $this->view->render($response, 'edit.twig', $args);
});

// Update a post
$app->post('/edit/{id}', function($request, $response, $args) {
  // Getting form data with updates 
  $args = array_merge($args, $request->getParsedBody());

  // Verifing completed fields
  if (!empty($args['title']) && !empty($args['entry'])) { //validate date as well?
      // Update post in database 
      $post = new Posts($this->db);
      $results = $post->updatePost($args['id'], $args['title'], $args['date'], $args['entry']);
  }
  // View updated post 
  return $this->response->withStatus(200)->withHeader('Location', '/post/'. $args['id'] );
});

// Display a single post
$app->get('/post/{id}', function($request, $response, $args) {
  // Retrieve specified post from database 
  $post = new Posts($this->db);
  $results = $post->getFullPost($args['id']);

  // Retrieve related comment(s) 
  $comm = new Comments($this->db);
  $postComm = $comm->getComments($args['id']);

  // Assign a keys to the args array & store results of queries
  $args['post'] = $results;
  $args['comments'] = $postComm;

  // View post & related comments
  return $this->view->render($response, 'post.twig', $args);
});

// Add comment to a specific post
$app->post('/post/{id}', function($request, $response, $args) {
  // Getting comment sent for posting
  $args = array_merge($args, $request->getParsedBody());

  // Add comment to commments table 
  $comm = new Comments($this->db);
  $addComm = $comm->addComment($args['name'], $args['comment'], $args['id']);

  // Display post with added comment
  return $this->response->withStatus(200)->withHeader('Location', '/post/'. $args['id'] );
});

// Delete a post its comments
$app->post('/delete/{id}', function($request, $response, $args) {
  
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