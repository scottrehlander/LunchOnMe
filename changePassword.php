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
			<a href="overview.php" data-icon="back" class="ui-btn-left">Back</a>
			<h1>Change PIN</h1>
			<a href="overview.php" data-icon="home" class="ui-btn-right">Home</a>
		</div>
				
		<div data-role="content" >
			<form action="actionHandler.php" method="post" data-ajax="false">
				<label>New PIN</label>
				<input type="text" name="changePasswordNew" />
				
				<label>Verify New PIN</label>
				<input type="text" name="changePasswordNew2" />
								
				<input type="submit" value="Change PIN" />
				
				<input type="hidden" name="action" value="changePassword" />
			</form> 
		</div>
		
		<?php
		
			if(isset($_GET['changePassword']))
			{
				if($_GET['changePassword'] >= 0)
				{
					echo("<center>Your PIN has been created.  Please check your email to view your new PIN.</center>");
				}
				else
				{
					if($_GET['changePassword'] == -1)
						echo("<center>PINs do not match.  Please try again.</center>");
					
					if($_GET['changePassword'] == -2)
						echo("<center>PIN must be at least 2 characters long.</center>");
				}
			}
		?>
	</div>
	
</body>
</html>