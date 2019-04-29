<?php
class Domain {
	private $table = 'bioscoopdomeinen';
	
	public $id;
	public $domein_naam;
	public $apiKey;
	
	public
	function __construct( $db ) {
		$this->conn = $db;
	}
	
	// Check of the domein overeen komt met de database
	public function checkDomain() {
		
		$query = "SELECT * FROM ".$this->table."  WHERE domeinNaam = :domeinnaam";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam( ':domeinnaam', $this->domein_naam );
		$stmt->execute();
		
		$row = $stmt->fetch(PDO::FETCH_ASSOC);    
		
		$this->domein_naam = $row['domeinNaam'];
	}
	
	// TODO pak domein op basis van de API key
	public function verifyDomain() {
		$query = "SELECT d.id, d.domeinNaam, a.api_key
		FROM ".$this->table." d
		INNER JOIN bioscoopapikey a on d.apiKey = a.id
		WHERE a.api_key = :apiKey";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam( ':apiKey', $this->apiKey );
		$stmt->execute();
		
		$row = $stmt->fetch(PDO::FETCH_ASSOC);    
		
		$this->domein_naam = $row['domeinNaam'];
		$this->apiKey = $row['api_key'];
	}
	
	//TODO: INSERT domain met bepaalde API key
		public function create() {
		$query = 'INSERT INTO '.$this->table.' SET 
		api_key = :api_key,
		api_level = :api_level';
		
		//prepare statement
		$stmt = $this->conn->prepare($query);

		
		//bind data
		$stmt->bindParam(':api_key', $this->api_key);
		$stmt->bindParam(':api_level', $this->api_level);
		
		//execute query
		if($stmt->execute()) {
			return true;
		}
		//print error if something goes wrong
		printf("Error: $s.\n", $stmt->error);
		return false;		
	}
}

?>