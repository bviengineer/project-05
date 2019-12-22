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
    public function addTags($post_id, $tags_id) {
        $sql = "INSERT INTO posts_tags (title, date, body) VALUES(:title, :date, :body)";

		$results = $this->db->prepare($sql); 
		$results->bindParam(':title', $title, PDO::PARAM_STR);
		$results->bindParam(':date', $date, PDO::PARAM_STR);
		$results->bindParam(':body', $body, PDO::PARAM_STR);
		$results->execute();
		return true;
    }
}