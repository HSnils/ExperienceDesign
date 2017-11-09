<?php require_once('partials/header.php');
	
$roomID = $_GET['id'];
//gets all rooms
$stmt = $db->prepare("
SELECT *
FROM rooms
WHERE roomName = '$roomID'");
$stmt->execute();
$room = $stmt->fetch(PDO::FETCH_ASSOC);
// printf($row['Building']);

// get all bookings
$stmt = $db->prepare("
SELECT *
FROM bookings
WHERE roomName = '$roomID'");
$stmt->execute();
    $numRows = $stmt->rowCount();
?>

    <div id="main">
        <a href="https://placeholder.com"><img src="http://via.placeholder.com/600x300"></a>
    </div>

    <div class="details">
        <div class="details-element">
            <p><strong>Building</strong>
                <?php echo $room['building'] ?>
            </p>
        </div>
        <div class="details-element">
            <p><strong>Room</strong>
                <?php echo $room['room'] ?>
            </p>
        </div>
        <div class="details-element">
            <p><strong>equipment</strong>
                <?php echo $room['equipment'] ?>
            </p>
        </div>
        <div class="details-element">
            <p><strong>Seats</strong>
                <?php echo $room['seats'] ?>
            </p>
        </div>
    </div>

    <div class="booking">

    <?php
    //writes out all the rooms into tablerows etc
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        if($numRows == 0){
        echo '<p style="text-align: center;">No rooms avalible!</p>';
        }else{
            //using substr on the booking time to take away seconds
            echo '
            <div class="booking-element">
                <p>'. $row['username'].'</p>
                <p class="date">'. substr($row['bookedFrom'],0,-3).' - '. substr($row['bookedTo'],0,-3).'</p>
            </div>';
            }
    }
    ?>
            <!-- if room availible - button clickable -->
            <a href="" class="btn">Book now</a>
    </div>