<?php
session_start();
include_once ('container.php');
include_once ("conexion.php");
include_once ("CustomTags.php");
include_once 'data_objects/DAOUser.php';
include_once 'data_objects/DAOConcurso.php';
//./concurso_enrollUser.php?cId=7
$userId = $_SESSION['userId'];
$concursoId=$_GET['cId'];
if(!isset($_SESSION['userId'])){// no ha iniciado session
    $msg = "<p class='mensaje'> Debe Iniciar sesi&oacute;n para inscribirse</p>";
    showPage("Inscribirse...",false,$msg, "");
}else{// session iniciada    
    $dat = firstRow("SELECT estado from concurso where id_concurso = '".$concursoId."'");
    if($dat[0]=="REGISTRATION_OPEN"){
        //Begin [10-Jun-2012] Raul - moving the registration from store procedure to php code.
        if(!DAOUser_isUserRegisteredInContest($userId,$concursoId)){
            $leagueId = DAOConcurso_getLeagueId($concursoId);
            if(!DAOUser_isUserRegisteredInSeason($userId, $leagueId)){
                DAOUser_registerInSeason($userId, $leagueId);
            }
            $puntosForSeason = DAOUser_getUserPuntosForSeason($userId, $leagueId);
            DAOUser_registerInContest($concursoId, $userId, $puntosForSeason);
            $msg = parrafoOK("&iexcl;Inscrito Correctamente!");
        }else{
            $msg = parrafoError("&iexcl;Ud. ya est&aacute; inscrito en este concurso!");
        }
//        $queryEnroll = "SELECT FC__enrollConcursante('".$concursoId."', '".$userId."');";
//        $rs = mysql_query($queryEnroll,conecDb()) or die($queryEnroll." ".mysql_error());
//        $data = mysql_fetch_row($rs);
//        if($data[0]){
//            $msg = parrafoOK("&iexcl;Inscrito Correctamente!");
//        }else{
//            $msg = parrafoError("&iexcl;Ud. ya est&aacute; inscrito en este concurso!");
//        }
//        showPage("Join The Fun!",false,$msg, "");
//        
        //End [10-Jun-2012] Raul - moving the registration from store procedure to php code.
        showPage("Join The Fun!",false,$msg, "");
    }else{
        $msg = parrafoError("Inscripciones cerradas");
        showPage("too late ;(",false,$msg, "");
    }
}
?>