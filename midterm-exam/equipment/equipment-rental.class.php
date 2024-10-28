<?php

require_once 'database.php';

class Rental{
    public $id = '';
    public $renter_name = '';
    public $equipment_id = '';
    public $rental_date = '';
    public $return_date = '';
    public $remarks = '';
    public $status = '';

    protected $db;

    function __construct() {
        $this->db = new Database(); // Instantiate the Database class.
    }

    function rent() {
        $sql = "INSERT INTO rental_transactions (renter_name, equipment_id, rental_date, remarks) VALUES (:renter_name, :equipment_id, :rental_date, :remarks);";
        $sql = $sql . "UPDATE equipment SET number_of_units = (SELECT number_of_units FROM equipment WHERE id = :equipment_id) - 1 WHERE id = :equipment_id";

        $query = $this->db->connect()->prepare($sql);

        $query->bindParam(':renter_name', $this->renter_name);
        $query->bindParam(':equipment_id', $this->equipment_id);
        $query->bindParam(':rental_date', $this->rental_date);
        $query->bindParam(':remarks', $this->remarks);

        return $query->execute();
    }

    function return() {
        $sql = "UPDATE rental_transactions SET return_date = :return_date, status = 'Returned', remarks = :remarks WHERE id = :id;";
        $sql = $sql . "UPDATE equipment SET number_of_units = (SELECT number_of_units FROM equipment WHERE id = :equipment_id) + 1 WHERE id = :equipment_id;";

        $query = $this->db->connect()->prepare($sql);

        $query->bindParam(':return_date', $this->return_date);
        $query->bindParam(':equipment_id', $this->equipment_id);
        $query->bindParam(':remarks', $this->remarks);
        $query->bindParam(':id', $this->id);

        return $query->execute();
    }

    function showAll($keyword='') {
        $sql = "SELECT r.id as rental_id, r.*, e.* FROM rental_transactions r INNER JOIN equipment e ON r.equipment_id = e.id WHERE (r.renter_name LIKE CONCAT('%', :keyword, '%') OR e.equipment_name LIKE CONCAT('%', :keyword, '%')) ORDER BY rental_date DESC;";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':keyword', $keyword);

        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll(); 
        }

        return $data;
    }

    function fetchEquipments() {
        $sql = "SELECT * FROM equipment WHERE number_of_units > 0 ORDER BY equipment_name ASC;";

        $query = $this->db->connect()->prepare($sql);

        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll(); 
        }

        return $data;
    }

    function fetchAllEquipments() {
        $sql = "SELECT * FROM equipment ORDER BY equipment_name ASC;";

        $query = $this->db->connect()->prepare($sql);

        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll(); 
        }

        return $data;
    }

    function fetchRecord($recordID) {
        $sql = "SELECT * FROM rental_transactions WHERE id = :recordID;";
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