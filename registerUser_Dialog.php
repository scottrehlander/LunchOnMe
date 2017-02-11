<?php session_start(); ?>

<div data-role="dialog" >
	
	<div data-role="header" >
		<h1>Regster</h1>
	</div>
	
	<div data-role="content" >
			<form action="actionHandler.php" method="post" data-ajax="false">
				<label>Username</label>
				<input name="registerUserUsername"></input>
				<label>PIN</label>
				<input name="registerUserPin"></input>
				<label>Email</label>
				<input name="registerUserEmail"></input>
				<label>First Name</label>
				<input name="registerUserFirstName"></input>
				<label>Last Name</label>
				<input name="registerUserLastName"></input>
				
				<input type="submit" value="Submit"  />
				
				<input type="hidden" name="action" value="registerUser"  />
			</form>
	</div>
</div> 