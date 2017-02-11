<?php

	session_start();
	
	require("api/DbDataAdapter.php");
	
	if(isset($_POST['username']))
	{
		// attempt a login
		$usersDa = new UsersTableDataAdapter();
		$userId = $usersDa->AuthenticateUser($_POST['username'], $_POST['password']);
			
		if($userId < 0)
		{
			setcookie("username", $_POST['username'], time()+3600*2400);
			setcookie("password", "", time()+3600*2400);
			
			$additionalParams = "";
			if(isset($_POST['redirectTo']))
				$additionalParams .= "&redirectTo=" . $_POST['redirectTo'];
			if(isset($_POST['redirectParamKey1']))
				$additionalParams .= "&redirectParamKey1=" . $_POST['redirectParamKey1'];
			if(isset($_POST['redirectParamValue1']))
				$additionalParams .= "&redirectParamValue1=" . $_POST['redirectParamValue1'];
					
			// redirect to index.php with fail
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=index.php?error=1" . $additionalParams . "\">";
			die();
		}
		
		$_SESSION['userId'] = $userId;
		
		setcookie("username", $_POST['username'], time()+3600*2400);
		setcookie("password", $_POST['password'], time()+3600*2400);
				
		if(isset($_POST['groupId']) && $_POST['groupId'] != "")
		{
			if($userId == -1)
			{
				// redirect to index.php with fail
				echo "<meta http-equiv=\"refresh\" content=\"0;URL=index.php?error=1&groupId=" . $_POST['groupId'] . "\">";
				die();
			}
			else
			{
				// Success, join the group
				$groupsDa = new GroupsTableAdapter();
				$groupsDa->AddGroupAffiliation($userId, $_POST['groupId']);
				
				echo "<meta http-equiv=\"refresh\" content=\"0;URL=overview.php?joinedGroup=" . $_POST['groupId'] . "\">";
				die();
			}
		}
		
		// Check redirect to
		if($_POST['redirectTo'] != "")
		{
			$paramsForRedirect = "";
			
			// Check params (only 1 for now)
			if($_POST['redirectParamKey1'] != "" && $_POST['redirectParamValue1'] != "")
			{
				$paramsForRedirect = "?" . $_POST['redirectParamKey1'] . "=" . $_POST['redirectParamValue1'];
			}
			
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=" . $_POST['redirectTo'] . '.php' . $paramsForRedirect . "\">";
			die();	
		}
		
		echo "<meta http-equiv=\"refresh\" content=\"0;URL=overview.php\">";
		die();
	}
	
?>