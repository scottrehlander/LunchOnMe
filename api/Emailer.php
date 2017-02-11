<?php

	class EMailer
	{
		public static function SendEmail($to, $from, $subject, $message)
		{
			//define the headers we want passed. Note that they are separated with \r\n
			$headers = 'From: '. $from . "\r\n" .
				'Reply-To: noreply@lxme.net' . "\r\n" .
				'X-Mailer: PHP/' . phpversion();
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			
			//send the email
			EMailer::Protect($to, $to);		
			
			$mail_sent = mail( $to, $subject, $message, $headers);
			
			//if the message is sent successfully print "Mail sent". Otherwise print "Mail failed" 
			return (($mail_sent ? "Mail sent" : "Mail failed"));	
		}
		
		public static function SendConfirmationEmail($userId, $emailAddress, $confirmationCode)
		{
			//define the receiver of the email
			$to = $emailAddress;
			
			//define the subject of the email
			$subject = 'Please Complete Your Lunch on Me Registration';
				
			$message = "<html><h3>Thanks for registering for Lunch on Me.</h3> <br /><br />Please click the following link to complete your Registration: " .
				"<a href=\"http://lxme.net/index.php?userId=" . $userId . "&confirmationCode=" . $confirmationCode . " \">Get me started!</a></html>";
				
			$from = 'registration@lxme.net';
			
			$mail_sent = Emailer::SendEmail( $to, $from, $subject, $message);
			
			//if the message is sent successfully print "Mail sent". Otherwise print "Mail failed" 
			return (($mail_sent ? "Mail sent" : "Mail failed"));
		}
		
		public static function SendGroupInviteEmail($from, $emailAddress, $groupId, $groupName, $message)
		{
			//define the receiver of the email
			$to = $emailAddress;
			
			//define the subject of the email
			$subject = 'Invitation to Lunch on Me Group';
			
			$message = "<html><h3>" . stripcslashes($from) . " has invited you to join his Lunch on Me group \"" . stripcslashes($groupName) . ".\"</h3> <br /><br />Please click the following link to accept: " .
				"<a href=\"http://lxme.net/index.php?groupId=" . $groupId . " \">I want in!</a></html>";
				
			//define the headers we want passed. Note that they are separated with \r\n
			$headers = 'From: invitations@lxme.net' . "\r\n" .
				'Reply-To: noreply@lxme.net' . "\r\n" .
				'X-Mailer: PHP/' . phpversion();
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			
			//send the email
			EMailer::Protect($to, $to);		
			
			//$mail_sent = mail( $to, $subject, $message, $headers);
			$mail_sent = mail( $emailAddress, 'Invitation to Lunch on Me Group', $message, $headers);
			
			//if the message is sent successfully print "Mail sent". Otherwise print "Mail failed" 
			return (($mail_sent ? "Mail sent" : "Mail failed"));
		}
		
		public static function SendLunchInvitation($from, $friends, $location, $date, $time)
		{
			//define the receiver of the email
			foreach($friends as $toUserRow)
			{
				$to .= $toUserRow['UserEmail'] . ' , ';
			}
			
			// Get rid of last comma
			$to = substr($to, 0, strlen($to) - 3);
			
			//define the subject of the email
			$subject = 'Join Me For Lunch!';
			
			$message = "<html><h3>" . stripcslashes($from['UserFirstName']) . " " . stripcslashes($from['UserLastName']) . " has invited you to lunch at " . stripcslashes($location) . ".  It will take place on " . $date . " at " . $time .".  ";
			$message .= "If you would like to respond, please email " . stripcslashes($from['UserFirstName']) . " directly at " . stripcslashes($from['UserEmail']) . ".</h3>";
				
			//define the headers we want passed. Note that they are separated with \r\n
			$headers = 'From: invitations@lxme.net' . "\r\n" .
				'Reply-To: noreply@lxme.net' . "\r\n" .
				'X-Mailer: PHP/' . phpversion();
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			
			//send the email
			EMailer::Protect($to, $to);		
			
			//$mail_sent = mail( $to, $subject, $message, $headers);
			$mail_sent = mail( $to, 'Join Me For Lunch!', $message, $headers);
			
			//if the message is sent successfully print "Mail sent". Otherwise print "Mail failed" 
			return (($mail_sent ? "Mail sent" : "Mail failed"));
		}
		
		public static function Protect($name, $email)
		{
			if ( preg_match( "/[\r\n]/", $name ) || preg_match( "/[\r\n]/", $email ) ) 
			{
				throw new Exception("Newlines are not allowed in the Name or Email fields.  Injection attack detected.");
			}
		}
		
		public static function ResetUserPassword($username)
		{
			include_once('DbDataAdapter.php');
			
			$userDa = new UsersTableDataAdapter();
			$user = $userDa->GetUserByUsername($username);
			if($user == -1)
				return "User not found";
			
			// Generate a new 4 digit password
			$random = substr(number_format(time() * rand(),0,'',''),0,4);
			
			$userDa->UpdatePassword($user['UserId'], $random);
			
			//define the receiver of the email
			$to = $user['UserEmail'];
			
			//define the subject of the email
			$subject = 'Lunch on Me PIN Reset';
			$message = "<html><h3>Your new Lunch on Me PIN is: " . $random . "</h3> <br /><br />Please click the following link to log back in. " .
				"<a href=\"http://lxme.net/index.php\">Login to Lunch on Me!</a></html>";
				
			$from = "registration@lxme.net";
			
			$mail_sent = Emailer::SendEmail($to, $from, $subject, $message);
			
			//if the message is sent successfully print "Mail sent". Otherwise print "Mail failed" 
			return (($mail_sent ? "Mail sent" : "Mail failed"));
			
			//return $random;
		}

		public static function ForgotUsername($emailAddress)
		{
			include_once('DbDataAdapter.php');
			
			$userDa = new UsersTableDataAdapter();
			$user = $userDa->GetUserByEmail($emailAddress);
			if($user == -1 || $user == "-1")
				return "User not found";
			
			//define the receiver of the email
			$to = $user['UserEmail'];
			
			//define the subject of the email
			$subject = 'Lunch on Me Username';
			$message = "<html><h3>Your Lunch on Me Username is: " . $user['UserName'] . "</h3> <br /><br />Please click the following link to log back in. " .
				"<a href=\"http://lxme.net/index.php\">Login to Lunch on Me!</a></html>";
				
			$from = "registration@lxme.net";
			
			$mail_sent = Emailer::SendEmail($to, $from, $subject, $message);
			
			//if the message is sent successfully print "Mail sent". Otherwise print "Mail failed" 
			return (($mail_sent ? "Mail sent" : "Mail failed"));
		}

		public static function RequestBillDelete($billId)
		{
			include_once('DbDataAdapter.php');
			
			$billsDa = new BillsTableAdapter();
			$bill = $billsDa->GetBillById($billId);
			
			$usersDa = new UsersTableDataAdapter();
			$userOwedTo = $usersDa->GetUser($bill['UserBillOwedTo']);
			
			$userOwedBy = $usersDa->GetUser($bill['UserBillOwedBy']);
			
			$to = $userOwedTo['UserEmail'];
			$from = 'billrequest@lxme.net';
			
			$message = $userOwedBy['UserFirstName'] . ' ' . $userOwedBy['UserLastName'] . ' has requested that you remove ';
			$message .= ' a bill owed to you at ' . $bill['BillLocation'] . '.  Please click the following link to remove the ';
			
			$urlToDelete = 'http://lxme.net/index.php?redirectTo=individualBillView&redirectParamKey1=billId&redirectParamValue1=' . $bill['BillId'];
			
			$message .= 'bill: <a href="'. $urlToDelete . '">View the Bill</a>';
			
			$subject = "Lunch on Me Request to Remove Bill";
			
			$mailSent = Emailer::SendEmail($to, $from, $subject, $message);
			
			//if the message is sent successfully print "Mail sent". Otherwise print "Mail failed" 
			return (($mailSent ? "Mail sent" : "Mail failed"));
		}

		public static function SendBillDeletedNotification($billId)
		{
			include_once('DbDataAdapter.php');
			
			$billsDa = new BillsTableAdapter();
			$bill = $billsDa->GetBillById($billId);
			
			$usersDa = new UsersTableDataAdapter();
			$userOwedTo = $usersDa->GetUser($bill['UserBillOwedTo']);
			
			$userOwedBy = $usersDa->GetUser($bill['UserBillOwedBy']);
			
			// Send one email to the ower and one to the owee
			$from = 'billmanager@lxme.net';
			
			// Bill Owed To:
			$message = 'A bill has been deleted that was owed by ' . $userOwedBy['UserFirstName'] . ' ' . $userOwedBy['UserLastName'] . ' ';
			$message .= 'to ' . $userOwedTo['UserFirstName'] . ' ' . $userOwedTo['UserLastName'] . ' at ' . $bill['BillLocation'] . '. ';
			
			$urlToBill = 'http://lxme.net/index.php?redirectTo=individualBillView&redirectParamKey1=billId&redirectParamValue1=' . $bill['BillId'];
			
			$message .= 'To view the bill click the following link: <a href="' . $urlToBill . '">View the Bill</a>';
			$subject = "Lunch on Me Bill Deleted";
			
			$to = $userOwedTo['UserEmail'];
			$mailSent = Emailer::SendEmail($to, $from, $subject, $message);
			
			$to = $userOwedBy['UserEmail'];
			$mailSent = Emailer::SendEmail($to, $from, $subject, $message);
			
			//if the message is sent successfully print "Mail sent". Otherwise print "Mail failed" 
			return (($mailSent ? "Mail sent" : "Mail failed"));
		}

		// requiresConfirmation should be either 0 or 1
		public static function SendBillCreatedNotification($billId, $requiresConfirmation)
		{
			include_once('DbDataAdapter.php');
			
			$billsDa = new BillsTableAdapter();
			$bill = $billsDa->GetBillById($billId);
			
			$usersDa = new UsersTableDataAdapter();
			$userOwedTo = $usersDa->GetUser($bill['UserBillOwedTo']);
			
			$userOwedBy = $usersDa->GetUser($bill['UserBillOwedBy']);
			
			// Send one email to the ower and one to the owee
			$from = 'billmanager@lxme.net';
			
			// Bill Owed To:
			$message = 'A bill has been created that is owed by ' . $userOwedBy['UserFirstName'] . ' ' . $userOwedBy['UserLastName'] . ' ';
			$message .= 'to ' . $userOwedTo['UserFirstName'] . ' ' . $userOwedTo['UserLastName'] . ' at ' . $bill['BillLocation'] . '. ';
			$message .= 'The amount was specified as ' . $bill['BillAmount'] . '. ';
			
			$urlToBill = 'http://lxme.net/index.php?redirectTo=individualBillView&redirectParamKey1=billId&redirectParamValue1=' . $bill['BillId'];
			
			$message .= 'To view the bill click the following link: <a href="' . $urlToBill . '">View the Bill</a>';
			$subject = "Lunch on Me Bill Created";
			
			$to = $userOwedTo['UserEmail'];
			$mailSent = Emailer::SendEmail($to, $from, $subject, $message);
			
			// If there is no confirmation required, send the same message
			if($requiresConfirmation == 0 || $requiresConfirmation == "0")
			{
				$to = $userOwedBy['UserEmail'];
				$mailSent = Emailer::SendEmail($to, $from, $subject, $message);
			}
			else 
			{
				$message = 'The following bill requires your confirmation. ';
				$message .= 'A bill has been created that is owed by ' . $userOwedBy['UserFirstName'] . ' ' . $userOwedBy['UserLastName'] . ' ';
				$message .= 'to ' . $userOwedTo['UserFirstName'] . ' ' . $userOwedTo['UserLastName'] . ' at ' . $bill['BillLocation'] . '. ';
				$message .= 'The amount was specified as ' . $bill['BillAmount'] . '.';
				
				$urlToBill = 'http://lxme.net/index.php?redirectTo=individualBillView&redirectParamKey1=billId&redirectParamValue1=' . $bill['BillId'];
				
				$message .= 'To confirm the bill click the following link: <a href="' . $urlToBill . '">View the Bill</a>';
				
				$to = $userOwedBy['UserEmail'];
				$mailSent = Emailer::SendEmail($to, $from, $subject, $message);
			}
			
			
			//if the message is sent successfully print "Mail sent". Otherwise print "Mail failed" 
			return (($mailSent ? "Mail sent" : "Mail failed"));
		}
		
		public static function SendBillConfirmedNotification($billId)
		{
			include_once('DbDataAdapter.php');
			
			$billsDa = new BillsTableAdapter();
			$bill = $billsDa->GetBillById($billId);
			
			$usersDa = new UsersTableDataAdapter();
			$userOwedTo = $usersDa->GetUser($bill['UserBillOwedTo']);
			$userOwedBy = $usersDa->GetUser($bill['UserBillOwedBy']);
			
			// Send an email to the owed
			$from = 'billmanager@lxme.net';
			
			$subject = 'Lunch on Me Bill Confirmation Notification';
			
			$message = 'A bill that is owed to you has been confirmed by ' . $userOwedBy['UserFirstName'] . ' ' . $userOwedBy['UserLastName'] . '. ';
			$message .= 'The bill was created on ' . date('m-d-Y', strtotime($bill['BillDate'])) . ' at ' . stripcslashes($bill['BillLocation']) . ' ';
			$message .= ' with an amount specified as ' . $bill['BillAmount'] . '. ';
			
			$urlToBill = 'http://lxme.net/index.php?redirectTo=individualBillView&redirectParamKey1=billId&redirectParamValue1=' . $billId;
			
			$message .= 'To view the bill click the following link: <a href="' . $urlToBill . '">View the Bill</a>';
			
			$to = $userOwedTo['UserEmail'];
			$mailSent = Emailer::SendEmail($to, $from, $subject, $message);
			
			return (($mailSent ? "Mail sent" : "Mail failed"));
		}
		
		public static function CreateConfirmationCode($dateCreated)
		{
			$replaceChars = array("-", " ", ":");
			$replaceWith = array("", "", "");
			$confCode = str_replace($replaceChars, $replaceWith, $dateCreated);
			
			$confCode = $confCode - 150;
			
			return $confCode;		
		}
	}

?>