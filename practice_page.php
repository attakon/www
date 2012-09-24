<?php
include_once 'conexion.php';
include_once 'practice_submit.php';
include_once 'container.php';
include_once 'CustomTags.php';

session_start();


function practice ($idConcurso, $idProblem){
include_once 'utils/ValidateSignedIn.php';

$firstProblem = firstRow("SELECT id_problema from problema WHERE id_concurso = '$idConcurso'");

$idProblem = $idProblem?$idProblem:$firstProblem[0];
$idConcurso = $idConcurso?$idConcurso:1;
$queryProblem = "SELECT id_problema, abrev, nombre, id_concurso
    from problema where id_concurso ='".$idConcurso."'";
$rsProb = mysql_query($queryProblem,conecDb()) or die($queryProblem);
$nameProb = firstRow("select nombre from problema where id_problema = '".$idProblem."'");
$concursoData = firstRow("select nombre, url_forum from concurso where id_concurso = '".$idConcurso."'");

//$body = get($nameProb, $rsProb, $idProblem);
//echo $body;

ob_start();
?>
    <table cellpadding="0" cellspacing="0" style="border-collapse: collapse"
           align="center" width="100%" height="181" >
        <tr>
            <td height="24" width="190">&nbsp;</td>
            <td height="24" >
                <label class="problemTitle"><?php echo$nameProb[0]?></label>
            </td>
        </tr>
        <tr>
            <td height="300" width="190" valign="top" rowspan="2">
                <table cellpadding="0" cellspacing="0" width="190" >
                    <?php
                    while($problems = mysql_fetch_row($rsProb)){
                        $classSelected = "";
                        if($problems[0]==$idProblem){
                            $classSelected="class='selectedProblem'";
                        }
                        ?>
                    <tr>
                        <td <?php echo$classSelected?> width="190" height="25">
                            <a href="./concurso_results.php?i=<?php echo$idConcurso?>&idp=<?php echo$problems[0]?>&tab=1">
                                <?php echo $problems[1]." ".$problems[2]?>
                            </a>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                </table>
            </td>
            <td class ="bordeable" height="30">
                <label>
                    Aqu&iacute; puedes intentar todas las veces que desees.<br>
                    Ni aqu&iacute; ni en Concurso interesa la extensi&oacute;n de tu output.
                    <br>&nbsp;
                </label>
                <?php echo getForm($idProblem); ?>
            </td>
        </tr>
        <tr>
            <td height="300" >&nbsp;</td>
        </tr>
    </table>
<?php
$r = ob_get_contents();
ob_clean();
return $r;
}
?>