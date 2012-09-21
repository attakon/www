<?php
include_once 'utils/DBUtils.php';


/*
  Second Group: problem creation; table co_problem, co_problem_testcase
*/
function DAOProblem_registerProblem($problemName, $problemDifficulty, $creatorId){
    $insert = "INSERT INTO co_problem (name, difficulty, creator_id)
    VALUES ('".$problemName."','".$problemDifficulty."','".$creatorId."')";
    runQuery($insert);
}
function DAOProblem_registerTestCase($problemId, $input, $output){
    $insert = "INSERT INTO co_problem_testcase (problem_id, input_case, output_case)
    VALUES ('".$problemId."','".$input."','".$output."')";
    runQuery($insert);
}
function DAOProblem_getProblemByName($problemName){
    $query = "SELECT problem_id FROM co_problem WHERE name='".$problemName."'";
    return getRow($query);
}
/*
  First Group
*/
function DAOProblem_getProblemConcursoId($problemId){
    $query="SELECT id_concurso 
        FROM problema where id_problema = '".$problemId."'";
    $concursoId = getRow($query);
    return $concursoId;
}
function DAOProblem_isSolvedByUserInContest($problemId,$userId){ 
   $query = "SELECT cd.solved 
       FROM campaigndetalle cd join campaign c using (id_campaign) 
       WHERE cd.id_problema = '".$problemId."' 
       AND c.id_usuario = '".$userId."' 
           AND cd.solved = 1";
   $res = getRow($query);
   return !is_null($res);
}

function DAOProblem_isAlrearySeenByUserInPractite($problemId,$userId){ 
   $query = "SELECT pc.status 
       FROM practice_campaigns pc 
       WHERE pc.id_problema = '".$problemId."' 
       AND pc.id_usuario = '".$userId."'";
   $res = getRow($query);
   if(is_null($res)){
       return false;
   }else{
       return $res;
   }
}
function DAOProblem_markProblemAsSeenInPractice($problemId,$userId){
    $query = "INSERT INTO practice_campaigns (id_problema, id_usuario, status)
         VALUES ('".$problemId."','".$userId."',1)";
    runQuery($query);
}

function DAOProblem_markAsSolved($problemId, $userId){
    if(DAOProblem_isSolvedByUserInContest($problemId, $userId))
            return;
    $status = DAOProblem_isAlrearySeenByUserInPractite($problemId, $userId);
    
     if(!$status){
        $query = "INSERT INTO practice_campaigns (id_problema, id_usuario, status)
         VALUES ('".$problemId."','".$userId."',3)";
        runQuery($query);
    }else if($status==1){
        $query = "UPDATE practice_campaigns 
            SET status = '2' 
            WHERE id_problema= '".$problemId."'
            AND id_usuario = '".$userId."'";
        runQuery($query);
    }
}

?>
