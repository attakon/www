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
$userDisplayName = $_SESSION['userDisplayName'];
// $fbUserId = $_SESSION['fb_285185548248441_user_id'];
// print_r($_SESSION);

// get status URL
//echo $facebook->getUser(); 
// $params = array(
//   'ok_session' => 'https://www.myapp.com/',
//   'no_user' => 'https://www.myapp.com/no_user',
//   'no_session' => 'https://www.myapp.com/no_session',
// );

// $next_url = $facebook->getLoginStatusUrl($params);
// echo $next_url;

// $fb_user_id = $facebook->getUser();

if($userId!=null) {
    // print_r($_SESSION);
    if(DAOPermissions_isUserGrantedWithPermission($userId, 'admin_feature', 'Y')){
        $adminLink = '
            <li >
                <a href="./admin.php" >Admin</a>
            <li>';
    }
    if(DAOPermissions_isUserGrantedWithPermission($userId, 'author_feature', 'Y')){
        $authorLink = '
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" role="button"
                    href="#" id="dLabel">
                    Author
                    <b class="caret"></b>
                </a>
                <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel" style="text-align: left;">
                    <li><a href="./admin_myleagues.php">My Leagues</a></li>
                    <li>
                        <a href="./admin_myproblems.php">My Problems</a>
                    </li>
                    <li>
                        <a href="./admin_mycontests.php">My Contests</a>
                    </li>
                </ul>
            </li>';
    }

    $img="";
    if(isset($_SESSION['fbImgURL'])){
        $img='<img width="32px" height="32px" src='.$_SESSION['fbImgURL'].'/>';
    }
    // echo '
    //     <div id="avatar">
    //         '.$img.'
    //     </div>
    //     <ul class="nav">
    //         <li class="dropdown">
    //             <label class="user">'.userLink($path,$userId ,$userDisplayName).'</label>
    //         </li>'
    //         .$authorLink.$adminLink.'
    //         <label>
    //             <a href="./user_settings.php" >Settings</a>
    //         </label>
    //         <label>
    //             <a href="./logout.php" >Logout</a>
    //         </label>
    //     </ul>';
    echo '
        <div id="avatar">
            '.$img.'
        </div>
        <ul class="nav nav-pills pull-right" style="margin-bottom: 0px; height:29px;" >
            <li>
                '.userLink($path,$userId ,$userDisplayName).'
            </li>'
            .$authorLink.$adminLink.'
            <li>
                <a href="./user_settings.php" >Settings</a>
            </li>
            <li>
                <a href="./logout.php" >Logout</a>
            </li>
        </ul>';
}
else if($_SESSION['wpass'] == '1') {
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
