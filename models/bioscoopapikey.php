<?php
class ApiKey {
	public $id;
	public $api_key;
	public $api_level;

	public
	function __construct( $db ) {
		$this->conn = $db;
	}

	public
	function checkApiKey() {
		$query = "SELECT * FROM bioscoopapikey WHERE api_key = :api_key";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam( ':api_key', $this->api_key );
		$stmt->execute();
		
		$row = $stmt->fetch(PDO::FETCH_ASSOC);    
		
		$this->id = $row['id'];
        $this->api_key = $row['api_key'];
        $this->api_level = $row['api_level'];
	}
	
	public function create() {
		
		
	}
}

?>