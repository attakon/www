<?php
error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);
session_start();
//error_reporting(E_ALL ^ E_WARNING);
include_once("CustomTags.php");
//include_once $_SERVER['DOCUMENT_ROOT'].'/data_objects/DAOPermissions.php';
//include_once('data_objects/DAOPermissions.php');

$user = $_SESSION['user'];
$userId = $_SESSION['userId'];

if($user!=null) {
    
//    if(DAOPermissions_isUserGrantedWithPermission($userId, 'admin_button', 'Y')){
//        $adminLink = ' <label><a href="'.$path.'/admin.php" >Admin</a></label> ';
//    }
    echo '
        <label class="user">'.userLink($path,$userId ,$user).'</label>'
        .$adminLink.'
        <label><a href="'.$path.'/procUserReq.php?req=cs" >Cerrar sesi&oacute;n</a>
        </label>';
}else if($_SESSION['wpass'] == '1') {
        include('header_loginForm.php');
        echo "<p align='right'>
        <font face='Verdana' color='#FF0000'>
            usuario o password incorrecto
        </font>
        </p>";
        $_SESSION['wpass']='0';
    }else {
        include('header_loginForm.php');
    }
?>
