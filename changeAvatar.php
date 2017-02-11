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
			<h1>Lunch on Me</h1>
			<a href="overview.php" data-icon="home" class="ui-btn-right">Home</a>
		</div>
		
		<div data-role="content" >
			
			<div>
				<form>
					<ul id="uploadList" data-role="listview" data-inset="true" data-theme="<?php echo($dataTheme); ?>" data-divider-theme="<?php echo($dividerDataTheme); ?>">
						<li id="overListDivier" data-role="list-divider">Upload (Desktop only)</li>
						<li><input type="file" value="Choose Image" /></li>
						<li><input type="submit" value="Upload" /></li>
					</ul>
				</form>
			</div>
			<div>
				<ul id="avatarList" data-role="listview" data-inset="true" data-theme="<?php echo($dataTheme); ?>" data-divider-theme="<?php echo($dividerDataTheme); ?>">
					<li id="overListDivier" data-role="list-divider">Choose Avatar</li>
				
					<?php
						$handle = opendir('./userPics/stockAvatars');
						while (false !== ($file = readdir($handle)))
						{
						  $extension = pathinfo($file, PATHINFO_EXTENSION);
						  if($extension == 'jpg' || $extension == 'gif' || $extension == 'png')
						  {
						 	$brokenName = explode('.', pathinfo($file, PATHINFO_BASENAME));
							$name = $brokenName[0];
							
							if($name == "No Image") continue;
							
							echo('<li><a href="actionHandler.php?action=changeAvatar&userId=' . $_SESSION['userId'] . '&newImageUrl=userPics/stockAvatars/' . $file . '"><img src="userPics/stockAvatars/' . $file . '" class="" />' . $name . '</a></li>');
						  }
						} 
					?>
				</ul>
			</div>

		</div>
	</div>
</body>
</html>
