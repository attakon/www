<?php
include_once ("conexion.php");
include_once 'CustomTags.php';

include_once 'data_objects/DAOGlobalDefaults.php';

if(isset($_GET['id'])){
    $contestId = $_GET['id'];
    
    include_once 'data_objects/DAOContest.php';
    $contestData = DAOContest_getContestData($contestId);
    if(!$contestData){
        die;
    }
    $contestPhase = DAOContest_getContestPhase($contestId);

    if($contestPhase=='NOT_STARTED'){
        include_once 'container.php';
        showPage($contestData['nombre'], false, parrafoError("Contest has not started yet"), "");
    }
    $body='';
    if($contestPhase=='IN_PROGRESS'){
        $leftSeconds = DAOContest_getContestLeftSeconds($contestId);
        $countDown = '<div id="left_time_div_'.$contestId.'" ></div>
                    <script type="text/javascript">
                        timers[timerCount++]={
                            div_name:"left_time_div_'.$contestId.'"
                            ,left_time:'.$leftSeconds.'
                            ,end_message:"contest has finished"
                            };
                    </script>';
        $body .= $countDown;
    }else if($contestPhase=='FINISHED'){
        $body .='<label>Contest has finished</label>';
    }
    $body .= getScoreboardHTML($contestId);

    include_once 'container.php';
    showPage($contestData['nombre']." Scoreboard", false, $body, "");
}

function getScoreboardHTML($contestId){
    
    include_once 'data_objects/DAOCampaign.php';
    $campaignData = DAOCampaign_getUserCampaigns($contestId);
    // print_r($campaignData);

    include_once 'data_objects/DAOContest.php';
    $problemData = DAOContest_getProblems($contestId);
    
    
    
    // $body ="<br>xxx";
    ob_start();
    ?>

<div id="scoreboard_div" align="center">
<!-- <p> Click en el tiempo de env&iacute;o para ver el c&oacute;digo </p> -->
    <table style="border-collapse: collapse">
        <tr>
            <th > </th>
            <th > </th>
            <th width="100" style="text-align:left"> Coder </th>
            <th > Points </th>
            <th width="100"> Penalty time </th>
            <?php
            // while($problems = mysql_fetch_array($rsProblems)){
            foreach ($problemData as $key => $problemValue) {
                $problemFileURL = 'files/problems/'.$problemValue['problem_id'].'_'.$problemValue['name'].'.pdf';
                ?>
            <th class="det" width="110" title="<?php echo $problems[1]?>">
                <?php echo "<label>".$key."</label>"; ?>
                <?php 
                    if(DAOGlobalDefaults_getGlobalValue('SHOW_PROBLEM_NAMES_IN_RESULTS_PAGE')=='Y'){
                        echo "<label class='scoreboard_problemName'>".$problemValue['name']."</label>";
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
                // echo "<img src=./images/ranking/".$campaignValue['new_ranking']."gif>";
                ?> 
            </td>  
                 <!-- class -->
            <td> <?php echo userLink(".", $campaignValue['id_usuario'], $campaignValue['username'])?> </td>        <!-- user-->
            <td style="font-weight:bold"align="center"> <?php echo $campaignValue['puntos']?> </td>
            <td align="center"> <?php echo $campaignValue['penalizacion']?> </td>
            <?php
            $campaignDetailData = DAOCampaign_getCampaignDetailForCampaign($contestId, $campaignValue['id_campaign']);
            // $queryCampaignDetalle ="SELECT problem_id, solved, tiempo_submision, intentos_fallidos, successful_source_code
            //             FROM campaigndetalle WHERE id_campaign = " .$campaignValue['id_campaign']. " " .
            //             "ORDER BY problem_id";


            include_once 'data_objects/DAOCampaign.php';
            // $campaignDetail = DAOCampaign_getCampaigneDetailForCampaign($campaignValue['id_campaign']);
            // print_r($campaignDetailData);
            // $rsCampaingDetalle = mysql_query($queryCampaignDetalle,conecDb()) or die ($queryCampaignDetalle);
            // while($campaingDetalle = mysql_fetch_row($rsCampaingDetalle)){
            foreach ($campaignDetailData as $key => $campaignDetailValue) {
            //     # code...
            // }
                include_once 'utils/StringUtils.php';
                $icon = matchLanguageIcon($campaignDetailValue['successful_source_code']);
                ?>
            <td class="det" align="center" height="40"> <?php
                if($campaignDetailValue['solved']){
                    if(DAOGlobalDefaults_getGlobalValue('SHOW_USER_CODE_IN_RESULTS')=='Y'){
                        echo '<a class="det" href="./viewcode.php?cpg='.$campaignValue['id_campaign'].'&p='.$campaignDetailValue['problem_id'].'">'.$campaignDetailValue['tiempo_submision']."</a>";
                        if($icon != "")
                            echo '<img src="../images/prog_language/'.$icon.'" width="28px" height="28px" />';
                    }else{
                        echo '<a class="det">'.$campaignDetailValue['tiempo_submision']."</a>";
                    }
                }else{
                    echo "--";
                }
                ?><br>
                <?php
                echo "<label class='wrongTrie'>";
                if($campaignDetailValue['attempts']>0){
                    echo $campaignDetailValue['attempts']." intento".($campaignDetailValue['attempts']!=1?"s fallidos":" fallido");
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