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
 
  // Reassign date / time as a UTC ISO string 
  $args['date'] = date('c');
  
  // Add post
  if (!empty($args['title']) && !empty($args['date']) && !empty($args['entry'])) {
	// Post
      $post = new Posts($this->db);
      $results = $post->addPost($args['title'], $args['date'], $args['entry']);

      // Add post & tag ids to junction table
      if (!empty($args['tags'])) {
        $recentPost = $post->getRecentPost();
        $postId = $recentPost['id'];

        $tagEntries = new PostsTags($this->db);
	   
	   foreach ($args['tags'] as $tagId) {
     	$insertTags = $tagEntries->addTags($postId, $tagId);
        }
     }
 }
  // Redirect to home page 
  return $this->response->withStatus(302)->withHeader('Location', '/'); 
});

// View post to be edited in edit mode (field values can be changed)
$app->get('/edit/{id}', function($request, $response, $args) {
  // retrieve post to be edited 
  $post = new Posts($this->db);
  $results = $post->getFullPost($args['id']);

  // Store the results of returned post
  $args['post'] = $results;

  // Get tags associated with the blog entry, if any
    $getTags = new PostsTags($this->db);
    $tags = $getTags->getTags($args['id']);
  
    if (!empty($tags)) {
      $args['tagIds'] = $tags;
      $args['tagId'] = [];
  
    // Adding each tag_id value to the $args['tagId] array without inner array
    for ($i = 0; $i < count($tags); $i++) {
      $tagId = $tags[$i]['tag_id'];
      array_push($args['tagId'], $tagId);
    }
  }
  // Display the post
  return $this->view->render($response, 'edit.twig', $args);
});

// Update content of a post
$app->post('/edit/{id}', function($request, $response, $args) {
  // Getting form data with updates 
  $args = array_merge($args, $request->getParsedBody());

  // Verifing completed fields
  if (!empty($args['title']) && !empty($args['entry'])) {
      // Update post in database 
      $post = new Posts($this->db);
      $results = $post->updatePost($args['id'], $args['title'], $args['date'], $args['entry']);
  }
  // Instance of PostTags junction class
  $postsTags = new PostsTags($this->db);

  // Delete existing tags selection or deselection of tags if any, 
  // Then add the checked ones if any
  if (!empty($args['tags'])) {
      if ($postsTags->getTags($args['id'])) {
          $deleteTags = $postsTags->deleteTags($args['id']);
      }
      // Add new tags
      for ($i = 0; $i < count($args['tags']); $i++) {
          // $addNewTags = $postsTags->addTags($args['id'], $args['tags'][$i]);
          $postsTags->addTags($args['id'], $args['tags'][$i]);
      }
    } elseif (empty($args['tags'])) {
        $deleteTags = $postsTags->deleteTags($args['id']);
  }
  // View updated post 
  return $this->response->withStatus(302)->withHeader('Location', '/post/'. $args['id'] );
});

// Display a single post
$app->get('/post/{id}', function($request, $response, $args) {
  // Get post from database 
  $post = new Posts($this->db);
  $results = $post->getFullPost($args['id']);
  
  // Assign key to the args array for the post & store the results
  $args['post'] = $results;
  
  // Check for & retrieve any related comment(s) 
  $comm = new Comments($this->db);
  $postComm = $comm->getComments($args['id']);
  
  // Assign key to the args array for comments & store results of query
  if (!empty($postComm)) {
    $args['comments'] = $postComm;
  }
  // Check for and retrieve tag id(s) for the specified post
  $getTagId = new PostsTags($this->db);
  $tagId = $getTagId->getTags($args['id']);
  
  // Retrieve related tag(s) name(s) for specified post
  if (!empty($tagId)) {
    $getTagName = new Tags($this->db);
    // Array for tag names
    $tags = [];
    // Retrieves tag names only and pushes them to a dedicated array 
    foreach ($tagId as $id) {
      $tagName = $getTagName->getTags($id['tag_id']);
      array_push($tags, $tagName[0]['name']);
    }
  } 
  $args['tags'] = $tags;
  // View post & related comments
  return $this->view->render($response, 'post.twig', $args);
});

// Add comment to a specific post
$app->post('/post/{id}', function($request, $response, $args) {
  // Getting comment sent for posting
  $args = array_merge($args, $request->getParsedBody());

  // Reassign date / time as a UTC ISO string
  $args['date'] = date('c');

  // Add comment to commments table 
  $comm = new Comments($this->db);
  $addComm = $comm->addComment($args['name'], $args['comment'], $args['id'], $args['date']);

  // Display post with added comment
  return $this->response->withStatus(302)->withHeader('Location', '/post/'. $args['id']);
});

// Delete a post, its comments & tags
$app->post('/delete/{id}', function($request, $response, $args) {
  // Post
  $post = new Posts($this->db);
  $deletePost = $post->deletePost($args['id']);
  // Comment(s) 
  $comm = new Comments($this->db);
  if (!empty($comm->getComments($args['id']))) {
    $deleteComm = $comm->deleteComment($args['id']);
  }
  // Tag(s) 
  $postsTags = new PostsTags($this->db);
  if (!empty($postsTags->getTags($args['id']))) {
    $deleteTags = $postsTags->deleteTags($args['id']);
  }
  // Redirect to home page 
  return $this->response->withStatus(302)->withHeader('Location', '/');
});