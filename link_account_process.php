<?php
	include_once ("utils/ValidateSignedIn.php");
	include_once ("GLOBALS.php");
	$key = "fb_".$FB_APP_ID."_user_id";
	if(isset($_SESSION[$key])){
		$fbUserId=$_SESSION[$key];
		$currentHCUserId=$_SESSION['userId'];

		$incomingUserName = $_POST['username'];
		$incomingPassword = $_POST['password'];

		include_once ("data_objects/DAOUser.php");
		$prettyUserName = DAOUser_login($incomingUserName,$incomingPassword);

		if($prettyUserName){
			$data = DAOUser_getUserByName($incomingUserName);
			$newUserId = $data['id_usuario'];

			DAOUser_deleteLinkUserToFBUser($currentHCUserId, $fbUserId);
			DAOUser_linkUserToFBUser($newUserId,$fbUserId);
			$_SESSION['userId']=$newUserId;
			$_SESSION['user']=$prettyUserName;

			$_SESSION['userDisplayName'] = $_SESSION['fbUserName']." (".$prettyUserName.")";

			include_once ('data_objects/DAOLog.php');
	    	DAOLog_log($_SESSION['fbUserName'].' linked his account with '.$incomingUserName);

			include_once('container.php');
			include_once('CustomTags.php');
			showPage("", false, parrafoOK("Su cuenta ha sido conectada con ".$incomingUserName), "");
		}else{
			include_once('container.php');
			include_once('CustomTags.php');
			showPage("", false, parrafoError("Credenciales no correctas para ".$incomingUserName), "");
		}
	}else{
		showPage("", false, parrafoOK("Su cuenta ha sido conectada con ".$incomingUserName), "");	
	}
?>