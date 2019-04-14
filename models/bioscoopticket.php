<?php
class Ticket {
    //DB stuff
    private $conn;
    private $table = 'bioscoopticket';

    //Film Properties
    public $id;
    public $ticketNummer;
    public $ticketKlant;
    public $ticketDatum;
    public $ticketTijd;
    public $ticketZaal;
	public $ticketPrijs;

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
        $this->ticketNummer = $row['ticketNummer'];
        $this->ticketKlant = $row['ticketKlant'];
        $this->ticketDatum = $row['ticketDatum'];
        $this->ticketTijd = $row['ticketTijd'];
        $this->ticketZaal = $row['ticketZaal'];
        $this->ticketPrijs = $row['ticketPrijs'];

    }
	
	//create film
	public function create() {
		$query = 'INSERT INTO '.$this->table.' SET 
		ticketNummer = :ticketNummer,
		ticketKlant = :ticketKlant,
		ticketDatum = :ticketDatum,
		ticketTijd = :ticketTijd,
		ticketZaal = :ticketZaal,
		ticketPrijs = :ticketPrijs';
		
		//prepare statement
		$stmt = $this->conn->prepare($query);
		
		//Clean data
		$this->ticketNummer = htmlspecialchars(strip_tags($this->ticketNummer));
		$this->ticketKlant = htmlspecialchars(strip_tags($this->ticketKlant));
		$this->ticketDatum = htmlspecialchars(strip_tags($this->ticketDatum));
		$this->ticketTijd = htmlspecialchars(strip_tags($this->ticketTijd));
		$this->ticketZaal = htmlspecialchars(strip_tags($this->ticketZaal));
		$this->ticketPrijs = htmlspecialchars(strip_tags($this->ticketPrijs));
		
		//bind data
		$stmt->bindParam(':ticketNummer', $this->ticketNummer);
		$stmt->bindParam(':ticketKlant', $this->ticketKlant);
		$stmt->bindParam(':ticketDatum', $this->ticketDatum);
		$stmt->bindParam(':ticketTijd', $this->ticketTijd);
		$stmt->bindParam(':ticketZaal', $this->ticketZaal);
		$stmt->bindParam(':ticketPrijs', $this->ticketPrijs);
		
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
		ticketNummer = :ticketNummer,
		ticketKlant = :ticketKlant,
		ticketDatum = :ticketDatum,
		ticketTijd = :ticketTijd,
		ticketZaal = :ticketZaal,
		ticketPrijs = :ticketPrijs
		WHERE
		 id = :id';
		
		//prepare statement
		$stmt = $this->conn->prepare($query);
		
		//Clean data
		$this->ticketNummer = htmlspecialchars(strip_tags($this->ticketNummer));
		$this->ticketKlant = htmlspecialchars(strip_tags($this->ticketKlant));
		$this->ticketDatum = htmlspecialchars(strip_tags($this->ticketDatum));
		$this->ticketTijd = htmlspecialchars(strip_tags($this->ticketTijd));
		$this->ticketZaal = htmlspecialchars(strip_tags($this->ticketZaal));
		$this->ticketPrijs = htmlspecialchars(strip_tags($this->ticketPrijs));
		$this->id = htmlspecialchars(strip_tags($this->id));
		
		
		
		//bind data
		$stmt->bindParam(':ticketNummer', $this->ticketNummer);
		$stmt->bindParam(':ticketKlant', $this->ticketKlant);
		$stmt->bindParam(':ticketDatum', $this->ticketDatum);
		$stmt->bindParam(':ticketTijd', $this->ticketTijd);
		$stmt->bindParam(':ticketZaal', $this->ticketZaal);
		$stmt->bindParam(':ticketPrijs', $this->ticketPrijs);
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
		
		//clean data
		$this->id = htmlspecialchars(strip_tags($this->id));
		
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