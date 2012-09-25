<?php
include_once ("utils/DBUtils.php");

// BEGIN ARENA
function DAOCampaign_registerAttempt($campaignId,$problemId,$time,$wasSolved,$sourceCode){
    $query = "CALL SP__UD_CAMPAIGNDETALLE('".$campaignId."','".$problemId."','NOW()','".$wasSolved."',NULL)";
    runQuery($query);
}

// END ARENA 
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

function DAOCampaign_deregegisterUser($userId, $contestId){
    $deleteQuery = "DELETE FROM campaigndetalle where id_campaign = 
      (SELECT id_campaign FROM campaign WHERE id_usuario ='".$userId."' AND id_concurso='".$contestId."')";
    runQuery($deleteQuery);
    $deleteQuery2 = "DELETE FROM campaign where id_usuario ='".$userId."' AND id_concurso='".$contestId."'";
    runQuery($deleteQuery2);
}

?>
