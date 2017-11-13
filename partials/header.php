<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="mobile-web-app-capable" content="yes">
    <!--Jquery and UI-->
    <script src="jquery-ui/external/jquery/jquery.js"></script>
    <link rel="stylesheet" href="jquery-ui/jquery-ui.min.css">
    <link rel="stylesheet" href="jquery-ui/jquery-ui.structure.min.css">
    <link rel="stylesheet" href="jquery-ui/jquery-ui.theme.min.css">
    <script src="jquery-ui/jquery-ui.min.js"></script>

    <!-- timepicker -->
    <link rel="stylesheet" href="picker/jquery.timepicker.min.css">
    <script src="picker/jquery.timepicker.min.js"></script>

    <!-- mazemap -->
    <link rel="stylesheet" href="https://api.mazemap.com/js/v2.0.0-beta.5/mazemap.min.css">
    <script type='text/javascript' src='https://api.mazemap.com/js/v2.0.0-beta.5/mazemap.min.js'></script>


    <!-- Ajax -->
    <script>
        function showRoom(str) {
            if (str == "") {
                document.getElementById("room").innerHTML = "";
                return;
            } else {
                if (window.XMLHttpRequest) {
                    // code for IE7+, Firefox, Chrome, Opera, Safari
                    xmlhttp = new XMLHttpRequest();
                } else {
                    // code for IE6, IE5
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("room").innerHTML = this.responseText;
                    }
                };
                xmlhttp.open("GET", "getroom.php?q=" + str, true);
                xmlhttp.send();
            }
        }
    </script>
    <!-- css -->
    <link rel="stylesheet" href="css/main.css?<?php echo time(); ?>">
</head>

<body>
    <header>
        <nav>
            <a id="logout" href="logout.php?logout=true">LOG OUT</a>
            <a id="logo" href="index.php">NTNU booking</a>
            <a id="profile" href="index.php">
                <?php echo $printableUsername ?>
            </a>
        </nav>
    </header>