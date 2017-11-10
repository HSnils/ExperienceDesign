<?php
if(isset($_POST["building"])){
    // Capture selected building
    $building = $_POST["building"];

    // Define building and city array
    $buildingArr = array(
                    "A" => array("161", "162", "266","267","269","270"),
                    "B" => array("210", "211", "212", "213", "214"),
                    "G" => array("208", "209", "213", "218", "219"),
                    "K" => array("110", "204", "210", "212", "214")
                );

    // Display city dropdown based on building name
    if($building !== 'Select'){
        foreach($buildingArr[$building] as $value){
            echo "<option>". $value . "</option>";
        }
    }
}
?>