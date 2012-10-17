<?php
include_once ('container.php');
include_once ('conexion.php');


$tablesPC="concurso co join usuario us on(co.creator_id = us.id_usuario)";
$columnsPC = array(
    array("co.contest_id",  "",     -1, ""),
    array("co.id_usuario",  "",     -1, ""),
    array("co.url_forum",  "",     -1, ""),
    array("co.nombre",  "Concurso",     200, "",""),
    array("co.total_time",  "Length",     80, "",""),
    array("'space'","name",-2,"",
        "type"=>"replacement",
        "value"=>'<a href="./contest_arena_scoreboard.php?id=#{0}">scoreboard</a>'),
    array("'space'","",-2,"",
        "type"=>"replacement","value"=>"|"),
    array("'practice'","practice",-2,"",
        "type"=>"replacement",
        "value"=>"<a href='./contest_arena.php?id=#{0}'>practice</a>"),
    array("date(fecha)",   "Fecha",            80, "class='penalty'","date"),
    array("us.username",   "Creator",            -2, "", "linked 1 user"),
);
// $conditionPC = "WHERE co.estado = 'FINALIZED'".
//     " AND co.id_usuario = us.id_usuario ".
//     "ORDER BY 10 DESC";

$conditionNE = "WHERE co.is_published = 1 
        AND TIMESTAMPDIFF(SECOND,now(),ADDTIME(fecha,total_time)) < 0 ".
        "ORDER BY 1 DESC"; 

include_once 'table2.php';
$tablePC = new RCTable(conecDb(),$tablesPC,$columnsPC,$conditionNE);
//$tablePC->setTitle("Concursos Pasados");
$tablePastContest = $tablePC->getTable();

showPage("Completed HuaHContests", false, $tablePastContest, "");
?>
