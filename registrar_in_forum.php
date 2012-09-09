<?php
//INSERVIBLE·######################################################

define('CWD','forum');
define('VB_AREA','OUTSIDE');
define('THIS_SCRIPT','OUTSIDE');
echo "XXXXXXXXXXXXXXXXXXXXXXX";
require_once(CWD.'/global.php');
echo "XXXXXXXXXXXXXXXXXXXXXXX2";
include_once(CWD."/includes/class_core.php");
require_once(CWD."/includes/init.php");    //vBulletin is here

//include_once("includes/functions.php"); // requires vBulletin

//$username_rC ="xxddx";
//$password_rC = "cocococ";
//$email_rC = "mm@hotm.com";

$dataman =& datamanager_init('User', $vbulletin, ERRTYPE_ARRAY);
$dataman->set("username", "xxddx");
$dataman->set('password', "cocococ");
$dataman->set("email", "mm@hotm.com");

if (!empty($vbulletin->GPC['options']))
{
    foreach ($vbulletin->GPC['options'] AS $optionname => $onoff)
    {
        $userdata->set_bitfield('options', $optionname, $onoff);
    }
}
//$dataman->pre_save();
$dataman->save();
//}

?>
