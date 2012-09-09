<?php
error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);
session_start();
//error_reporting(E_ALL ^ E_WARNING);
include_once("CustomTags.php");
$cwd = str_replace('/forum', '', getcwd());
$incl = $cwd .'/data_objects/DAOPermissions.php';
include_once $incl;
//include_once $_SERVER['DOCUMENT_ROOT'].'/data_objects/DAOPermissions.php';
//include_once('data_objects/DAOPermissions.php');
//include_once 'DAOPermissions.php';


$user = $_SESSION['user'];
$userId = $_SESSION['userId'];

if($userId!=null) {
    if(DAOPermissions_isUserGrantedWithPermission($userId, 'admin_button', 'Y')){
        $adminLink = ' <label><a href="'.$path.'/admin.php" >Admin</a></label> ';
    }
    echo '
        <label class="user">'.userLink($path,$userId ,$user).'</label>'
        .$adminLink.'
        <label><a href="'.$path.'/procUserReq.php?req=cs" >Logout</a>
        </label>';
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
