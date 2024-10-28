<?php

require_once('functions.php');
require_once('venue-rental.class.php');

$did = $organizer = $venue_id = $event_date = $remarks = '';
$organizerErr = $venue_idErr = $event_dateErr = $remarksErr = '';

$rentalObj = new Rental();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $record = $rentalObj->fetchRecord($id);
        if (!empty($record)) {
            $organizer = $record['organizer'];
            $venue_id = $record['venue_id'];
            $event_date = date('Y-m-d', strtotime($record['event_date']));
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
        $organizer = $record['organizer'];
        $venue_id = $record['venue_id'];
        $event_date = date('Y-m-d', strtotime($record['event_date']));
        $remarks = $record['remarks'];
    }
    $remarks = clean_input($_POST['remarks']);

    if(!empty($id)){
        $rentalObj->id = $id;
        $rentalObj->remarks = $remarks;

        if($rentalObj->complete()){
            header('Location: view-rental.php');
        } else {
            echo 'Something went wrong when completing a book.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Book</title>
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
        <input type="text" name="organizer" id="organizer" value="<?= $organizer ?>" disabled>
        <br>
        <?php if(!empty($organizerErr)): ?>
            <span class="error"><?= $organizerErr ?></span><br>
        <?php endif; ?>

        <label for="venue_id">Venue</label><span class="error">*</span>
        <br>
        <select name="venue_id" id="venue_id" disabled>
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
        <input type="date" name="event_date" id="event_date" value="<?= $event_date ?>" disabled>
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
        <input type="submit" value="Complete Booking">
    </form>
</body>
</html>
