<?php
include_once ("utils/DBUtils.php");

// user_event_type_id	event_name	description
// 1	log_in	user logs in HC
// 2	log_out	user logs out of HC
// 3	unlock_another_code	User unlocks and sees somebody else code
// 4	solve_problem	User solves a problem in practice mode
// 5	download_problem_set_pdf	User donwloads a pdf for a contest
// 6	failed_log_in	User fails getting logged in

// function DAOUserEvents_logEvent($userId, $eventName, $extraText){ 
// 	$query = 
// 	  "INSERT INTO user_events (user_id, user_event_type_id, extra_text) VALUES 
// 	  (
// 	  ".$userId.", 
// 	  (SELECT user_event_type_id FROM user_event_types WHERE event_name ='".$eventName."'), 
// 	  '".$extraText."'
// 	)";
// 	// echo $query;
//   	runQuery($query);
// }

function DAOUserEvents_logEventById($userId, $eventId, $extraText){ 
	$extraText = mysql_escape_string($extraText);
	$query = 
	  "INSERT INTO user_events (user_id, user_event_type_id, extra_text) VALUES 
	  (
	  '".$userId."', 
	  '".$eventId."', 
	  '".$extraText."'
	)";
	// echo $query;
  	runQuery($query);
}

?>
