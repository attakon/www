<?php
	session_start();


	require_once "facebook-facebook-php-sdk-98f2be1/src/facebook.php";
	$facebook = new Facebook(array(
	    'appId'  => '285185548248441',
	    'secret' => 'dc7a0ea4f8d1bad33bf046bbe3673918',
	    'cookie' => true
	));
	$fb_user_id = $facebook->getUser();
	
	// print_r($fb_user_id);
	if($fb_user_id){
		try{
			$fql = 'SELECT name, pic_small from user where uid = ' . $fb_user_id;
	    	$ret_obj = $facebook->api(array(
	                                   'method' => 'fql.query',
	                                   'query' => $fql,
	                                 ));
	    	$fbUserName = $ret_obj[0]['name'];
	    	$fbPicSmallURL = $ret_obj[0]['pic_small'];
	    	$_SESSION['fbImgURL']=$fbPicSmallURL;
	    	$_SESSION['fbUserName']=$fbUserName;

	    	include_once('data_objects/DAOFBUser.php');
        	DAOFBUser_registerOrUpdateUser($facebook->api('/me'));

		 } catch(FacebookApiException $e) {
	        // If the user is logged out, you can have a 
	        // user ID even though the access token is invalid.
	        // In this case, we'll get an exception, so we'll
	        // just ask the user to login again here.
	        // $login_url = $facebook->getLoginUrl(); 
	        // echo 'Please <a href="' . $login_url . '">login.</a>';
	        // echo $e->getType();
	        // echo $e->getMessage();
	        // $login_url = $facebook->getLoginUrl();
	        // echo $login_url;
	        // header ('Location: '.$login_url);
	        // die;
    	} 
		
	}
	// print_r($_SESSION);
	
	// print_r($_GET);
	if(!$_GET['nextPage']){
		header ('Location: .');
	}else{
		header ('Location: '.$_GET['nextPage']);
	}
?>