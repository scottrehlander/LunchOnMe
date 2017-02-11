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
		
		function switchUsernameField(toWhat)
		{
			$('#lblTargetUsername').html(toWhat);
		}
		
	</script>
</head>

<body>
	<div data-role="page" id="page" data-theme="<?php echo($dataTheme); ?>">
		<div data-role="header" data-theme="<?php echo($headerDataTheme); ?>">
			<?php
				$returnTo = "owedToMe.php";
				if(isset($_GET['returnTo']))
				{
					$returnTo = $_GET['returnTo'] . ".php";
				} 
			?>
			<a href="<?php echo($returnTo); ?>" data-icon="back" class="ui-btn-left">Back</a>
			<h1>Add Bill</h1>
			<a href="overview.php" data-icon="home" class="ui-btn-right">Home</a>
		</div>
		
		<div data-role="content" >
			<form action="actionHandler.php" method="post" data-ajax="false"> 
				
				<fieldset data-role="controlgroup" data-role="fieldcontain">
					<legend>Bill Type:</legend>
					
			         	<input onclick="switchUsernameField('Who Owes Me')" type="radio" name="rdoOwesMe" id="rdoOwesMeId" value="owedToMe" checked="checked"  />
			         	<label for="rdoOwesMeId">Owed to Me</label>
			         			
						<input onclick="switchUsernameField('I Owe To')" type="radio" name="rdoOwesMe" id="rdoOwedToId" value="iOweThis" />
			         	<label for="rdoOwedToId">I Owe this Bill</label>
		
				</fieldset>
				<br />
				
				<label id="lblTargetUsername">User</label>
				<select name="userBillOwedBy">
					
					<?php 
						require('api/DbDataAdapter.php');
					
						// Populate the select
						$groupsDa = new GroupsTableAdapter();
						$groupAffiliations = $groupsDa->GetAllGroupsAfilliated($_SESSION['userId']);
						
						$usersAdded = array();
						foreach($groupAffiliations['users'] as $groupAffiliation)
						{
							if(!in_array($groupAffiliation['UserId'], $usersAdded))
							{
								if($groupAffiliation['UserId'] == $_SESSION['userId']) continue;
								
								echo("<option value=" .$groupAffiliation['UserId'] . ">" . $groupAffiliation['UserFirstName'] . " " . $groupAffiliation['UserLastName'] . "</option>");			
								
								$usersAdded[] = $groupAffiliation['UserId'];
							}
						}
					?>
				
				</select>
				<label>Location</label>
				<input name="billLocation"></input>
				<label>Amount</label>
				<input name="billAmount"></input>
				<label>Notes</label>
				<TextArea name="billNotes"></TextArea>
				
				<input type="hidden" name="userBillOwedTo" value="<?php echo($_SESSION['userId']) ?>">
				<input type="hidden" name="action" value="addBill">
				
				<input type="submit" value="Submit" />
			</form>
			
			<br/>
			Note: You can only add bills for users in one of your groups.  Navigate to <a href="createGroup.php?returnTo=addBill">Create Group</a> to create a group now.
			<br/><br/>
			If you have already created a group, navigate to <a href="inviteUserToGroup.php?returnTo=addBill">Invite User to Group</a> to invite a user to your group.
		</div>
	</div>
</body>
</html>