<?php
$id=$_GET['id'];
//$id=2;
if ($id) {
    include_once "conexion.php";
    $sql = "SELECT input_file, nombre FROM problema WHERE id_problema='".$id."'";
    $result = @mysql_query($sql, conecDb());
    $data = @mysql_result($result, 0, "input_file");
    $name = str_replace(" ","",@mysql_result($result, 0, "nombre"));

    header("Content-type: text/in");
    header("Content-Disposition: attachment; filename=$name.in");
    header("Content-Description: PHP Generated Data");
    echo $data;
}
?>
