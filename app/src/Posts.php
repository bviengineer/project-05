<?php

namespace App\src;

class Posts {
	
	// Database variable 
	protected $db; 

	// Databse PDO Construtor Method
	public function __construct(\PDO $db) {
		$this->db = $db; 
	}
	// Retrieve all posts for display 
	public function getAllPosts() {
		$results = $this->db->prepare("SELECT * FROM posts ORDER BY id"); 
		$results->execute();
		return $results->fetchAll(PDO::FETCH_ASSOC);
	}
	// Retrieve single post and associated comment(s)
	public function getFullPost() {
		$sql = "SELECT posts.title, posts.date, posts.body, comments.name, comments.body 
          	FROM posts 
						LEFT OUTER JOIN comments
						WHERE comments.post_id = posts.id";

		$results = $this->db->prepare($sql);
		$results->execute();
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