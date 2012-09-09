<?php
function getConcursoDetalle($idConcurso ) {
    include_once('./conexion.php');
    include_once('./CustomTags.php');
    $q = "SELECT nombre, day(fecha),month(fecha), year(fecha), time(fecha), locacion, inscripcion, premio, estado, descripcion,
        url_forum
        FROM concurso where id_concurso = '".$idConcurso."'";
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
    $url_register ='./concurso_enrollUser.php?cId='.$idConcurso;
    $url_registereds ='./concurso_registeredUsers.php?id='.$idConcurso;

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
        <td class='torneolabel'>Fecha:</td>
        <td><?php echo getSpanishDate($day,$month,$year).' a las  '.$time?></td>
    </tr>
    <tr>
        <td class='torneolabel'>Locaci&oacute;n:</td>
        <td>
                <?php echo$location?>
        </td>
    </tr>
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
    <tr>
        <td>&nbsp; <!-- space -->
        </td>
    </tr>
    <tr>
        <td colspan='2'><?php echo$description?></td>
    </tr>
    <tr>
        <td>&nbsp; <!-- space -->
        </td>
    </tr>
    <?php if($estado=='REGISTRATION_OPEN'){

        ?>
    <tr>
        <td align="center" colspan='2'>
            <a href="<?php echo $url_register?>">[registrarse]</a>
            <!-- commenting-out Raul
            <a href="<?php echo$url_registereds?>">[ver registrados]</a>
            
            <a href="<?php echo$url_forum?>">[discute este evento]</a>
            -->
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

