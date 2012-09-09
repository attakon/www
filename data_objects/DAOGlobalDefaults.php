<?php

include_once ("utils/DBUtils.php");

function DAOGlobalDefaults_getGlobalValue($name){
   $query = "SELECT value FROM global_defaults WHERE name = '".$name."'";
   $value = getRow($query);
   return $value;
}


?>
