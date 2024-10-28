<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental</title>
    <style>
        /* Styling for the search results */
        p.search {
            text-align: center;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <a href="book-venue.php">Book Venue</a>
    
    <?php
        require_once 'venue-rental.class.php';
        require_once 'functions.php';

        $rentalObj = new Rental();

        $keyword = '';
        
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $keyword = clean_input($_POST['keyword']);
        }

        $array = $rentalObj->showAll($keyword);
    ?>

    <form action="" method="post">
        <label for="keyword">Search</label>
        <input type="text" name="keyword" id="keyword" value="<?= $keyword ?>">
        <input type="submit" value="Search" name="search" id="search">
    </form>

    <table border="1">
        <tr>
            <th>No.</th> 
            <th>Organizer</th>
            <th>Venue</th>
            <th>Event Date</th>
            <th>Remarks</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        
        <?php
        $i = 1;
        if (empty($array)) {
        ?>
            <tr>
                <td colspan="7"><p class="search">No rental found.</p></td>
            </tr>
        <?php
        }else{
            foreach ($array as $arr) {
                $status = $arr['status'];
            ?>
            <tr>
                <td><?= $i ?></td>
                <td><?= $arr['organizer'] ?></td>
                <td><?= $arr['venue_name'] ?></td>
                <td><?= date('m-d-Y', strtotime($arr['event_date'])) ?></td>
                <td><?= $arr['remarks'] ?></td>
                <td><?= $arr['status'] ?></td>
                <?php
                if ($status == 'Booked'){
                ?>
                    <td>
                        <a href="complete-book.php?id=<?= $arr['rental_id'] ?>">Complete</a>
                    </td>
                <?php
                }
                ?>
            </tr>
            <?php
                $i++;
            }
        }
        ?>
    </table>
</body>
</html>
