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
    // Add tag entries 
    public function addTags($post_id, $tag_id) {
        $sql = "INSERT INTO posts_tags (post_id, tag_id) VALUES(:post_id, :tag_id)";

		$results = $this->db->prepare($sql); 
		$results->bindParam(':post_id', $post_id, PDO::PARAM_INT);
		$results->bindParam(':tag_id', $tag_id, PDO::PARAM_INT);
		$results->execute();
		return true;
    }
}