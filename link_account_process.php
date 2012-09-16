<?php
	include_once ("utils/ValidateSignedIn.php");
	if(isset($_SESSION['fb_285185548248441_user_id'])){
		$fbUserId=$_SESSION['fb_285185548248441_user_id'];
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

			include_once('container.php');
			include_once('CustomTags.php');
			showPage("", false, parrafoOK("Su cuenta ha sido conectada con ".$incomingUserName), "");
		}
	}
?>