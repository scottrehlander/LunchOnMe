<?php
	session_start(); 
?>

<html>

<head>
	<meta charset="utf-8" http-equiv="X-UA-Compatible" content="IE=9" />
	<!--[if IE 7]><meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" /><![endif]-->
	<!--[if IE]>
	
	    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	    <style type="text/css">
	        .clear {
	            zoom: 1;
	            display: none;
	        }
	    </style>
	<![endif]-->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<?php require("styleSheetInclude.php"); ?>
	<script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
	<script src="http://code.jquery.com/mobile/1.1.0-rc.1/jquery.mobile-1.1.0-rc.1.min.js"></script>
	
	<script type="text/javascript">
	
		$(document).ready(function() {
							
		});
		
	</script>
</head>

<body>
 
	<!-- Login Page -->
	<div data-role="page" id="login" data-theme="<?php echo($dataTheme); ?>">
		<div data-role="header" data-theme="<?php echo($headerDataTheme); ?>">
			<h1>Lunch on Me</h1>
		</div>
				
		<div data-role="content" >
			
				<?php
				
					// Handle error messages
					if(isset($_GET['error']))
					{
						if($_GET['error'] == "1")
						{
							echo("<center>Login failed, please try again.</center>");
						}
						if($_GET['error'] == "2")
						{
							echo("<center>You must login to access the requested page.</center>");
						}
					}
				
					// Accept some commands, like joining a group or registering a new user
					if(isset($_GET['groupId']) && $_GET['groupId'] > -1)
					{
						// Accept the registration
						echo("<center>Please login to accept the group invitation.</center>");		
					}
					
					if(isset($_GET['usernameSent']))
					{
						if($_GET['usernameSent'] >= 0)
						{
							echo("<center>Your username has been sent to " . $_GET['email'] . ".</center>");
						}
					}
					
					if(isset($_GET['confirmationCode']) && isset($_GET['userId']))
					{
						require("api/DbDataAdapter.php");
						require("api/Emailer.php");
						
						$userDa = new UsersTableDataAdapter();
						$userRow = $userDa->GetUser($_GET['userId']);
						
						$confCode = Emailer::CreateConfirmationCode($userRow['UserCreatedDate']);
						
						if($confCode == $_GET['confirmationCode'])
						{
							$userDa->SetUserToActivated($userRow['UserId']);
							echo("<center>You have successfully activated your Lunch on Me account.  You may now login and start using the app!</center>");
						}
						else
						{
							// Failed to activate
							echo("<center>Invalid confirmation code - " . $confCode . " " . $_GET['confirmationCode'] . "</center>");
						}						
					}
				?>
				
				<form id="loginForm" action="loginHandler.php" method="post" data-ajax="false">
					
					<?php
						
						// Handle automatic population of Login Form
						$userName = "";
						$userPassword = "";
						
						if(isset($_COOKIE['username']))
							$userName = $_COOKIE['username'];
						if(isset($_COOKIE['password']))
							$userPassword = $_COOKIE['password'];
						
						// Is this a user registering?
						if(isset($userRow))
						{
							// User is regsitering, auto populate their info
							$userName = $userRow['UserName'];
							$userPassword = $userRow['UserPassword'];
						}
					?>
					
					<label>Username</label>
					<input width="50" id="usernameButton" name="username" value="<?php echo($userName); ?>" />
					<br />
					<label>PIN</label>
					<input width="50" type="password" id="passwordButton" name="password" value="<?php echo($userPassword); ?>" />
					
					<input type="submit" id="loginButton" data-role="button" value="Login" />
					
					<input type="hidden" name="groupId" value="<?php echo($_GET['groupId']); ?>" />
					
					<?php
						// If groupId exists, pass that through to registerUser
						$extraParams = "";
						if(isset($_GET['groupId']) && $_GET['groupId'] != "")
							$extraParams = "?groupId=" . $_GET['groupId'];
						 
						echo('<a href="registerUser.php' . $extraParams . '" data-role="button" >Register</a>');
					?>
					
					<input type="hidden" name="redirectTo" value="<?php echo($_GET['redirectTo']); ?>" />
					<input type="hidden" name="redirectParamKey1" value="<?php echo($_GET['redirectParamKey1']); ?>" />
					<input type="hidden" name="redirectParamValue1" value="<?php echo($_GET['redirectParamValue1']); ?>" />
					
					<!--<input type="submit2" value="Register" onclick="switchPage('#registerNewUserPage')" />-->
				</form>
				
				<p align="right"><a href="forgotUsername.php">Forgot Username</a></p>
				<p align="right"><a href="resetPassword.php">Reset Password</a></p>
				
			<?php
				
				if(isset($_GET['userRegistered']))
				{
					if($_GET['userRegistered'] < 0)
					{
						if($_GET['userRegistered'] == -1)
						{
							echo("<center>Registration failed.  Username is taken.</center>");	
						}
						else if($_GET['userRegistered'] == -2)
						{
							echo("<center>Registration confirmation email failed to send.  Please check your address and try again.</center>");
						}
					}
					else
					{
						echo("<center>Registration successful.  Please check your email to confirm.</center>");	
					}
				}
				if(isset($_GET['passwordReset']))
				{
					if($_GET['passwordReset'] >= 0)
					{
						echo("<center>Your PIN has been created.  Please check your email to view your new PIN.</center>");
					}
					else
					{
						echo("<center>PIN reset failed, please try again.</center>");
					}
				}				
			?>
		</div>
	</div>
	
</body>
</html>