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

  // Date conversion & time capture 
  //$args['date'] = date('l, F jS, Y h:i:s a');
  
  // Reassign date / time as a UTC ISO string 
  $args['date'] = date('c');
  
  // Add post
  if (!empty($args['title']) && !empty($args['date']) && !empty($args['entry'])) {
      // Add post to database 
      $post = new Posts($this->db);
      $results = $post->addPost($args['title'], $args['date'], $args['entry']);

      //Add post & tag ids to junction table
      if (!empty($args['tags'])) {
        //$getRecentPost = new Posts($this->db);
        $recentPost = $post->getRecentPost();
        $postId = $recentPost['id'];

        $tagEntries = new PostsTags($this->db);
        foreach ($args['tags'] as $tagId) {
          $insertTags = $tagEntries->addTags($postId, $tagId);
        } // end foreach 
     } // end if
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
  $args['tagIds'] = $tags;
  $args['tagId'] = [];
  
  // Adding each tag_id value to the $args['tagId] array without inner array
  for ($i = 0; $i < count($tags); $i++) {
    $tagId = $tags[$i]['tag_id'];
    array_push($args['tagId'], $tagId);
  }
  // Display the post
  return $this->view->render($response, 'edit.twig', $args);
});

// Update content of a post
$app->post('/edit/{id}', function($request, $response, $args) {
  // Getting form data with updates 
  $args = array_merge($args, $request->getParsedBody());

  // Verifing completed fields
  if (!empty($args['title']) && !empty($args['entry'])) { //validate date as well?
      // Update post in database 
      $post = new Posts($this->db);
      $results = $post->updatePost($args['id'], $args['title'], $args['date'], $args['entry']);
  }
  // Instance of to PostTags junction class
  $postsTags = new PostsTags($this->db);

  // Delete existing tags selection or deselection if any, then add the checked ones if any
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
  // Retrieve specified post from database 
  $post = new Posts($this->db);
  $results = $post->getFullPost($args['id']);
  
  // Assign a keys to the args array & store respective results of queries
  $args['post'] = $results;

  // Retrieve related comment(s) 
  $comm = new Comments($this->db);
  $postComm = $comm->getComments($args['id']);
  
  // Assign a keys to the args array & store respective results of queries
  $args['comments'] = $postComm;

  // Add conditional here to check if a post has tags before making call to database 
  // Retrieve tag id(s) for a specified post
  $getTagId = new PostsTags($this->db);
  $tagId = $getTagId->getTags($args['id']);
  $args['tagId'] = $tagId;

  // Retrieve related tag(s) name(s) for specified post 
  if (!empty($args['tagId'])) {
      $getTagName = new Tags($this->db);
      $tags = []; // array for tag names
      foreach ($args['tagId'] as $id) {
    
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

  $url = $this->router->pathFor('comments', ['id' => $args['id'], 'title' => $args['title'] ]);
  // Display post with added comment
  return $this->response->withStatus(302)->withHeader('Location', $url);
})->setName("comments");

// Delete a post, its comments & tags
$app->post('/delete/{id}', function($request, $response, $args) {
  // Delete specified post
  $post = new Posts($this->db);
  $deletePost = $post->deletePost($args['id']);

  // Delete comment(s) for specified post 
  $comm = new Comments($this->db);
  $deleteComm = $comm->deleteComment($args['id']);

  // Delete tag(s) for specified post 
  $postsTags = new PostsTags($this->db);
  $deleteTags = $postsTags->deleteTags($args['id']);

  // Redirect to home page 
  return $this->response->withStatus(302)->withHeader('Location', '/');
});