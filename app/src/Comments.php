<?php

namespace App;
use PDO;

class Comments {

	// Database variable 
protected $db; 

	// Databse PDO Construtor Method
	public function __construct(\PDO $db) {
		$this->db = $db; 
	}
	// Retrieve all comments
	public function getComments($postId) {
		$sql = "SELECT * 
				FROM comments 
				WHERE post_id = :postId";
 
			$results = $this->db->prepare($sql); 
			$results->bindParam(':postId', $postId, PDO::PARAM_INT);
			$results->execute();
			return $results->fetchAll(PDO::FETCH_ASSOC);
	}
	// Add a comment
	public function addComment($name, $comm, $postId, $date) {
		$sql = "INSERT INTO comments (name, body, post_id, date) 
				VALUES(:name, :body, :postId, :date)"; 
				
		$results = $this->db->prepare($sql);
		$results->bindParam(':name', $name, PDO::PARAM_STR);
		$results->bindParam(':body', $comm, PDO::PARAM_STR);
		$results->bindParam(':postId', $postId, PDO::PARAM_INT);
		$results->bindParam(':date', $date, PDO::PARAM_STR);
		$results->execute();
		return true;
	}
	// Delete a comment 
	public function deleteComment($postId) {
		$results = $this->db->prepare("DELETE FROM comments WHERE post_id = :postId"); 
		$results->bindParam(':postId', $postId, PDO::PARAM_INT);
		$results->execute();
		return true;
	}
}