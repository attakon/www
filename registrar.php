<?php
include_once('./conexion.php');
//include_once $_SERVER['DOCUMENT_ROOT'] . 'huachotorneos/securimage/securimage.php';
include_once('./securimage/securimage.php');
$securimage = new Securimage();
$username_rC= $_GET['user'];
$fn= $_GET['first_name'];
$ln= $_GET['last_name'];
$email_rC= $_GET['email'];
$school= $_GET['school'];
$password_rC= $_GET['pass'];
$repass= $_GET['repass'];
$params = "user=".$username_rC."&first_name=".$fn."&last_name=".$ln."&email=".$email_rC."&school=".$school;
$toheader="Location: registration.php?".$params;
if(strlen($password_rC)<5 || strlen($password_rC)>15 && $password_rC!=$repass){
    header($toheader."&message=pw");
}else if(!isValidUser($username_rC)){
    header($toheader."&message=invalidUser");
}else if(!isValidEmail($email_rC)){
    header($toheader."&message=invalidEmail");
}else if(!isAvailableUser($username_rC)){
    header($toheader."&message=unavailableUser");
}else if(!isAvailableEmail($email_rC)){
    header($toheader."&message=unavailableEmail");
}else if($school==0){
    header($toheader."&message=Please choose school");
}else if($securimage->check($_GET['captcha_code']) == false) {
    header($toheader."&message=El texto ingresado no corresponde a la imagen");
}else{  // really register
    $q3 = "CALL sp__registerConcursante
             ('".$fn."','".$ln."','".$school."','".$email_rC."','".$username_rC."','".$password_rC."');";
    mysql_query($q3,conecDb()) or die("horror : ".$q3);

    
    include("registrar_in_forum.php");
    header($toheader."&message=success");
}

function isAvailableEmail($x){
    $query = "SELECT * FROM usuario WHERE email ='".$x."'";
    $rsEmail = mysql_query($query,conecDb()) or die('error on :isAvailableEmail'.$query);
    if(mysql_num_rows($rsEmail)==0){
        return true;
    }else
    return false;
}
function isAvailableUser($x){
    //    include('conexion.php');
    $conexion = conecDb();
    $query = "SELECT * FROM usuario WHERE username ='".$x."'";
    $rsUser = mysql_query($query,$conexion);
    if(mysql_num_rows($rsUser)==0){
        return true;
    }else
    return false;
}
function isValidUser($x){
    return strlen($x)>=2 && strlen($x)<=15;
}
function isValidEmail($x){
    $count = substr_count($x,'@');
    $count2 = substr_count($x,'.');
    $indexOf = strpos($x,'@');
    if($count==1 && $indexOf!=0 && $count2==1){
        return true;
    }
    return false;
}
?>
