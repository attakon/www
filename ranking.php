<?php
include_once 'table.php';
include_once 'conexion.php';
include_once 'CustomTags.php';
include_once 'container.php';
include_once 'GLOBALS.php';

//error_reporting(E_ALL ^ E_NOTICE);  // DON'T SHOW NOTICES

$idSchool = $_GET['ids'];
$seasonId = isset ($_GET['seasonid'])?$_GET['seasonid']:$GLOBAL_CURRENT_SEASON;

$tables = "competidor c, usuario us";
$title = "HuaHCoding";
$rankField="position";
$schoolCondition = "AND c.id_temporada = $seasonId ";

if($idSchool) {
    $schoolCondition .="AND us.id_escuela = es.id_escuela ".
            "AND es.id_escuela = '$idSchool' ";
    $rankField="position_school";
    $tables = "competidor c, usuario us, escuela es";
    $rs = firstRow("SELECT nombre from escuela WHERE id_escuela = '$idSchool'");
    $title = $rs[0];
}

$columns = array(
        array("us.id_usuario",  "username",     -1, ""),
        array("c.$rankField",   "#",            20, ""),
        array("c.id_ranking",   "",             0,  "","img images/ranking gif"),
        array("us.username",    "Competidor",   150,"","linked 0 user"),
        array("c.puntos",       "Pts",          30, "class='pts'"),
        array("c.penalty_time", "Penalizaci&oacute;n",50,"class='penalty'")
);

$condition = "WHERE c.id_usuario = us.id_usuario ".$schoolCondition.
        " AND c.$rankField >=1 ".
        "ORDER BY 2 ASC,1 ASC";
$table = new RCTable(conecDb(),$tables,10,$columns,$condition);
showPage("Ranking de $title", false, $table->getTable(), "");

?>
