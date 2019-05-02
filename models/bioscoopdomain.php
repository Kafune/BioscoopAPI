<?php
class Domain {
	private $table = 'bioscoopdomeinen';
	
	public $id;
	public $domein_naam;
	public $api_key;
	
	public
	function __construct( $db ) {
		$this->conn = $db;
	}
	
	// Check of the domein overeen komt met de database voordat je toegang hebt tot de API zelf.
	public function checkDomain() {
		
		$query = "SELECT * FROM ".$this->table."  WHERE domeinNaam = :domeinnaam";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam( ':domeinnaam', $this->domein_naam );
		$stmt->execute();
		
		$row = $stmt->fetch(PDO::FETCH_ASSOC);    
		
		$this->domein_naam = $row['domeinNaam'];
	}
	
	// Check de domein als de gebruiker deze is vergeten op basis van de API key.
	public function verifyDomain() {
		$query = "SELECT * FROM ".$this->table." as d
		INNER JOIN bioscoopapikey as a ON d.api_key = a.id
		WHERE a.api_key = :api_key";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam( ':api_key', $this->api_key );
		$stmt->execute();
		
		$row = $stmt->fetch(PDO::FETCH_ASSOC);    
		
		$this->id = $row['id'];
		$this->domein_naam = $row['domeinNaam'];
	}
	
	//TODO: INSERT domain met bepaalde API key
		public function create() {
		$query = 'SELECT * FROM bioscoopapikey WHERE api_key = :api_key';			

			
		//prepare statement
		$stmt = $this->conn->prepare($query);

		
		//bind data
		$stmt->bindParam(':api_key', $this->api_key);
		
		//execute query
		$stmt->execute();
			
		$row = $stmt->fetch();
			
		$result = $row['id'];
			
		$query = 'INSERT INTO '.$this->table.' SET 
		domeinNaam = :domein_naam,
		api_key = :api_key';
		
		//prepare statement
		$stmt = $this->conn->prepare($query);

		
		//bind data
		$stmt->bindParam(':domein_naam', $this->domein_naam);
		$stmt->bindParam(':api_key', $result);
		
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