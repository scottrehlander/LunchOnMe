<?php session_start(); ?>

<div data-role="dialog" >
	
	<div data-role="header" >
		<h1>Invite To Group</h1>
	</div>
	
	<div data-role="content" >
		<form action="actionHandler.php" method="post" data-ajax="false">
			
			<label>Group</label>
			<select name="inviteUserToGroupGroup">
				<?php 
						require('.api/DbDataAdapter.php');
					
						// Populate the select
						$groupsDa = new GroupsTableAdapter();
						$groupAffiliations = $groupsDa->GetAllGroupsAfilliated($_SESSION['userId']);
						
						$usersAdded = array();
						foreach($groupAffiliations['groups'] as $group)
						{
							echo("<option value=" .$group['GroupId'] . ">" . stripcslashes($group['GroupName']) . "</option>");			
						}
					?>
			</select>
			<br />
			<label>Friend's Email</label>
			<input name="inviteUserToGroupEmail"></input>
			
			<!--<label>Message</label>-->
			<!--<TextArea type="hidden" name="inviteUserToGroupMessage"></TextArea>-->
			<input type="submit" value="Submit" />
						
			<input type="hidden" name="userInviting" value="<?php echo($_SESSION['userId']) ?>">
			<input type="hidden" name="action" value="inviteUserToGroup">
			
		</form>
	</div>
</div> 