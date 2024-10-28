<?php

require_once 'database.php';

class Rental{
    public $id = '';
    public $organizer = '';
    public $venue_id = '';
    public $event_date = '';
    public $remarks = '';
    public $status = '';

    protected $db;

    function __construct() {
        $this->db = new Database(); // Instantiate the Database class.
    }

    function book() {
        $sql = "INSERT INTO booking_transactions (organizer, venue_id, event_date, remarks) VALUES (:organizer, :venue_id, :event_date, :remarks);";

        $query = $this->db->connect()->prepare($sql);

        $query->bindParam(':organizer', $this->organizer);
        $query->bindParam(':venue_id', $this->venue_id);
        $query->bindParam(':event_date', $this->event_date);
        $query->bindParam(':remarks', $this->remarks);

        return $query->execute();
    }

    function complete() {
        $sql = "UPDATE booking_transactions SET status = 'Completed', remarks = :remarks WHERE id = :id;";

        $query = $this->db->connect()->prepare($sql);

        $query->bindParam(':remarks', $this->remarks);
        $query->bindParam(':id', $this->id);

        return $query->execute();
    }

    function showAll($keyword='') {
        $sql = "SELECT a.id as rental_id, a.*, b.* FROM booking_transactions a INNER JOIN venues b ON a.venue_id = b.id WHERE (a.organizer LIKE CONCAT('%', :keyword, '%') OR b.venue_name LIKE CONCAT('%', :keyword, '%')) ORDER BY event_date DESC;";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':keyword', $keyword);

        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll(); 
        }

        return $data;
    }

    function isVenueAvailable($venue_id, $event_date) {
        $sql = "SELECT * FROM booking_transactions WHERE venue_id=:venue_id AND status='Booked' AND event_date=:event_date;";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':venue_id', $venue_id);
        $query->bindParam(':event_date', $event_date);

        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll(); 
        }

        return $data;
    }

    function fetchAllVenues() {
        $sql = "SELECT * FROM venues ORDER BY venue_name ASC;";

        $query = $this->db->connect()->prepare($sql);

        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll(); 
        }

        return $data;
    }

    function fetchRecord($recordID) {
        $sql = "SELECT * FROM booking_transactions WHERE id = :recordID;";
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