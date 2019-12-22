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
	public function getTags($postId) {
        $results = $this->db->prepare("SELECT tag_id FROM posts_tags WHERE post_id = :postId"); 
        $results->bindParam(":postId", $postId, PDO::PARAM_INT);
		$results->execute();
		return $results->fetchAll(PDO::FETCH_ASSOC);
    }
    // Add tag entries 
    public function addTags($post_id = null, $tag_id) {
        $sql = "INSERT INTO posts_tags (post_id, tag_id) VALUES(:post_id, :tag_id)";

		$results = $this->db->prepare($sql); 
		$results->bindParam(':post_id', $post_id, PDO::PARAM_INT);
		$results->bindParam(':tag_id', $tag_id, PDO::PARAM_INT);
		$results->execute();
		return true;
    }
    // Delete tag(s) for a given post 
	public function deleteTags($postId) {
		$results = $this->db->prepare("DELETE FROM posts_tags WHERE post_id = :postId"); 
		$results->bindParam(':postId', $postId, PDO::PARAM_INT);
		$results->execute();
		return true;
	}
}