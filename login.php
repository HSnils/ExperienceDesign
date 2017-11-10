<?php
	require_once 'connect.php';
	
	//redirects user to index if allready logged in
	if($user->is_loggedin()!=""){
		$user->redirect('index.php');
	}
	
	if(isset($_POST['submit'])){
		$username = htmlentities($_POST['username']);
		$password = htmlentities($_POST['pw']);
		
		//uses login function the the userclass
		if($user->login($username, $password)){
			$user->redirect('index.php');
		}else{
			$error = "Wrong Login details!";
		}
	}
		
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!--Jquery and UI-->
        <script src="jquery-ui/external/jquery/jquery.js"></script>
        <link rel="stylesheet" href="jquery-ui/jquery-ui.min.css">
        <link rel="stylesheet" href="jquery-ui/jquery-ui.structure.min.css">
        <link rel="stylesheet" href="jquery-ui/jquery-ui.theme.min.css">
        <script src="jquery-ui/jquery-ui.min.js"></script>

        <link rel="stylesheet" href="css/login.css?<?php echo time(); ?>">
        <link rel="stylesheet" href="css/main.css?<?php echo time(); ?>">
    </head>

    <body>
        <div id="main">
            <div class="container">
                <h1>Group room Booking</h1>
                <form action="login.php" method="post" id="login">
                    <?php
					if(isset($error)){?>
                    <div>
                        &nbsp;
                        <?php echo $error; ?> !
                    </div>
                    <?php
					}
				?>
                    <label for="username">USERNAME</label>
                    <input type="text" name="username" id="username" value="" required>
                    <label for="pass">PASSWORD</label>
                    <input type="password" name="pw" id="pass" value="" required>

                    <!--Log in and register buttons -->
					<input class="btn" type="submit" name="submit" value="SIGN IN">
					<input class="btn"  id="regButton" type="button" name="register" value="SIGN UP">
                </form>

                <div id="error"></div>
            </div>
        </div>


        <script>
            //redirects to Register.php on click on register button
            $('#regButton').click(function() {
                window.location.href = "register.php";
            });
        </script>
    </body>

    </html>