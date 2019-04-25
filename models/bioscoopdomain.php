<?php
class Domain {
	public $id;
	public $domein_naam;
	public $api_key;
	
	public
	function __construct( $db ) {
		$this->conn = $db;
	}
	
	public function checkDomain() {
		
		$query = "SELECT * FROM bioscoopdomeinen WHERE domeinNaam = :domein_naam AND apiKey = :apiKey";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam( ':domeinNaam', $this->domeinNaam );
		$stmt->bindParam( ':apikey', $this->api_key );
		$stmt->execute();
		
		$row = $stmt->fetch(PDO::FETCH_ASSOC);    
		
        $this->api_key = $row['domeinNaam'];
        $this->api_level = $row['apiKey'];
	
	}
}

?>