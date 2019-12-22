<?php

namespace App;
use PDO;

class Tags {
	
	// Database variable 
	protected $db; 

	// Databse PDO Construtor Method
	public function __construct(\PDO $db) {
		$this->db = $db; 
	}
	// Get all tags
	public function getTags($tag_id) {
		$results = $this->db->prepare("SELECT name FROM tags WHERE id = :tag_id"); 
		$results->bindParam(':tag_id', $tag_id, PDO::PARAM_INT);
		$results->execute();
		return $results->fetchAll(PDO::FETCH_ASSOC);
	}
}