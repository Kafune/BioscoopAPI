<?php
class Bioscoop {
    //DB stuff
    private $conn;
    private $table = 'bioscoop';

    //bioscoop Properties
    public $id;
    public $bioscoopLocatie;
    public $bioscoopZalen;
    public $bioscoopTickets;
    public $bioscoopVoorstelling;

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
        $this->bioscoopLocatie = $row['bioscoopLocatie'];
        $this->bioscoopZalen = $row['bioscoopZalen'];
        $this->bioscoopTickets = $row['bioscoopTickets'];
        $this->bioscoopVoorstelling = $row['bioscoopVoorstelling'];

    }
	
	//create bioscoop
	public function create() {
		$query = 'INSERT INTO '.$this->table.' SET 
		bioscoopLocatie = :bioscoopLocatie,
		bioscoopZalen = :bioscoopZalen,
		bioscoopTickets = :bioscoopTickets,
		bioscoopVoorstelling = :bioscoopVoorstelling';
		
		//prepare statement
		$stmt = $this->conn->prepare($query);

		
		//bind data
		$stmt->bindParam(':bioscoopLocatie', $this->bioscoopLocatie);
		$stmt->bindParam(':bioscoopZalen', $this->bioscoopZalen);
		$stmt->bindParam(':bioscoopTickets', $this->bioscoopTickets);
		$stmt->bindParam(':bioscoopVoorstelling', $this->bioscoopVoorstelling);
		
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
		bioscoopZalen = :bioscoopZalen,
		bioscoopTickets = :bioscoopTickets,
		bioscoopVoorstelling = :bioscoopVoorstelling
		WHERE
		 id = :id';
		
		//prepare statement
		$stmt = $this->conn->prepare($query);		
		
		
		//bind data
		$stmt->bindParam(':bioscoopLocatie', $this->bioscoopLocatie);
		$stmt->bindParam(':bioscoopZalen', $this->bioscoopZalen);
		$stmt->bindParam(':bioscoopTickets', $this->bioscoopTickets);
		$stmt->bindParam(':bioscoopVoorstelling', $this->bioscoopVoorstelling);
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