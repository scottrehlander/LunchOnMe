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
 
	
	<!-- Overview Navigation Page -->
	<div data-role="page" id="overview" data-theme="<?php echo($dataTheme); ?>">
		<div data-role="header" data-theme="<?php echo($headerDataTheme); ?>">
			<h1>Overview</h1>
			<a href="index.php?logout=1" data-icon="alert" class="ui-btn-right">Log out</a>
		</div>
		
		<div data-role="content" >
			<ul id="overviewList" data-role="listview" data-inset="true" data-theme="<?php echo($dataTheme); ?>" data-divider-theme="<?php echo($dividerDataTheme); ?>">
				<li id="overListDivier" data-role="list-divider">Main Navigation</li> 
				<li id="overviewWhoOwes"><a href="owedToMe.php" data-ajax="false">Bills Owed To Me</a></li>
				<li id="overviewWhoOwed"><a href="billsIOwe.php">Bills That I Owe</a></li>
				<li id="overviewGroup"><a href="groups.php">Groups</a></li>
			</ul>
			
			<ul id="shortcutList" data-role="listview" data-inset="true" data-theme="<?php echo($dataTheme); ?>" data-divider-theme="<?php echo($dividerDataTheme); ?>">
				<li id="overListDivier" data-role="list-divider">Shortcuts</li>
				<li id="addBill"><a href="addBill.php?returnTo=overview">Add Bill</a></li>
				<li id="createGroup"><a href="createGroup.php?returnTo=overview">Create Group</a></li>
				<li id="inviteUserToGroup"><a href="inviteUserToGroup.php?returnTo=overview">Invite User to Group</a></li>
			</ul>
			
			<ul id="infoList" data-role="listview" data-inset="true" data-theme="<?php echo($dataTheme); ?>" data-divider-theme="<?php echo($dividerDataTheme); ?>">
				<li id="overListDivier" data-role="list-divider">Information</li>
				<!--<li id="editProfile"><a href="help.php">Edit Profile</a></li>-->
				<li id="help"><a href="help.php">How it Works</a></li>
				<li id="editProfile"><a href="userView.php">Edit Profile</a></li>
				<li id="changePw"><a href="changePassword.php">Change PIN</a></li>
				<!--<li id="about"><a href="help.php">About</a></li>-->
			</ul>
			
			<?php
				if(isset($_GET['joinedGroup']))
				{
					require('api/DbDataAdapter.php');
					$groupsDa = new GroupsTableAdapter();
					$group = $groupsDa->GetGroup($_GET['joinedGroup']);
					echo("<center>You have successfully joined the group \"" . stripcslashes($group['GroupName']) . "\"</center>");
				}
				
				if(isset($_GET['changePassword']))
				{
					if($_GET['changePassword'] == 1)
						echo("<center>You have successfully changed your PIN.</center>");	
				}
			?>
		</div>
	</div>
</body>
</html>
