<?php
include_once("data_objects/DAOUserEvents.php");
session_start();


// require_once "facebook-facebook-php-sdk-98f2be1/src/facebook.php";
// $facebook = new Facebook(array(
//     'appId'  => '285185548248441',
//     'secret' => 'dc7a0ea4f8d1bad33bf046bbe3673918',
//     'cookie' => true
// ));
// try{
// 	// $facebook->setAccessToken(null);
// }catch (FacebookApiException $e) {
//    error_log($e);
//    $facebook->setSession(null);
// }


// header('Location: http://localhost/fb/index.php');

//close session
	if(isset($_SESSION['userId'])){
		include_once 'GLOBALS.php';
	   	DAOUserEvents_logEventById($_SESSION['userId'],USER_EVENT_LOG_OUT,'');
	}
	
	// foreach ($_SESSION as $key => $value) {
	// 	unset($_SESSION[$key]);
	// }
	// $_SESSION['userId']=null;
	// $_SESSION['wpass']=null;
	// $_SESSION['userId']=null;
	include_once "GLOBALS.php";
	unset($_SESSION['user']);
	unset($_SESSION['userId']);
	unset($_SESSION['wpass']);
	unset($_SESSION['fb_'.$FB_APP_ID.'_user_id']);
	unset($_SESSION['fbUserName']);
	unset($_SESSION['fbImgURL']);
	unset($_SESSION['userDisplayName']);
	unset($_SESSION);
	session_unset();
	if(isset($_SESSION['lastvisitedurl'])){
		header('Location: '.$_SESSION['lastvisitedurl']);
	}else{
		header('Location: ./index.php');
	}
	
?>
