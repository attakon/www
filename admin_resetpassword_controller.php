<?php
session_start();
include_once 'utils/ValidateAdmin.php';

include_once 'CustomTags.php';
include_once 'container.php';
include_once 'utils/DBUtils.php';

$REQ = $_POST;

$pass = $REQ['new_password'];
$username = $REQ['username'];

$huahQry = "UPDATE usuario 
            SET PASS = MD5('".$pass."') 
            WHERE USERNAME ='".$username."'";
runQuery($huahQry);

$huahVbQry= "UPDATE user
set password = MD5(concat(MD5('".$pass."'), user.salt))
WHERE username = '".$username."'";
runQueryOnHuaHVB($huahVbQry);
showPage('Password Reset Done', false, parrafoOK('Password has been reset successfully'), null);
?>