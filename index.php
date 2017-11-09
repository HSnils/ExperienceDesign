<?php 
require_once('partials/phphead.php');
require_once('partials/header.php'); 
?>

<div id="main">
    <a href="https://placeholder.com"><img src="http://via.placeholder.com/600x300"></a>
</div>

<div class="bookings">
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
						ORDER BY dayBooked ASC");
					$stmt->execute();
					$numRows = $stmt->rowCount();

					//writes out all the rooms into tablerows etc
					while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

						if($numRows == 0){
							echo '<p style="text-align: center;">No rooms avalible!</p>';
						}else{
							//using substr on the booking time to take away seconds
							echo '
							<tr>
								<td><b>'. $row['roomName'].'</b></td>
								<td><b>'. $row['dayBooked'].'</b></td>
								<td><b>'. $row['bookedFrom'].' - '.$row['bookedTo'].'</b>
                                </td>
                                <td><a href="delete.php?id='. $row['bookingID']. '" class="icon cancel"></a></td>
                                <td><a href="room.php?id='. $row['roomName'].'" class="icon goTo"></a></td>
							</tr>';
							
						}
					}
				?>
            </tbody>
        </table>
    </div>
</div>

<div class="linkbox">
    <a class="linkbutton btn" href="findroom.php">FIND ROOM </a>
</div>
<script>
    function allCaps(a) {
        return a.toUpperCase;
    }
</script>
</body>

</html>