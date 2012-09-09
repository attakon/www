<?php
include_once ("utils/DBUtils.php");

function DAOUserEvents_logEvent($userId, $eventName, $extraText){ 
  $query = "INSERT INTO user_events (user_id, user_event_type_id, extra_text) VALUES 
    (".$userId.", (SELECT user_event_type_id FROM user_event_types WHERE event_name ='".$eventName."'), '".$extraText."')";
  return runQuery($query);
}

?>
