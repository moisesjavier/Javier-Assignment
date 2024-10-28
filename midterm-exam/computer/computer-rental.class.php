<?php

require_once 'database.php';

class Rental{
    public $id = '';
    public $customer_name = '';
    public $computer_unit_id = '';
    public $start_time = '';
    public $end_time = '';
    public $remarks = '';
    public $status = '';

    protected $db;

    function __construct() {
        $this->db = new Database(); // Instantiate the Database class.
    }

    function rent() {
        $sql = "INSERT INTO computer_rentals (customer_name, computer_unit_id, start_time, remarks) VALUES (:customer_name, :computer_unit_id, :start_time, :remarks);";
        $sql = $sql . "UPDATE computer_units SET is_available = 0 WHERE id = :computer_unit_id;";

        $query = $this->db->connect()->prepare($sql);

        $query->bindParam(':customer_name', $this->customer_name);
        $query->bindParam(':computer_unit_id', $this->computer_unit_id);
        $query->bindParam(':start_time', $this->start_time);
        $query->bindParam(':remarks', $this->remarks);

        return $query->execute();
    }

    function complete() {
        $sql = "UPDATE computer_rentals SET end_time = :end_time, status = 'Completed', remarks = :remarks WHERE id = :id;";
        $sql = $sql . "UPDATE computer_units SET is_available = 1 WHERE id = :computer_unit_id";

        $query = $this->db->connect()->prepare($sql);

        $query->bindParam(':end_time', $this->end_time);
        $query->bindParam(':computer_unit_id', $this->computer_unit_id);
        $query->bindParam(':remarks', $this->remarks);
        $query->bindParam(':id', $this->id);

        return $query->execute();
    }

    function showAll($keyword='') {
        $sql = "SELECT r.id as rental_id, r.*, c.* FROM computer_rentals r INNER JOIN computer_units c ON r.computer_unit_id = c.id WHERE (r.customer_name LIKE CONCAT('%', :keyword, '%') OR c.unit_name LIKE CONCAT('%', :keyword, '%')) ORDER BY start_time DESC;";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':keyword', $keyword);

        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll(); 
        }

        return $data;
    }

    function fetchComputerUnits() {
        $sql = "SELECT * FROM computer_units WHERE is_available = 1 ORDER BY unit_name ASC;";

        $query = $this->db->connect()->prepare($sql);

        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll(); 
        }

        return $data;
    }

    function fetchAllComputerUnits() {
        $sql = "SELECT * FROM computer_units ORDER BY unit_name ASC;";

        $query = $this->db->connect()->prepare($sql);

        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll(); 
        }

        return $data;
    }

    function fetchRecord($recordID) {
        $sql = "SELECT * FROM computer_rentals WHERE id = :recordID;";
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

// var_dump($obj->fetchComputerUnits());