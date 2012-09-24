<?php
include_once ("utils/DBUtils.php");

function DAOConcurso_getContestData($concursoId){
	$query = "SELECT con.nombre, con.estado FROM concurso con WHERE con.id_concurso = '".$concursoId."'";
   	$contestName = getWholeRow($query);
   	return $contestName;
}

function DAOConcurso_getLeagueId($concursoId){ 
   $query = "SELECT con.id_temporada FROM concurso con WHERE con.id_concurso = '".$concursoId."'";
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

// co_contest Problems
function DAOConcurso_getFirstProblem($contestId){
  $query = "SELECT problem.problem_id, problem.name 
  FROM co_contest_problems ctp join co_problems using (problem_id) 
  WHERE contest_id = '".$contestId."' LIMIT 0,1";
  return getWholeRow($query);
}

function DAOConcurso_addProblemToContest($contestId, $problemId){
    $insert = "INSERT INTO co_contest_problems (contest_id, problem_id)
    VALUES ('".$contestId."','".$problemId."')";
    runQuery($insert);
}
function DAOConcurso_removeProblemFromContest($contestId, $problemId){
    $insert = "DELETE FROM co_contest_problems
    WHERE contest_id ='".$contestId."' AND problem_id ='".$problemId."'";
    runQuery($insert);
}

function DAOConcurso_getProblems($contestId){
    $query = "SELECT problem.problem_id, problem.name 
     FROM co_contest_problems ctp join co_problem problem using(problem_id)
     WHERE ctp.contest_id = '".$contestId."'";
    return getRowsInArray($query);
}


?>
