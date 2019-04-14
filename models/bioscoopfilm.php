<?php
class Film {
    //DB stuff
    private $conn;
    private $table = 'bioscoopfilm';

    //Film Properties
    public $id;
    public $filmNaam;
    public $filmTijd;
    public $filmPrijs;
    public $filmDuur;
    public $filmType;

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
        $this->filmNaam = $row['filmNaam'];
        $this->filmTijd = $row['filmTijd'];
        $this->filmPrijs = $row['filmPrijs'];
        $this->filmDuur = $row['filmDuur'];
        $this->filmType = $row['filmType'];

    }
	
	//create film
	public function create() {
		$query = 'INSERT INTO '.$this->table.' SET 
		filmNaam = :filmNaam,
		filmTijd = :filmTijd,
		filmPrijs = :filmPrijs,
		filmDuur = :filmDuur,
		filmType = :filmType';
		
		//prepare statement
		$stmt = $this->conn->prepare($query);
		
		//Clean data
		$this->filmNaam = htmlspecialchars(strip_tags($this->filmNaam));
		$this->filmTijd = htmlspecialchars(strip_tags($this->filmTijd));
		$this->filmPrijs = htmlspecialchars(strip_tags($this->filmPrijs));
		$this->filmDuur = htmlspecialchars(strip_tags($this->filmDuur));
		$this->filmType = htmlspecialchars(strip_tags($this->filmType));
		
		//bind data
		$stmt->bindParam(':filmNaam', $this->filmNaam);
		$stmt->bindParam(':filmTijd', $this->filmTijd);
		$stmt->bindParam(':filmPrijs', $this->filmPrijs);
		$stmt->bindParam(':filmDuur', $this->filmDuur);
		$stmt->bindParam(':filmType', $this->filmType);
		
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
		filmNaam = :filmNaam,
		filmTijd = :filmTijd,
		filmPrijs = :filmPrijs,
		filmDuur = :filmDuur,
		filmType = :filmType
		WHERE
		 id = :id';
		
		//prepare statement
		$stmt = $this->conn->prepare($query);
		
		//Clean data
		$this->filmNaam = htmlspecialchars(strip_tags($this->filmNaam));
		$this->filmTijd = htmlspecialchars(strip_tags($this->filmTijd));
		$this->filmPrijs = htmlspecialchars(strip_tags($this->filmPrijs));
		$this->filmDuur = htmlspecialchars(strip_tags($this->filmDuur));
		$this->filmType = htmlspecialchars(strip_tags($this->filmType));
		$this->id = htmlspecialchars(strip_tags($this->id));
		
		
		
		//bind data
		$stmt->bindParam(':filmNaam', $this->filmNaam);
		$stmt->bindParam(':filmTijd', $this->filmTijd);
		$stmt->bindParam(':filmPrijs', $this->filmPrijs);
		$stmt->bindParam(':filmDuur', $this->filmDuur);
		$stmt->bindParam(':filmType', $this->filmType);
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