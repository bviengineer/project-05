<?php
// Useage of model classes 
use App\Posts;
use App\Comments;
use App\Tags;
use App\PostsTags;

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

  // Date conversion
  $dateArray = explode("/", $args['date']); 
  $year = $dateArray[2];
  $month =  date('F', $dateArray[0]);
  $day = $dateArray[1];
  $date = $month . " " . $day . ", " . $year;
  $args['date'] = $date;
  //$args['date'] = date('F d, Y', $args['date']);

  // Add post
  if (!empty($args['title']) && !empty($args['date']) && !empty($args['entry'])) {
      // Add post to database 
      $post = new Posts($this->db);
      $results = $post->addPost($args['title'], $args['date'], $args['entry']);

      //Add post & tag ids to junction table
      if (!empty($args['tags'])) {
          $getRecentPost = new Posts($this->db);
          $recentPost = $getRecentPost->getRecentPost();
          $postId = $recentPost['id'];

        foreach ($args['tags'] as $tagId) {
          $tagEntries = new PostsTags($this->db);
          $insertTags = $tagEntries->addTags($postId, $tagId);
        } // end foreach 
      } // end if
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
  
  $dateArray = explode("/", $args['date']); 
  $year = $dateArray[2];
  $month =  date('F', $dateArray[0]);
  $day = $dateArray[1];
  $date = $month . " " . $day . ", " . $year;
  $args['date'] = $date;

  // Add comment to commments table 
  $comm = new Comments($this->db);
  $addComm = $comm->addComment($args['name'], $args['comment'], $args['id'], $args['date']);

  // Display post with added comment
  return $this->response->withStatus(200)->withHeader('Location', '/post/'. $args['id']);
});

// Delete a post its comments
$app->post('/delete/{id}', function($request, $response, $args) {
  // Delete specified post
  $post = new Posts($this->db);
  $deletePost = $post->deletePost($args['id']);

  // Delete comment for specified post 
  $comm = new Comments($this->db);
  $deleteComm = $comm->deleteComment($args['id']);

  // Delete tag(s) for specified post 
  $postsTags = new PostsTags($this->db);
  $deleteTags = $postsTags->deleteTags($args['id']);

  // Redirect to home page 
  return $this->response->withStatus(200)->withHeader('Location', '/');
});

// Test of tags retieval 
$app->get('/tags', function($request, $response, $args) {
  $tags = new Tags($this->db);
  $results = $tags->getTags();
  $args = $results;

  echo "<pre>";
  var_dump($args);
  echo "</pre>";
});
//Get data from junction table
$app->get('/ptags', function($request, $response, $args) {
  $tags = new PostsTags($this->db);
  $results = $tags->getTags();
  $args = $results;

  echo "<pre>";
  var_dump($args);
  echo "</pre>";
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