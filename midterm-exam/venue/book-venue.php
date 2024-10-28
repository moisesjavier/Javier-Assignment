<?php

require_once('functions.php');
require_once('venue-rental.class.php');

$organizer = $venue_id = $event_date = $remarks = '';
$organizerErr = $venue_idErr = $event_dateErr = $remarksErr = '';

$rentalObj = new Rental();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    
    $organizer = clean_input($_POST['organizer']);
    $venue_id = clean_input($_POST['venue_id']);
    $event_date = clean_input($_POST['event_date']);
    $remarks = clean_input($_POST['remarks']);

    if(empty($organizer)){
        $organizerErr = 'Organizer name is required';
    }

    if(empty($venue_id)){
        $venue_idErr = 'Please select a venue to book';
    }

    if(empty($event_date)){
        $event_dateErr = 'Event Date is required';
    }else if($event_date < date("Y-m-d")){
        $event_dateErr = 'Event date cant be in the past';
    }else if($rentalObj->isVenueAvailable($venue_id, $event_date)){
        $event_dateErr = 'Event date is already booked';
    }

    if(empty($organizerErr) && empty($venue_idErr) && empty($event_dateErr)){
        $rentalObj->organizer = $organizer;
        $rentalObj->venue_id = $venue_id;
        $rentalObj->event_date = $event_date;
        $rentalObj->remarks = $remarks;

        if($rentalObj->book()){
            header('Location: view-rental.php');
        } else {
            echo 'Something went wrong when booking a venue.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Venue</title>
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

        <label for="organizer">Organizer Name</label><span class="error">*</span>
        <br>
        <input type="text" name="organizer" id="organizer" value="<?= $organizer ?>">
        <br>
        <?php if(!empty($organizerErr)): ?>
            <span class="error"><?= $organizerErr ?></span><br>
        <?php endif; ?>

        <label for="venue_id">Venue</label><span class="error">*</span>
        <br>
        <select name="venue_id" id="venue_id">
            <option value="">--Select--</option>
            <?php
                $venueList = $rentalObj->fetchAllVenues();
                foreach ($venueList as $list){
            ?>
                <option value="<?= $list['id'] ?>" <?= ($venue_id == $list['id']) ? 'selected' : '' ?>><?= $list['venue_name'] ?></option>
            <?php
                }
            ?>
        </select>
        <br>
        <?php if(!empty($venue_idErr)): ?>
            <span class="error"><?= $venue_idErr ?></span><br>
        <?php endif; ?>

        <label for="event_date">Event Date</label><span class="error">*</span>
        <br>
        <input type="date" name="event_date" id="event_date" value="<?= $event_date ?>">
        <br>
        <?php if(!empty($event_dateErr)): ?>
            <span class="error"><?= $event_dateErr ?></span><br>
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
        <input type="submit" value="Book Venue">
    </form>
</body>
</html>
