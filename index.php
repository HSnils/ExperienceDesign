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
	<a id="logo" href="index.php">GROUP ROOM BOOKING</a>
	<header>
		<a id="createnews" href="createnews.php">CREATE ARTICLE</a>
		<a id="logout" href="logout.php?logout=true">LOG OUT</a>
		<a id="profile" href="profile.php">WELCOME, <?php echo $printableUsername ?></a>
	</header>
	
	<h1>My bookings</h1>
	<div id="main">
		
	</div>
	
	<script>
	
	function allCaps(a){
		return a.toUpperCase;
	}
	</script>
</body>
</html>