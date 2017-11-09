<?php 
require_once('partials/phphead.php');
require_once('partials/header.php');

$roomID = $_GET['id'];
//gets the room
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
//booking of rooms
if(isset($_POST['submit'])){
	
	//Puts username into variables for use in SQL statement
	$dayBooked = date("Y/m/d");
	$timeFrom = date("H").":00";
	$timeTo = date("H") + 1 .":00";
	$roomName = $room['roomName'];
	$building = $room['building'];

	//puts values into statement
	$stmt = $db->prepare("
		INSERT INTO bookings(roomName, dayBooked, bookedFrom, bookedTo, username)
		VALUES('$roomName', '$dayBooked', '$timeFrom', '$timeTo', '$userID')
		");

	$stmt->execute();
	
	//redirects user to homepage
	//$user->redirect('');
	header("Refresh:0");
}
?>
  <div id="map" class="mazemap">

	</div>
<div class="findroombox">
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
                <p class="date">'. $row['bookedFrom'].' - '. $row['bookedTo'].'</p>
            </div>';
            }
    }
    ?>
  
   <?php 
    $timeNow = date("H").":00";
    $theRoom = $room['roomName'];
    $check = $db->query("
	SELECT *
	FROM bookings
	WHERE roomName = '$theRoom'
    AND bookedFrom = '$timeNow'");
    if($check->rowCount() > 0) { echo
        '<form action="" method="post">
            <input type="submit" class="btn disabled" name="submit" value="Room already reserved" disabled>
        </form>';
    } else {
        echo
        '<form action="" method="post">
            <input type="submit" class="btn" name="submit" value="Reserve room">
        </form>';
    }
    ?>
</div>
</div>
<script type="text/javascript" src="mazemap/script.js"></script>

