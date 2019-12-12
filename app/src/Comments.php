<?php

class Comments {

	// Database variable 
protected $db; 

// Databse PDO Construtor Method
public function __construct(\PDO $db) {
	$this->db = $db; 
}

	// Retrieve all comments
	public function getComments() {
		$sql = "SELECT * FROM comments ORDER BY id";

		$results = $this->db->prepare($sql); 
		$results->execute();
		return $results->fetchAll(PDO::FETCH_ASSOC);
	}
}