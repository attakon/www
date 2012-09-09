<?php
include_once('./conexion.php');
$q3_rC = "call ssp__registerConcursante
             ('firstname','lastname','1','tupapa22@hotmail.com','rcrcrc','cacapedo');";
    mysql_query($q3_rC,conecDb()) or die("horror : ". mysql_error());
echo "EXITO";
?>
