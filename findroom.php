<?php
	require_once('partials/phphead.php');

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
        $building = htmlentities($_POST['buildingSelect']);

        $newRoom = $building . $room;

        //puts values into statement
        $stmt = $db->prepare("
            INSERT INTO bookings(roomName, dayBooked, bookedFrom, bookedTo, username)
            VALUES('$newRoom', '$dayBooked', '$timeFrom', '$timeTo', '$userID')
        ");

        $stmt->execute();
        
        //redirects user to homepage
        $user->redirect('index.php');
       
    }

    //htmlhead
	require_once('partials/header.php');


?>

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

    <div id="reserve" hidden>
    	<div id="X">X</div>
        <form action="findroom.php" method="post">

            <label for="date">Date</label>
            <input type="date" name="date" id="date" value="" required >

            <label for="timeFrom">From</label>
            <input id="timeFrom" type="text" value="" name="timeFrom" required max="5" min="5" placeholder="hh:mm">

            <label for="timeTo">To</label>
            <input id="timeTo" type="text" value="" name="timeTo" required max="5" min="5" placeholder="hh:mm">

            <label for="building">Building</label>
            <select id="buildingSelect" name="buildingSelect" required>
            	<option value="">Choose building</option>
				<option value="A">Building A</option>
				<option value="B">Building B</option>
				<option value="G">Building G</option>
				<option value="K">Building K</option>
            </select>

            <label for="room">Room</label>
            <input id="room" type="number" value="" name="room" required >

            <label for="regSubmit" hidden> RESERVE </label>
            <input class="buttonclass" id="resSubmit" type="submit" name="submit" value="RESERVE">
        </form>
    </div>


    <div class="linkbox" id="reserveBox">
        <a class="linkbutton" id="reservebutton">RESERVE FUTURE </a>
    </div>

    <script type="text/javascript">

    	function allCaps(a) {
            return a.toUpperCase;
        }

    	$('#reservebutton').click(function(){
			
			$('#reserveBox').hide();
			
			$('#reserve').toggle("slide", {direction: 'down'})
		});

		$('#X').click(function(){
			$('#reserve').toggle("slide", {direction: 'down'})

			$('#reserveBox').fadeIn(1000);
			
			
		});
    </script>
    </body>

    </html>