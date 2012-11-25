<?php
include_once ("utils/DBUtils.php");

function DAOContest_IsContestDeletable($contestId){
  $query = "SELECT fecha, total_time FROM concurso WHERE contest_id='".$contestId."'";
  $row = getWholeRow($query);
  // print_r($row);
  // echo 'xxxx';
  if($row['fecha']==null || $row['fecha']=='' || $row['total_time']==null){
    return true;
  }
  if(DAOContest_getContestPhase($contestId)!='FINISHED'){
    return true;   
  }
  return false;
} 

function DAOContest_registerContest($leagueId, $name, $descripcion, $duration, $isInvitational, $isPublished, $creatorId){
  $insertQuery = "INSERT INTO concurso (league_id, nombre, descripcion, total_time, is_invitational, is_published, creator_id) 
        VALUES ('".$leagueId."','".$name."','".$descripcion."','".$duration."','".$isInvitational."','".$isPublished."','".$creatorId."')";
  runQuery($insertQuery);
}

function DAOContest_isContestOpen($contestId){
    $query = "SELECT TIMESTAMPDIFF(SECOND,now(),fecha) FROM concurso con WHERE con.contest_id = '".$contestId."'";
    $diff = getRow($query);
    if($diff<=0){
      return true;
    }else 
      return false;
}

function DAOContest_getContestPhase($contestId){
    $leftTime = DAOContest_getContestLeftSeconds($contestId);
    // echo $leftTime;
    $query = "SELECT IF(".$leftTime.">TIME_TO_SEC(total_time),'NOT_STARTED',IF(".$leftTime."<=TIME_TO_SEC(total_time) AND ".$leftTime.">=0,'IN_PROGRESS','FINISHED')) 
    FROM concurso con WHERE con.contest_id = '".$contestId."'";
    return getRow($query);
}

function DAOContest_getContestElapsedTime($contestId){
    $query = "SELECT (TO_SECONDS(NOW())-TO_SECONDS(fecha))
      FROM concurso con WHERE con.contest_id = '".$contestId."'";
    $elapsedSeconds = getRow($query);
    return $elapsedSeconds;
}
function DAOContest_getContestLeftSeconds($contestId){
    $query = "SELECT (TO_SECONDS(fecha)+TIME_TO_SEC(total_time)-TO_SECONDS(NOW()))
      FROM concurso con WHERE con.contest_id = '".$contestId."'";
    $secondsToFinish = getRow($query);
    return $secondsToFinish;
}

function DAOContest_getContestData($contestId){
  	$query = "SELECT nombre, estado, fecha, league_id, creator_id, is_invitational, is_published 
      FROM concurso con 
      WHERE contest_id = '".$contestId."'";
   	$contestData = getWholeRow($query);
   	return $contestData;
}

function DAOContest_getContestData2($contestId){
    $query = "SELECT 
        nombre, 
        day(fecha) 'day', month(fecha) 'month', 
        year(fecha) 'year', time(fecha) 'time'
        ,total_time,
        inscripcion, 
        estado,
        descripcion,
        creator_id,
        is_invitational 
        
      FROM concurso con 
      WHERE contest_id = '".$contestId."'";
    $contestData = getWholeRow($query);
    return $contestData;
}

function DAOContest_getLeagueId($contestId){ 
   $query = "SELECT con.league_id FROM concurso con WHERE con.contest_id = '".$contestId."'";
   $leagueId = getRow($query);
   return $leagueId;
}

function DAOContest_getActiveContests(){
	$query = "select contest_id, nombre from concurso co
    	WHERE co.is_published = 1 ".
      " AND TIMESTAMPDIFF(SECOND,now(),ADDTIME(fecha,total_time)) >= 0".
      " AND TIMESTAMPDIFF(SECOND,now(),ADDTIME(fecha,total_time)) <= TIME_TO_SEC(total_time)".
      "ORDER BY co.contest_id ASC";
  
  // $query = "SELECT IF(".$leftTime.">TIME_TO_SEC(total_time),'NOT_STARTED',IF(".$leftTime."<=TIME_TO_SEC(total_time) AND ".$leftTime.">=0,'IN_PROGRESS','FINISHED')) FROM concurso con WHERE con.contest_id = '".$contestId."'";

    return getRowsInArray($query);
}

function DAOContest_deleteContest($contestId){
    $delete = "DELETE FROM concurso
    WHERE contest_id ='".$contestId."'";
    runQuery($delete);
}
function DAOContest_deleteContestProblems($contestId){
    $delete = "DELETE FROM co_contest_problems
    WHERE contest_id ='".$contestId."'";
    runQuery($delete);
}
function DAOContest_deleteContestInvites($contestId){
  $delete = "DELETE FROM co_contest_invites
    WHERE contest_id ='".$contestId."'";
    runQuery($delete);
}
function DAOContest_deleteContestCampaigns($contestId){
  $delete = "DELETE FROM campaign
    WHERE contest_id ='".$contestId."'";
    runQuery($delete); 
}

function DAOContest_publishContest($contestId){
    $update = "UPDATE concurso
    SET is_published ='1'
    WHERE contest_id ='".$contestId."'";
    runQuery($update);
}

function DAOContest_uninviteUser($contestId, $userId){
    $delete = "DELETE FROM co_contest_invites WHERE contest_id ='".$contestId."' AND user_id ='".$userId."'";
    runQuery($delete);
}

function DAOContest_isUserInvited($contestId, $userId){
    $query = "SELECT count(*) FROM co_contest_invites WHERE contest_id ='".$contestId."' AND user_id ='".$userId."'";
    $res = getRow($query);
    return $res == 1;
}

// co_contest Problems
function DAOContest_getFirstProblem($contestId){
  $query = "SELECT problem.problem_id, problem.name 
  FROM co_contest_problems ctp join co_problems using (problem_id) 
  WHERE contest_id = '".$contestId."' LIMIT 0,1";
  return getWholeRow($query);
}

function DAOContest_addProblemToContest($contestId, $problemId, $points, $languageId){
  $addProblemQuery = "INSERT INTO co_contest_problems (contest_id, problem_id, points, problem_language_id)
  VALUES ('".$contestId."','".$problemId."','".$points."','".$languageId."')";
  runQuery($addProblemQuery);
  include_once 'data_objects/DAOCampaign.php';
  DAOCampaign_resetCampaignDetails($contestId);
}

function DAOContest_removeProblemFromContest($contestId, $problemId){
    $removeProblemQuery = "DELETE FROM co_contest_problems
    WHERE contest_id ='".$contestId."' AND problem_id ='".$problemId."'";
    runQuery($removeProblemQuery);
    include_once 'data_objects/DAOCampaign.php';
    DAOCampaign_resetCampaignDetails($contestId);
}



function DAOContest_getProblems($contestId){
    $query = "SELECT problem.problem_id, problem.name , ccp.points, problem.example_cases, cps.statement
     FROM co_contest_problems ccp join co_problem problem using(problem_id)
      JOIN co_problem_statement cps on(ccp.problem_id = cps.problem_id AND cps.language_id = ccp.problem_language_id) 
     WHERE ccp.contest_id = '".$contestId."'";
    return getRowsInArray($query);
}


?>
