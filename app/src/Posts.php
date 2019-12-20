<?php

namespace App;
use PDO;

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
	public function getFullPost($id) {
		$sql = "SELECT posts.title, posts.date, posts.body 
          	FROM posts 
						WHERE id = :id";

		$results = $this->db->prepare($sql);
		$results->bindParam(':id', $id, PDO::PARAM_INT);
		$results->execute();
		return $results->fetch(PDO::FETCH_ASSOC);
	}
	// Add a post to database
	public function addPost($title, $date, $body) {
		$sql = "INSERT INTO posts (title, date, body) VALUES(:title, :date, :body)";

		$results = $this->db->prepare($sql); 
		$results->bindParam(':title', $title, PDO::PARAM_STR);
		$results->bindParam(':date', $date, PDO::PARAM_STR);
		$results->bindParam(':body', $body, PDO::PARAM_STR);
		$results->execute();
		return true;
	}
}