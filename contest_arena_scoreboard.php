<?php
include_once ("conexion.php");
include_once 'CustomTags.php';

include_once 'data_objects/DAOGlobalDefaults.php';

if(isset($_GET['id'])){
    $contestId = $_GET['id'];

    include_once 'data_objects/DAOConcurso.php';
    $contestPhase = DAOConcurso_getContestPhase($contestId);
    $contestData = DAOConcurso_getContestData($contestId);

    if($contestPhase=='NOT_STARTED'){
        include_once 'container.php';
        showPage($contestData['nombre'], false, parrafoError("Contest has not started yet"), "");
    }
    $body='';
    if($contestPhase=='IN_PROGRESS'){
        $secondsLeft = DAOConcurso_getContestLeftTime($contestId);
        $body .= '<div id="left_time_div_'.$contestId.'" ></div>
            <script type="text/javascript">
                timers[timerCount++]=new Array("left_time_div_'.$contestId.'", '.$secondsLeft.');
            </script>';
    }else if($contestPhase=='FINISHED'){
        $body .='<label>Contest has finished</label>';
    }
    $body .= getScoreboardHTML($contestId);

    include_once 'container.php';
    showPage($contestData['nombre'], false, $body, "");
}

function getScoreboardHTML($contestId){
    
    include_once 'data_objects/DAOCampaign.php';
    $campaignData = DAOCampaign_getUserCampaigns($contestId);
    // print_r($campaignData);

    include_once 'data_objects/DAOConcurso.php';
    $problemData = DAOConcurso_getProblems($contestId);
    
    
    
    // $body ="<br>xxx";
    ob_start();
    ?>

<div id="scoreboard_div" align="center">
<!-- <p> Click en el tiempo de env&iacute;o para ver el c&oacute;digo </p> -->
    <table style="border-collapse: collapse">
        <tr>
            <th > Rank </th>
            <th > &nbsp; </th>
            <th width="100"> Coder </th>
            <th > Points </th>
            <th width="100"> Penalizaci&oacute;n </th>
            <?php
            // while($problems = mysql_fetch_array($rsProblems)){
            foreach ($problemData as $key => $problemValue) {
                $problemFileURL = 'files/problems/'.$problemValue['problem_id'].'_'.$problemValue['name'].'.pdf';
                ?>
            <th class="det" width="110" title="<?php echo $problems[1]?>">
                <?php echo "<label>".$key."</label>"; ?> <br>
                <?php 
                    if(DAOGlobalDefaults_getGlobalValue('SHOW_PROBLEM_NAMES_IN_RESULTS_PAGE')=='Y'){
                        echo "<label class='scoreboard_problemName'><a href='".$problemFileURL."'> ".$problemValue['name']."</a></label><br/>";
                    }
                ?>
                <?php echo $problemValue['points']."pt"?>
            </th>
            <?php }?>
        </tr>


    <?php $i =0;
    // while ($data = mysql_fetch_row($rs)){ 
    foreach ($campaignData as $key => $campaignValue) {
    
        ?>
        <tr bgcolor="<?php if($i++%2==0)echo "#f1f0f0"?>">
            <td> <?php echo $campaignValue['puesto']?> </td>       <!-- rank in contest-->
            <td width="5"> <?php
                echo "<img src=./images/ranking/".$campaignValue['new_ranking']."gif>";
                ?> </td>       <!-- class -->
            <td> <?php echo userLink(".", $campaignValue['id_usuario'], $campaignValue['username'])?> </td>        <!-- user-->
            <td style="font-weight:bold"align="center"> <?php echo $campaignValue['puntos']?> </td>
            <td align="center"> <?php echo $campaignValue['penalizacion']?> </td>
            <?php
            $queryCampaignDetalle ="SELECT Id_Problema, solved, tiempo_submision, intentos_fallidos, successful_source_code
                        FROM campaigndetalle WHERE id_campaign = " .$campaignValue['id_campaign']. " " .
                        "ORDER BY Id_Problema";

            $rsCampaingDetalle = mysql_query($queryCampaignDetalle,conecDb()) or die ($queryCampaignDetalle);
            while($campaingDetalle = mysql_fetch_row($rsCampaingDetalle)){
                ?>
            <td class="det" align="center" height="40"> <?php
                if($campaingDetalle[1]){
                    if(DAOGlobalDefaults_getGlobalValue('SHOW_USER_CODE_IN_RESULTS')=='Y'){
                        echo '<a class="det" href="./viewcode.php?cpg='.$campaignValue['id_campaign'].'&p='.$campaingDetalle[0].'">'.$campaingDetalle[2]."</a>";
                    }else{
                        echo '<a class="det">'.$campaingDetalle[2]."</a>";
                    }
                }else{
                    echo "--";
                }
                ?><br>
                <?php
                echo "<label class='wrongTrie'>";
                if($campaingDetalle[3]>0){
                    echo $campaingDetalle[3]." intento".($campaingDetalle[3]!=1?"s fallidos":" fallido");
                }else{
                    echo "&nbsp;";
                }
                echo "</label>";
                ?>
            </td>

            <?php
        }
    }

    ?>
        </tr>
    </table>
</div>
<?php
$body .= ob_get_contents();
ob_end_clean();
return $body;
}
?>