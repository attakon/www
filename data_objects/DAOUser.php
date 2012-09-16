<?php
include_once ("utils/DBUtils.php");

function DAOUser_registerUser($firstName, $lastName, $school, $email, $userName, $password){
  $insert = " INSERT INTO usuario 
  (nombres, apellidos, id_escuela, Ciclo, email, username, pass)
    VALUES
  ('".$firstName."',
  '".$lastName."',
  '".$school."',
  -1,
  '".$email."',
  '".$userName."',
  MD5('".$password."'));";
  runQuery($insert);
}

function DAOFBUser_linkUser($hcUserId, $fbID){
  $query = "INSERT INTO fb_user_users (user_id, fb_id)
  VALUES ('".$hcUserId."','".$fbID."')";
    runQuery($query);
}

function DAOUser_getUserById($userId){ 
   $query = "SELECT id_usuario, username  FROM usuario WHERE id_usuario = '".$$userId."'";
   $n = getWholeRow($query);
   return $n;
}

function DAOUser_getUserByName($userName){ 
   $query = "SELECT id_usuario, username  FROM usuario WHERE username = '".$userName."'";
   $n = getWholeRow($query);
   return $n;
}

function DAOUser_isUserRegisteredInContest($userId,$concursoId){ 
   $query = "SELECT COUNT(*) FROM campaign cpg WHERE cpg.id_concurso = '".$concursoId."' AND cpg.id_usuario = '".$userId."'";
   $n = getRow($query);
   return $n>0;
}

function DAOUser_isUserRegisteredInSeason($userId, $temporadaId){ 
   $query = "SELECT COUNT(*) FROM competidor c
                       WHERE c.id_usuario = '".$userId."'
                       AND c.id_temporada = '".$temporadaId."'";
   $n = getRow($query);
   return $n>0;
}

function DAOUser_getUserPuntosForSeason($userId,$temporadaId){ 
   $query = "SELECT c.puntos FROM competidor c WHERE c.id_usuario = '".$userId."' AND c.id_temporada= '".$temporadaId."'";
   $n = getRow($query);
   return $n;
}


function DAOUser_registerInSeason($userId,$temporadaId){
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
    $q = "SELECT id_usuario, username FROM usuario WHERE username ='".$incomingUserName."'";
    $rs = mysql_query($q,$conexion) or die ($q);
    $row  = mysql_fetch_row($rs);
    $_SESSION['userId']=$row[0];
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
