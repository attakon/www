<?php
require_once('./global.php');
include_once("includes/class_core.php");
require_once("includes/init.php");    //$vBulletin is here

$dataman =& datamanager_init('User', $vbulletin, ERRTYPE_ARRAY);
$dataman->set("username", $username_rC);
$dataman->set('password', $password_rC);
$dataman->set("email", $email_rC);
if (!empty($vbulletin->GPC['options'])){
    foreach ($vbulletin->GPC['options'] AS $optionname => $onoff){
        $userdata->set_bitfield('options', $optionname, $onoff);
    }
}
//$dataman->pre_save();
$dataman->save();
?>
