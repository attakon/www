<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);

session_start();

include("conexion.php");

include("data_objects/DAOUser.php");
include("data_objects/DAOUserEvents.php");

$conexion = conecDb();
$username = $_POST['vb_login_username'];
$password = $_POST['vb_login_password'];

//echo $username." ".$password;
$q = "SELECT id_usuario, username FROM usuario WHERE username ='".$username."' and
    pass = MD5('".$password."')";
$rs = mysql_query($q,$conexion) or die ($q);

if(mysql_num_rows($rs)==1){
    $userData = DAOUser_getUserByName($username);
    $_SESSION['user']=$username;
    $_SESSION['userId'] = $userData['id_usuario'];

    //Begin Adding Raul July 28, 2012
    DAOUserEvents_logEvent($userData['id_usuario'],'log_in','');
    //End Adding Raul July 28, 2012

    header ('Location: '.$_SESSION['lastvisitedurl']);
}else{
    $_SESSION['wpass'] ='1';
    header ('Location: '.$_SESSION['lastvisitedurl']);
}
?>