<?php

require_once('functions.php');
require_once 'car-rental.class.php';

$client_name = $car_id = $rental_date = $remarks = '';
$client_nameErr = $car_idErr = $rental_dateErr = $remarksErr = '';

$rentalObj = new Rental();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    
    $client_name = clean_input($_POST['client_name']);
    $car_id = clean_input($_POST['car_id']);
    $rental_date = clean_input($_POST['rental_date']);
    $remarks = clean_input($_POST['remarks']);

    if(empty($client_name)){
        $client_nameErr = 'Client name is required';
    }

    if(empty($car_id)){
        $car_idErr = 'Please select a car';
    }

    if(empty($rental_date)){
        $rental_dateErr = 'Rental Date is required';
    }else if($rental_date < date("Y-m-d")){
        $rental_dateErr = 'Rental Date cant be in the past';
    }

    if(empty($client_nameErr) && empty($car_idErr) && empty($rental_dateErr)){
        $rentalObj->client_name = $client_name;
        $rentalObj->car_id = $car_id;
        $rentalObj->rental_date = $rental_date;
        $rentalObj->remarks = $remarks;

        if($rentalObj->book()){
            header('Location: view-rental.php');
        } else {
            echo 'Something went wrong when renting a car.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rent Car</title>
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
        <input type="text" name="client_name" id="client_name" value="<?= $client_name ?>">
        <br>
        <?php if(!empty($client_nameErr)): ?>
            <span class="error"><?= $client_nameErr ?></span><br>
        <?php endif; ?>

        <label for="car_id">Car</label><span class="error">*</span>
        <br>
        <select name="car_id" id="car_id">
            <option value="">--Select--</option>
            <?php
                $carList = $rentalObj->fetchCars();
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
        <input type="date" name="rental_date" id="rental_date" value="<?= $rental_date ?>">
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
        <input type="submit" value="Rent Car">
    </form>
</body>
</html>
