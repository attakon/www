<?php
include_once ("utils/DBUtils.php");



function DAOUser_linkUserToFBUser($hcUserId, $fbID){
  $query = "INSERT INTO fb_user_users (user_id, fb_id)
  VALUES ('".$hcUserId."','".$fbID."')";
    runQuery($query);
}
function DAOUser_deleteLinkUserToFBUser($hcUserId, $fbID){
  $query = "DELETE FROM fb_user_users 
  WHERE fb_id='".$fbID."' AND user_id = '".$hcUserId."'";
  runQuery($query);
}

function DAOUser_getUserById($userId){ 
   $query = "SELECT id_usuario, username, id_escuela  FROM usuario WHERE id_usuario = '".$userId."'";
   $n = getWholeRow($query);
   return $n;
}


function DAOUser_registerUser($firstName, $lastName, $email, $userName, $password=null){

  if($password){
    $passwordValue = "MD5('".$password."')";
  }else{
    $passwordValue="null";
  }
  $insert = " INSERT INTO usuario 
  (nombres, apellidos, ciclo, email, username, pass)
    VALUES
  (
  '".$firstName."'
  ,'".$lastName."'
  ,-1
  ,'".$email."'
  ,'".$userName."'
  ,".$passwordValue."
  );";
  runQuery($insert);
}

function DAOUser_getUserByName($userName){ 
   $query = "SELECT id_usuario, username  FROM usuario WHERE lower(username) = lower('".$userName."')";
  // $query = "SELECT id_usuario, username  FROM usuario WHERE username = '".$userName."'";
  // echo $query;
   $n = getWholeRow($query);
   return $n;
}

function DAOUser_isUserRegisteredInContest($userId, $concursoId){  
   $query = "SELECT COUNT(*) FROM campaign cpg WHERE cpg.contest_id = '".$concursoId."' AND cpg.id_usuario = '".$userId."'";
   $n = getRow($query);
   return $n>0;
}

function DAOUser_isUserRegisteredInLeague($userId, $leagueId){ 
   $query = "SELECT COUNT(*) FROM competidor c
                       WHERE c.id_usuario = '".$userId."'
                       AND c.league_id = '".$leagueId."'";
   $n = getRow($query);
   return $n>0;
}

function DAOUser_getUserLeaguePoints($userId,$leagueId){ 
   $query = "SELECT c.puntos FROM competidor c WHERE c.id_usuario = '".$userId."' AND c.league_id= '".$leagueId."'";
   $n = getRow($query);
   return $n;
}


function DAOUser_registerInLeague($userId,$leagueId){
   $insertQ = "INSERT INTO competidor(id_usuario, league_id, puntos, penalty_time, `position`,
      position_school, competitions_count) VALUES
    ('".$userId."', '".$leagueId."', 0, '0:0:0', -1, -1, 0)";
    runQuery($insertQ);
}

function DAOUser_registerInContest($contestId, $userId, $oldPts){
  $insertQ = "INSERT INTO campaign (contest_id, id_usuario, old_puntaje)
    VALUES ('".$contestId."', '".$userId."', '".$oldPts."');";

  runQuery($insertQ);
  
  include_once 'data_objects/DAOCampaign.php';
  $campaignData = DAOCampaign_getCampaignForUser($userId, $contestId);

  $campaignDetailToInsert = DAOContest_getProblems($contestId);
  // print_r($campaignDetailToInsert);
  foreach ($campaignDetailToInsert as $key => $problemsToInsertValue) {
      DAOCampaign_createCampaignDetail(
        $campaignData['id_campaign'],
        $problemsToInsertValue['problem_id']
        );
  }

}

function DAOUser_login($incomingUserName, $incomingPassword){
    $query = "SELECT username FROM usuario 
      WHERE username ='".$incomingUserName."'
      AND pass = MD5('".$incomingPassword."')";
    $n = getRow($query);
    return $n;
}

function DAOUser_getUserCampaignHistory($userId){
    $q = "(
        SELECT 
        p.problem_id, p.nombre, p.abrev, con.nombre_corto as 'contest_name', con.contest_id as 'contest_id', 4 as 'status', cd.id_campaign as 'cpg_id', con.fecha
        FROM campaigndetalle cd join problema p using(problem_id)
        join concurso con on (con.contest_id = p.contest_id) 
        join campaign camp using(id_campaign) 
        join usuario u on(camp.id_usuario = u.id_usuario)
        WHERE u.id_usuario = '".$userId."'
            AND cd.solved = 1)
            UNION
        (SELECT p.problem_id, p.nombre, p.abrev, con.nombre_corto as 'contest_name', con.contest_id as 'contest_id', pc.status, '-1' as 'cpg_id', con.fecha
        FROM practice_campaigns pc join problema p using(problem_id)
        join concurso con on (con.contest_id = p.contest_id) 
        join usuario u on(pc.id_usuario = u.id_usuario)
        WHERE u.id_usuario = '".$userId."' and pc.status<>1)";
//            ORDER BY concurso.fecha";
    return getRowsInArray($q);
//    return getRowsInArray($q);
}

function DAOUser_getUserCampaignHistory2($userId){
    $p = "(
        SELECT 
          p.problem_id, 
          p.name, 
          con.nombre as 'contest_name', 
          con.contest_id as 'contest_id', 4 as 'status', 
          cd.id_campaign as 'cpg_id', 
          con.fecha
        FROM campaigndetalle cd join co_problem p on(cd.problem_id = p.problem_id) 
          join campaign camp on(cd.id_campaign=camp.id_campaign)
          join co_contest_problems cp using(problem_id)
          join concurso con on (con.contest_id = cp.contest_id and con.contest_id=camp.contest_id) 
        WHERE camp.id_usuario = '".$userId."'
            AND cd.solved = 1)";
            
        
    $q = "(SELECT p.problem_id, p.name, 
        con.nombre as 'contest_name', con.contest_id as 'contest_id', pc.status, '-1' as 'cpg_id', con.fecha
        FROM practice_campaigns pc join co_problem p on(pc.problem_id = p.problem_id) 
        join co_contest_problems cp using(problem_id)
        join concurso con on (con.contest_id = cp.contest_id)
        WHERE pc.id_usuario = '".$userId."' and pc.status<>1)"; 
    $query = $p ." UNION ".$q;
//            ORDER BY concurso.fecha";
    return getRowsInArray($query);
//    return getRowsInArray($q);
}

function DAOUser_getUserPracticeCampaignHistory($userId){
    $q = "SELECT p.problem_id, p.nombre, con.nombre_corto as 'contest_name', pc.status
        FROM practice_campaigns pc join problema p using(problem_id)
        join concurso con on (con.contest_id = p.contest_id) 
        join usuario u on(pc.id_usuario = u.id_usuario)
        WHERE u.id_usuario = '".$userId."'";
    return getRowsInArray($q);
}

?>
