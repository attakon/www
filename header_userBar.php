<?php
error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);
session_start();
//error_reporting(E_ALL ^ E_WARNING);


// $cwd = str_replace('/forum', '', getcwd());
include_once("CustomTags.php");
// $incl = 
include_once '/data_objects/DAOPermissions.php';
//include_once $_SERVER['DOCUMENT_ROOT'].'/data_objects/DAOPermissions.php';
include_once('data_objects/DAOPermissions.php');


$user = $_SESSION['user'];
$userId = $_SESSION['userId'];

// FB api
require_once "facebook-facebook-php-sdk-98f2be1/src/facebook.php";
$facebook = new Facebook(array(
    'appId'  => '285185548248441',
    'secret' => 'dc7a0ea4f8d1bad33bf046bbe3673918',
    'cookie' => true
));

// get status URL
//echo $facebook->getUser(); 
// $params = array(
//   'ok_session' => 'https://www.myapp.com/',
//   'no_user' => 'https://www.myapp.com/no_user',
//   'no_session' => 'https://www.myapp.com/no_session',
// );

// $next_url = $facebook->getLoginStatusUrl($params);
// echo $next_url;

$fb_user_id = $facebook->getUser();

print_r($_SESSION);


if($userId!=null) {
    if(DAOPermissions_isUserGrantedWithPermission($userId, 'admin_button', 'Y')){
        $adminLink = ' <label><a href="'.$path.'/admin.php" >Admin</a></label> ';
    }

    echo '
        <div id="avatar">
        </div>
        <div id="username">
            <label class="user">'.userLink($path,$userId ,$user).'</label>'
            .$adminLink.'
            <label><a href="'.$path.'/logout.php" >Logout</a>
            </label>
        </div>';
}else if($fb_user_id){
        // Proceed knowing you have a logged in user who's authenticated.
    try {
        $fql = 'SELECT name, pic_small from user where uid = ' . $fb_user_id;
        $ret_obj = $facebook->api(array(
                                   'method' => 'fql.query',
                                   'query' => $fql,
                                 ));

        print_r($facebook->api('/me'));
        // FQL queries return the results in an array, so we have
        //  to get the user's name from the first element in the array.
        $fbUserName = $ret_obj[0]['name'];
        $fbPicSmall = $ret_obj[0]['pic_small'];

        $params = array( 'next' => 'http://localhost:8888/logout.php/');
        $fb_user_id=null;

        // $user_profile = $facebook->api('/me');
        // print_r($user_profile);
        
     
        echo '
        <div id="avatar">
            <img width="22px" height="22px" src='.$fbPicSmall.'/>
        </div>
        <div id="username">
            <label class="user">'.userLink("",1, $fbUserName).'</label>'
            .$adminLink.'
            <label><a onclick="fblogout()" >Logout</a>
            </label>
        </div>';

    } catch(FacebookApiException $e) {
        // If the user is logged out, you can have a 
        // user ID even though the access token is invalid.
        // In this case, we'll get an exception, so we'll
        // just ask the user to login again here.
        // $login_url = $facebook->getLoginUrl(); 
        // echo 'Please <a href="' . $login_url . '">login.</a>';
        // echo $e->getType();
        // echo $e->getMessage();
        // die;
        include_once ('header_loginForm.php');
    }   
}else if($_SESSION['wpass'] == '1') {
        include_once ('header_loginForm.php');
        echo "<p align='right'>
        <font face='Verdana' color='#FF0000'>
            usuario o password incorrecto
        </font>
        </p>";
        $_SESSION['wpass']='0';
}else {
        include_once ('header_loginForm.php');
}
?>
