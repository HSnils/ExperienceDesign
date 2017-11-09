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

//booking of rooms
if(isset($_POST['submit'])){
	
	//Puts username into variables for use in SQL statement
	$dayBooked =htmlentities($_POST['date']);
	$timeFrom = htmlentities($_POST['timeFrom']);
	$timeTo = htmlentities($_POST['timeTo']);
	$room = htmlentities($_POST['room']);

	//puts values into statement
	$stmt = $db->prepare("
		INSERT INTO bookings(roomName, dayBooked, bookedFrom, bookedTo, username)
		VALUES('$room', '$dayBooked', '$timeFrom:02', '$timeTo:02', '$userID')
		");

	$stmt->execute();
	
	//redirects user to homepage
	$user->redirect('index.php');
	
}
require_once('partials/header.php') ?>

    <div id="main">
        <a href="https://placeholder.com"><img src="http://via.placeholder.com/600x300"></a>
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

			//writes out all the rooms
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

				if($numRows == 0){
					echo '<p style="text-align: center;">No rooms avalible!</p>';
				}else{
					echo '

					<tr>
						<td class="roomDistance"> 10m </td>
						<td><b>'. $row['roomName'].'</b></td>
                                <td><a href="room.php?id='. $row['roomName'].'" class="goTo"></a></td>
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

            <label for="timeFrom">From</label>
            <input id="timeFrom" type="time" value="" name="timeFrom" required>

            <label for="timeTo">To</label>
            <input id="timeTo" type="time" value="" name="timeTo" required>

            <label for="building">Building</label>
            <select>
              <option value="volvo">Bygg A</option>
              <option value="saab">Bygg B</option>
              <option value="mercedes">Bygg G</option>
              <option value="audi">Bygg K</option>
            </select>

            <label for="room">Room</label>
            <input id="room" type="text" value="" name="room" required>

            <label for="regSubmit" hidden> RESERVE </label>
            <input class="buttonclass" id="resSubmit" type="submit" name="submit" value="RESERVE">
        </form>
    </div>


    <div class="linkbox">
        <a class="linkbutton">RESERVE FUTURE </a>
    </div>

    <script>
        function allCaps(a) {
            return a.toUpperCase;
        }
    </script>
    </body>

    </html>