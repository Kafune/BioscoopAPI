<?php
class Locatie {
    //DB stuff
    private $conn;
    private $table = 'bioscooplocatie';

    //bioscoop Properties
    public $id;
    public $locatieNaam;
    public $locatieStraat;
    public $locatiePostcode;
    public $locatieProvincie;

    //constructor with DB
    public function __construct($db) {
        $this->conn = $db;
    }

    // Get Bioscoop
    public function read() {
        // Create query
        $query = 'SELECT *
        FROM '.$this->table;

        // Prepared statement
        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }

    //get single bioscoop
    public function read_single() {
        // Create query
        $query = 'SELECT * FROM '. $this->table .' WHERE id = ?';

        //Prepare statement

        $stmt = $this->conn->prepare($query);

        //bind id
        $stmt->bindParam(1, $this->id);

        //execute query
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        //Set properties
        $this->id = $row['id'];
        $this->locatieNaam = $row['locatieNaam'];
        $this->locatieStraat = $row['locatieStraat'];
        $this->locatiePostcode = $row['locatiePostcode'];
        $this->locatieProvincie = $row['locatieProvincie'];

    }
	
	//create bioscoop
	public function create() {
		$query = 'INSERT INTO '.$this->table.' SET 
		locatieNaam = :locatieNaam,
		locatieStraat = :locatieStraat,
		locatiePostcode = :locatiePostcode,
		locatieProvincie = :locatieProvincie';
		
		//prepare statement
		$stmt = $this->conn->prepare($query);
		
		//bind data
		$stmt->bindParam(':locatieNaam', $this->locatieNaam);
		$stmt->bindParam(':locatieStraat', $this->locatieStraat);
		$stmt->bindParam(':locatiePostcode', $this->locatiePostcode);
		$stmt->bindParam(':locatieProvincie', $this->locatieProvincie);
		
		//execute query
		if($stmt->execute()) {
			return true;
		}
		//print error if something goes wrong
		printf("Error: $s.\n", $stmt->error);
		return false;
	}
	
	//update bioscoop
	public function update() {
		$query = 'UPDATE '.$this->table.' SET 
		locatieNaam = :locatieNaam,
		locatieStraat = :locatieStraat,
		locatiePostcode = :locatiePostcode,
		locatieProvincie = :locatieProvincie
		WHERE
		 id = :id';
		
		//prepare statement
		$stmt = $this->conn->prepare($query);
		
		
		//bind data
		$stmt->bindParam(':locatieNaam', $this->locatieNaam);
		$stmt->bindParam(':locatieStraat', $this->locatieStraat);
		$stmt->bindParam(':locatiePostcode', $this->locatiePostcode);
		$stmt->bindParam(':locatieProvincie', $this->locatieProvincie);
		$stmt->bindParam(':id', $this->id);
		
		//execute query
		if($stmt->execute()) {
			return true;
		}
		//print error if something goes wrong
		printf("Error: $s.\n", $stmt->error);
		return false;
	}
	
	//delet bioscoop
	public function delete() {
		$query = 'DELETE FROM '.$this->table.' WHERE id = :id';
			
		//prepare statement
		$stmt = $this->conn->prepare($query);
		
		//bind data
		$stmt->bindParam(':id', $this->id);
		
		//execute query
		if($stmt->execute()) {
			return true;
		}
		//print error if something goes wrong
		printf("Error: $s.\n", $stmt->error);
		return false;
	}
}