<?php

namespace App;
use PDO;

class PostsTags {
	
	// Database variable 
	protected $db; 

	// Databse PDO Construtor Method
	public function __construct(\PDO $db) {
		$this->db = $db; 
	}
	// Get all tags
	public function getTags() {
		$results = $this->db->prepare("SELECT * FROM posts_tags ORDER BY post_id"); 
		$results->execute();
		return $results->fetchAll(PDO::FETCH_ASSOC);
	}
}