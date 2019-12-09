<?php

class Comments {
// my test db query

	// Retrieve all comments
	public function getComments() {
		include __DIR__ . "/../src/dbconnection.php";
		$sql = "SELECT * FROM comments ORDER BY id";

		try {
			$results = $db->query($sql); 
		} catch (Exception $e) {
			echo $e->getMessage();
			return array();
		}
		return $results->fetchAll(PDO::FETCH_ASSOC);
	}
}