<?php

require_once('functions.php');
require_once('computer-rental.class.php');

$id = $customer_name = $computer_unit_id = $start_time = $end_time = $remarks = '';
$customer_nameErr = $computer_unit_idErr = $start_timeErr = $end_timeErr = $remarksErr = '';

$rentalObj = new Rental();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $record = $rentalObj->fetchRecord($id);
        if (!empty($record)) {
            $customer_name = $record['customer_name'];
            $computer_unit_id = $record['computer_unit_id'];
            $start_time = date('Y-m-d\TH:i', strtotime($record['start_time']));
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
        $customer_name = $record['customer_name'];
        $computer_unit_id = $record['computer_unit_id'];
        $start_time = date('Y-m-d\TH:i', strtotime($record['start_time']));
        $remarks = $record['remarks'];
    }
    $end_time = date('Y-m-d\TH:i', strtotime($_POST['end_time']));
    $remarks = clean_input($_POST['remarks']);

    if(empty($end_time)){
        $end_timeErr = 'End Time is required';
    }else if($end_time < $start_time){
        $end_timeErr = 'End Time cant be prior to start time';
    }

    if(empty($end_timeErr)){
        $rentalObj->id = $id;
        $rentalObj->computer_unit_id = $computer_unit_id;
        $rentalObj->end_time = $end_time;
        $rentalObj->remarks = $remarks;

        if($rentalObj->complete()){
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
        <input type="text" name="customer_name" id="customer_name" value="<?= $customer_name ?>" disabled>
        <br>
        <?php if(!empty($customer_nameErr)): ?>
            <span class="error"><?= $customer_nameErr ?></span><br>
        <?php endif; ?>

        <label for="computer_unit_id">Computer</label><span class="error">*</span>
        <br>
        <select name="computer_unit_id" id="computer_unit_id" disabled>
            <option value="">--Select--</option>
            <?php
                $computerList = $rentalObj->fetchAllComputerUnits();
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
        <input type="datetime-local" name="start_time" id="start_time" value="<?= $start_time ?>" disabled>
        <br>
        <?php if(!empty($start_timeErr)): ?>
            <span class="error"><?= $start_timeErr ?></span><br>
        <?php endif; ?>

        <label for="end_time">End Time</label><span class="error">*</span>
        <br>
        <input type="datetime-local" name="end_time" id="end_time" value="<?= $end_time ?>" >
        <br>
        <?php if(!empty($end_timeErr)): ?>
            <span class="error"><?= $end_timeErr ?></span><br>
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
        <input type="submit" value="End Computer Time">
    </form>
</body>
</html>
