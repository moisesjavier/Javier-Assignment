<?php

require_once 'database.php';

class Rental{
    public $id = '';
    public $borrower_name = '';
    public $book_id = '';
    public $borrow_date = '';
    public $return_date = '';
    public $remarks = '';
    public $status = '';

    protected $db;

    function __construct() {
        $this->db = new Database(); // Instantiate the Database class.
    }

    function borrow() {
        $sql = "INSERT INTO book_borrowing_transactions (borrower_name, book_id, borrow_date, remarks) VALUES (:borrower_name, :book_id, :borrow_date, :remarks);";
        $sql = $sql . "UPDATE books SET number_of_copies = (SELECT number_of_copies FROM books WHERE id = :book_id) - 1 WHERE id = :book_id";

        $query = $this->db->connect()->prepare($sql);

        $query->bindParam(':borrower_name', $this->borrower_name);
        $query->bindParam(':book_id', $this->book_id);
        $query->bindParam(':borrow_date', $this->borrow_date);
        $query->bindParam(':remarks', $this->remarks);

        return $query->execute();
    }

    function return() {
        $sql = "UPDATE book_borrowing_transactions SET return_date = :return_date, status = 'Returned', remarks = :remarks WHERE id = :id;";
        $sql = $sql . "UPDATE books SET number_of_copies = (SELECT number_of_copies FROM books WHERE id = :book_id) + 1 WHERE id = :book_id";

        $query = $this->db->connect()->prepare($sql);

        $query->bindParam(':return_date', $this->return_date);
        $query->bindParam(':book_id', $this->book_id);
        $query->bindParam(':remarks', $this->remarks);
        $query->bindParam(':id', $this->id);

        return $query->execute();
    }

    function showAll($keyword='') {
        $sql = "SELECT a.id as rental_id, a.*, b.* FROM book_borrowing_transactions a INNER JOIN books b ON a.book_id = b.id WHERE (a.borrower_name LIKE CONCAT('%', :keyword, '%') OR b.title LIKE CONCAT('%', :keyword, '%')) ORDER BY borrow_date DESC;";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':keyword', $keyword);

        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll(); 
        }

        return $data;
    }

    function fetchBooks() {
        $sql = "SELECT * FROM books WHERE number_of_copies > 0 ORDER BY title ASC;";

        $query = $this->db->connect()->prepare($sql);

        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll(); 
        }

        return $data;
    }

    function fetchAllBooks() {
        $sql = "SELECT * FROM books ORDER BY title ASC;";

        $query = $this->db->connect()->prepare($sql);

        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll(); 
        }

        return $data;
    }

    function fetchRecord($recordID) {
        $sql = "SELECT * FROM book_borrowing_transactions WHERE id = :recordID;";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':recordID', $recordID);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetch();
        }
        return $data;
    }
}
