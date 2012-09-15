<?php
include_once ('container.php');
include_once ('conexion.php');
include_once 'table.php';

$tablesPC="concurso co, usuario us";
$columnsPC = array(
    array("co.id_concurso",  "",     -1, ""),
    array("co.id_usuario",  "",     -1, ""),
    array("co.url_forum",  "",     -1, ""),
    array("co.nombre",  "Concurso",     160, "",""),    
    array("'enunciados'",  "Enunciado",     60, "style='{text-align: center;}'","linked 3 enunciado"),
    array("co.total_time",  "Duración",     80, "",""),
    array("'resultados'",  "",     70, "","linked 0 con_res"),
    array("'practicar'",  "",     60,"", "linked 0 con_pra"),
    array("'discutir'",  "",     50,"", "linked 2 forum"),
    array("date(fecha)",   "Fecha",            80, "class='penalty'","date"),
    array("us.username",   "Problem Setter",            120, "", "linked 1 user"),
);
$conditionPC = "WHERE co.estado = 'FINALIZED'".
    " AND co.id_usuario = us.id_usuario ".
    "ORDER BY 10 DESC";
$tablePC = new RCTable(conecDb(),$tablesPC,10,$columnsPC,$conditionPC);
//$tablePC->setTitle("Concursos Pasados");
$tablePastContest = $tablePC->getTable();

showPage("HuaHContests", false, $tablePastContest, "");
?>
