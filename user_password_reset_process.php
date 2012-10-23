<?php
	session_start();
	include_once ("utils/ValidateSignedIn.php");
	include_once 'container.php';

	
	$currentPassword = $_POST['current_password'];
	$incomingPassword = $_POST['new_password'];
	$incomingPassword2 = $_POST['new_password_2'];
	if($incomingPassword!=$incomingPassword2){
		$_SESSION['message']="Passwords don't match ";
		$_SESSION['message_type']='error';
    	redirectToLastVisitedPage();
	}

	include_once ("data_objects/DAOUser.php");
	$prettyUserName = DAOUser_loginWithId($_SESSION['userId'],$currentPassword);

	if($prettyUserName){
		$userData = DAOUser_getUserByName($prettyUserName);
		$newUserId = $userData['id_usuario'];

		DAOUser_updateUserPaswword($_SESSION['userId'],$incomingPassword2);

		include_once ('data_objects/DAOLog.php');
    	DAOLog_log($userData['username'].' changed his password ');

    	$_SESSION['message']="Your password has been succesfully updated.";
    	redirectToLastVisitedPage();
	}else{
		$_SESSION['message']="Current password is incorrect.";
		$_SESSION['message_type']='error';
    	redirectToLastVisitedPage();
	}
?>