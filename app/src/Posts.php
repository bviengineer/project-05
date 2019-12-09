<?php

class Posts {
// my test db query

	// Retrieve all posts
	public function getPosts() {
		include __DIR__ . "/../src/dbconnection.php";
		$sql = "SELECT * FROM posts ORDER BY id";

		try {
			$results = $db->query($sql); 
		} catch (Exception $e) {
			echo $e->getMessage();
			return array();
		}
		return $results->fetchAll(PDO::FETCH_ASSOC);
	}

}