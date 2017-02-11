<?php
	session_start();
	
	require('api/DbDataAdapter.php');
	
	$action = $_POST['action'];
	
	if($action == 'addBill')
	{
		$billsDa = new BillsTableAdapter();
		
		// Check if we owe, or someone owes us
		if($_POST['rdoOwesMe'] == 'owedToMe')
		{
			$retVal = $billsDa->CreateBill($_POST['billAmount'], $_POST['billLocation'], $_POST['userBillOwedTo'], $_POST['userBillOwedBy'], $_POST['billNotes'], 0);
			if($retVal > 0)
			{
				require('api/Emailer.php');
				Emailer::SendBillCreatedNotification($retVal, 1);
				
				echo "<meta http-equiv=\"refresh\" content=\"0;URL=owedToMe.php?billCreate=1\">";
				die();
			}		
		}
		else 
		{
			// If I owe this bill, let's auto confirm it
			
			// Revese the order of userBillOwedBy and userBillOwed To (They are named incorrectly in this case)
			$retVal = $billsDa->CreateBill($_POST['billAmount'], $_POST['billLocation'], $_POST['userBillOwedBy'], $_POST['userBillOwedTo'], $_POST['billNotes'], 1);
			
			if($retVal > 0)
			{
				require('api/Emailer.php');
				Emailer::SendBillCreatedNotification($retVal, 0);
				
				echo "<meta http-equiv=\"refresh\" content=\"0;URL=billsIOwe.php?billCreate=1\">";
				die();
			}
		}		
		
		echo "<meta http-equiv=\"refresh\" content=\"0;URL=owedToMe.php?billCreate=-1\">";
		die();
	}
	else if($action == 'confirmBill')
	{
		$billsDa = new BillsTableAdapter();
		
		$billsDa->ConfirmBill($_POST['billId']);
		
		$additonalParams = "";
		
		// Check returnTo values
		if($_POST['returnTo'] != "" && $_POST['withParamKey'] != "" && $_POST['withParamVal'] != "")
		{
			$additionalParams = "&returnTo=" . $_POST['returnTo'] . "&withParamKey=" . $_POST['withParamKey'];
			$additionalParams .= "&withParamVal=" . $_POST['withParamVal'];	
		}
		
		require('api/Emailer.php');
		Emailer::SendBillConfirmedNotification($_POST['billId']);
		
		echo "<meta http-equiv=\"refresh\" content=\"0;URL=individualBillView.php?billId=" . $_POST['billId'] . "&billConfirmed=1" . $additionalParams . " \">";
		die();
	}
	else if($action == "remindBillOwed")
	{
		
		die("not implemented");
		
	}
	else if ($action == 'deleteBill')
	{
		$billsDa = new BillsTableAdapter();
		
		$retVal = $billsDa->RemoveBill($_POST['billId']);
		if($retVal == 0)
		{
			require('api/Emailer.php');	
			Emailer::SendBillDeletedNotification($_POST['billId']);
			
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=owedToMe.php?&billRemoved=1\">";
			die();
		}
		else
		{
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=owedToMe.php?billRemoved=-1\">";
			die();
		}
	}
	else if ($action == "requestBillDelete")
	{
		require('api/Emailer.php');
		
		$wasMailSent = Emailer::RequestBillDelete($_POST['billId']);
		
		// Check returnTo values
		if($_POST['returnTo'] != "" && $_POST['withParamKey'] != "" && $_POST['withParamVal'] != "")
		{
			$additionalParams = "&returnTo=" . $_POST['returnTo'] . "&withParamKey=" . $_POST['withParamKey'];
			$additionalParams .= "&withParamVal=" . $_POST['withParamVal'];	
		}
		
		if($wasMailSent == "Mail sent")
		{
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=individualBillView.php?billId=" . $_POST['billId'] . "&requestSent=1" . $additionalParams . "\">";
		}
		else 
		{
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=individualBillView.php?billId=" . $_POST['billId'] . "&requestSent=-1" . $additionalParams . "\">";
		}
		die();
	}
	else if($action == "createGroup")
	{
		$groupsDa = new GroupsTableAdapter();
		
		//function CreateGroup($creatorId, $groupName, $groupPin, $groupDescription)
		$retVal = $groupsDa->CreateGroup($_POST['groupCreator'], $_POST['groupCreateName'], $_POST['groupCreatePin'], $_POST['groupCreateDescription']);
		
		if(!isset($retVal) || !is_array($retVal) || !$retVal['Response'] == "OK")
		{
			// Fail
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=groups.php?groupCreated=-1\">";
			die();
		}
		else
		{
			// Success
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=groups.php?groupCreated=1\">";
			die();
		}
	}
	else if ($action == "inviteUserToGroup")
	{
		require('api/Emailer.php');
		
		$groupsDa = new GroupsTableAdapter();
		
		// Get the user name of the user who requested the invitation
		$userDa = new UsersTableDataAdapter();
		$user = $userDa->GetUser($_POST['userInviting']);
		
		$group = $groupsDa->GetGroup($_POST['inviteUserToGroupGroup']);
		
		//$message = $_POST['inviteUserToGroupMessage'];
		$message = "";
		
		// Send email
		$response = Emailer::SendGroupInviteEmail($user['UserFirstName'] . ' ' . $user['UserLastName'], $_POST['inviteUserToGroupEmail'], $_POST['inviteUserToGroupGroup'], $group['GroupName'], $message);
		
		// Check the response
		if($response == "Mail sent")
		{
			// Success
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=groups.php?userInvited=1\">";
			die();
		}
		else
		{
			// Fail
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=groups.php?userInvited=-1\">";
			die();
		}
	}
	else if ($action == 'registerUser')
	{		
		require('api/Emailer.php');
		
		$userDa = new UsersTableDataAdapter();
		$userId = $userDa->RegisterUser($_POST['registerUserUsername'], $_POST['registerUserPin'], $_POST['registerUserEmail'], $_POST['registerUserFirstName'], $_POST['registerUserLastName']);
		
		if($userId == -1)
		{
			//die("user: " . $userId);
		
			// Fail user not created
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=index.php?userRegistered=-1\">";
			die();
		}
		
		// Grab the user so we can calculate the confirmation code
		$userRow = $userDa->GetUser($userId);
		
		// Send email
		$confCode = Emailer::CreateConfirmationCode($userRow['UserCreatedDate']);
		$wasMailSent = Emailer::SendConfirmationEmail($userRow['UserId'], $userRow['UserEmail'], $confCode);
		
		if($wasMailSent == "Mail sent")
		{
			if(isset($_POST['groupId']) && $_POST['groupId'] > -1)
			{
				// Success, join the group
				$groupsDa = new GroupsTableAdapter();
				$groupsDa->AddGroupAffiliation($userId, $_POST['groupId']);
			}
			
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=overview.php?joinedGroup=" . $_POST['groupId'] . "\">";
			die();
			
			// Success
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=index.php?userRegistered=1\">";
			die();
		}
		else
		{
			$userDa->DeleteUser($userRow['UserId']);
			
			// Fail email not sent
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=index.php?userRegistered=-2\">";
			die();
		}
	}
	else if ($action =="resetPassword")
	{
		require('api/Emailer.php');
		
		$wasMailSent = Emailer::ResetUserPassword($_POST['userNameReset']);
		if($wasMailSent == "Mail sent")
		{
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=resetPassword.php?passwordReset=1\">";
		}
		else 
		{		
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=resetPassword.php?passwordReset=-1\">";
		}
		die();
	}
	else if($action == "forgotUsername")
	{
		require('api/Emailer.php');
		
		$wasMailSent = Emailer::ForgotUsername($_POST['userEmail']);
		
		if($wasMailSent == "User not found")
		{
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=forgotUsername.php?usernameSent=-2\">";
		}
		else if($wasMailSent == "Mail sent")
		{
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=index.php?usernameSent=1&email=" . $_POST['userEmail'] . "\">";
		}
		else 
		{		
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=forgotUsername.php?usernameSent=-1\">";
		}
		die();
	}
	else if ($action =="changePassword")
	{
		include_once('api/DbDataAdapter.php');
		
		if($_POST['changePasswordNew'] != $_POST['changePasswordNew2'])
		{
			// Verification failed, they do not match
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=changePassword.php?changePassword=-1\">";
			die();
		}
		
		// Make sure the new PIN is valid
		if(strlen($_POST['changePasswordNew']) < 2 )
		{
			// Must be at least 2 chars
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=changePassword.php?changePassword=-2\">";
			die();
		}	
		
		$userDa = new UsersTableDataAdapter();
		$userDa->UpdatePassword($_SESSION['userId'], $_POST['changePasswordNew']);
		
		echo "<meta http-equiv=\"refresh\" content=\"0;URL=overview.php?changePassword=1\">";
		die();
	}
	else if ($action == "changeName")
	{
		include_once('api/DbDataAdapter.php');
		
		$userDa = new UsersTableDataAdapter();
		$userDa->UpdateFirstAndLastName($_SESSION['userId'], $_POST['userFirstName'], $_POST['userLastName']);

		// Success
		echo "<meta http-equiv=\"refresh\" content=\"0;URL=userView.php?changeName=1\">";
		die();
	}
	else if ($action == "changeEmail")
	{
		include_once('api/DbDataAdapter.php');
		
		$userDa = new UsersTableDataAdapter();
		$userDa->UpdateEmail($_SESSION['userId'], $_POST['userEmail']);

		// Success
		echo "<meta http-equiv=\"refresh\" content=\"0;URL=userView.php?changeEmail=1\">";
		die();
	}
	else if ($_GET['action'] == "changeAvatar")
	{
		include_once('api/DbDataAdapter.php');
		
		if($_GET['userId'] != $_SESSION['userId'])
		{
			// You can not change an image unless you are logged in as that user
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=changeAvatar.php?changeAvatar=-1\">";
			die();
		}
		
		$userDa = new UsersTableDataAdapter();
		$userDa->UpdateImage($_SESSION['userId'], $_GET['newImageUrl']);
		
		header('Location: userView.php?changeAvatar=1');
		
		// Success
		echo "<meta http-equiv=\"refresh\" content=\"0;URL=userView.php?changeAvatar=1\">";
		die();
	}
	else
	{
		echo "<meta http-equiv=\"refresh\" content=\"0;URL=index.php?error=-2\">";
		die();
	}
	
	
	
	
	
?>