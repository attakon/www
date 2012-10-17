<?php
include_once('conexion.php');
include_once('securimage/securimage.php');

$securimage = new Securimage();
$username_rC= $_GET['user'];
$firstName_rC= $_GET['first_name'];
$lastName_rC= $_GET['last_name'];
$email_rC= $_GET['email'];
// $school_rC= $_GET['school'];
$password_rC= $_GET['pass'];
$repass= $_GET['repass'];
$params = "user=".$username_rC."&first_name=".$firstName_rC."&last_name=".$lastName_rC."&email=".$email_rC;
$toheader_rC="Location: ../register.php?".$params;
if(strlen($password_rC)<5 || strlen($password_rC)>15 || $password_rC!=$repass){
    header($toheader_rC."&message=error en password");
}else if(!isValidUser($username_rC)){
    header($toheader_rC."&message=Nombre de Usuario no valido");
}else if(!isValidEmail($email_rC)){
    header($toheader_rC."&message=Email no valido");
}else if(!isAvailableUser($username_rC)){
    header($toheader_rC."&message=Nombre de Usuario No Disponible");
}else if(!isAvailableEmail($email_rC)){
    header($toheader_rC."&message=Email No Disponible");
}else if($securimage->check($_GET['captcha_code']) == false) {
    header($toheader_rC."&message=El texto ingresado no corresponde a la imagen");
}else{  // really register
    //            mail($to, $subject, $body);

    //register into the forum first
    //include_once("rC_register_forum.php");

    // register into the HuaH then

    //Begin raul 21-Oct-2011 stop calling procedure
    include_once ('data_objects/DAOUser.php');
    DAOUser_registerUser($firstName_rC, $lastName_rC, $email_rC, $username_rC, $password_rC);
    include_once ('emailing.php');
    sendWelcomeEmail($email_rC,$username_rC);
    // $q3_rC = "INSERT INTO usuario (Nombres, Apellidos, id_escuela, Ciclo, email, username, pass)
             // values ('".$firstName_rC."','".$lastName_rC."','".$school_rC."',-1,'".$email_rC."','".$username_rC."',MD5('".$password_rC."'));";
    //End raul 21-Oct-2011

    // mysql_query($q3_rC,conecDb()) or die("horror : ".$q3_rC);

    header($toheader_rC."&message=success");
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
    return strlen($x)>=3 && strlen($x)<=15;
}

function isValidEmail($x){
    $count = substr_count($x,'@');
    $count2 = substr_count($x,'.');
    $indexOfAt = strpos($x,'@');
    $indexOfDot = strpos($x,'.');
    $n = strlen($x);

    // check if '@' is at the first position or at
    //last position or absent or used more than once in given email
    if($indexOfAt==0 || $indexOfAt==$n || substr_count($x,'@')!=1){
        return false;
    }

    // check if '.' is at the first position or at last position
    //or absent in given email
    if($indexOfDot==0 || $indexOfDot==$n || substr_count($x,'.')==0){
        return false;
    }

    //check for blank spaces
    if(substr_count($x, " ")!=0){
        return false;
    }
    return true;
}
?>
