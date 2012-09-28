<?php
function getConcursoDetalle($contestId) {

    //TODO This has to be refactored to use data objects.
    include_once('./conexion.php');
    include_once('./CustomTags.php');
    $q = "SELECT nombre, day(fecha),month(fecha), 
        year(fecha), time(fecha), locacion, inscripcion, premio, estado, 
        descripcion,
        url_forum,        
        creator_id,
        is_invitational
        FROM concurso where id_concurso = '".$contestId."'";
    $rsConcurso = mysql_query($q,conecDb());
    $data = mysql_fetch_row($rsConcurso);

    $name = $data[0];
    $day = $data[1];
    $month= $data[2];
    $year = $data[3];
    $time = $data[4];
    $location = $data[5];
    $inscripcion =$data[6];
    $premio = $data[7];
    $estado = $data[8];
    $description = $data[9];
    $url_forum = $data[10];
    $creatorId = $data[11];
    $isInvitational = $data[12];
    include_once 'data_objects/DAOUser.php';
    $userData = DAOUser_getUserById($creatorId);


    $url_register ='./concurso_enrollUser.php?cId='.$contestId;
    $url_registereds ='./concurso_registeredUsers.php?id='.$contestId;

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
        <td><?php echo getSpanishDate($day,$month,$year).' a las  '.$time?></td>
    </tr>
   
    <!-- <tr>
        <td class='torneolabel'>Locaci&oacute;n:</td>
        <td>
                <?php echo$location?>
        </td>
    </tr> -->

        


        <?php if($inscripcion) { ?>
            <tr>
                <td class='torneolabel'>Inscripci&oacute;n:</td>
                <td><?php echo$inscripcion?></td>
            </tr>
            <?php }
        if($premio) {?>
            <tr>
                <td class='torneolabel'>Premio:</td>
                <td><?php echo$premio?></td>
            </tr>
            <?php } ?>

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
    <tr>
    <?php 

    include_once 'data_objects/DAOConcurso.php';
    $contestPhase = DAOConcurso_getContestPhase($contestId);

    if($contestPhase=='NOT_STARTED'){?>
            <td align="center" colspan='2'>
                <a href="<?php echo $url_register?>">[Register]</a>
            </td>
            <?php
    }else if($contestPhase=="IN_PROGRESS"){?>
            <td align="center" colspan='2'>
            <a href="contest_arena.php?id=<?php echo $contestId?>">[Enter]</a>
            </td>
    <?php
    }else if($contestPhase=="FINISHED"){?>
        <td align="center" colspan='2'>
            CONTEST HAS FINISHED
        </td>  
            <!-- commenting-out Raul
            <a href="<?php echo$url_registereds?>">[ver registrados]</a>
            
            <a href="<?php echo$url_forum?>">[discute este evento]</a>
            -->
        
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

