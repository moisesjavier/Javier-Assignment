<?php

require_once('functions.php');
require_once 'equipment-rental.class.php';

$renter_name = $equipment_id = $rental_date = $remarks = '';
$renter_nameErr = $equipment_idErr = $rental_dateErr = $remarksErr = '';

$rentalObj = new Rental();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    
    $renter_name = clean_input($_POST['renter_name']);
    $equipment_id = clean_input($_POST['equipment_id']);
    $rental_date = clean_input($_POST['rental_date']);
    $remarks = clean_input($_POST['remarks']);

    if(empty($renter_name)){
        $renter_nameErr = 'Renter name is required';
    }

    if(empty($equipment_id)){
        $equipment_idErr = 'Please select an equipment';
    }

    if(empty($rental_date)){
        $rental_dateErr = 'Rental Date is required';
    }else if($rental_date < date("Y-m-d")){
        $rental_dateErr = 'Rental Date cant be in the past';
    }

    if(empty($renter_nameErr) && empty($equipment_idErr) && empty($rental_dateErr)){
        $rentalObj->renter_name = $renter_name;
        $rentalObj->equipment_id = $equipment_id;
        $rentalObj->rental_date = $rental_date;
        $rentalObj->remarks = $remarks;

        if($rentalObj->rent()){
            header('Location: view-rental.php');
        } else {
            echo 'Something went wrong when renting an equipment.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rent Equipment</title>
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

        <label for="renter_name">Renter Name</label><span class="error">*</span>
        <br>
        <input type="text" name="renter_name" id="renter_name" value="<?= $renter_name ?>">
        <br>
        <?php if(!empty($renter_nameErr)): ?>
            <span class="error"><?= $renter_nameErr ?></span><br>
        <?php endif; ?>

        <label for="equipment_id">Equipment</label><span class="error">*</span>
        <br>
        <select name="equipment_id" id="equipment_id">
            <option value="">--Select--</option>
            <?php
                $equipmentsList = $rentalObj->fetchEquipments();
                foreach ($equipmentsList as $list){
            ?>
                <option value="<?= $list['id'] ?>" <?= ($equipment_id == $list['id']) ? 'selected' : '' ?>><?= $list['equipment_name'] ?></option>
            <?php
                }
            ?>
        </select>
        <br>
        <?php if(!empty($equipment_idErr)): ?>
            <span class="error"><?= $equipment_idErr ?></span><br>
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
        <input type="submit" value="Rent Equipment">
    </form>
</body>
</html>
