<?php

require_once('functions.php');
require_once 'books-rental.class.php';

$id = $borrower_name = $book_id = $borrow_date = $return_date = $remarks = '';
$borrower_nameErr = $book_idErr = $borrow_dateErr = $return_dateErr = $remarksErr = '';

$rentalObj = new Rental();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $record = $rentalObj->fetchRecord($id);
        if (!empty($record)) {
            $borrower_name = $record['borrower_name'];
            $book_id = $record['book_id'];
            $borrow_date = date('Y-m-d', strtotime($record['borrow_date']));
            $remarks = $record['remarks'];
        } else {
            echo 'No rental found';
            exit;
        }
    } else {
        echo 'No rental found';
        exit;
    }
} else if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $id = $_GET['id'];
    $record = $rentalObj->fetchRecord($id);
    if (!empty($record)) {
        $borrower_name = $record['borrower_name'];
        $book_id = $record['book_id'];
        $borrow_date = date('Y-m-d', strtotime($record['borrow_date']));
        $remarks = $record['remarks'];
    }
    $return_date = !empty($_POST['return_date'])? clean_input(date('Y-m-d', strtotime($_POST['return_date']))): '';
    $remarks = clean_input($_POST['remarks']);

    if(empty($return_date)){
        $return_dateErr = 'Return Date is required';
    }else if($return_date < $borrow_date){
        $return_dateErr = 'Return Date cant be prior to borrow date';
    }

    if(empty($return_dateErr)){
        $rentalObj->id = $id;
        $rentalObj->book_id = $book_id;
        $rentalObj->return_date = $return_date;
        $rentalObj->remarks = $remarks;

        if($rentalObj->return()){
            header('Location: view-books.php');
        } else {
            echo 'Something went wrong when returning a book.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrow Book</title>
    <style>
        /* Error message styling */
        .error{
            color: red;
        }
    </style>
</head>
<body>
    <form action="" method="post">
        <!-- Display a note indicating required fields -->
        <span class="error">* are required fields</span>
        <br>

        <label for="borrower_name">Borrower Name</label><span class="error">*</span>
        <br>
        <input type="text" name="borrower_name" id="borrower_name" value="<?= $borrower_name ?>" disabled>
        <br>
        <?php if(!empty($borrower_nameErr)): ?>
            <span class="error"><?= $borrower_nameErr ?></span><br>
        <?php endif; ?>

        <label for="book_id ">Books</label><span class="error">*</span>
        <br>
        <select name="book_id" id="book_id " disabled>
            <option value="">--Select--</option>
            <?php
                $bookList = $rentalObj->fetchAllBooks();
                foreach ($bookList as $list){
            ?>
                <option value="<?= $list['id'] ?>" <?= ($book_id == $list['id']) ? 'selected' : '' ?>><?= $list['title'] . ' by ' . $list['author']?></option>
            <?php
                }
            ?>
        </select>
        <br>
        <?php if(!empty($book_idErr)): ?>
            <span class="error"><?= $book_idErr ?></span><br>
        <?php endif; ?>

        <label for="borrow_date">Borrow Date</label><span class="error">*</span>
        <br>
        <input type="date" name="borrow_date" id="borrow_date" value="<?= $borrow_date ?>" disabled>
        <br>
        <?php if(!empty($borrow_dateErr)): ?>
            <span class="error"><?= $borrow_dateErr ?></span><br>
        <?php endif; ?>

        <label for="return_date">Return Date</label><span class="error">*</span>
        <br>
        <input type="date" name="return_date" id="return_date" value="<?= $return_date ?>">
        <br>
        <?php if(!empty($return_dateErr)): ?>
            <span class="error"><?= $return_dateErr ?></span><br>
        <?php endif; ?>

        <label for="remarks">Remarks</label><span class="error">*</span>
        <br>
        <textarea name="remarks" id="" cols="30" rows="10"><?= $remarks ?></textarea>
        <br>
        <?php if(!empty($remarksErr)): ?>
            <span class="error"><?= $remarksErr ?></span>
            <br>
        <?php endif; ?>

        <br>
        <input type="submit" value="Return Book">
    </form>
</body>
</html>
