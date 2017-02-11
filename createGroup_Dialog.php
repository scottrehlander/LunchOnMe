<?php session_start(); ?>

<div data-role="dialog" >
	
	<div data-role="header" >
		<h1>Create Group</h1>
	</div>
	
	<div data-role="content" >
		<form action="actionHandler.php" method="post" data-ajax="false">
			<label>Group Name</label>
			<input name="groupCreateName"></input>
			<label>PIN (optional)</label>
			<input name="groupCreatePin"></input>
			<label>Confirm PIN</label>
			<input name="groupCreatePin2"></input>
			<label>Description</label>
			<TextArea name="groupCreateDescription"></TextArea>
			<input type="submit" value="Submit" />
			
			<input type="hidden" name="groupCreator" value="<?php echo($_SESSION['userId']) ?>">
			<input type="hidden" name="action" value="createGroup">
		</form>
	</div>
</div> 