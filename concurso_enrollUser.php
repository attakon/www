<?php
session_start();
include_once 'utils/ValidateSignedIn.php';

include_once ("conexion.php");
include_once ("CustomTags.php");


//./concurso_enrollUser.php?cId=7
$userId = $_SESSION['userId'];
$contestId=$_GET['cId'];
include_once 'data_objects/DAOContest.php';
$contestData = DAOContest_getContestData($contestId);

if(!$contestData){
    die;
}

if(!$contestData['is_published']){
    include_once 'container.php';
    showPage($contestData['nombre'],false,parrafoError('Contest is not published yet. Come back later.'), "");
}

$contestPhase = DAOContest_getContestPhase($contestId);
if($contestPhase=='FINISHED'){
    include_once 'container.php';
    showPage($contestData['nombre'],false,parrafoError('You cannot register because the contest has already finished'), "");
}
if($contestPhase=='IN_PROGRESS'){
    include_once 'container.php';
    showPage($contestData['nombre'],false,parrafoError('Contest has already started. You can check the scoreboard here'), "");
}

if($contestData['is_invitational']){
    if(!DAOContest_isUserInvited($contestId, $_SESSION['userId'])){
        include_once 'container.php';
        showPage($contestData['nombre'],false,parrafoError('Sorry, you are not invited to this contest'), "");       
        die;
    }
}
    

        //Begin [10-Jun-2012] Raul - moving the registration from store procedure to php code.
        include_once 'data_objects/DAOUser.php';
        if(!DAOUser_isUserRegisteredInContest($userId,$contestId)){
            // include_once 'data_objects/DAOContest.php';
            $leagueId = DAOContest_getLeagueId($contestId);
            if(!DAOUser_isUserRegisteredInLeague($userId, $leagueId)){
                DAOUser_registerInLeague($userId, $leagueId);
            }
            
            $leaguePoints = DAOUser_getUserLeaguePoints($userId, $leagueId);
            DAOUser_registerInContest($contestId, $userId, $leaguePoints);
            $msg = parrafoOK("&iexcl;Inscrito Correctamente!");
            include_once 'container.php';
            showPage($contestData['nombre'],false,$msg, "");
        }else{
            include_once 'container.php';
            showPage($contestData['nombre'],false,parrafoError('You are already registered in this contest'), "");
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
        
    // }else{
    //     $msg = parrafoError("Inscripciones cerradas");
    //     showPage("too late ;(",false,$msg, "");
    // }
    // }
    // }
?>