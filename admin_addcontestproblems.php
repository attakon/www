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
    // array("'resultados'",  "",     70, "", "linked 0 con_res"),
    // array("'scoreboard'",  "See Scoreboard",     70, "", "replacement", 
    //     'value' => "<a href='/concurso_results.php?i=#{0}&tab=2'>scoreboard</a>"),
    array("'Add Problems'",  "Add problems", 80, "", "replacement", 
        'value' => "<a href='/admin_addcontestproblem_selectproblem.php?i=#{0}'>Add Problems</a>")
    // array("us.username",   "Problem Setter",            120, "", "linked 1 user"),
);

        // case 'con_res':return "<a href='$path/concurso_results.php?i=$id&tab=2'>$caption</a>";

$conditionPC = "WHERE co.estado = 'REGISTRATION_OPEN' ".
    " AND co.creator_id = us.id_usuario ".
    "ORDER BY 1 DESC";

include_once 'table2.php';
$tablePC = new RCTable(conecDb(),$tablesPC,$columnsPC,$conditionPC);
//$tablePC->setTitle("Concursos Pasados");
$tablePastContest = $tablePC->getTable();


showPage("HuaHContests", false, $tablePastContest, "");
?>
