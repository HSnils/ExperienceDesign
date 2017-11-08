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
        <h3 id="logo" href="index.php">NTNU booking</h3>
        <a id="logout" href="logout.php?logout=true">LOG OUT</a>
		<a id="profile" href="profile.php"> <?php echo $printableUsername ?></a>
	</header>
    <div id="main">
        <a href="https://placeholder.com"><img src="http://via.placeholder.com/360x250"></a>
    </div>
	
	<div>
		<h2>My bookings</h2>
		<table>
			<thead>
				<td>Room</td>
				<td>Date</td>
				<td>Time</td>
			</thead>
			<tbody>
				<?php
					//gets all rooms 
					$stmt = $db->prepare("
						SELECT *
						FROM bookings
						WHERE username = '$userID'
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
								<td>'. $row['roomName'].'</td>
								<td>'. $row['dayBooked'].'</td>
								<td>'. $row['bookedFrom'].'-'. $row['bookedTo'].'</td>
							</tr>';
							
						}
					}
				?>
			</tbody>
		</table>
	</div>

	<div class="linkbox">
		<a class="linkbutton" href="findroom.php">FIND ROOM </a>
	</div>
	<script>
	
	function allCaps(a){
		return a.toUpperCase;
	}
	</script>
</body>
</html>