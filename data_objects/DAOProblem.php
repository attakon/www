<?php
include_once 'utils/DBUtils.php';


/*
  Second Group: problem creation; table co_problem, co_problem_testcase
*/

function DAOProblem_updateExplanationForTestCase($testCaseId, $explanation){
    $query = "UPDATE co_problem_testcase SET explanation = '".$explanation."' WHERE testcase_id='".$testCaseId."'";
    runQuery($query);
}

function DAOProblem_deleteProblemStatementForLanguage($problemId, $languageId){
    $query = "DELETE FROM co_problem_statement WHERE problem_id='".$problemId."' 
    AND language_id = '".$languageId."'";
    runQuery($query);
}

function DAOProblem_getProblemStatements($problemId){
    $query = "SELECT statement, languages.language_id, languages.name FROM co_problem_statement join languages using(language_id)
      WHERE problem_id='".$problemId."'";
    return getRowsInArray($query);
}

function DAOProblem_getProblemStatementForLanguage($problemId, $languageId){
    $query = "SELECT statement FROM co_problem_statement WHERE problem_id='".$problemId."' 
    AND language_id = '".$languageId."'";
    return getRow($query);
}

function DAOProblem_insertProblemStatement($problemId, $languageId, $statement){
    $query = "INSERT INTO co_problem_statement (problem_id, language_id, statement) 
      VALUES ('".$problemId."','".$languageId."','".$statement."')";
    runQuery($query);
}

function DAOProblem_registerProblem($problemName, $problemDifficulty, $creatorId, $exampleCases){
    $insert = "INSERT INTO co_problem (name, difficulty, creator_id, example_cases)
    VALUES ('".$problemName."','".$problemDifficulty."','".$creatorId."', ".$exampleCases.")";
    runQuery($insert);
}
function DAOProblem_registerTestCase($problemId, $input, $output){
    $insert = "INSERT INTO co_problem_testcase (problem_id, case_input, case_output)
    VALUES ('".$problemId."','".$input."','".$output."')";
    runQuery($insert);
}
function DAOProblem_getProblemByName($problemName){
    $query = "SELECT problem_id FROM co_problem WHERE name='".$problemName."'";
    return getRow($query);
}

function DAOProblem_getProblemData($problemId){
    $query = "SELECT problem_id, name, example_cases FROM co_problem WHERE problem_id='".$problemId."'";
    return getWholeRow($query);
}

function DAOProblem_deleteProblem($problemId){
    $query = "DELETE FROM co_problem WHERE problem_id='".$problemId."'";
    runQuery($query);
}

function DAOProblem_getProblemIO($problemId){
    $query = "SELECT case_input, case_output FROM co_problem_testcase WHERE problem_id='".$problemId."'";
    return getRowsInArray($query);
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

function DAOProblem_markAsSolved($problemId, $userId, $sourceCode=null){
    // if(DAOProblem_isSolvedByUserInContest($problemId, $userId))
    //         return;
    $status = DAOProblem_isAlrearySeenByUserInPractite($problemId, $userId);
    
     if(!$status){
        $query = "INSERT INTO practice_campaigns 
          (id_problema, id_usuario, status, solving_date, source_code)
         VALUES ('".$problemId."','".$userId."',3, CURRENT_TIMESTAMP(),'".$sourceCode."')";
        runQuery($query);
    }else { //$status =1,2,3
        $newStatus = 0;
        if($status==1){
          $newStatus=2;
        }else if($status==2){
          $newStatus = 2;
        }else if($status==3){
          $newStatus = 3;
        }
        if($sourceCode){
          $query = "UPDATE practice_campaigns
            SET status = '".$newStatus."',
              solving_date = CURRENT_TIMESTAMP(),
              source_code = '".$sourceCode."'
            WHERE id_problema= '".$problemId."'
            AND id_usuario = '".$userId."'";  
        }else{
          $query = "UPDATE practice_campaigns
            SET status = '".$newStatus."',
              solving_date = CURRENT_TIMESTAMP()
            WHERE id_problema= '".$problemId."'
            AND id_usuario = '".$userId."'";  
        }
        
        runQuery($query);
    }
}

?>
