<?php
include_once 'contest_arenasubmit.php';

include_once 'CustomTags.php';

session_start();
include_once 'utils/ValidateSignedIn.php';

if(isset($_GET['id'])){
    $contestId = $_GET['id'];
    include_once 'data_objects/DAOConcurso.php';
    $contestData = DAOConcurso_getContestData($contestId);
    $contestName = $contestData['nombre'];
    
    $selectedProblemId=null;
    if(isset($_GET['pid'])){
        $selectedProblemId = $_GET['pid'];    
    }
    // echo practice($contestId);

    include_once 'container.php';
    showPage($contestName.'\'s Arena',false,practice($contestId,$selectedProblemId));
}

function practice($contestId, $selectedProblemId=null){



// include_once 'data_objects/DAOConcurso.php';
// $firstProblem = DAOConcurso_getFirstProblem($contestId);
// print_r($firstProblem);


$contestId = $contestId?$contestId:1;
include_once 'data_objects/DAOConcurso.php';
$problemsData = DAOConcurso_getProblems($contestId);

$firstProblem = $problemsData[0];
$selectedProblemId = $selectedProblemId?$selectedProblemId:$firstProblem['problem_id'];
foreach ($problemsData as $key => $value) {
    if($value['problem_id']==$selectedProblemId){
        $problemName = $value['name'];
    }
}

print_r($problemsData);




// $queryProblem = "SELECT id_problema, abrev, nombre, id_concurso
//     from problema where id_concurso ='".$contestId."'";
// $rsProb = mysql_query($queryProblem,conecDb()) or die($queryProblem);
// $nameProb = firstRow("select nombre from problema where id_problema = '".$selectedProblemId."'");
// $concursoData = firstRow("select nombre, url_forum from concurso where id_concurso = '".$contestId."'");

//$body = get($nameProb, $rsProb, $selectedProblemId);
//echo $body;

ob_start();
?>
    <table cellpadding="0" cellspacing="0" style="border-collapse: collapse"
           align="center" width="100%" height="181" >
        <tr>
            <td height="24" width="190">&nbsp;</td>
            <td height="24" >
                <label class="problemTitle"><?php echo$problemName?></label>
            </td>
        </tr>
        <tr>
            <td height="300" width="190" valign="top" rowspan="2">
                <table cellpadding="0" cellspacing="0" width="190" >
                    <?php
                    // while($problems = mysql_fetch_row($rsProb)){
                    foreach ($problemsData as $key => $problemValue) {
                        $classSelected = "";
                        if($problemValue['problem_id']==$selectedProblemId){
                            $classSelected="class='selectedProblem'";
                        }
                        ?>
                    <tr>
                        <td <?php echo$classSelected?> width="190" height="25">
                            <a href="./contest_arena.php?id=<?php echo$contestId?>&pid=<?php echo $problemValue['problem_id']?>">
                                <?php echo $problemValue['name']?>
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
                <?php echo getForm($selectedProblemId); ?>
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