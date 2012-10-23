<?php
// ini_set('display_errors', 'On');
// error_reporting(E_ALL);



$incomingUserName = $_POST['vb_login_username'];
$incomingPassword = $_POST['vb_login_password'];

//echo $username." ".$password;
// $q = "SELECT id_usuario, username FROM usuario WHERE username ='".$username."' and
    // pass = MD5('".$password."')";

// $rs = mysql_query($q,$conexion) or die ($q);

include("data_objects/DAOUser.php");
$prettyUserName = DAOUser_login($incomingUserName,$incomingPassword);

if($prettyUserName){
    $userData = DAOUser_getUserByName($incomingUserName);
    session_start();
    $_SESSION['user'] = $userData['username'];
    $_SESSION['userDisplayName']= $userData['username'];
    $_SESSION['userId'] = $userData['id_usuario'];

    include("data_objects/DAOUserEvents.php");
    include("GLOBALS.php");
    DAOUserEvents_logEventById($userData['id_usuario'], USER_EVENT_LOG_IN,'');

    include_once 'container.php';
    redirectToLastVisitedPage();
}else{
    $_SESSION['wpass'] ='1';
    include_once 'data_objects/DAOLog.php';
    DAOLog_log('Attempt to log in by incomingUserName:'.$incomingUserName,'');
    include_once 'container.php';
    redirectToLastVisitedPage();
}
?>