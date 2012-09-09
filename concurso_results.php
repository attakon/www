<?php
include_once ("conexion.php");
include_once 'CustomTags.php';
include_once 'container.php';
include_once 'results.php';
include_once 'practice_page.php';


//$idConcurso = 1;
error_reporting(E_ALL ^ E_NOTICE);
conecDb();
$idConcurso = mysql_real_escape_string($_GET['i']);
$estadoContest = firstRow("SELECT estado from concurso where id_concurso= '$idConcurso' ");
if($estadoContest[0]== null || $estadoContest[0]!="FINALIZED"){
    showPage("Resultados", false, parrafoOK("Resultados no disponible para este Concurso"), "");
}
else{
    $dataConcurso = firstRow("SELECT nombre, fecha, url_forum  From concurso WHERE id_concurso= '$idConcurso'");
    $tab = mysql_real_escape_string($_GET['tab']);
    $classPractice = "header";
    $classResult = "header";
    if($tab=="1"){
        $classPractice = "headerSelected";
    }else{
        $classResult = "headerSelected";
    }
    ob_start();
    ?>
<table align="center" width="100%" height="24" style="border-collapse: collapse">
    <tr>
        <td height="24" width="160">
            <a class ="<?php echo$classPractice?>" href="concurso_results.php?i=<?php echo$idConcurso?>&tab=1">Practicar</a>
        </td>
        <td height="24" width="160">
            <a class ="<?php echo$classResult?>" href="./concurso_results.php?i=<?php echo$idConcurso?>&tab=2">Resultados</a>
        </td>
        <td class ="header">
            <a  target="_blank" href="./files/<?php echo$dataConcurso[0]?>.pdf" >Descargar Enunciados </a>
        </td>
        <td height="24" width="200">
            <a class ="header" href="<?php echo$dataConcurso[2]?>">[Discutir sobre este evento]</a>
        </td>
        <td height="24" width="227">&nbsp;</td>
    </tr>
</table>
<hr>
<?php
$body = ob_get_contents();
ob_end_clean();

if($tab=="1"){
    $idProblem = mysql_real_escape_string($_GET['idp']);
    $body .= practice($idConcurso, $idProblem);
}else if($tab=="2"){    
    $body .= results($idConcurso);
}
showPage($dataConcurso[0]." - ".$dataConcurso[1], false, $body, "");
}
?>