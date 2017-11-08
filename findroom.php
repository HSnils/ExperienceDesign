<?php
require_once 'connect.php';

if(!$user->is_loggedin()){
	$user->redirect('login.php');

}else if($user->is_loggedin()){
	//gets username from the session
	$userID = $_SESSION['username'];

	//changes the username to allways appear in uppercase when printed (and using th e variable)
	$printableUsername = strtoupper($userID);
}

//Funtion to refresh, uses another type of way than redirect as redirect cant be used in the middle of the code
/*function refresh(){
	echo "<meta http-equiv='refresh' content='0'>";
}*/


if(isset($_POST['submit'])){
	
	//Puts username into variables for use in SQL statement
	$dayBooked =htmlentities($_POST['date']);
	$timeFrom = htmlentities($_POST['timeFrom']);
	$timeTo = htmlentities($_POST['timeTo']);
	$room = htmlentities($_POST['room']);

	$stmt = $db->prepare("
		INSERT INTO bookings(roomName, dayBooked, bookedFrom, bookedTo, username)
		VALUES('$room', '$dayBooked', '$timeFrom:02', '$timeTo:02', '$userID')
		");

	$stmt->execute();
	
	$user->redirect('index.php');
	
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--Jquery and UI-->
    <script src="jquery-ui/external/jquery/jquery.js"></script>
    <link rel="stylesheet" href="jquery-ui/jquery-ui.min.css">
    <link rel="stylesheet" href="jquery-ui/jquery-ui.structure.min.css">
    <link rel="stylesheet" href="jquery-ui/jquery-ui.theme.min.css">
    <script src="jquery-ui/jquery-ui.min.js"></script>
    
    <link rel="stylesheet" href="css/main.css?<?php echo time(); ?>">
    <link rel="stylesheet" href="css/header.css?<?php echo time(); ?>">
    <link rel="stylesheet" href="css/fonts.css?<?php echo time(); ?>">
</head>
<body>
	<!--Menu-->
	
	<header>
        <h3 id="logo" href="index.php">Find Room</h3>
        <a id="logout" href="logout.php?logout=true">LOG OUT</a>
		<a id="profile" href="profile.php"> <?php echo $printableUsername ?></a>
	</header>
    <div id="main">
        <a href="https://placeholder.com"><img src="http://via.placeholder.com/360x250"></a>
    </div>
	
	


	<table>
		<thead>
			
		</thead>
		<tbody>
			<?php
			//gets all rooms 
			$stmt = $db->prepare("
				SELECT *
				FROM rooms
				ORDER BY roomName ASC");
			$stmt->execute();
			$numRows = $stmt->rowCount();

			//writes out all the news, one by one in the order they are selected to be displayed by
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

				if($numRows == 0){
					echo '<p style="text-align: center;">No rooms avalible!</p>';
				}else{
					echo '

					<tr>
						<td class="roomDistance"> 10m </td>
						<td><b>'. $row['roomName'].'</b></td>
						<td class="roomArrow"><b>--></b><td>
					</tr>';
					
				}
			}
		?>
		</tbody>
	</table>

	<div id="reserve">
		<form action="findroom.php" method="post">

			<label for="date">Date</label>
			<input type="date" name="date" id="date" value="" required>
			
			<br>
			<label for="timeFrom">From</label>
			<input id="timeFrom" type="time" value="" name="timeFrom" required>

			<label for="timeTo">To</label>
			<input id="timeTo" type="time" value="" name="timeTo" required>

			<br>
			
			<label for="room">Room</label>
			<input id="room" type="text" value="" name="room" required>
			
			<br>

			<label for="regSubmit" hidden> RESERVE </label> 
			<input class="buttonclass" id="resSubmit" type="submit" name="submit" value="RESERVE">
		</form>
	</div>


	<div class="linkbox">
		<a class="linkbutton">RESERVE FUTURE </a>
	</div>

	<script>
	
	function allCaps(a){
		return a.toUpperCase;
	}
	</script>
</body>
</html>