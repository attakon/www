<?php
include_once 'utils/DBUtils.php';

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
