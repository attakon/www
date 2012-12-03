<?php
include_once ("utils/DBUtils.php");

function DAOCampaign_getPracticeCampaignCode($userId, $problemId){
  $dataQuery = "SELECT 
    us.username, prob.name 'problem_name', pc.source_code 'code' 
    FROM practice_campaigns pc JOIN co_problem prob using(problem_id)
    JOIN usuario us using (id_usuario)
     WHERE pc.id_usuario = ".$userId."
      AND pc.problem_id = '".$problemId."'";
  return getWholeRow($dataQuery);
}

function DAOCampaign_getCampaignCode($campaignId, $problemId){
  $dataQuery = "SELECT 
    us.username, prob.name 'problem_name', con.nombre 'contest_name', cd.successful_source_code 'code' 
    FROM usuario us, concurso con, co_problem prob, campaigndetalle cd, campaign ca
     WHERE us.id_usuario = ca.id_usuario AND
        ca.contest_id = con.contest_id AND
        ca.id_campaign = cd.id_campaign AND
        prob.problem_id = cd.problem_id AND
        ca.id_campaign = '".$campaignId."' AND
        prob.problem_id = '".$problemId."'";
  return getWholeRow($dataQuery);
}
// BEGIN ARENA
//scoreboard without order of problems
function DAOCampaign_getCampaignDetailForCampaign1($contestId, $campaignId){
  include_once 'data_objects/DAOContest.php';
  $elapsedSeconds = DAOContest_getContestElapsedTime($contestId);

  include_once 'GLOBALS.php';

  $queryCampaignDetalle =
    "SELECT 
      cd.problem_id, 
      cd.solved, 
      cd.tiempo_submision, 
      count(cs.campaign_submission_id) 'attempts'
    FROM campaigndetalle cd LEFT JOIN campaign_submission cs 
       on(cs.campaign_id = cd.id_campaign AND cs.problem_id = cd.problem_id 
        AND (
          (cs.status=0 AND ".$elapsedSeconds."-TIME_TO_SEC(cs.download_time)>".SUBMISSION_ALLOWED_SECONDS.") 
          OR (cs.status=0 AND cs.submission_time IS NOT NULL)
          )
        )
    WHERE id_campaign = ".$campaignId." 
    GROUP BY problem_id
    ORDER BY problem_id";

//     SELECT cd.problem_id, cd.solved, cd.tiempo_submision,count(cs.campaign_submission_id), cd.successful_source_code FROM campaigndetalle cd join campaign_submission cs 
//     on(cs.campaign_id = cd.id_campaign AND cs.problem_id = cd.problem_id) 
// WHERE cd.id_campaign = 221 
// AND cs.status=0

  // echo $queryCampaignDetalle;
  $res = getRowsInArray($queryCampaignDetalle);
  // print_r($res);
  return $res;
}
//last version of scoreboard data
function DAOCampaign_getCampaignDetailForCampaign($contestId, $campaignId){
  include_once 'data_objects/DAOContest.php';
  $elapsedSeconds = DAOContest_getContestElapsedTime($contestId);

  include_once 'GLOBALS.php';

  $queryCampaignDetalle =
    "SELECT 
      cd.problem_id, 
      cd.solved, 
      cd.tiempo_submision, 
      count(cs.campaign_submission_id) 'attempts'
    FROM campaigndetalle cd LEFT JOIN campaign_submission cs 
       on(cs.campaign_id = cd.id_campaign AND cs.problem_id = cd.problem_id 
        AND (
          (cs.status=0 AND ".$elapsedSeconds."-TIME_TO_SEC(cs.download_time)>".SUBMISSION_ALLOWED_SECONDS.") 
          OR (cs.status=0 AND cs.submission_time IS NOT NULL)
          )
        ) JOIN co_contest_problems cp on(cp.problem_id=cd.problem_id and cp.contest_id=".$contestId.")
    WHERE id_campaign = ".$campaignId." 
    GROUP BY problem_id
    ORDER BY cp.order ASC";
  $res = getRowsInArray($queryCampaignDetalle);
  return $res;
}

function DAOCampaign_getCampaignDetailForCampaign2($contestId, $campaignId){
  include_once 'data_objects/DAOContest.php';

  include_once 'GLOBALS.php';

  $queryCampaignDetalle =
    "SELECT 
      cd.problem_id, 
      cd.solved, 
      cd.tiempo_submision,
      cd.successful_source_code,
      cs.download_time,
      cs.submission_time,
      cs.status,
      IF((SELECT (TO_SECONDS(NOW())-TO_SECONDS(fecha))FROM concurso con WHERE con.contest_id = ".$contestId.")
          -TIME_TO_SEC(cs.download_time)<=".SUBMISSION_ALLOWED_SECONDS.",
          ((SELECT (TO_SECONDS(NOW())-TO_SECONDS(fecha))FROM concurso con WHERE con.contest_id = ".$contestId.")-TIME_TO_SEC(cs.download_time))/(".SUBMISSION_ALLOWED_SECONDS."),'RUNOUT') COUNTDOWN
    FROM campaigndetalle cd LEFT JOIN campaign_submission cs 
       on(cs.campaign_id = cd.id_campaign AND cs.problem_id = cd.problem_id) 
       JOIN co_contest_problems cp on(cp.problem_id=cd.problem_id and cp.contest_id=".$contestId.")
    WHERE id_campaign = ".$campaignId."
    ORDER BY cp.order ASC,download_time DESC;";
  $res = getRowsInArray($queryCampaignDetalle);
  // print_r($res);
  return $res;
}



function DAOCampaign_getPendingSubmission($contestId, $campaignId, $problemId){
  include_once 'data_objects/DAOContest.php';
  $elapsedSeconds = DAOContest_getContestElapsedTime($contestId);

  include_once 'GLOBALS.php';

  $query = "SELECT campaign_submission_id
    , ".SUBMISSION_ALLOWED_SECONDS." - (".$elapsedSeconds." - TIME_TO_SEC(download_time)) 'submission_left_time' 
    , io_seed
    FROM campaign_submission 
    WHERE campaign_id = '".$campaignId."' AND problem_id = '".$problemId."'
      AND ".$elapsedSeconds." - TIME_TO_SEC(download_time) <= ".SUBMISSION_ALLOWED_SECONDS." AND submission_time IS NULL";
   // echo $query;

  return getWholeRow($query);
}

function DAOCampaign_getLastSubmission($contestId, $campaignId, $problemId){
  include_once 'data_objects/DAOContest.php';
  $elapsedSeconds = DAOContest_getContestElapsedTime($contestId);

  include_once 'GLOBALS.php';

  $query = "SELECT cs.campaign_submission_id
    , ".SUBMISSION_ALLOWED_SECONDS." - (".$elapsedSeconds." - TIME_TO_SEC(cs.download_time)) 'submission_left_time' 
    , cs.io_seed
    , cpt.case_output
    , cpt.case_input
    , cs.killed_answer
    FROM campaign_submission cs left join co_problem_testcase cpt on(cs.killer_case_id = cpt.testcase_id)
    WHERE cs.campaign_id = '".$campaignId."' AND cs.problem_id = '".$problemId."'
      ORDER by cs.campaign_submission_id DESC";
   // echo $query;

  return getWholeRow($query);
}

function DAOCampaign_isProblemSolved($contestId, $campaignId, $problemId){
  $query = "SELECT solved FROM campaigndetalle WHERE id_campaign = '".$campaignId."' 
    AND problem_id ='".$problemId."'";
  $res = getRow($query);
  return $res=='1';
}

function DAOCampaign_startSubmission($contestId, $campaignId, $problemId, $seed){
  include_once 'data_objects/DAOContest.php';
  $elapsedSeconds = DAOContest_getContestElapsedTime($contestId);
  
  $query = "INSERT INTO campaign_submission (campaign_id, problem_id, download_time, io_seed)
   VALUES ('".$campaignId."','".$problemId."',SEC_TO_TIME(".$elapsedSeconds."),".$seed.")";
  runQuery($query);
  
  $idQuery = "SELECT LAST_INSERT_ID()";
  return getRow($idQuery);
}
function DAOCampaign_registerSubmission($contestId, 
  $campaignId, $problemId, $time, $accepted, $sourceCode, $killer_case_id=null, $killed_answer=null){

  // $query = sprintf("SELECT * FROM users WHERE user='%s' AND password='%s'",
  //           mysql_real_escape_string($user),
  //           mysql_real_escape_string($password));
  $sourceCode=mysql_escape_string($sourceCode);
  $killed_answer=mysql_escape_string($killed_answer);

  include_once 'data_objects/DAOContest.php';
  $elapsedSeconds = DAOContest_getContestElapsedTime($contestId);
  $query = sprintf("CALL SP__UD_CAMPAIGNDETALLE_V2(%s,'%s',SEC_TO_TIME(".$elapsedSeconds."),'%s','%s','%s','%s')"
    ,$campaignId
    ,$problemId
    ,$accepted
    ,$killer_case_id
    ,$killed_answer
    ,$sourceCode);
  runQuery($query);
}

function DAOCampaign_getFastestSubmission($contestId){
  $query = "SELECT campaign_id, problem_id FROM co_contest_fastest_submit
    WHERE contest_id='".$contestId."'";
  return getWholeRow($query);
}

// en 3 2 1 

function DAOCampaign_registerFastestSubmissionIfPossible($contestId, $campaignId, $problemId){
  if(!DAOCampaign_getFastestSubmission($contestId)){
    $query2 = "INSERT INTO co_contest_fastest_submit (contest_id, campaign_id, problem_id) 
    VALUES (".$contestId.",".$campaignId.",".$problemId.")";  
    runQuery($query2);
  }
}

function DAOCampaign_registerSubmission_old($contestId,$campaignId, $problemId, $time, $wasSolved, $sourceCode){

  // $query = sprintf("SELECT * FROM users WHERE user='%s' AND password='%s'",
  //           mysql_real_escape_string($user),
  //           mysql_real_escape_string($password));
  include_once 'data_objects/DAOContest.php';
  $elapsedSeconds = DAOContest_getContestElapsedTime($contestId);
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
  $query = "SELECT contest_id, id_usuario FROM campaign WHERE id_campaign='".$campaignId."'";
  return getWholeRow($query);
}

function DAOCampaign_getUserCampaigns_V2($contestId){
  $campaignQuery = "SELECT camp.id_campaign, camp.puesto,
            camp.new_ranking, user.id_usuario, user.username, camp.puntos, camp.penalizacion " .
                    "FROM usuario user, campaign camp " .
                    "WHERE user.id_usuario = camp.id_usuario " .
                    "AND camp.contest_id = '".$contestId."' " .
                    "ORDER BY camp.puesto ASC, camp.id_campaign ASC";

  $campaignData = getRowsInArray($campaignQuery);
  return $campaignData;
}

function DAOCampaign_getUserCampaigns($contestId){
  $campaignQuery = "SELECT camp.id_campaign, camp.puesto,
            camp.new_ranking, user.id_usuario, user.username, camp.puntos, camp.penalizacion " .
                    "FROM usuario user, campaign camp " .
                    "WHERE user.id_usuario = camp.id_usuario " .
                    "AND camp.contest_id = '".$contestId."' " .
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
                    "AND camp.contest_id = '".$contestId."' " .
                    "ORDER BY camp.puesto ASC, camp.id_campaign ASC";

  $campaignData = getWholeRow($campaignQuery);
  return $campaignData;
}

function DAOCampaign_getCampaignsNotCreatedForUserInContest($userId, $contestId){
	$query = "SELECT problem_id from co_contest_problems where contest_id = ".$contestId."
	AND 
	problem_id NOT IN 
	(SELECT problem_id from campaigndetalle cmpd join campaign cmp using(id_campaign) WHERE 
	cmp.contest_id = ".$contestId." AND cmp.id_usuario = ".$userId.")";
	return getRowsInArray($query);
}

function DAOCampaign_createCampaignDetail($campaignId, $problemId){
    $queryC = "INSERT INTO campaigndetalle".
    "(id_campaign, problem_id) VALUES (".$campaignId.",".$problemId.")";
    runQuery($queryC);
}

function DAOCampaign_resetCampaignDetails($contestId){
  // delete old campaigns
  $deleteCampaignsForContest = "DELETE FROM 
    campaigndetalle WHERE id_campaign in 
      (SELECT id_campaign FROM campaign WHERE contest_id = '".$contestId."')";
  runQuery($deleteCampaignsForContest);
  //add new campaigns
  
  DAOCampaign_createCampaingDetailsForContestans($contestId);
}

function DAOCampaign_createCampaingDetailsForContestans($contestId){
  $campaigns = DAOCampaign_getUserCampaigns($contestId);
  // echo 'x';
  // print_r($campaigns);
  include_once 'data_objects/DAOContest.php';
  foreach ($campaigns as $key => $campaignValue) {
    $campaignDetailToInsert = DAOContest_getProblems($contestId);
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
      (SELECT id_campaign FROM campaign WHERE id_usuario ='".$userId."' AND contest_id='".$contestId."')";
    runQuery($deleteQuery);
    $deleteQuery2 = "DELETE FROM campaign where id_usuario ='".$userId."' AND contest_id='".$contestId."'";
    runQuery($deleteQuery2);
}

?>
