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
			$fql = 'SELECT name, first_name, last_name, pic_small from user where uid = ' . $fb_user_id;
	    	$ret_obj = $facebook->api(array(
	                                   'method' => 'fql.query',
	                                   'query' => $fql,
	                                 ));
	    	$fbUserName = $ret_obj[0]['name'];
	    	$fbPicSmallURL = $ret_obj[0]['pic_small'];
	    	$fbFirstName = $ret_obj[0]['first_name'];
	    	$fbLastName = $ret_obj[0]['last_name'];
	    	
	    	$_SESSION['fbImgURL']=$fbPicSmallURL;
	    	$_SESSION['fbUserName']=$fbUserName;

			include_once ('data_objects/DAOFBUser.php');
	    	$hcUserId = DAOFBUser_getHCUserId($fb_user_id);
	    	// print_r(print_r($ret_obj,true));
	    	if(!$hcUserId){
	    		//Is new user
	    		include_once ('data_objects/DAOLog.php');
	    		DAOLog_log($fbUserName.' is a new user and will get registered', print_r($ret_obj,true));
	    		include_once ('data_objects/DAOUser.php');
    			DAOUser_registerUser($fbFirstName, $fbLastName, '', '', $fbUserName);
				$userData = DAOUser_getUserByName($fbUserName);
				$userId = $userData['id_usuario'];
    			DAOUser_linkUserToFBUser($userId, $fb_user_id);
	    	}
	    	include_once('data_objects/DAOFBUser.php');
	    	$hcUserId = DAOFBUser_getHCUserId($fb_user_id);

	    	include_once ('data_objects/DAOUser.php');
	    	$row = DAOUser_getUserById($hcUserId);
	    	$_SESSION['userId'] = $row['id_usuario'];
	    	$_SESSION['user'] = $row['username'];
	    	if($row['id_escuela']==0){
	    		$_SESSION['userDisplayName'] = $fbUserName;
	    	}else{
	    		$_SESSION['userDisplayName'] = $fbUserName." (".$row['username'].")";
	    	}
	    	
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