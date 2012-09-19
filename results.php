<?php
include_once ("conexion.php");
include_once 'CustomTags.php';
include_once 'container.php';
include_once 'data_objects/DAOGlobalDefaults.php';

function results($idConcurso){
    $qDetalle = "SELECT camp.id_campaign, camp.puesto,
            camp.new_ranking, user.id_usuario, user.username, camp.puntos, camp.penalizacion " .
                    "FROM usuario user, campaign camp " .
                    "WHERE user.id_usuario = camp.id_usuario " .
                    "AND camp.Id_Concurso = '$idConcurso' " .
                    "ORDER BY camp.puesto ASC, camp.id_campaign ASC";


    $rs = mysql_query($qDetalle,conecDb()) or die ($qDetalle);

    $queryProblem = "SELECT id_problema, nombre, abrev, valor From problema " .
                    "WHERE id_concurso = '$idConcurso' " .
                    "ORDER BY Id_Problema";
    //    mysql_real_escape_string($idConcurso);

    $rsProblems =  mysql_query($queryProblem,conecDb()) or die ($queryProblem);

    ob_start();
    ?>


<div align="center">
<p> Click en el tiempo de env&iacute;o para ver el c&oacute;digo </p>
    <table style="border-collapse: collapse">
        <tr>
            <th > Rank </th>
            <th > &nbsp; </th>
            <th width="100"> Competidor </th>
            <th > Puntos </th>
            <th width="100"> Penalizaci&oacute;n </th>
            <?php
            while($problems = mysql_fetch_array($rsProblems)){
                $problemFileURL = 'files/problems/'.$problems['id_problema'].'_'.$problems['nombre'].'.pdf';
                ?>
            <th class="det" width="110" title="<?php echo $problems[1]?>">
                <?php echo "<label>".$problems[2]."</label>"; ?> <br>
                <?php 
                    if(DAOGlobalDefaults_getGlobalValue('SHOW_PROBLEM_NAMES_IN_RESULTS_PAGE')=='Y'){
                        echo "<label class='scoreboard_problemName'><a href='".$problemFileURL."'> ".$problems[1]."</a></label><br/>";
                    }
                ?>
                <?php echo $problems[3]."pt"?>
            </th>
            <?php }?>
        </tr>


    <?php $i =0;
    while ($data = mysql_fetch_row($rs)){ ?>
        <tr bgcolor="<?php if($i++%2==0)echo "#f1f0f0"?>">
            <td> <?php echo $data[1]?> </td>       <!-- rank in contest-->
            <td width="5"> <?php
                echo "<img src=./images/ranking/$data[2].gif>";
                ?> </td>       <!-- class -->
            <td> <?php echo userLink(".", $data[3], $data[4])?> </td>        <!-- user-->
            <td style="font-weight:bold"align="center"> <?php echo $data[5]?> </td>
            <td align="center"> <?php echo $data[6]?> </td>
            <?php
            $queryCampaignDetalle ="SELECT Id_Problema, solved, tiempo_submision, intentos_fallidos, sourcecode
                        FROM campaigndetalle WHERE id_campaign = " .$data[0]. " " .
                        "ORDER BY Id_Problema";

            $rsCampaingDetalle = mysql_query($queryCampaignDetalle,conecDb()) or die ($queryCampaignDetalle);
            while($campaingDetalle = mysql_fetch_row($rsCampaingDetalle)){
                ?>
            <td class="det" align="center" height="40"> <?php
                if($campaingDetalle[1]){
                    if(DAOGlobalDefaults_getGlobalValue('SHOW_USER_CODE_IN_RESULTS')=='Y'){
                        echo '<a class="det" href="./viewcode.php?cpg='.$data[0].'&p='.$campaingDetalle[0].'">'.$campaingDetalle[2]."</a>";
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
$body = ob_get_contents();
ob_end_clean();
return $body;
}
?>