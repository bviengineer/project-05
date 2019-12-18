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
}