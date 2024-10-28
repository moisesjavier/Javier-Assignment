<?php

require_once('functions.php');
require_once('computer-rental.class.php');

$customer_name = $computer_unit_id = $start_time = $remarks = '';
$customer_nameErr = $computer_unit_idErr = $start_timeErr = $remarksErr = '';

$rentalObj = new Rental();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    
    $customer_name = clean_input($_POST['customer_name']);
    $computer_unit_id = clean_input($_POST['computer_unit_id']);
    $start_time = clean_input($_POST['start_time']);
    $remarks = clean_input($_POST['remarks']);

    if(empty($customer_name)){
        $customer_nameErr = 'Customer name is required';
    }

    if(empty($computer_unit_id)){
        $computer_unit_idErr = 'Please select a computer';
    }

    if(empty($start_time)){
        $start_timeErr = 'Start Time is required';
    }else if($start_time < date("Y-m-d\TH:i")){
        $start_timeErr = 'Start Time cant be in the past';
    }

    if(empty($customer_nameErr) && empty($computer_unit_idErr) && empty($start_timeErr)){
        $rentalObj->customer_name = $customer_name;
        $rentalObj->computer_unit_id = $computer_unit_id;
        $rentalObj->start_time = $start_time;
        $rentalObj->remarks = $remarks;

        if($rentalObj->rent()){
            header('Location: view-computer.php');
        } else {
            echo 'Something went wrong when renting a computer.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rent Computer</title>
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

        <label for="customer_name">Customer Name</label><span class="error">*</span>
        <br>
        <input type="text" name="customer_name" id="customer_name" value="<?= $customer_name ?>">
        <br>
        <?php if(!empty($customer_nameErr)): ?>
            <span class="error"><?= $customer_nameErr ?></span><br>
        <?php endif; ?>

        <label for="computer_unit_id">Computer</label><span class="error">*</span>
        <br>
        <select name="computer_unit_id" id="computer_unit_id">
            <option value="">--Select--</option>
            <?php
                $computerList = $rentalObj->fetchComputerUnits();
                foreach ($computerList as $list){
            ?>
                <option value="<?= $list['id'] ?>" <?= ($computer_unit_id == $list['id']) ? 'selected' : '' ?>><?= $list['unit_name'] ?></option>
            <?php
                }
            ?>
        </select>
        <br>
        <?php if(!empty($computer_unit_idErr)): ?>
            <span class="error"><?= $computer_unit_idErr ?></span><br>
        <?php endif; ?>

        <label for="start_time">Start Time</label><span class="error">*</span>
        <br>
        <input type="datetime-local" name="start_time" id="start_time" value="<?= $start_time ?>">
        <br>
        <?php if(!empty($start_timeErr)): ?>
            <span class="error"><?= $start_timeErr ?></span><br>
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
        <input type="submit" value="Rent Computer">
    </form>
</body>
</html>
