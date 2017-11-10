<?php 
require_once('partials/phphead.php');
require_once('partials/header.php');
require('partials/roomfunctions.php');
?>

<div id="map" class="mazemap">

</div>
<div class="findroombox">
    <div class="details">
        <div class="details-element">
            <p><strong>Building</strong>
                <span><?php echo $room['building'] ?></span>
            </p>
        </div>
        <div class="details-element">
            <p><strong>Room</strong>
                <span><?php echo $room['room'] ?></span>
            </p>
        </div>
        <div class="details-element">
            <p><strong>equipment</strong>
                <span><?php echo $room['equipment'] ?></span>
            </p>
        </div>
        <div class="details-element">
            <p><strong>Seats</strong>
                <span><?php echo $room['seats'] ?></span>
            </p>
        </div>
    </div>

    <div class="booking">
        <?php
        // get all bookings
        $stmt = $db->prepare("
            SELECT *
            FROM bookings
            WHERE roomName = '$roomID'
            AND dayBooked = '$dateToday'");
        $stmt->execute();
        $numRows = $stmt->rowCount();
        //writes out all the rooms into tablerows etc
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            if($numRows == 0){
            echo '<p style="text-align: center;">No rooms avalible!</p>';
            }else{
                //using substr on the booking time to take away seconds
                echo '
                <div class="booking-element">
                    <p>'. $row['username'].'</p>
                    <p class="date">'. $row['bookedFrom'].' - '. $row['bookedTo'].'</p>
                </div>';
                }
            }
            /* Check in action */
            if(isset($_POST['checkinSub'])){
                $stmt = $db ->prepare ("
                   UPDATE bookings
                   SET isThere = 1
                   WHERE roomName = '$roomID'
                   AND dayBooked = '$dateToday'
                   AND bookedFrom = '$bookedFrom'
                   AND bookedTo = '$bookedTo' ");  
                $stmt->execute();
                    header("Refresh:0");
            }
        ?>
            <form action="" method="post">
                <input type="submit" class="btn <?= $btnDisabled ?>" name="<?= $btnName; ?>" value="<?= $btnValue; ?>" <?= $btnDisabled ?>>
            </form>


    </div>
</div>
<script type="text/javascript" src="mazemap/script.js"></script>