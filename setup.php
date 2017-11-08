<?php
/*CREATE USER 'snils'@'localhost'
IDENTIFIED BY 'snils';
*/
require_once('dblogon.php');
//include_once("connect.php");

//using only localhost here, no username, pw or database as it just wants to connect to localhost, not any database at this point
try{
	$db = new PDO ("mysql: host='localhost';charset=utf8",'root', '');
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
		roomName varchar(4) PRIMARY KEY
	);

	CREATE TABLE IF NOT EXISTS bookings(
		bookingID INT AUTO_INCREMENT PRIMARY KEY,
		roomName varchar(4),
		dayBooked DATE,
		bookedFrom time,
		bookedTo time,
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
	VALUES ('admin', '2017-01-01', '$2y$10$.SmmhnJtIQxGRvuD59.JY.vJH2sClwNVKz3wwge2sC4DLXtdEFUoS');
	
	INSERT INTO rooms (roomName)
	VALUES ('A210');

	INSERT INTO rooms (roomName)
	VALUES ('B210');

	INSERT INTO rooms (roomName)
	VALUES ('E210');

	INSERT INTO rooms (roomName)
	VALUES ('C210');

	INSERT INTO bookings (roomName, dayBooked, username)
	VALUES ('A210', '2017-01-01', 'Henrik');
	";
if ($db->exec($query)===false){
	die('Can not INSERT INTO tables:' . $db->errorInfo()[2]);
}
?>