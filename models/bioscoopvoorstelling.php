<?php
class Voorstelling {
    //DB stuff
    private $conn;
    private $table = 'bioscoopvoorstelling';

    //Film Properties
    public $id;
    public $voorstellingNummer;
    public $voorstellingTickets;
    public $voorstellingZaal;
    public $voorstellingFilm;
    public $voorstellingDuur;
	public $voorstellingLocatie;

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
        $query = 'SELECT * FROM '. $this->table .' WHERE ticketID = ?';

        //Prepare statement

        $stmt = $this->conn->prepare($query);

        //bind id
        $stmt->bindParam(1, $this->ticketID);

        //execute query
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        //Set properties
        $this->id = $row['id'];
        $this->voorstellingNummer = $row['voorstellingNummer'];
        $this->voorstellingTickets = $row['voorstellingTickets'];
        $this->voorstellingZaal = $row['voorstellingZaal'];
        $this->voorstellingFilm = $row['voorstellingFilm'];
        $this->voorstellingDuur = $row['voorstellingDuur'];
        $this->voorstellingLocatie = $row['voorstellingLocatie'];

    }
	
	//create film
	public function create() {
		$query = 'INSERT INTO '.$this->table.' SET 
		voorstellingNummer = :voorstellingNummer,
		voorstellingTickets = :voorstellingTickets,
		voorstellingZaal = :voorstellingZaal,
		voorstellingFilm = :voorstellingFilm,
		voorstellingDuur = :voorstellingDuur,
		voorstellingLocatie = :voorstellingLocatie';
		
		//prepare statement
		$stmt = $this->conn->prepare($query);
		
		//Clean data
		$this->voorstellingNummer = htmlspecialchars(strip_tags($this->voorstellingNummer));
		$this->voorstellingTickets = htmlspecialchars(strip_tags($this->voorstellingTickets));
		$this->voorstellingZaal = htmlspecialchars(strip_tags($this->voorstellingZaal));
		$this->voorstellingFilm = htmlspecialchars(strip_tags($this->voorstellingFilm));
		$this->voorstellingDuur = htmlspecialchars(strip_tags($this->voorstellingDuur));
		$this->voorstellingLocatie = htmlspecialchars(strip_tags($this->voorstellingLocatie));
		
		//bind data
		$stmt->bindParam(':voorstellingNummer', $this->voorstellingNummer);
		$stmt->bindParam(':voorstellingTickets', $this->voorstellingTickets);
		$stmt->bindParam(':voorstellingZaal', $this->voorstellingZaal);
		$stmt->bindParam(':voorstellingFilm', $this->voorstellingFilm);
		$stmt->bindParam(':voorstellingDuur', $this->voorstellingDuur);
		$stmt->bindParam(':voorstellingLocatie', $this->voorstellingLocatie);
		
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
		voorstellingNummer = :voorstellingNummer,
		voorstellingTickets = :voorstellingTickets,
		voorstellingZaal = :voorstellingZaal,
		voorstellingFilm = :voorstellingFilm,
		voorstellingDuur = :voorstellingDuur,
		voorstellingLocatie = :voorstellingLocatie
		WHERE
		 id = :id';
		
		//prepare statement
		$stmt = $this->conn->prepare($query);
		
		//Clean data
		$this->voorstellingNummer = htmlspecialchars(strip_tags($this->voorstellingNummer));
		$this->voorstellingTickets = htmlspecialchars(strip_tags($this->voorstellingTickets));
		$this->voorstellingZaal = htmlspecialchars(strip_tags($this->voorstellingZaal));
		$this->voorstellingFilm = htmlspecialchars(strip_tags($this->voorstellingFilm));
		$this->voorstellingDuur = htmlspecialchars(strip_tags($this->voorstellingDuur));
		$this->voorstellingLocatie = htmlspecialchars(strip_tags($this->voorstellingLocatie));
		$this->id = htmlspecialchars(strip_tags($this->id));
		
		
		
		//bind data
				$stmt->bindParam(':voorstellingNummer', $this->voorstellingNummer);
		$stmt->bindParam(':voorstellingTickets', $this->voorstellingTickets);
		$stmt->bindParam(':voorstellingZaal', $this->voorstellingZaal);
		$stmt->bindParam(':voorstellingFilm', $this->voorstellingFilm);
		$stmt->bindParam(':voorstellingDuur', $this->voorstellingDuur);
		$stmt->bindParam(':voorstellingLocatie', $this->voorstellingLocatie);
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