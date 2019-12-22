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
	public function getTags() {
		$results = $this->db->prepare("SELECT * FROM tags ORDER BY id"); 
		$results->execute();
		return $results->fetchAll(PDO::FETCH_ASSOC);
	}
}