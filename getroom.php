<?php
require_once 'partials/phphead.php';

$q = $_GET['q'];
$stmt = $db->prepare("
    SELECT * 
    FROM rooms 
    WHERE building = '".$q."'");
$stmt->execute();
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    //echo "<option>" . $row['roomName'] . "</option>";
    echo '<option>' . $row['roomName'] . '</option>';
}
?>