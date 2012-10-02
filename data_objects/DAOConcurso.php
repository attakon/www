<?php
include_once ("utils/DBUtils.php");

function DAOConcurso_isContestOpen($contestId){
    $query = "SELECT TIMESTAMPDIFF(SECOND,now(),fecha) FROM concurso con WHERE con.id_concurso = '".$contestId."'";
    $diff = getRow($query);
    if($diff<=0){
      return true;
    }else 
      return false;
}

function DAOConcurso_getContestPhase($contestId){
    $leftTime = DAOConcurso_getContestLeftSeconds($contestId);
    // echo $leftTime;
    $query = "SELECT IF(".$leftTime.">TIME_TO_SEC(total_time),'NOT_STARTED',IF(".$leftTime."<=TIME_TO_SEC(total_time) AND ".$leftTime.">=0,'IN_PROGRESS','FINISHED')) FROM concurso con WHERE con.id_concurso = '".$contestId."'";
    return getRow($query);
}

function DAOConcurso_getContestElapsedTime($contestId){
    $query = "SELECT (TO_SECONDS(NOW())-TO_SECONDS(fecha))
      FROM concurso con WHERE con.id_concurso = '".$contestId."'";
    $elapsedSeconds = getRow($query);
    return $elapsedSeconds;
}
function DAOConcurso_getContestLeftSeconds($contestId){
    $query = "SELECT (TO_SECONDS(fecha)+TIME_TO_SEC(total_time)-TO_SECONDS(NOW()))
      FROM concurso con WHERE con.id_concurso = '".$contestId."'";
    $secondsToFinish = getRow($query);
    return $secondsToFinish;
}

function DAOConcurso_getContestData($contestId){
  	$query = "SELECT nombre, estado, fecha, id_temporada, creator_id, is_invitational, is_published FROM concurso con WHERE id_concurso = '".$contestId."'";
   	$contestData = getWholeRow($query);
   	return $contestData;
}

function DAOConcurso_getLeagueId($contestId){ 
   $query = "SELECT con.id_temporada FROM concurso con WHERE con.id_concurso = '".$contestId."'";
   $temporadaId = getRow($query);
   return $temporadaId;
}

function DAOConcurso_getActiveContests(){
	$query = "select id_concurso, nombre from concurso 
    	WHERE estado in('REGISTRATION_OPEN')
        ORDER BY 1 ASC LIMIT 5";

    return getRowsInArray($query);
}

function DAOContest_deleteContest($contestId){
    $delete = "DELETE FROM concurso
    WHERE id_concurso ='".$contestId."'";
    runQuery($delete);
}

function DAOContest_publishContest($contestId){
    $update = "UPDATE concurso
    SET is_published ='1'
    WHERE id_concurso ='".$contestId."'";
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
function DAOConcurso_getFirstProblem($contestId){
  $query = "SELECT problem.problem_id, problem.name 
  FROM co_contest_problems ctp join co_problems using (problem_id) 
  WHERE contest_id = '".$contestId."' LIMIT 0,1";
  return getWholeRow($query);
}

function DAOConcurso_addProblemToContest($contestId, $problemId, $points){
    $insert = "INSERT INTO co_contest_problems (contest_id, problem_id,points)
    VALUES ('".$contestId."','".$problemId."','".$points."')";
    runQuery($insert);
}
function DAOConcurso_removeProblemFromContest($contestId, $problemId){
    $insert = "DELETE FROM co_contest_problems
    WHERE contest_id ='".$contestId."' AND problem_id ='".$problemId."'";
    runQuery($insert);
}

function DAOConcurso_getProblems($contestId){
    $query = "SELECT problem.problem_id, problem.name , ctp.points
     FROM co_contest_problems ctp join co_problem problem using(problem_id)
     WHERE ctp.contest_id = '".$contestId."'";
    return getRowsInArray($query);
}


?>
