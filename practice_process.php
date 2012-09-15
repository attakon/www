<?php
//$userout = $_POST['userout'];
include 'conexion.php';
include_once 'CustomTags.php';
include_once 'container.php';
include_once 'utils/ValidateSignedIn.php';
include_once 'data_objects/DAOProblem.php';

include_once 'z_misc/checkLoggedIn.php';

if(!isset($_POST['p'])) die;
$idp = $_POST['p'];

$tmpName = $_FILES['userout']['tmp_name'];

if(empty($tmpName)){
    showPage("",false,parrafoError("file cannot be empty"),"");
    die;
}
$respuesta = sonIguales($tmpName,$idp);

$problemName = firstRow("SELECT nombre FROM problema where id_problema = '$idp' ");
$problemName  = $problemName[0];
include_once 'data_objects/DAOUserEvents.php';
if($respuesta[0]) {

    DAOUserEvents_logEvent($_SESSION['userId'],'submit_a_solution','successful for $problemName');

    //deprecating soon
    include_once('data_objects/DAOLog.php');
    $msgLog = $_SESSION['user']." solved $problemName";
    DAOLog_log($msgLog,'','');

    DAOProblem_markAsSolved($idp,$_SESSION['userId']);
    showPage("", false, parrafoOK("Accepted Solution for ".$problemName), "");
}else {

    DAOUserEvents_logEvent($_SESSION['userId'],'submit_a_solution','failed for $problemName: message:$respuesta[1]');

    // deprecating soon
    
    include_once('data_objects/DAOLog.php');
    $msgLog = $_SESSION['user']." failed solving $problemName";
    DAOLog_log($msgLog,$respuesta[1],'');

    showPage("", false, parrafoError($respuesta[1]), "");
}

function sonIguales($tmpName, $idProblem) {
    $correctOut = firstRow("select output_file from problema where id_problema ='".$idProblem."'");
    $correct = explode("\n", $correctOut[0]);
    $file_handle = fopen($tmpName, "r");
    $i = 0;
    foreach($correct as $correctLine) {
        $correctLine = trim($correctLine);
        if(strcmp($correctLine,"")!=0) {
            $i++;
            if(!feof($file_handle)) {
                $userLine = trim(str_replace("\n", "",fgets($file_handle)));
                if(strcmp($correctLine, $userLine)!=0) {
                    fclose($file_handle);
                    return array(false,"Error en Linea ".$i." tu salida[".$userLine."] esperado[".$correctLine."]");
                }
            }else {
                fclose($file_handle);
                return array(false,"Respuesta incorrecta tu salida[] esperado[".$userLine."]");
            }
        }
    }
    fclose($file_handle);
    return array(true,true);
}
?>
