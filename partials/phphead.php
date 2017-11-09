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

?>