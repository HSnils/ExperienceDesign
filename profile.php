<?php
require_once 'connect.php';

//ïf user isnt logged in, sends them to login page
if(!$user->is_loggedin()){
	$user->redirect('login.php');
}
//gets username from the session
$userID = $_SESSION['username'];

//changes the username to allways appear in uppercase when printed (and using th e variable)
$printableUsername = strtoupper($userID);


//Checks if the "hidden"-delete butten is clicked if it is clicked then deletes the article
if (isset($_POST['deleteArticle'])){
	$stmt = $db->prepare("DELETE FROM news WHERE newsID='".intval($_POST['deleteArticle'])."'");
	$stmt->execute();
};

if (isset($_POST['editArticle'])){
	//Setter articlen sin ID i session for bruk av å vite hvilken article å endre
	$_SESSION['editArticleID'] = $_POST['editArticle'];
	$user->redirect('editarticle.php');
};

//Update username
$exsistingAccountError = '';
if (isset($_POST['unameSubmit'])){
	
	$newUsername = htmlentities($_POST['newUsername']);
	
	//looks for an exsisting account with the same username
	$stmt = $db->prepare(
	"SELECT username
	FROM users
	WHERE username = '".$newUsername."'
	");
	$stmt->execute(); 
	$accountAlreadyExsists = $stmt->rowCount();
	
	//if accountallreadyexsists is more than 0 (rows got with the sql)
	if($accountAlreadyExsists > 0){
		// fills the username error variable
		$exsistingAccountError = 'User with the same name allready exsist!';
	}else{
		//if it is 0 aka no other users with same username, runs the code
		$stmt = $db->prepare("
		UPDATE users
		SET username='".$newUsername."' 
		WHERE username='". $userID ."'");
		$stmt->execute();

		//fills session variable to the new username
		$_SESSION['username'] = $newUsername;

		//to refresh page to get new username
		$user->redirect('profile.php');
	}
	
};

//Update birthdate
if (isset($_POST['bDateSubmit'])){
	$stmt = $db->prepare("
	UPDATE users
	SET bdate='".htmlentities($_POST['newBDate']) ."' 
	WHERE username='". $userID ."'");
	$stmt->execute();
}

//update password
if (isset($_POST['passSubmit'])){
	//hashes password for the new password
	$hash = password_hash(htmlentities($_POST['newPassword']), PASSWORD_DEFAULT);
	$stmt = $db->prepare("
	UPDATE users
	SET pw='".$hash."' 
	WHERE username='". $userID ."'");
	$stmt->execute();
};

//function to display rating (this was made before i knew of "avg()" but it works so i kept it)using PDO + variable to send the databasevariable into the function
function displayRating(PDO $db, $newsID){
	//sets rating to 0
	$rating = 0;
	//crates array to be filled with ratings
	$ratingarr = array();

	//looks for ratings connected to a selected article
	$ratingstmt = $db->prepare("
		SELECT *
		FROM ratings r
		WHERE r.newsID = ". $newsID .";
		");
	$ratingstmt->execute();
	//counts number of total ratings (aka how many ratings it has got)(number of rows fetched)
	$numberOfRatings = $ratingstmt->rowCount();

	//if numberOfRatings is 0 just returns 0
	if($numberOfRatings == 0){
		return $rating;
	}else{
		while ($ratingrow = $ratingstmt->fetch(PDO::FETCH_ASSOC)){
			//fills the array with values from db
			$ratingarr[] = $ratingrow['rating'];
		}

		//gets the sum of all the values in the array and devides it by number of ratings, could aslo use "count($ratingarr)" here, would do the same
		$rating = array_sum($ratingarr) / $numberOfRatings;
		
		//returns average of all ratings for the selected article/newsID
		return $rating;
	}
}


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
    
    <link rel="stylesheet" href="css/profile.css?<?php echo time(); ?>">
    <link rel="stylesheet" href="css/header.css?<?php echo time(); ?>">
    <link rel="stylesheet" href="css/fonts.css?<?php echo time(); ?>">
</head>
<body>
	<!--Menu-->
	<a id="logo" href="index.php">SUPERNEWS</a>
	<?php
	//ïf user isnt logged in, gives new users different menu
	if(!$user->is_loggedin()){
		echo '
		<header>
			<a id="profile" href="login.php">LOG IN</a>
			<a id="createnews" href="register.php">SIGN UP</a>
		</header>';
	}else if($_SESSION['username'] == 'admin'){
		echo '
		<header>
			<a id="createnews" href="createnews.php">CREATE ARTICLE</a>
			<a id="logout" href="logout.php?logout=true">LOG OUT</a>
			<a id="profile" href="profile.php">WELCOME, '. $printableUsername .'</a>
		</header>
		<a class="adminbutton" href="admin.php">ADMIN DASHBOARD</a>';
	}else{
		echo '
		<header>
			<a id="createnews" href="createnews.php">CREATE ARTICLE</a>
			<a id="logout" href="logout.php?logout=true">LOG OUT</a>
			<a id="profile" href="profile.php">WELCOME, '. $printableUsername .'</a>
		</header>';
	}
	?>
	
	<h1><?php print($printableUsername); ?>'S PROFILE</h1>
	<div id="main">
		<div class="container">
			<form method="post" class="newDetails">
				<h3>Update your userdetails:</h3>
				<?php
					//checks if username is admin, if it isnt then displays option to change username and password, if they are admin, just gets the password change
					if($userID == 'admin'){
						//Does nothing
					}else{
						echo '
						<label for="newUsername">Update username:</label>
						<br>
						<input class="field" id="newUsername" type="text" name="newUsername">
						<input class="updateButton" type="submit" name="unameSubmit" value="Update">
						<div style="color: crimson;">'.$exsistingAccountError.'</div>
						
						<br>

						<label for="newUsername">Update birthdate:</label>
						<br>
						<input class="field" id="newBdate" type="date" name="newBDate">
						<input class="updateButton" type="submit" name="bDateSubmit" value="Update">

						<br>';
					}
				?>
				
				

				<label for="newPassword">Update password:</label>
				<br>
				<input class="field" id="newPassword" type="password" name="newPassword">
				<input class="updateButton" type="submit" name="passSubmit" value="Update">
			</form>

			<h3>Your Articles:</h3>
			<!--Table to show users created news-->
			
			<?php
				//gets users created news
				$stmt = $db->prepare("SELECT newsID, title, descr, category, uploadDate, rating FROM news n INNER JOIN users u ON n.authorID = u.username WHERE u.username=:userID;");
				//Binder
				$stmt->bindParam(':userID', $userID);
				$stmt->execute();
				//Just to check if they dont have any
				$numRows = $stmt->rowCount();
			?>
			
				<table>
					<tr>
						<td><b>Title</b></td>
						<td><b>Category</b></td>
						<td><b>Upload Date</b></td>
						<td><b>Rating</b></td>
						<td style="text-align: center;"><b>EDIT</b></td>
						<td style="text-align: center;"><b>DELETE</b></td>
					</tr>
					<?php
					if($numRows == 0){
						echo '<p style="text-align: center;">No news made!</p>';
					}else{
						//goes through db to find all users created news
						while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
							echo '<tr>';
							echo '<td>' .$row['title']. '</td>';
							echo '<td>'.$row['category'].'</td>';
							echo '<td>'.$row['uploadDate'].'</td>';
							echo '<td>' .displayRating($db, $row['newsID']).'<i> /5</i></td>';
							echo '<td>
								<form method="post">
									<input type="hidden" name="editArticle" value="'.$row['newsID'].'">
									<input class="delbut" type="submit" value="EDIT">
								</form>
							</td>';
							echo '<td>
								<form method="post">
									<input type="hidden" name="deleteArticle" value="'.intval($row['newsID']).'">
									<input class="delbut" type="submit" value="DELETE">
								</form>
							</td>';
							echo '</tr>';
						}
					};?>
				</table>
			
		</div>
	</div>
	
	<script>
	//function to make name to uppercase
	function allCaps(a){
		return a.toUpperCase;
	}
	</script>
</body>
</html>