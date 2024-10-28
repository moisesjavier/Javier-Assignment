<?php

require_once('functions.php');
require_once 'car-rental.class.php';

$id = $client_name = $rental_date = $car_id = $remarks = '';
$client_nameErr = $rental_dateErr = $car_idErr = $remarksErr = '';

$rentalObj = new Rental();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $record = $rentalObj->fetchRecord($id);
        if (!empty($record)) {
            $client_name = $record['client_name'];
            $car_id = $record['car_id'];
            $rental_date = date('Y-m-d', strtotime($record['rental_date']));
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
        $client_name = $record['client_name'];
        $car_id = $record['car_id'];
        $rental_date = date('Y-m-d', strtotime($record['rental_date']));
        $remarks = $record['remarks'];
    }
    $remarks = clean_input($_POST['remarks']);

    if(!empty($id)){
        $rentalObj->id = $id;
        $rentalObj->car_id = $car_id;
        $rentalObj->remarks = $remarks;

        if($rentalObj->cancel()){
            header('Location: view-rental.php');
        } else {
            echo 'Something went wrong when cancelling.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Equipment</title>
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

        <label for="client_name">Client Name</label><span class="error">*</span>
        <br>
        <input type="text" name="client_name" id="client_name" value="<?= $client_name ?>" disabled>
        <br>
        <?php if(!empty($client_nameErr)): ?>
            <span class="error"><?= $client_nameErr ?></span><br>
        <?php endif; ?>

        <label for="car_id">Car</label><span class="error">*</span>
        <br>
        <select name="car_id" id="car_id" disabled>
            <option value="">--Select--</option>
            <?php
                $carList = $rentalObj->fetchAllCars();
                foreach ($carList as $list){
            ?>
                <option value="<?= $list['id'] ?>" <?= ($car_id == $list['id']) ? 'selected' : '' ?>><?= $list['car_name'] . ' (' . $list['car_model'] . ')' ?></option>
            <?php
                }
            ?>
        </select>
        <br>
        <?php if(!empty($car_idErr)): ?>
            <span class="error"><?= $car_idErr ?></span><br>
        <?php endif; ?>

        <label for="rental_date">Rental Date</label><span class="error">*</span>
        <br>
        <input type="date" name="rental_date" id="rental_date" value="<?= $rental_date ?>" disabled>
        <br>
        <?php if(!empty($rental_dateErr)): ?>
            <span class="error"><?= $rental_dateErr ?></span><br>
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
        <input type="submit" value="Cancel Rental">
    </form>
</body>
</html>
