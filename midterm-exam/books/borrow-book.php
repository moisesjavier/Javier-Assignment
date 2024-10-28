<?php

require_once('functions.php');
require_once 'books-rental.class.php';

$borrower_name = $book_id = $borrow_date = $remarks = '';
$borrower_nameErr = $book_idErr = $borrow_dateErr = $remarksErr = '';

$rentalObj = new Rental();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    
    $borrower_name = clean_input($_POST['borrower_name']);
    $book_id = clean_input($_POST['book_id']);
    $borrow_date = clean_input($_POST['borrow_date']);
    $remarks = clean_input($_POST['remarks']);

    if(empty($borrower_name)){
        $borrower_nameErr = 'Borrower name is required';
    }

    if(empty($book_id)){
        $book_idErr = 'Please select a book';
    }

    if(empty($borrow_date)){
        $borrow_dateErr = 'Borrow Date is required';
    }else if($borrow_date < date("Y-m-d")){
        $borrow_dateErr = 'Borrow Date cant be in the past';
    }

    if(empty($borrower_nameErr) && empty($book_idErr) && empty($borrow_dateErr)){
        $rentalObj->borrower_name = $borrower_name;
        $rentalObj->book_id = $book_id;
        $rentalObj->borrow_date = $borrow_date;
        $rentalObj->remarks = $remarks;

        if($rentalObj->borrow()){
            header('Location: view-books.php');
        } else {
            echo 'Something went wrong when borrowing a book.';
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
        <input type="text" name="borrower_name" id="borrower_name" value="<?= $borrower_name ?>">
        <br>
        <?php if(!empty($borrower_nameErr)): ?>
            <span class="error"><?= $borrower_nameErr ?></span><br>
        <?php endif; ?>

        <label for="book_id ">Books</label><span class="error">*</span>
        <br>
        <select name="book_id" id="book_id ">
            <option value="">--Select--</option>
            <?php
                $bookList = $rentalObj->fetchBooks();
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
        <input type="date" name="borrow_date" id="borrow_date" value="<?= $borrow_date ?>">
        <br>
        <?php if(!empty($borrow_dateErr)): ?>
            <span class="error"><?= $borrow_dateErr ?></span><br>
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
        <input type="submit" value="Borrow Book">
    </form>
</body>
</html>
