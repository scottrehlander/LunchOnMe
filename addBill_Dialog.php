<?php session_start(); ?>

<div data-role="dialog" >
	
	<div data-role="header" data-theme="<?php echo($headerDataTheme); ?>">
		<h1>Add Bill</h1>
	</div>
	
	<div data-role="content" >
			<form action="actionHandler.php" method="post" data-ajax="false">
				<label>Username</label>
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
	</div>
</div> 