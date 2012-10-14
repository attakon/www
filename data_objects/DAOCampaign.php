<?php
include_once ("utils/DBUtils.php");

// BEGIN ARENA
function DAOCampaign_getCampaignDetailForCampaign($contestId, $campaignId){
  include_once 'data_objects/DAOConcurso.php';
  $elapsedSeconds = DAOConcurso_getContestElapsedTime($contestId);

  $queryCampaignDetalle =
    "SELECT cd.id_problema, cd.solved, cd.tiempo_submision, count(cs.campaign_submission_id) 'attempts'
    FROM campaigndetalle cd LEFT JOIN campaign_submission cs 
       on(cs.campaign_id = cd.id_campaign AND cs.problem_id = cd.id_problema 
        AND (
          (cs.status=0 AND ".$elapsedSeconds."-TIME_TO_SEC(cs.download_time)>180) 
          OR (cs.status=0 AND cs.submission_time IS NOT NULL)
          )
        ) 
    WHERE id_campaign = ".$campaignId." 
    GROUP BY id_problema
    ORDER BY id_problema";

//     SELECT cd.Id_Problema, cd.solved, cd.tiempo_submision,count(cs.campaign_submission_id), cd.successful_source_code FROM campaigndetalle cd join campaign_submission cs 
//     on(cs.campaign_id = cd.id_campaign AND cs.problem_id = cd.id_problema) 
// WHERE cd.id_campaign = 221 
// AND cs.status=0

  // echo $queryCampaignDetalle;
  $res = getRowsInArray($queryCampaignDetalle);
  // print_r($res);
  return $res;
}

function DAOCampaign_isSubmissionPending($contestId, $campaignId, $problemId){
  include_once 'data_objects/DAOConcurso.php';
  $elapsedSeconds = DAOConcurso_getContestElapsedTime($contestId);

  $query = "SELECT campaign_submission_id, 4*60 - (".$elapsedSeconds." - TIME_TO_SEC(download_time)) 'submission_left_time' 
    FROM campaign_submission 
    WHERE campaign_id = '".$campaignId."' AND problem_id = '".$problemId."'
      AND ".$elapsedSeconds." - TIME_TO_SEC(download_time) <=3*60 AND submission_time IS NULL";
   // echo $query;

  return getWholeRow($query);
}

function DAOCampaign_isProblemSolved($contestId, $campaignId, $problemId){
  $query = "SELECT solved FROM campaigndetalle WHERE id_campaign = '".$campaignId."' 
    AND id_problema ='".$problemId."'";
  $res = getRow($query);
  return $res=='1';
}

function DAOCampaign_startSubmission($contestId, $campaignId, $problemId){
  include_once 'data_objects/DAOConcurso.php';
  $elapsedSeconds = DAOConcurso_getContestElapsedTime($contestId);
  
  $query = "INSERT INTO campaign_submission (campaign_id, problem_id, download_time)
   VALUES ('".$campaignId."','".$problemId."',SEC_TO_TIME(".$elapsedSeconds."))";
  runQuery($query);
  
  $idQuery = "SELECT LAST_INSERT_ID()";
  return getRow($idQuery);
}
function DAOCampaign_registerSubmission($contestId, $campaignId, $problemId, $time, $wasSolved, $sourceCode){

  // $query = sprintf("SELECT * FROM users WHERE user='%s' AND password='%s'",
  //           mysql_real_escape_string($user),
  //           mysql_real_escape_string($password));
  include_once 'data_objects/DAOConcurso.php';
  $elapsedSeconds = DAOConcurso_getContestElapsedTime($contestId);
  $query = sprintf("CALL SP__UD_CAMPAIGNDETALLE_V2(%s,'%s',SEC_TO_TIME(".$elapsedSeconds."),'%s','%s')"
    ,$campaignId
    ,$problemId
    ,$wasSolved
    ,$sourceCode);
  // print_r($query);
  runQuery($query);
}

function DAOCampaign_registerSubmission_old($contestId,$campaignId, $problemId, $time, $wasSolved, $sourceCode){

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

function DAOCampaign_getUserCampaigns_V2($contestId){
  $campaignQuery = "SELECT camp.id_campaign, camp.puesto,
            camp.new_ranking, user.id_usuario, user.username, camp.puntos, camp.penalizacion " .
                    "FROM usuario user, campaign camp " .
                    "WHERE user.id_usuario = camp.id_usuario " .
                    "AND camp.id_concurso = '".$contestId."' " .
                    "ORDER BY camp.puesto ASC, camp.id_campaign ASC";

  $campaignData = getRowsInArray($campaignQuery);
  return $campaignData;
}

function DAOCampaign_getUserCampaigns($contestId){
  $campaignQuery = "SELECT camp.id_campaign, camp.puesto,
            camp.new_ranking, user.id_usuario, user.username, camp.puntos, camp.penalizacion " .
                    "FROM usuario user, campaign camp " .
                    "WHERE user.id_usuario = camp.id_usuario " .
                    "AND camp.id_concurso = '".$contestId."' " .
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

function DAOCampaign_resetCampaignDetails($contestId){
  // delete old campaigns
  $deleteCampaignsForContest = "DELETE FROM 
    campaigndetalle WHERE id_campaign in 
      (SELECT id_campaign FROM campaign WHERE id_concurso = '".$contestId."')";
  runQuery($deleteCampaignsForContest);
  //add new campaigns
  
  DAOCampaign_createCampaingDetailsForContestans($contestId);
}

function DAOCampaign_createCampaingDetailsForContestans($contestId){
  $campaigns = DAOCampaign_getUserCampaigns($contestId);
  // echo 'x';
  // print_r($campaigns);
  include_once 'data_objects/DAOConcurso.php';
  foreach ($campaigns as $key => $campaignValue) {
    $campaignDetailToInsert = DAOConcurso_getProblems($contestId);
    // print_r($campaignDetailToInsert);
    foreach ($campaignDetailToInsert as $key => $problemsToInsertValue) {
        DAOCampaign_createCampaignDetail(
          $campaignValue['id_campaign'],
          $problemsToInsertValue['problem_id']
          );
    }
  }
}

function DAOCampaign_deregegisterUser($contestId, $userId){
    $deleteQuery = "DELETE FROM campaigndetalle where id_campaign = 
      (SELECT id_campaign FROM campaign WHERE id_usuario ='".$userId."' AND id_concurso='".$contestId."')";
    runQuery($deleteQuery);
    $deleteQuery2 = "DELETE FROM campaign where id_usuario ='".$userId."' AND id_concurso='".$contestId."'";
    runQuery($deleteQuery2);
}

?>
