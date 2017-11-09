<?php
require_once 'connect.php';

if(!$user->is_loggedin()){
	$user->redirect('login.php');

}else if($user->is_loggedin()){

	$_SESSION['timezone'] = date_default_timezone_set('Europe/Oslo');
	$_SESSION['dateToday'] = $date = date('Y-m-d', time());
	//gets username from the session
	$userID = $_SESSION['username'];
	$dateToday = $_SESSION['dateToday'];
	$timezone = $_SESSION['timezone'];

	//changes the username to allways appear in uppercase when printed (and using th e variable)
	$printableUsername = strtoupper($userID);
}

?>