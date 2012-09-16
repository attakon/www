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
	   	DAOUserEvents_logEvent($_SESSION['userId'],'log_out','');
	}
	
	// foreach ($_SESSION as $key => $value) {
	// 	unset($_SESSION[$key]);
	// }
	// $_SESSION['userId']=null;
	// $_SESSION['wpass']=null;
	// $_SESSION['userId']=null;
	unset($_SESSION['user']);
	unset($_SESSION['userId']);
	unset($_SESSION['wpass']);
	unset($_SESSION['fb_285185548248441_user_id']);
	unset($_SESSION['fbUserName']);
	unset($_SESSION['fbImgURL']);
	unset($_SESSION['userDisplayName']);
	header('Location: '.$_SESSION['lastvisitedurl']);
?>
