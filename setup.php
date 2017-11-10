<?php
require_once('dblogon.php');

try { // Attempt a connection to the database
	$db = new PDO("mysql:host=".$db_host,$db_user,$db_pass);
	$sql = "CREATE DATABASE IF NOT EXISTS $db_database DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci";
	$db->exec($sql);
	$sql = "
		DROP TABLE IF EXISTS `bookings`;
		CREATE TABLE `bookings` (
		  `bookingID` int(11) NOT NULL,
		  `roomName` varchar(4) DEFAULT NULL,
		  `bookedTo` varchar(5) DEFAULT NULL,
		  `dayBooked` date DEFAULT NULL,
		  `bookedFrom` varchar(5) DEFAULT NULL,
		  `username` varchar(30) DEFAULT NULL,
		  `isThere` tinyint(1) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;

		INSERT INTO `bookings` (`bookingID`, `roomName`, `bookedTo`, `dayBooked`, `bookedFrom`, `username`, `isThere`) VALUES
		(1, 'A270', '15:00', '2017-01-01', '12:15', 'Henrik', 0),
		(2, 'K204', '15:00', '2017-01-01', '12:15', 'Anders', 0),
		(3, 'G219', '15:00', '2017-01-01', '12:15', 'Mats', 0),
		(4, 'B210', '15:00', '2017-01-01', '12:15', 'Cato', 0),
		(5, 'B214', '15:00', '2017-01-01', '12:15', 'Espen', 0);

		DROP TABLE IF EXISTS `isuserthere`;
		CREATE TABLE `isuserthere` (
		  `bookingID` int(11) NOT NULL,
		  `isUserHere` tinyint(1) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;

		DROP TABLE IF EXISTS `rooms`;
		CREATE TABLE `rooms` (
		  `roomName` varchar(4) NOT NULL,
		  `equipment` varchar(30) DEFAULT NULL,
		  `building` varchar(1) DEFAULT NULL,
		  `room` int(3) DEFAULT NULL,
		  `seats` int(3) DEFAULT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;

		INSERT INTO `rooms` (`roomName`, `equipment`, `building`, `room`, `seats`) VALUES
		('A161', NULL, 'A', 161, 3),
		('A162', NULL, 'A', 162, 4),
		('A266', NULL, 'A', 266, 4),
		('A267', NULL, 'A', 267, 4),
		('A269', NULL, 'A', 269, 5),
		('A270', NULL, 'A', 270, 5),
		('B210', NULL, 'B', 210, 25),
		('B211', NULL, 'B', 211, 25),
		('B212', NULL, 'B', 212, 25),
		('B213', NULL, 'B', 213, 25),
		('B214', NULL, 'B', 214, 25),
		('G209', NULL, 'G', 209, 4),
		('G213', NULL, 'G', 213, 4),
		('G218', NULL, 'G', 218, 4),
		('G219', NULL, 'G', 219, 4),
		('K110', NULL, 'K', 110, 4),
		('K204', NULL, 'K', 204, 4),
		('K206', NULL, 'K', 206, 4),
		('K210', NULL, 'K', 210, 4);

		DROP TABLE IF EXISTS `users`;
		CREATE TABLE `users` (
		  `username` varchar(30) NOT NULL,
		  `pw` varchar(120) DEFAULT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;


		INSERT INTO `users` (`username`, `pw`) VALUES
		('admin', '$2y$10$.SmmhnJtIQxGRvuD59.JY.vJH2sClwNVKz3wwge2sC4DLXtdEFUoS'),
		('Anders', '$2y$10$1Aj4qC8CMc3qMjQGQRy1HuKyIlz6v.P0xsjjXnmpm3o61nAW2YgpO'),
		('Cato', '$2y$10$1Aj4qC8CMc3qMjQGQRy1HuKyIlz6v.P0xsjjXnmpm3o61nAW2YgpO'),
		('Espen', '$2y$10$1Aj4qC8CMc3qMjQGQRy1HuKyIlz6v.P0xsjjXnmpm3o61nAW2YgpO'),
		('Henrik', '$2y$10$1Aj4qC8CMc3qMjQGQRy1HuKyIlz6v.P0xsjjXnmpm3o61nAW2YgpO'),
		('Mats', '$2y$10$1Aj4qC8CMc3qMjQGQRy1HuKyIlz6v.P0xsjjXnmpm3o61nAW2YgpO');

		ALTER TABLE `bookings`
		  ADD PRIMARY KEY (`bookingID`),
		  ADD KEY `FK_roomName` (`roomName`),
		  ADD KEY `FK_username` (`username`);

		ALTER TABLE `isuserthere`
		  ADD PRIMARY KEY (`bookingID`,`isUserHere`);

		ALTER TABLE `rooms`
		  ADD PRIMARY KEY (`roomName`);

		ALTER TABLE `users`
		  ADD PRIMARY KEY (`username`);

		ALTER TABLE `bookings`
		  MODIFY `bookingID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

		ALTER TABLE `bookings`
		  ADD CONSTRAINT `FK_roomName` FOREIGN KEY (`roomName`) REFERENCES `rooms` (`roomName`) ON DELETE CASCADE ON UPDATE CASCADE,
		  ADD CONSTRAINT `FK_username` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

		ALTER TABLE `isuserthere`
		  ADD CONSTRAINT `FK_booking` FOREIGN KEY (`bookingID`) REFERENCES `bookings` (`bookingID`) ON DELETE CASCADE ON UPDATE CASCADE;
		"
	;
	$db->exec($sql);
	$db = new PDO("mysql:host=$db_host;dbname=$db_database;charset=utf8",$db_user,$db_pass);
	$db->exec($sql);
	} catch (PDOException $e) { // If an error is detected
			die ('Unable to connect to database : ' . $e->getMessage());
	}
?>
