<?php

class Posts {
// my test db query

	// Retrieve all posts
	public function getPosts() {
		include __DIR__ . "/../src/dbconnection.php";
		$sql = "SELECT * FROM posts ORDER BY id";

		try {
			$results = $db->query($sql); 
		} catch (Exception $e) {
			echo $e->getMessage();
			return array();
		}
		return $results->fetchAll(PDO::FETCH_ASSOC);
	}
	// Retrieve all posts with associated comments
	public function getFullPosts() {
		include __DIR__ . "/../src/dbconnection.php";
		$sql = 'SELECT posts.title, posts.date, posts.body, comments.name, comments.body 
          	FROM posts
        		LEFT OUTER JOIN comments on comments.id = posts.id';
		try {
			$results = $db->query($sql); 
		} catch (Exception $e) {
			echo $e->getMessage();
			return array();
		}
		return $results->fetchAll(PDO::FETCH_ASSOC);
	}
	// Add a post to database
	// public function addPost() {
	// 	include __DIR__ . "/../src/dbconnection.php";
	// 	$sql = "INSERT INTO posts (title, date, body) VALUES(?, ?, ?, ?);

	// 	try {
	// 		$results = $db->query($sql); 
	// 	} catch (Exception $e) {
	// 		echo $e->getMessage();
	// 		return array();
	// 	}
	// 	return $results->fetchAll(PDO::FETCH_ASSOC);
	// }

}