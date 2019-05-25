<?php
class ApiKey {
	private $table = 'bioscoopapikey';

	public $id;
	public $api_key;
	public $api_level;

	public
	function __construct( $db ) {
		$this->conn = $db;
	}

    public
    function checkApiKey() {
        $query = 'SELECT * FROM '.$this->table.' WHERE api_key = :api_key';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam( ':api_key', $this->api_key );
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->id = $row['id'];
        $this->api_key = $row['api_key'];
        $this->api_level = $row['api_level'];
    }

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