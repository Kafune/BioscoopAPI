<?php
class Bioscoop {
    //DB stuff
    private $conn;
    private $table = 'bioscoop';

    //bioscoop Properties
    public $id;
    public $bioscoopLocatie;
    public $bioscoopZaal;
    public $bioscoopAantalPersoneel;

    //constructor with DB
    public function __construct($db) {
        $this->conn = $db;
    }

    // Get Bioscoop
    public function read() {
        // Create query
        $query = 'SELECT * FROM '.$this->table.' as b
		INNER JOIN bioscooplocatie as l ON b.bioscoopLocatie = l.id
		INNER JOIN bioscoopzalen as z ON b.bioscoopZaal = z.id';

        // Prepared statement
        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
//        $row = $stmt->fetch(PDO::FETCH_ASSOC);
//
//        $this->id = $row['id'];
//        $this->bioscoopLocatie = $row['bioscoopLocatie'];
//        $this->bioscoopZaal = $row['bioscoopZaal'];
//        $this->bioscoopAantalPersoneel = $row['bioscoopAantalPersoneel'];
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
        $this->bioscoopLocatie = $row['bioscoopLocatie'];
        $this->bioscoopZaal = $row['bioscoopZaal'];
        $this->bioscoopAantalPersoneel = $row['bioscoopAantalPersoneel'];

    }
	
	//create bioscoop
	public function create() {
		$query = 'INSERT INTO '.$this->table.' SET 
		bioscoopLocatie = :bioscoopLocatie,
		bioscoopZaal = :bioscoopZaal,
		bioscoopAantalPersoneel = :bioscoopAantalPersoneel';
		
		//prepare statement
		$stmt = $this->conn->prepare($query);

		
		//bind data
		$stmt->bindParam(':bioscoopLocatie', $this->bioscoopLocatie);
		$stmt->bindParam(':bioscoopZaal', $this->bioscoopZaal);
		$stmt->bindParam(':bioscoopAantalPersoneel', $this->bioscoopAantalPersoneel);
		
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
		bioscoopLocatie = :bioscoopLocatie,
		bioscoopZaal = :bioscoopZaal,
		bioscoopAantalPersoneel = :bioscoopAantalPersoneel
		WHERE
		 id = :id';
		
		//prepare statement
		$stmt = $this->conn->prepare($query);		
		
		
		//bind data
		$stmt->bindParam(':bioscoopLocatie', $this->bioscoopLocatie);
		$stmt->bindParam(':bioscoopZaal', $this->bioscoopZaal);
		$stmt->bindParam(':bioscoopAantalPersoneel', $this->bioscoopAantalPersoneel);
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