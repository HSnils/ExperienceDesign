<?php
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
	$stmt = $db->prepare("INSERT INTO bookings(roomName, dayBooked, bookedFrom, bookedTo, username, isThere)
		VALUES('$roomName','$dayBooked', '$timeFrom', '$timeTo', '$userID',0)");

	$stmt->execute();
	
	//redirects user to homepage
	//$user->redirect('');
	header("Refresh:0");
}

/* big ugly button */
    $timeNow = date("H").":00";
    $timeNowReal = date("H:i");
    $theRoom = $room['roomName'];
    $btnDisabled = '';
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
            $btnValue = 'Check in';
            $btnName = 'checkinSub';
    } elseif($check['isThere'] == 1 && $check['username'] == $userID) {
            $btnValue = 'You have checked in';
            $btnName = 'submit';
            $btnDisabled = 'disabled';
    } elseif($check['isThere'] == 1) {
            $btnValue = 'Room already reserved';
            $btnName = 'submit';
            $btnDisabled = 'disabled';
    } elseif($check['isThere'] == 0) {
            $btnValue = 'Reserve room';
            $btnName = 'submit';
    } else {
        echo 'not sure when this happens';
    }