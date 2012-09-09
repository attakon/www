<?php

include_once 'conexion.php';
include_once 'table.php';
include_once 'container.php';
include_once 'CustomTags.php';


$columns = array(
    array("id_ranking",  "Rank",     20, "","img images/ranking gif"),
    array("`from`",   "[Desde(pts)",       80, ""),
    array("`to`",   "Hasta(pts)]",         80,  "")
//    array("(`to` - `from`)",   "Diferencia",         80,  "")
);
$table = "ranking";
$table = new RCTable(conecDb(),$table,"",$columns,"");

$parrafo = parrafoOK("- Cada Temporada ofrece 10 Concursos Individuales.<br>
        - Y cada Concurso Individual ofrece 100 pts.<br>");
$parrafo =$table->getTable()."<br>".$parrafo;
showPage("Sistema de Ranking ", false, $parrafo, "");

?>
