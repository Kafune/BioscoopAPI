<?php
class Zalen {
    //DB stuff
    private $conn;
    private $table = 'bioscoopzalen';

    //Film Properties
    public $id;
    public $zaalNummer;
    public $zaalAantalStoelen;
    public $zaalAantalRijen;
    public $zaalBeeld;

    //constructor with DB
    public function __construct($db) {
        $this->conn = $db;
    }

    // Get Films
    public function read() {
        // Create query
        $query = 'SELECT *
        FROM '.$this->table;

        // Prepared statement
        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }

    //get single films
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
        $this->zaalNummer = $row['zaalNummer'];
        $this->zaalAantalStoelen = $row['zaalAantalStoelen'];
        $this->zaalAantalRijen = $row['zaalAantalRijen'];
        $this->zaalBeeld = $row['zaalBeeld'];

    }
	
	//create film
	public function create() {
		$query = 'INSERT INTO '.$this->table.' SET 
		zaalNummer = :zaalNummer,
		zaalAantalStoelen = :zaalAantalStoelen,
		zaalAantalRijen = :zaalAantalRijen,
		zaalBeeld = :zaalBeeld';
		
		//prepare statement
		$stmt = $this->conn->prepare($query);
		
		//bind data
		$stmt->bindParam(':zaalNummer', $this->zaalNummer);
		$stmt->bindParam(':zaalAantalStoelen', $this->zaalAantalStoelen);
		$stmt->bindParam(':zaalAantalRijen', $this->zaalAantalRijen);
		$stmt->bindParam(':zaalBeeld', $this->zaalBeeld);

		//execute query
		if($stmt->execute()) {
			return true;
		}
		//print error if something goes wrong
		printf("Error: $s.\n", $stmt->error);
		return false;
	}
	
	//update film
	public function update() {
		$query = 'UPDATE '.$this->table.' SET 
		zaalNummer = :zaalNummer,
		zaalAantalStoelen = :zaalAantalStoelen,
		zaalAantalRijen = :zaalAantalRijen,
		zaalBeeld = :zaalBeeld
		WHERE
		 id = :id';
		
		//prepare statement
		$stmt = $this->conn->prepare($query);
		
		
		//bind data
				$stmt->bindParam(':zaalNummer', $this->zaalNummer);
		$stmt->bindParam(':zaalAantalStoelen', $this->zaalAantalStoelen);
		$stmt->bindParam(':zaalAantalRijen', $this->zaalAantalRijen);
		$stmt->bindParam(':zaalBeeld', $this->zaalBeeld);
		$stmt->bindParam(':id', $this->id);
		
		//execute query
		if($stmt->execute()) {
			return true;
		}
		//print error if something goes wrong
		printf("Error: $s.\n", $stmt->error);
		return false;
	}
	
	//delet films
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