<?php
function getConcursoDetalleHTML($contestId) {

    //TODO This has to be refactored to use data objects.
    // include_once('./CustomTags.php');

    include_once('data_objects/DAOContest.php');
    $contestData = DAOContest_getContestData2($contestId);

    $name = $contestData['nombre'];
    $day = $contestData['day']<=9?'0'.$contestData['day']:$contestData['day'];
    $month= $contestData['month'];
    $year = $contestData['year'];
    $time = $contestData['time'];
    $estado = $contestData['estado'];
    $description = $contestData['descripcion'];
    $creatorId = $contestData['creator_id'];
    $isInvitational = $contestData['is_invitational'];

    include_once 'data_objects/DAOUser.php';
    $userData = DAOUser_getUserById($creatorId);
    $timeanddate = "http://www.timeanddate.com/worldclock/fixedtime.html?msg=".$name."&iso=".$year.$month.$day."T".str_replace(":", "", $time)."&p1=94&ah=4";
    // <!-- http://www.timeanddate.com/worldclock/fixedtime.html?msg=UNMSM+2012&iso=20121114T09&p1=94&ah=4 -->

    ob_start();
    $returnedValue ="";
    ?>
<table class='concursoDetail' align='center' border='2' width='400'  >
    <tr>

    </tr>
    <tr>
        <td colspan='2' style="text-align: center;">
            <img  src='images/contest_banner.png' />
        </td>
    </tr>
    <tr>
        <td align ='center' colspan='2' class='concursoTitle'><?php echo $name?></td>
    </tr>
     <tr>
        <td class='torneolabel'>Author:</td>
        <td>
                <?php echo userLink("",$userData['id_usuario'],$userData['username']);?>
        </td>
    </tr>
    <tr>
        <td class='torneolabel'>Fecha:</td>
        <td><?php echo getSpanishDate($day,$month,$year).' a las  '.$time?> CST <br/><a href="<?php echo $timeanddate;?>">See time in your place</a></td>
    </tr>
   
    <!-- <tr>
        <td class='torneolabel'>Locaci&oacute;n:</td>
        <td>
                <?php echo$location?>
        </td>
    </tr> -->


    <!-- commenting-out Raul
    <tr>
        <td class='torneolabel'>Estado:</td>
        <td><?php echo$estado?></td>
    </tr> -->
    
            <?php if($isInvitational) { ?>
            <tr>
                <td colspan='2' class='torneolabel'>This contest is Invitational</td>                
            </tr>
            <?php } ?>
    <tr>        
        <td>&nbsp; <!-- space -->
    </td>
    </tr>
    <tr>
        <td class='torneolabel' colspan='2'>Description:</td>
    </tr>
    <tr>
        <td colspan='2'><?php echo$description?></td>
    </tr>
    <tr>
        <td>&nbsp; <!-- space -->
        </td>
    </tr>
    
    <?php 

    include_once 'data_objects/DAOContest.php';
    $contestPhase = DAOContest_getContestPhase($contestId);

    if($contestPhase=='NOT_STARTED'){?>
        <tr>
            <td align="center" colspan='2'>
                <a href="./concurso_enrollUser.php?cId=<?php echo $contestId?>">[Register]</a>
            </td>
        </tr>
        <?php
    }else if($contestPhase=="IN_PROGRESS"){?>
        <tr>
            <td align="center" colspan='2'>
                <a href="contest_arena.php?id=<?php echo $contestId?>">[Enter]</a>
                <a href="contest_arena_scoreboard.php?id=<?php echo $contestId?>">[Scoreboard]</a>
            </td>
        </tr>
    <?php
    }else if($contestPhase=="FINISHED"){?>
        <tr>
            <td align="center" colspan='2'>
                CONTEST HAS FINISHED
            </td>
        </tr>
        <tr>
            <td align="center" colspan='2'>
                <a href="contest_arena.php?id=<?php echo $contestId?>">[Practice]</a>
                <a href="contest_arena_scoreboard.php?id=<?php echo $contestId?>">[Scoreboard]</a>
            </td>
        </tr>
    <?php 
        }
    ?>
</table>
<br/>
    <?php
    $returnedValue = ob_get_contents();
    ob_end_clean();
    return $returnedValue;
}
?>