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


function DAOUser_registerUser($firstName, $lastName, $school, $email, $userName, $password=null){

  if($password){
    $passwordValue = "MD5('".$password."')";
  }else{
    $passwordValue="null";
  }
  $insert = " INSERT INTO usuario 
  (nombres, apellidos, id_escuela, ciclo, email, username, pass)
    VALUES
  (
  '".$firstName."'
  ,'".$lastName."'
  ,'".$school."'
  ,-1
  ,'".$email."'
  ,'".$userName."'
  ,".$passwordValue."
  );";
  runQuery($insert);
}

function DAOUser_getUserByName($userName){ 
   $query = "SELECT id_usuario, username  FROM usuario WHERE lower(username) = lower('".$userName."')";
   $n = getWholeRow($query);
   return $n;
}

function DAOUser_isUserRegisteredInContest($userId, $concursoId){ 
   $query = "SELECT COUNT(*) FROM campaign cpg WHERE cpg.id_concurso = '".$concursoId."' AND cpg.id_usuario = '".$userId."'";
   $n = getRow($query);
   return $n>0;
}

function DAOUser_isUserRegisteredInLeague($userId, $temporadaId){ 
   $query = "SELECT COUNT(*) FROM competidor c
                       WHERE c.id_usuario = '".$userId."'
                       AND c.id_temporada = '".$temporadaId."'";
   $n = getRow($query);
   return $n>0;
}

function DAOUser_getUserLeaguePoints($userId,$temporadaId){ 
   $query = "SELECT c.puntos FROM competidor c WHERE c.id_usuario = '".$userId."' AND c.id_temporada= '".$temporadaId."'";
   $n = getRow($query);
   return $n;
}


function DAOUser_registerInLeague($userId,$temporadaId){
   $insertQ = "INSERT INTO competidor(id_usuario, id_temporada, puntos, penalty_time, `position`,
      position_school, competitions_count) VALUES
    ('".$userId."', '".$temporadaId."', 0, '0:0:0', -1, -1, 0)";
    runQuery($insertQ);
}

function DAOUser_registerInContest($concursoId, $userId, $oldPts){
   $insertQ = "INSERT INTO campaign (id_concurso, id_usuario, old_puntaje)
    VALUES ('".$concursoId."', '".$userId."', '".$oldPts."');";
   runQuery($insertQ);
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
        p.id_problema, p.nombre, p.abrev, con.nombre_corto as 'contest_name', con.id_concurso as 'contest_id', 4 as 'status', cd.id_campaign as 'cpg_id', con.fecha
        FROM campaigndetalle cd join problema p using(id_problema)
        join concurso con on (con.id_concurso = p.id_concurso) 
        join campaign camp using(id_campaign) 
        join usuario u on(camp.id_usuario = u.id_usuario)
        WHERE u.id_usuario = '".$userId."'
            AND cd.solved = 1)
            UNION
        (SELECT p.id_problema, p.nombre, p.abrev, con.nombre_corto as 'contest_name', con.id_concurso as 'contest_id', pc.status, '-1' as 'cpg_id', con.fecha
        FROM practice_campaigns pc join problema p using(id_problema)
        join concurso con on (con.id_concurso = p.id_concurso) 
        join usuario u on(pc.id_usuario = u.id_usuario)
        WHERE u.id_usuario = '".$userId."' and pc.status<>1)";
//            ORDER BY concurso.fecha";
    return getRowsInArray($q);
//    return getRowsInArray($q);
}
function DAOUser_getUserPracticeCampaignHistory($userId){
    $q = "SELECT p.id_problema, p.nombre, con.nombre_corto as 'contest_name', pc.status
        FROM practice_campaigns pc join problema p using(id_problema)
        join concurso con on (con.id_concurso = p.id_concurso) 
        join usuario u on(pc.id_usuario = u.id_usuario)
        WHERE u.id_usuario = '".$userId."'";
    return getRowsInArray($q);
}

?>
