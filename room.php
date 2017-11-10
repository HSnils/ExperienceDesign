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
	WHERE roomName = '$roomID'
    AND dayBooked = '$dateToday'");
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
            <span><?php echo $room['building'] ?></span>
        </p>
    </div>
    <div class="details-element">
        <p><strong>Room</strong>
            <span><?php echo $room['room'] ?></span>
        </p>
    </div>
    <div class="details-element">
        <p><strong>equipment</strong>
            <span><?php echo $room['equipment'] ?></span>
        </p>
    </div>
    <div class="details-element">
        <p><strong>Seats</strong>
            <span><?php echo $room['seats'] ?></span>
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
    $timeNowReal = date("H:i");
    $theRoom = $room['roomName'];
    $stmt = $db->query("
	SELECT *
	FROM bookings
	WHERE roomName = '$theRoom'
    AND bookedFrom = '$timeNow' ");
    $check = $stmt->fetch(PDO::FETCH_ASSOC);
    $bookedFrom = $check['bookedFrom'];
    $bookedTo = $check['bookedTo'];
    $overdue = substr($check['bookedFrom'], 0, -2).'15';
    if($timeNowReal >= $overdue && $check['isThere'] == 0 && $check['username'] !== $userID) {
        echo 'user did not show up, you can book now';
        $stmt = $db->prepare("
	DELETE
	FROM bookings
	WHERE roomName = '$roomID'
    AND dayBooked = '$dateToday'
    AND bookedFrom = '$bookedFrom'
    AND bookedTo = '$bookedTo' ");
    $stmt->execute();
    }
    if($check['username'] == $userID && $check['isThere'] == 0 && $timeNow == $check['bookedFrom'] ) {
        echo
        '<form action="" method="post">
            <input type="submit" class="btn" name="checkinSub" value="Check in">
        </form>';
    } elseif($check['isThere'] == 1 && $check['username'] == $userID) { echo
        '<form action="" method="post">
            <input type="submit" class="btn disabled" name="submit" value="You have checked in" disabled>
        </form>';
    } elseif($check['isThere'] == 1) { echo
        '<form action="" method="post">
            <input type="submit" class="btn disabled" name="submit" value="Room already reserved" disabled>
        </form>';
    } elseif($check['isThere'] == 0) {
        echo
        '<form action="" method="post">
            <input type="submit" class="btn" name="submit" value="Reserve room">
        </form>';
    } else {
        echo 'fuck you';
    }
    
    if(isset($_POST['checkinSub'])){
    $stmt = $db ->prepare ("
       UPDATE bookings
       SET isThere = 1
	   WHERE roomName = '$roomID'
       AND dayBooked = '$dateToday'
       AND bookedFrom = '$bookedFrom'
       AND bookedTo = '$bookedTo' ");  
    $stmt->execute();
        header("Refresh:0");
}
    ?>
</div>
</div>
<script type="text/javascript" src="mazemap/script.js"></script>