<?php
//$userout = $_POST['userout'];
include 'conexion.php';
include_once 'CustomTags.php';
include_once 'container.php';
include_once 'utils/ValidateSignedIn.php';
include_once 'data_objects/DAOProblem.php';

if(!isset($_POST['p'])) die;
$idp = $_POST['p'];
$tmpName = $_FILES['userout']['tmp_name'];
$respuesta = sonIguales($tmpName,$idp);
$problemName = firstRow("SELECT nombre FROM problema where id_problema = '$idp' ");
$problemName  = $problemName[0];
if($respuesta[0]) {
    $msgLog = $_SESSION['user']." solved $problemName";
    $query="INSERT INTO `log`(`event`) VALUES('$msgLog');";
    $rs = mysql_query($query,conecDb())or die ($query);
    DAOProblem_markAsSolved($idp,$_SESSION['userId']);
    showPage("", false, parrafoOK("Solucion Correcta para ".$problemName), "");
}else {
    $msgLog = $_SESSION['user']." failed solving $problemName : $respuesta[1]";
    $query="INSERT INTO `log`(`event`) VALUES('$msgLog');";
    $rs = mysql_query($query,conecDb())or die ($query);
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
