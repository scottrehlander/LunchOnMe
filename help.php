<?php
	session_start();
?>	

<html>
	
	
<?php	

	// Check that the user is logged in
	if(!isset($_SESSION["userId"]) || $_SESSION['userId'] < 0)
	{
		echo "<meta http-equiv=\"refresh\" content=\"0;URL=index.php?error=2\">";
		die();
	}	
?>

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
 
	
	<!-- Help Page -->
	<div data-role="page" id="help" data-theme="<?php echo($dataTheme); ?>">
		<div data-role="header" data-theme="<?php echo($headerDataTheme); ?>">
			<a href="overview.php" data-icon="back" class="ui-btn-left">Back</a>
			<h1>Help</h1>
			<a href="overview.php" data-icon="home" class="ui-btn-right">Home</a>
		</div>
		
		<div data-role="content" >
			<div>
				<h2>Overview</h2>
				Lunch on Me is a tool to help you, your coworkers, and your friends manage payments owed to each other from
				meals shared together. 
			</div>
			<div>
				<h2>Adding Bills</h2>
				 You may only add bills for people that are in one of your groups.  To create a group, navigate to the groups
				 page and select "Create Group."  Once you have created a group, you can choose to invite friends to the group.
				 Enter their email and ask them to sign in (they must register first if they haven't already).  Bill amounts
				 can be input as monetary value or as a food item.
			</div>
			<div>
				<h2>Removing Bills</h2>
				 To remove a bill, navigate to the bill in question by going through "Bills Owed to Me."  From the bill view 
				 you can choose to delete the bill.
			</div>
		</div>
	</div>
</body>
</html>
