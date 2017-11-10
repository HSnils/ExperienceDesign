<?php
/*CREATE USER 'snils'@'localhost'
IDENTIFIED BY 'snils';
*/
require_once('dblogon.php');
//include_once("connect.php");

//using only localhost here, no username, pw or database as it just wants to connect to localhost, not any database at this point
try{
	$db = new PDO("mysql:host=$db_host;dbname=$db_database;charset=utf8",$db_user, $db_pass);
} catch(PDOException $e){
	die ("Error(Could not connect): ".$e->getMessage());
};

//uses $db_database variable from dblogon
//checks if a database with name $db_database exsists, if it does then deletes it
$query = 'DROP DATABASE IF EXISTS '.$db_database.'';
if ($db->exec($query)===false){
	die('Query failed(1):' . $db->errorInfo()[2]);
};

//checks if a database with name $db_database exsists, if it does then creates it
$query = 'CREATE DATABASE IF NOT EXISTS '.$db_database.'';
if ($db->exec($query)===false){
	die('Query failed(1):' . $db->errorInfo()[2]);
};

//Select the database
$query = 'USE '.$db_database.'';
if ($db->exec($query)===false){
	die('Can not select database:' . $db->errorInfo()[2]);
}

//CREATES USERS TABLE AND NEWS TABLE (using cascade FK only on update, to not delete the news of accounts that changed name)
$query = 
	"CREATE TABLE IF NOT EXISTS users(
		username varchar(30) PRIMARY KEY,
		bdate DATE,
		pw varchar(120)
	);

	CREATE TABLE IF NOT EXISTS rooms(
		roomName varchar(4) PRIMARY KEY,
		equipment varchar(30),
		building varchar(1),
		room INT(3),
		seats INT(3)

	);

	CREATE TABLE IF NOT EXISTS bookings(
		bookingID INT AUTO_INCREMENT PRIMARY KEY,
		roomName varchar(4),
		bookedTo varchar(5),
		dayBooked DATE,
		bookedFrom varchar(5),
		username varchar(30),

		CONSTRAINT `FK_roomName` FOREIGN KEY (`roomName`) 
		REFERENCES `rooms`(`roomName`) 
		ON DELETE CASCADE ON UPDATE CASCADE,

		CONSTRAINT `FK_username` FOREIGN KEY (`username`) 
		REFERENCES `users`(`username`) 
		ON DELETE CASCADE ON UPDATE CASCADE
	);

	CREATE TABLE IF NOT EXISTS isUserThere(
		bookingID INT,
		isUserHere boolean,

		PRIMARY KEY (bookingID, isUserHere),

		CONSTRAINT FK_booking FOREIGN KEY (bookingID)
		REFERENCES bookings(bookingID)
		ON DELETE CASCADE ON UPDATE CASCADE
	);
	"
;
if ($db->exec($query)===false){
	die('Can not create tables:' . $db->errorInfo()[2]);
}
//in the ratings table i would have liked to use ON DELETE SET NULL, but i can not since username is primarykey, i was considering making ratingID, but opted to not do so as it might be usefull to automatichally delete all the ratings a user has made, if for example they were rating everything 0 and thats why they got banned, could be changed though easily if needed.



//makes 2 dummy accounts with username "Henrik" with password test and "admin" with password "admin" 


$query =
	"
	
	
	INSERT INTO users (username, bdate, pw)
	VALUES ('Henrik', '2017-01-01', '$2y$10$1Aj4qC8CMc3qMjQGQRy1HuKyIlz6v.P0xsjjXnmpm3o61nAW2YgpO');

	INSERT INTO users (username, bdate, pw)
	VALUES ('Anders', '2017-01-01', '$2y$10$1Aj4qC8CMc3qMjQGQRy1HuKyIlz6v.P0xsjjXnmpm3o61nAW2YgpO');

	INSERT INTO users (username, bdate, pw)
	VALUES ('Mats', '2017-01-01', '$2y$10$1Aj4qC8CMc3qMjQGQRy1HuKyIlz6v.P0xsjjXnmpm3o61nAW2YgpO');

	INSERT INTO users (username, bdate, pw)
	VALUES ('Cato', '2017-01-01', '$2y$10$1Aj4qC8CMc3qMjQGQRy1HuKyIlz6v.P0xsjjXnmpm3o61nAW2YgpO');

	INSERT INTO users (username, bdate, pw)
	VALUES ('Espen', '2017-01-01', '$2y$10$1Aj4qC8CMc3qMjQGQRy1HuKyIlz6v.P0xsjjXnmpm3o61nAW2YgpO');
	
	INSERT INTO users (username, bdate, pw)
	VALUES ('admin', '2017-01-01', '$2y$10$.SmmhnJtIQxGRvuD59.JY.vJH2sClwNVKz3wwge2sC4DLXtdEFUoS');
	
	INSERT INTO `rooms`(`roomName`, `building`, `room`, `seats`) 
	VALUES ('A266','A',266, 4);

	INSERT INTO `rooms`(`roomName`, `building`, `room`, `seats`) 
	VALUES ('A210','A',210, 3);

	INSERT INTO `rooms`(`roomName`, `building`, `room`, `seats`) 
	VALUES ('B266','B',266,4);

	INSERT INTO `rooms`(`roomName`, `building`, `room`, `seats`) 
	VALUES ('B210','B',210, 4);

	INSERT INTO `rooms`(`roomName`, `building`, `room`, `seats`) 
	VALUES ('G266','G',266,4);

	INSERT INTO `rooms`(`roomName`, `building`, `room`, `seats`) 
	VALUES ('K266','K',266, 72);

	INSERT INTO bookings (roomName, dayBooked, bookedFrom, bookedTo, username)
	VALUES ('A210', '2017-01-01', '12:15', '15:00', 'Henrik');

	INSERT INTO bookings (roomName, dayBooked, bookedFrom, bookedTo, username)
	VALUES ('K266', '2017-01-01', '12:15', '15:00', 'Anders');

	INSERT INTO bookings (roomName, dayBooked, bookedFrom, bookedTo, username)
	VALUES ('G266', '2017-01-01', '12:15', '15:00', 'Mats');
	
	INSERT INTO bookings (roomName, dayBooked, bookedFrom, bookedTo, username)
	VALUES ('B210', '2017-01-01', '12:15', '15:00', 'Cato');

	INSERT INTO bookings (roomName, dayBooked, bookedFrom, bookedTo, username)
	VALUES ('B266', '2017-01-01', '12:15', '15:00', 'Espen');
	";


if ($db->exec($query)===false){
	die('Can not INSERT INTO tables:' . $db->errorInfo()[2]);
}
?>