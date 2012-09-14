<?php
include_once ("utils/DBUtils.php");

function DAOLog_log($logText, $extraLogText, $logType){
  $query = "INSERT INTO log (text, extra_text, log_type_id) VALUES 
    (
    	'".$logText."',
    	'".$extraLogText."',
    	(SELECT id FROM log_type WHERE name ='".$logType."')
    )";
  return runQuery($query);
}
?>
