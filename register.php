<?php 
require_once 'connect.php';

$exsistingAccountError = '';

if(isset($_POST['submit'])){
	
	//Puts username into variables for use in SQL statement
	$username =htmlentities($_POST['uname']);
	$pw = htmlentities($_POST['pw']);
					
	//looks for an exsisting account with the same username
	$stmt = $db->prepare(
	"SELECT username
	FROM users
	WHERE username = '".$username."'
	");
	$stmt->execute(); 
	$accountAlreadyExsists = $stmt->rowCount();
	
	//if accountallreadyexsists is more than 0 (rows got with the sql)
	if($accountAlreadyExsists > 0){
		// fills the username error variable
		$exsistingAccountError = 'User with the same name allready exsist!';
	}else{
		//if it is 0 aka no other users with same username, runs the register function from the user class
		$user->register($username,$pw);
		//redirects you to the loginpage
		header("Location: login.php");
	}
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
    
    <link rel="stylesheet" href="css/login.css?<?php echo time(); ?>">
    <link rel="stylesheet" href="css/main.css?<?php echo time(); ?>">
</head>
<body>
	<div id="main">
	<div class="container">
		<h1>REGISTER YOUR ACCOUNT</h1>
		<form action="register.php" method="post">

			<label for="username">Username*</label>
			<input type="text" name="uname" id="username" value="">
			<div id="usernameError"><?php echo $exsistingAccountError; ?></div>

			<br>

			<label for="pass1">Password*</label>
			<input type="password" id="pass1" name="pw" value="">

			<br>

			<label for="pass2">Confirm Password*</label>
			<input type="password" id="pass2" name="pw2" value="">
			<div id="passError"></div>

			<br>
			
			<input class="btn" id="regSubmit" type="submit" name="submit" value="Create account">
		</form>
	</div>
	</div>
	<script src="js/validateRegister.js"></script>
	

</body>
</html>