<?php

require_once 'database.php';

class Rental{
    public $id = '';
    public $client_name = '';
    public $car_id = '';
    public $rental_date = '';
    public $return_date = '';
    public $remarks = '';
    public $status = '';

    protected $db;

    function __construct() {
        $this->db = new Database(); // Instantiate the Database class.
    }

    function book() {
        $sql = "INSERT INTO rentals (client_name, car_id, rental_date, remarks) VALUES (:client_name, :car_id, :rental_date, :remarks);";
        $sql = $sql . "UPDATE cars SET quantity = (SELECT quantity FROM cars WHERE id = :car_id) - 1 WHERE id = :car_id";

        $query = $this->db->connect()->prepare($sql);

        $query->bindParam(':client_name', $this->client_name);
        $query->bindParam(':car_id', $this->car_id);
        $query->bindParam(':rental_date', $this->rental_date);
        $query->bindParam(':remarks', $this->remarks);

        return $query->execute();
    }

    function return() {
        $sql = "UPDATE rentals SET return_date = :return_date, status = 'Completed', remarks = :remarks WHERE id = :id;";
        $sql = $sql . "UPDATE cars SET quantity = (SELECT quantity FROM cars WHERE id = :car_id) - 1 WHERE id = :car_id";

        $query = $this->db->connect()->prepare($sql);

        $query->bindParam(':return_date', $this->return_date);
        $query->bindParam(':car_id', $this->car_id);
        $query->bindParam(':remarks', $this->remarks);
        $query->bindParam(':id', $this->id);

        return $query->execute();
    }

    function cancel() {
        $sql = "UPDATE rentals SET status = 'Cancelled', remarks = :remarks WHERE id = :id;";
        $sql = $sql . "UPDATE cars SET quantity = (SELECT quantity FROM cars WHERE id = :car_id) - 1 WHERE id = :car_id";

        $query = $this->db->connect()->prepare($sql);
        
        $query->bindParam(':car_id', $this->car_id);
        $query->bindParam(':remarks', $this->remarks);
        $query->bindParam(':id', $this->id);

        return $query->execute();
    }

    function showAll($keyword='') {
        $sql = "SELECT a.id as rental_id, a.*, b.* FROM rentals a INNER JOIN cars b ON a.car_id = b.id WHERE (a.client_name LIKE CONCAT('%', :keyword, '%') OR b.car_name LIKE CONCAT('%', :keyword, '%')) ORDER BY rental_date DESC;";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':keyword', $keyword);

        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll(); 
        }

        return $data;
    }

    function fetchCars() {
        $sql = "SELECT * FROM cars WHERE quantity > 0 ORDER BY car_name ASC;";

        $query = $this->db->connect()->prepare($sql);

        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll(); 
        }

        return $data;
    }

    function fetchAllCars() {
        $sql = "SELECT * FROM cars ORDER BY car_name ASC;";

        $query = $this->db->connect()->prepare($sql);

        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll(); 
        }

        return $data;
    }

    function fetchRecord($recordID) {
        $sql = "SELECT * FROM rentals WHERE id = :recordID;";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':recordID', $recordID);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetch();
        }
        return $data;
    }
}

// $obj = new Rental();

// var_dump($obj->showAll());