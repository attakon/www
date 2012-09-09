<?php
include("conexion.php");
$rC_con = conecDb();
$pass = $vbulletin->GPC['newpassword_md5'] ? $vbulletin->GPC['newpassword_md5'] : $vbulletin->GPC['newpassword'];
$query ="UPDATE usuario SET password = '".$pass."' where username = '".$vbulletin->GPC['newpassword']."'";
$rC_query = mysql_query();

?>
