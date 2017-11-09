<?php require_once('partials/header.php');
$roomID = $_GET['id'];
//gets all rooms
$stmt = $db->prepare("
DELETE
FROM bookings
WHERE roomName = '$roomID'
AND username ='$userID'");
$stmt->execute();
	
	//redirects user to homepage
	$user->redirect('index.php');