<?php
include_once ('container.php');
include_once ('conexion.php');


$tablesPC="concurso co, usuario us";
$columnsPC = array(
    array("co.contest_id",  "",     -1, ""),
    array("co.creator_id",  "",     -1, ""),
    array("co.nombre",  "Concurso",     160, "",""),    
    // array("'enunciados'",  "Enunciados",     60, "", "replacement", 
        // 'value' => "<a target='_blank' href='/files/#{2}.pdf'><img src='/images/PDF_icon.gif'></img></a>"),
    // array("'scoreboard'",  "See Scoreboard",     70, "", "replacement", 

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
