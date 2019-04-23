<?php
class checkApiKey {
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
		$stmt->bindParam( ':api_key', $api_key );
		$stmt->execute();
		
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

        

		if ( $row[ 'api_key' ] > 0 ) {
			//Set properties
        $this->id = $row['id'];
        $this->api_key = $row['api_key'];
        $this->api_level = $row['api_level'];

		return true;
		} else {
			return false;
		}
	}
	
	public function generateApiKey($api_key) {
		
		
	}
}

?>