<?php
include_once ("utils/DBUtils.php");

// BEGIN ARENA
function DAOCampaign_registerAttempt($contestId,$campaignId, $problemId, $time, $wasSolved, $sourceCode){

  // $query = sprintf("SELECT * FROM users WHERE user='%s' AND password='%s'",
  //           mysql_real_escape_string($user),
  //           mysql_real_escape_string($password));
    include_once 'data_objects/DAOConcurso.php';
    $elapsedSeconds = DAOConcurso_getContestElapsedTime($contestId);
    $query = sprintf("CALL SP__UD_CAMPAIGNDETALLE(%s,'%s',SEC_TO_TIME(".$elapsedSeconds."),'%s','%s')"
      ,$campaignId
      ,$problemId
      ,$wasSolved
      ,$sourceCode);
    // print_r($query);
    runQuery($query);
}

// END ARENA 
function DAOCampaign_getCampaignData($campaignId){
  $query = "SELECT id_concurso, id_usuario FROM campaign WHERE id_campaign='".$campaignId."'";
  return getWholeRow($query);
}

function DAOCampaign_getUserCampaigns($contestId){
  $campaignQuery = "SELECT camp.id_campaign, camp.puesto,
            camp.new_ranking, user.id_usuario, user.username, camp.puntos, camp.penalizacion " .
                    "FROM usuario user, campaign camp " .
                    "WHERE user.id_usuario = camp.id_usuario " .
                    "AND camp.Id_Concurso = '".$contestId."' " .
                    "ORDER BY camp.puesto ASC, camp.id_campaign ASC";

  $campaignData = getRowsInArray($campaignQuery);
  return $campaignData;
}

function DAOCampaign_getCampaignForUser($userId, $contestId){
  $campaignQuery = "SELECT camp.id_campaign, camp.puesto,
            camp.new_ranking, user.id_usuario, user.username, camp.puntos, camp.penalizacion " .
                    "FROM usuario user, campaign camp " .
                    "WHERE user.id_usuario = camp.id_usuario " .
                    "AND user.id_usuario = '".$userId."'".
                    "AND camp.Id_Concurso = '".$contestId."' " .
                    "ORDER BY camp.puesto ASC, camp.id_campaign ASC";

  $campaignData = getWholeRow($campaignQuery);
  return $campaignData;
}

function DAOCampaign_getCampaignsNotCreatedForUserInContest($userId, $contestId){
	$query = "SELECT problem_id from co_contest_problems where contest_id = ".$contestId."
	AND 
	problem_id NOT IN 
	(SELECT id_problema from campaigndetalle cmpd join campaign cmp using(id_campaign) WHERE 
	cmp.id_concurso = ".$contestId." AND cmp.id_usuario = ".$userId.")";
	return getRowsInArray($query);
}

function DAOCampaign_createCampaignDetail($campaignId, $problemId){
    $queryC = "INSERT INTO campaigndetalle".
    "(id_campaign, id_problema) VALUES (".$campaignId.",".$problemId.")";
    runQuery($queryC);
}

function DAOCampaign_deregegisterUser($contestId, $userId){
    $deleteQuery = "DELETE FROM campaigndetalle where id_campaign = 
      (SELECT id_campaign FROM campaign WHERE id_usuario ='".$userId."' AND id_concurso='".$contestId."')";
    runQuery($deleteQuery);
    $deleteQuery2 = "DELETE FROM campaign where id_usuario ='".$userId."' AND id_concurso='".$contestId."'";
    runQuery($deleteQuery2);
}

?>
