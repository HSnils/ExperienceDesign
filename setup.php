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
	
	INSERT INTO users (username, pw)
	VALUES ('Henrik', '$2y$10$1Aj4qC8CMc3qMjQGQRy1HuKyIlz6v.P0xsjjXnmpm3o61nAW2YgpO');

	INSERT INTO users (username, pw)
	VALUES ('Anders',  '$2y$10$1Aj4qC8CMc3qMjQGQRy1HuKyIlz6v.P0xsjjXnmpm3o61nAW2YgpO');

	INSERT INTO users (username, pw)
	VALUES ('Mats',  '$2y$10$1Aj4qC8CMc3qMjQGQRy1HuKyIlz6v.P0xsjjXnmpm3o61nAW2YgpO');

	INSERT INTO users (username, pw)
	VALUES ('Cato',  '$2y$10$1Aj4qC8CMc3qMjQGQRy1HuKyIlz6v.P0xsjjXnmpm3o61nAW2YgpO');

	INSERT INTO users (username,  pw)
	VALUES ('Espen',  '$2y$10$1Aj4qC8CMc3qMjQGQRy1HuKyIlz6v.P0xsjjXnmpm3o61nAW2YgpO');
	
	INSERT INTO users (username,  pw)
	VALUES ('admin',  '$2y$10$.SmmhnJtIQxGRvuD59.JY.vJH2sClwNVKz3wwge2sC4DLXtdEFUoS');
	
	INSERT INTO `rooms`(`roomName`, `building`, `room`, `seats`) 
	VALUES ('A266','A',266, 4);

	INSERT INTO `rooms`(`roomName`, `building`, `room`, `seats`) 
	VALUES ('A269','A',269, 5);

	INSERT INTO `rooms`(`roomName`, `building`, `room`, `seats`) 
	VALUES ('A270','A',270, 5);

	INSERT INTO `rooms`(`roomName`, `building`, `room`, `seats`) 
	VALUES ('A161','A',161, 3);

	INSERT INTO `rooms`(`roomName`, `building`, `room`, `seats`) 
	VALUES ('A162','A',162, 4);

	INSERT INTO `rooms`(`roomName`, `building`, `room`, `seats`) 
	VALUES ('B210','B',210,25);

	INSERT INTO `rooms`(`roomName`, `building`, `room`, `seats`) 
	VALUES ('B211','B',211, 25);

	INSERT INTO `rooms`(`roomName`, `building`, `room`, `seats`) 
	VALUES ('B212','B',212, 25);

	INSERT INTO `rooms`(`roomName`, `building`, `room`, `seats`) 
	VALUES ('B213','B',213, 25);

	INSERT INTO `rooms`(`roomName`, `building`, `room`, `seats`) 
	VALUES ('B214','B',214, 25);

	INSERT INTO `rooms`(`roomName`, `building`, `room`, `seats`) 
	VALUES ('G219','G',219,4);

	INSERT INTO `rooms`(`roomName`, `building`, `room`, `seats`) 
	VALUES ('G209','G',209,4);

	INSERT INTO `rooms`(`roomName`, `building`, `room`, `seats`) 
	VALUES ('G213','G',213,4);

	INSERT INTO `rooms`(`roomName`, `building`, `room`, `seats`) 
	VALUES ('G218','G',218,4);

	INSERT INTO `rooms`(`roomName`, `building`, `room`, `seats`) 
	VALUES ('K210','K',210,4);

	INSERT INTO `rooms`(`roomName`, `building`, `room`, `seats`) 
	VALUES ('K204','K',204,4);

	INSERT INTO `rooms`(`roomName`, `building`, `room`, `seats`) 
	VALUES ('K206','K',206,4);

	INSERT INTO `rooms`(`roomName`, `building`, `room`, `seats`) 
	VALUES ('K110','K',110,4);

	INSERT INTO `rooms`(`roomName`, `building`, `room`, `seats`) 
	VALUES ('A267','A',267,4);

	INSERT INTO bookings (roomName, dayBooked, bookedFrom, bookedTo, username)
	VALUES ('A270', '2017-01-01', '12:15', '15:00', 'Henrik');

	INSERT INTO bookings (roomName, dayBooked, bookedFrom, bookedTo, username)
	VALUES ('K204', '2017-01-01', '12:15', '15:00', 'Anders');

	INSERT INTO bookings (roomName, dayBooked, bookedFrom, bookedTo, username)
	VALUES ('G219', '2017-01-01', '12:15', '15:00', 'Mats');
	
	INSERT INTO bookings (roomName, dayBooked, bookedFrom, bookedTo, username)
	VALUES ('B210', '2017-01-01', '12:15', '15:00', 'Cato');

	INSERT INTO bookings (roomName, dayBooked, bookedFrom, bookedTo, username)
	VALUES ('B214', '2017-01-01', '12:15', '15:00', 'Espen');
	";


if ($db->exec($query)===false){
	die('Can not INSERT INTO tables:' . $db->errorInfo()[2]);
}
?>