<?php
include_once ("utils/DBUtils.php");

function DAOLog_log($logText, $extraLogText=null, $logType=null){
	$logText = mysql_escape_string($logText);
	$query = "INSERT INTO log (text, extra_text, log_type_id) VALUES 
	    (
	    	'".$logText."',
	    	'".$extraLogText."',
	    	(SELECT id FROM log_type WHERE name ='".$logType."')
	    )";
	  return runQuery($query);
}
?>
