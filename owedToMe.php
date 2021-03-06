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
	<div data-role="page" id="page" data-theme="<?php echo($dataTheme); ?>">
		<div data-role="header" data-theme="<?php echo($headerDataTheme); ?>">
			<a href="overview.php" data-icon="back" class="ui-btn-left">Back</a>
			<h1>Lunch on Me</h1>
			<a href="overview.php" data-icon="home" class="ui-btn-right">Home</a>
		</div>
		
		<div data-role="content" >
			<ul id="owedToMeBillsList" data-role="listview" data-inset="true" data-filter="true" data-divider-theme="<?php echo($dividerDataTheme); ?>">
				<li id="owedToMeBillsListDivider" data-role="list-divider">Who Owes Me?</li>
				
				<?php
					// Grab the bills owed to me
					require("api/DbDataAdapter.php");
	
					$billsDa = new BillsTableAdapter();
					$bills = $billsDa->GetBillsForUser($_SESSION['userId']);
					
					$usersDa = new UsersTableDataAdapter();
					$usersRetreived = array();
										
					foreach($bills['owed'] as $bill)
					{
						if(!array_key_exists($bill['UserBillOwedBy'], $usersRetreived))
						{
							$userInfo = $usersDa->GetUser($bill['UserBillOwedBy']);
							$usersRetreived[$bill['UserBillOwedBy']] = $userInfo;
							
							echo('<li style="height: 80px"><a href="userOwedToMe.php?userOwedId=' . $bill['UserBillOwedBy'] .'"><img style="height: 70px; width: 70px; margin-left: 5px; margin-top: 5px" src="' . $userInfo['UserPicture'] . '" />' . stripcslashes($usersRetreived[$bill['UserBillOwedBy']]["UserFirstName"]) . ' ');
							echo(stripcslashes($usersRetreived[$bill['UserBillOwedBy']]["UserLastName"]) . '</a></li>'); 
						}
					}
				?>
				
			</ul>
			
			<?php
				// Action response handling
			
				if(isset($_GET['billCreate']))
				{
					if($_GET['billCreate'] < 0)
					{
						// Bill creation failed
						if($_GET['billCreate'] == -1)
						{
							echo("<center>Failed to add bill.  Please try again.</center>");
						}
					}
					else
					{
						echo("<center>Bill created.</center>");	
					}
				}
				
				if(isset($_GET['billRemoved']))
				{
					if($_GET['billRemoved'] < 0)
					{
						// Bill creation failed
						if($_GET['billRemoved'] == -1)
						{
							echo("<center>Failed to remove bill.  Please try again.</center>");
						}
					}
					else
					{
						echo("<center>Bill removed.</center>");	
					}
				}
			?>
			
			<ul id="owedToMeActions" data-role="listview" data-inset="true" data-divider-theme="<?php echo($dividerDataTheme); ?>">
				<li id="owedToMeAddBillDivider" data-role="list-divider" >Actions</li>
				<li id="owedToMeAddBill"><a href="addBill.php" >Add a Bill</a></li>
			</ul>
		</div>
	</div>
</body>
</html>