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
 
	
	<!-- User View Page -->
	<div data-role="page" id="overview" data-theme="<?php echo($dataTheme); ?>">
		<div data-role="header" data-theme="<?php echo($headerDataTheme); ?>">
			
			<?php 
				if(isset($_GET['returnTo']))
				{
					echo('<a href="' . $_GET['returnTo'] . '.php?' . $_GET['withParamKey'] . '=' . $_GET['withParamVal'] . '" data-icon="back" class="ui-btn-left">Back</a>');
				}
				else 
				{
					echo('<a href="groups.php" data-icon="back" class="ui-btn-left">Back</a>');
				}
			?>
			
			
			<h1>Lunch on Me</h1>
			<a href="overview.php" data-icon="home" class="ui-btn-right">Home</a>
		</div>
		
		<div data-role="content" >
			
			<div>
				
				<ul id="userDataList" data-role="listview" data-inset="true" data-theme="<?php echo($dataTheme); ?>" data-divider-theme="<?php echo($dividerDataTheme); ?>">
					<li id="userDataListDivider" data-role="list-divider">User Profile</li>
					
					<?php
					
						$editable = false;
						if(!isset($_GET['userId']) || $_GET['userId'] == $_SESSION['userId'])
						{
							$editable = true;
						}
						
						include_once("api/DbDataAdapter.php");
						$usersDa = new UsersTableDataAdapter();
						
						// It is editable if your are looking at yourself
						if($editable)
						{
							$user = $usersDa->GetUser($_SESSION['userId']);
					
							$imageUrl = "userPics/noPic.gif";
							if($user['UserPicture'] != "")
								$imageUrl = $user['UserPicture'];
							
							echo('<li ><img style="height: 70px; width: 70px; margin-left: 5px; margin-top: 5px" src="' . $imageUrl . '" />');
								echo('<h3>' . $user['UserName'] . '</h3>');
								echo('<p><a href="changeAvatar.php" class="">Change Picture</a></p>');
							echo('</li>');
							echo('<li style="height: 40px" >');
								echo('<span>' . $user['UserFirstName'] . ' ' . $user['UserLastName'] . '</span>');
								echo('<p style="text-align: right"><a href="changeName.php">edit</a></p>');
							echo('</li>');
							echo('<li style="height: 40px" >');
								echo('<span>' . $user['UserEmail'] . '</span>');
								echo('<p style="text-align: right"><a href="changeEmail.php">edit</a></p>');
							echo('</li>');
							echo('<li style="height: 40px" >');
								echo('<span>Joined ' . date('m-d-Y', strtotime($user['UserCreatedDate'])) . '</span>');
							echo('</li>');
						}
						else 
						{
							$user = $usersDa->GetUser($_GET['userId']);
							
							if($user == null)
								die("User not found with id " . $_GET['userId']);
							
							$imageUrl = "userPics/noPic.gif";
							if($user['UserPicture'] != "")
								$imageUrl = $user['UserPicture'];
							
							echo('<li ><img style="height: 70px; width: 70px; margin-left: 5px; margin-top: 5px" src="' . $imageUrl . '" />');
								echo('<span>' . $user['UserFirstName'] . ' ' . $user['UserLastName'] . '</span>');
							echo('</li>');
							echo('<li >');
								echo('<span>' . $user['UserEmail'] . '</span>');
							echo('</li>');
							echo('<li >');
								echo('<span>Joined ' . date('m-d-Y', strtotime($user['UserCreatedDate'])) . '</span>');
							echo('</li>');
						}
					
					?>
				
				</ul>
				
				<!-- Not editable
				<ul id="userDataList" data-role="listview" data-inset="true" data-theme="<?php echo($dataTheme); ?>" data-divider-theme="<?php echo($dividerDataTheme); ?>">
					<li id="userDataListDivider" data-role="list-divider">User Profile</li>
					<li ><img style="height: 70px; width: 70px; margin-left: 5px; margin-top: 5px" src="userPics/scott.jpg" />
						<h3>Scott Rehlander</h3>
					</li>
					<li >
						<span>scott@rehlander.com</span>
					</li>
					<li >
						<span>Joined 3-12-2012</span>
					</li>
				</ul>
				-->
				
				<!-- If editable
				<ul id="userDataList" data-role="listview" data-inset="true" data-theme="<?php echo($dataTheme); ?>" data-divider-theme="<?php echo($dividerDataTheme); ?>">
					<li id="userDataListDivider" data-role="list-divider">User Profile</li>
					<li ><img style="height: 70px; width: 70px; margin-left: 5px; margin-top: 5px" src="userPics/scott.jpg" />
						<h3>xi2elic</h3>
						<p>
							<a href="#" class="">Change Picture</a>
						</p>
					</li>
					<li style="height: 40px" >
						<span>Scott Rehlander</span>
						<p style="text-align: right"><a href="#">change name</a></p> 
					</li>
					<li style="height: 40px" >
						<span>scott@rehlander.com</span>
						<p style="text-align: right"><a href="#">change email</a></p>
					</li>
					<li style="height: 40px">
						<span>Joined 3-12-2012</span>
					</li>
				</ul>
				-->
				
				<!-- Action
					<ul id="shortcutList" data-role="listview" data-inset="true" data-theme="<?php echo($dataTheme); ?>" data-divider-theme="<?php echo($dividerDataTheme); ?>">
					<li id="overListDivier" data-role="list-divider">Shortcuts</li>
					<li id="addBill"><a href="addBill.php?returnTo=overview">Add Bill</a></li>
				</ul>-->
			</div>
			
			<?php
				if(isset($_GET['changeAvatar']))
				{
					if($_GET['changeAvatar'] > -1)
					{
						echo("<center>Successfully changed avatar.</center>");
					}
				}
			?>
		</div>
	</div>
</body>
</html>
