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
  $args['date'] = date('l, F jS, Y h:i:s a');
  // $dateArray = explode("/", $args['date']); 
  // $year = $dateArray[2];
  // $month =  date('F', $dateArray[0]);
  // $day = $dateArray[1];
  // $date = $month . " " . $day . ", " . $year;
  // $args['date'] = $date;
  //$args['date'] = date('F d, Y', $args['date']);
  // echo "<pre>";
  // var_dump($args);
  // echo "</pre>";
  //echo date('l, F jS, Y h:i:s a');
  //echo "<pre> </pre>";
  // $args['date'] = date('l, F jS, Y h:i:s a');
  //var_dump($args['date']);
  // $date = strtotime($args['date']);
  // $gmdate = gmdate($date);
  // var_dump($date);
  // var_dump($gmdate);
  // $readabledate = date($gmdate);
  // var_dump($readabledate);

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
  return $this->response->withStatus(200)->withHeader('Location', '/'); 
});

// View post to be edited in edit mode (field values can be changed)
$app->get('/edit/{id}', function($request, $response, $args) {
  // retrieve post to be edited 
  $post = new Posts($this->db);
  $results = $post->getFullPost($args['id']);

  // Store the results of returned post
  $args['post'] = $results;
  // echo "<pre>";
  // var_dump($args['post']);
  // echo "</pre>";
  // var_dump($args['id']);

  // Get tags associated with the blog entry, if any
  $getTags = new PostsTags($this->db);
  $tags = $getTags->getTags($args['id']);
  $args['tagId'] = $tags;
  // Adding each tag_id value to the $args['tagId] array without inner array
  // for ($i = 0; $i < count($tags); $i++) {
  //   $tagId = $tags[$i]['tag_id'];
  //   array_push($args['tagId'], $tagId);
  // }

  //foreach ($args['tagIds'] as $tag) {
    // echo "<pre>";
    // var_dump($args['tagId']);
    // echo "</pre>";
    // array_push($args['post'], $tag);
  //}
  
  // echo "<pre>";
  // var_dump($args['post']);
  // echo "</pre>";

  // echo "<pre>";
  // var_dump($args['tagIds']);
  // echo "</pre>";

  // Display the post
  return $this->view->render($response, 'edit.twig', $args);
});

// Update content of a post
$app->post('/edit/{id}', function($request, $response, $args) {
  // Getting form data with updates 
  $args = array_merge($args, $request->getParsedBody());
  // echo "<pre>";
  // var_dump($args);
  // echo "</pre>";

  // echo "<pre>";
  // var_dump($args['tags'][0]);
  // echo "</pre>";


  // Date conversion
  // $dateArray = explode("/", $args['date']); 
  // $year = $dateArray[2];
  // $month =  date('F', $dateArray[0]);
  // $day = $dateArray[1];
  // $date = $month . " " . $day . ", " . $year;
  // $args['date'] = $date;

  // Verifing completed fields
  if (!empty($args['title']) && !empty($args['entry'])) { //validate date as well?
      // Update post in database 
      $post = new Posts($this->db);
      $results = $post->updatePost($args['id'], $args['title'], $args['date'], $args['entry']);
  }
  // Go to PostTags junction table
  $postsTags = new PostsTags($this->db);

  // Delete existing tags selection or deselection if any, then add the checked ones if any
  if (!empty($args['tags'])) {
      if ($postsTags->getTags($args['id'])) {
        //var_dump($tagsPosts->getTags($args['id']));
        // $currentTags = $tagsPosts->getTags($args['id']);
        // echo "Before deleting <pre>";
        // var_dump($currentTags);
        // echo "</pre>";
        $deleteTags = $postsTags->deleteTags($args['id']);
        // echo "After deleting <pre>";
        // var_dump($deleteTags);
        // echo "</pre>";
      }
      // Add new tags
      for ($i = 0; $i < count($args['tags']); $i++) {
        // $addNewTags = $postsTags->addTags($args['id'], $args['tags'][$i]);
        $postsTags->addTags($args['id'], $args['tags'][$i]);
        // var_dump($args['tags'][$i]);
      }

    // echo "<pre>";
    // var_dump($args['tags'][$i]);
    // echo "</pre>";

    // echo "<pre>";
    // var_dump($addNewTAgs);
    // echo "</pre>";
  } elseif (empty($args['tags'])) {
      $deleteTags = $postsTags->deleteTags($args['id']);
  }
  // View updated post 
  return $this->response->withStatus(200)->withHeader('Location', '/post/'. $args['id'] );
});

// Display a single post
$app->get('/post/{id}', function($request, $response, $args) {
  // Retrieve specified post from database 
  $post = new Posts($this->db);
  $results = $post->getFullPost($args['id']);
  
  // Assign a keys to the args array & store respective results of queries
  $args['post'] = $results;
  // echo "The post";
  // echo "<pre>";
  // var_dump($args['post']);
  // echo "</pre>";

  // Retrieve related comment(s) 
  $comm = new Comments($this->db);
  $postComm = $comm->getComments($args['id']);
  
  // Assign a keys to the args array & store respective results of queries
  $args['comments'] = $postComm;
  // echo "The comments";
  // echo "<pre>";
  // var_dump($args['comments']);
  // echo "</pre>";

  // Add conditional here to check if a post has tags before making call to database 
  // Retrieve tag id(s) for a specified post
  $getTagId = new PostsTags($this->db);
  $tagId = $getTagId->getTags($args['id']);
  $args['tagId'] = $tagId;
  // echo "The tag IDs for the specified post";
  // echo "<pre>";
  // var_dump($args['tagId']);
  // echo "</pre>";

  // Retrieve related tag(s) name(s) for specified post 
  if (!empty($args['tagId'])) {
    $getTagName = new Tags($this->db);
    $tags = []; // array for tag names
    foreach ($args['tagId'] as $id) {
  
    //echo "Looping through the tag IDs before getting their corresponing names<pre>";
    // var_dump($id['tag_id']);
    // var_dump($id);  
    // echo "</pre>";
  
   $tagName = $getTagName->getTags($id['tag_id']);
   array_push($tags, $tagName[0]['name']);
    // echo "The tag names for each tag id<pre>";
    // var_dump($tagName[0]['name']);
    // echo "</pre>";
  }
}
  // Assign a keys to the args array & store respective results of queries
  // $args['post'] = $results;
  // $args['comments'] = $postComm;
  
 $args['tags'] = $tags;
  // echo "The tag name for each tag is in the args array<pre>";
  // var_dump($tagName[0]['name']);
  // var_dump($args['tags']);
  // echo "</pre>";

  // echo "Args after looping through tags and pushing them to the array <pre>";
  // var_dump($args);
  // echo "</pre>";

  // View post & related comments
  return $this->view->render($response, 'post.twig', $args);
});

// Add comment to a specific post
$app->post('/post/{id}', function($request, $response, $args) {
  // Getting comment sent for posting
  $args = array_merge($args, $request->getParsedBody());
  // echo "<pre>";
  // var_dump($args);
  // echo "</pre>";

  // UTC time 
  $args['date'] = date('c');

  //$args['date'] = date('l, F jS, Y h:i:s a'); // day of wk, Month(th), Year 
  //$args['date'] = date('m-d-Y, H:i:s');
  //$date = date('Y-m-d, H:i:s');
  //var_dump($date);
  //$date2 = date(date('c'));
  //var_dump($date2);
  //var_dump(date($date));
  //var_dump(date($date2));
  
  // echo "<pre>";
  // var_dump($args);
  // echo "</pre>";
  
  // $dateArray = explode("/", $args['date']); 
  // $year = $dateArray[2];
  // $month =  date('F', $dateArray[0]);
  // $day = $dateArray[1];
  // $date = $month . " " . $day . ", " . $year;
  // $args['date'] = $date;

  // Add comment to commments table 
  // $comm = new Comments($this->db);
  // $addComm = $comm->addComment($args['name'], $args['comment'], $args['id'], $args['date']);

  // Display post with added comment
  return $this->response->withStatus(200)->withHeader('Location', '/post/'. $args['id']);
});

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
  return $this->response->withStatus(200)->withHeader('Location', '/');
});