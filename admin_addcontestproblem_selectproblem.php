<?php
include_once ('container.php');
include_once ('conexion.php');


$tablesPC="problems pr";
$columnsPC = array(
    array("co.problem_id",  "",     -1, ""),
    array("co.creator_id",  "",     -1, ""),
    array("co.nombre",  "Concurso",     160, "",""),
    array("'Add Problems'",  "Add problems", 80, "", "replacement", 
        'value' => "<a href='/admin_addcontestproblem_selectproblem.php?i=#{0}'>Add Problems</a>")
    // array("us.username",   "Problem Setter",            120, "", "linked 1 user"),
);


$conditionPC = "WHERE co.estado = 'REGISTRATION_OPEN' ".
    " AND co.creator_id = us.id_usuario ".
    "ORDER BY 1 DESC";

include_once 'table2.php';
$tablePC = new RCTable(conecDb(),$tablesPC,$columnsPC,$conditionPC);
//$tablePC->setTitle("Concursos Pasados");
$tablePastContest = $tablePC->getTable();


showPage("HuaHContests", false, $tablePastContest, "");
?>
