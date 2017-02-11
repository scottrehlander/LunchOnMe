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
			
			<?php 
				if(isset($_GET['returnTo']))
				{
					echo('<a href="' . $_GET['returnTo'] . '.php?' . $_GET['withParamKey'] . '=' . $_GET['withParamVal'] . '" data-icon="back" class="ui-btn-left">Back</a>');
				}
				else 
				{
					echo('<a href="overview.php" data-icon="back" class="ui-btn-left">Back</a>');					
				}
			?>			
			
			<h1>Lunch on Me</h1>
			<a href="overview.php" data-icon="home" class="ui-btn-right">Home</a>
		</div>
		
		<?php
		
			// Grab the bill from the id and show it
			require('api/DbDataAdapter.php');
		
			$billsDa = new BillsTableAdapter();
			$billInfo = $billsDa->GetBillById($_GET['billId']);
					
		?>
		
		<div data-role="content" >
			<ul id="billViewList" data-role="listview" data-inset="true" data-theme="<?php echo($dataTheme); ?>" data-divider-theme="<?php echo($dividerDataTheme); ?>">
				<li id="billViewListDivider" data-role="list-divider" >Bill <?php echo($billInfo['BillConfirmed'] == "0" ? " (unconfirmed)" : ""); ?></li>
				
				<?php
				
					echo('<li>Bill Date: ' . date('m-d-Y', strtotime($billInfo['BillDate'])) .'</li>');
					echo('<li>Location: ' . stripcslashes($billInfo['BillLocation']) .'</li>');
					echo('<li>Amount: ' . stripcslashes($billInfo['BillAmount']) .'</li>');
					echo('<li>Notes: ' . stripcslashes($billInfo['BillNotes']) .'</li>');
				?>
				
			</ul>
			
			<?php
			
				if(isset($billInfo) && $billInfo['BillArchived'] == "0")
				{
					// Show the Delete Bill button if the user is owed this bill
					if(isset($billInfo) && $billInfo['UserBillOwedTo'] == $_SESSION['userId'])
					{
						echo('<form action="actionHandler.php" method="post" data-ajax="false">');
							echo('<input id="billDelete" type="submit" value="Delete Bill" data-theme="e" />');
							
							echo('<input name="billId" type="hidden" value="' . $billInfo['BillId'] . '" data-theme="e" />');
							echo('<input name="action" type="hidden" value="deleteBill" data-theme="e" />');
						echo('</form>'); 
						
						//echo('<form action="actionHandler.php" method="post" data-ajax="false">');
						//	echo('<input id="billRemind" type="submit" value="Remind Friend of this Bill" data-theme="e" />');
						//	
						//	echo('<input name="billId" type="hidden" value="' . $billInfo['BillId'] . '" data-theme="e" />');
						//	echo('<input name="action" type="hidden" value="remindBillOwed" data-theme="e" />');
						//	
						//	echo('<input name="returnTo" type="hidden" value="' . $_GET['returnTo'] . '" data-theme="e" />');
						//	echo('<input name="withParamKey" type="hidden" value="' . $_GET['withParamKey'] . '" data-theme="e" />');
						//	echo('<input name="withParamVal" type="hidden" value="' . $_GET['withParamVal'] . '" data-theme="e" />');
						//echo('</form>');
					}
					else if (isset($billInfo) && $billInfo['UserBillOwedBy'] == $_SESSION['userId'])
					{
						if($billInfo['BillConfirmed'] == "0")
						{
							echo('<form action="actionHandler.php" method="post" data-ajax="false">');
								echo('<input id="billConfirm" type="submit" value="Confirm Bill" data-theme="e" />');
								
								echo('<input name="billId" type="hidden" value="' . $billInfo['BillId'] . '" data-theme="e" />');
								echo('<input name="action" type="hidden" value="confirmBill" data-theme="e" />');
								
								echo('<input name="returnTo" type="hidden" value="' . $_GET['returnTo'] . '" data-theme="e" />');
								echo('<input name="withParamKey" type="hidden" value="' . $_GET['withParamKey'] . '" data-theme="e" />');
								echo('<input name="withParamVal" type="hidden" value="' . $_GET['withParamVal'] . '" data-theme="e" />');
							echo('</form>');
						}
						
						echo('<form action="actionHandler.php" method="post" data-ajax="false">');
							echo('<input id="billRequestDelete" type="submit" value="Request Bill Removal" data-theme="e" />');
							
							echo('<input name="billId" type="hidden" value="' . $billInfo['BillId'] . '" data-theme="e" />');
							echo('<input name="action" type="hidden" value="requestBillDelete" data-theme="e" />');
							
							echo('<input name="returnTo" type="hidden" value="' . $_GET['returnTo'] . '" data-theme="e" />');
							echo('<input name="withParamKey" type="hidden" value="' . $_GET['withParamKey'] . '" data-theme="e" />');
							echo('<input name="withParamVal" type="hidden" value="' . $_GET['withParamVal'] . '" data-theme="e" />');
						echo('</form>');
					}
				}
				// Handle return actions
				if(isset($_GET['requestSent']))
				{
					if($_GET['requestSent'] == 1)
						echo("<center>Bill Delete Request has been sent.</center>");
					else if($_GET['requestSent'] == -1)
						echo("<center>Bill Delete Request failed to send, please try again.</center>");	
				}
			?>
		</div>
	</div>
</body>
</html>