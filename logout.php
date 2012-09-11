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
	$_SESSION['user']=null;
	$_SESSION['userId']=null;
	$_SESSION['wpass']=null;
	header('Location: '.$_SESSION['lastvisitedurl']);
?>
