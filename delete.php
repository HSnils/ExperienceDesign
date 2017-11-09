<?php require_once('partials/phphead.php');
$bookingID = $_GET['id'];
//gets all rooms
$stmt = $db->prepare("
DELETE
FROM bookings
WHERE bookingID = '$bookingID'
AND username ='$userID'");
$stmt->execute();
	
	//redirects user to homepage
	$user->redirect('index.php');