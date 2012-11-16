<?php
include_once ('container.php');
include_once ('conexion.php');


// RUNNING CONTESTS
$runningContestsTable="concurso co join usuario us on(co.creator_id = us.id_usuario)";
// TIME
// <div id="timer_"/>
// <script type="text/javascript">window.onload = CreateTimer("timer1", 30);</script>

$columnsNE = array(
        array("co.contest_id",  "contest",     -1, ""),
        array("TIMESTAMPDIFF(SECOND,now(),ADDTIME(fecha,total_time))",  "total_time",     -1, ""),
        // array("now()",   "",            -2, "",""),
        array("nombre",  "Siguente",     -2, "",
            "type"=>"linked 0 concurso"),
        // array("time(fecha)",   "",            30, "","time"),
        array("'countdown'",   "",            100, "",
            "type"=>"replacement",
            'value'=>'<div id="timer_div_#{0}"/>
                <script type="text/javascript">
                timers[timerCount++]={ "div_name":"timer_div_#{0}"
                    ,"left_time":#{1}
                    ,"end_message":\'Contest has finished.\'
                };
                </script>'),
        array("'scoreboard'", "", -2,
            "type"=>'replacement',
            "value"=>'<a href="./contest_arena_scoreboard.php?id=#{0}">scoreboard</a>'),
        array("co.creator_id",  "username",     -1, "",""),
        array("us.username",  "creator",     -2, "",
            "type"=>"replacement",
            "value"=>"<a class='userLink' href='./user.php?u=#{5}'>#{6}</a>")
);

$conditionNE = "WHERE co.is_published = 1 
        AND TIMESTAMPDIFF(SECOND,now(),ADDTIME(fecha,total_time)) >= 0
        AND TIMESTAMPDIFF(SECOND,now(),ADDTIME(fecha,total_time)) <= TIME_TO_SEC(total_time) ".
        "ORDER BY 1 ASC"; 

include_once 'table2.php';
$runningContestsTable = new RCTable(conecDb(),$runningContestsTable,$columnsNE,$conditionNE);
$runningContestsTable->setTitle("Running Contests");
$runningContestsTable->setTableAtr("width='400'");


// UPCOMING CONTESTS
$upcomingTableList="concurso co join usuario us on(co.creator_id = us.id_usuario)";

$columnsNE = array(
        array("co.contest_id",  "contest",     -1, ""),
        array("TIMESTAMPDIFF(SECOND,now(),fecha)",   "",            -1, "",""),
        // array("now()",   "",            -2, "",""),
        array("co.nombre",  "Upcoming Contests",    -2, "",
            "type"=>"linked 0 concurso"),
        array("fecha",   "Date",            -2, "class='penalty'","date"),
        array("co.total_time",  "Duration",     100, "",""),
        array("'countdown'",   "Countdown",            100, "",
            "type"=>"replacement",
            'value'=>'<div id="timer_div_#{0}"/>
                <script type="text/javascript">
                timers[timerCount++]={ "div_name":"timer_div_#{0}"
                    ,"left_time":#{1}
                    ,"end_message":\'contest is now open\'
                };
                </script>'),
        array("'scoreboard'","Register", 100,
            "type"=>'replacement',
            "value"=>'<a href="./concurso_enrollUser.php?cId=#{0}">register</a>'),
        array("co.creator_id",  "username",     -1, "",""),
        array("us.username",  "Creator",     100, "",
            "type"=>"replacement",
            "value"=>"<a class='userLink' href='./user.php?u=#{7}'>#{8}</a>")
);

$conditionNE = "WHERE co.is_published = 1 
        AND TIMESTAMPDIFF(SECOND,now(),fecha) >= 0 ".
        "ORDER BY co.contest_id ASC";

include_once 'table2.php';
$upcomingContestsTable = new RCTable(conecDb(),$upcomingTableList,$columnsNE,$conditionNE);
// $upcomingContestsTable->setTitle("Upcoming Contests");
$upcomingContestsTable->setTableAtr("width='700'");


// FINISHED CONTESTS
$tablesPC="concurso co join usuario us on(co.creator_id = us.id_usuario)";
$columnsPC = array(
    array("co.contest_id",  "",     -1, ""),
    array("co.url_forum",  "",     -1, ""),
    array("co.nombre",  "Finished Contests",     200, "",""),
    array("fecha",   "Date",            -2, "class='penalty'","date"),
    array("co.total_time",  "Duration",     80, "",""),
    array("'space'","Scoreboard",-2,"",
        "type"=>"replacement",
        "value"=>'<a href="./contest_arena_scoreboard.php?id=#{0}">scoreboard</a>'),
    array("'space'","",-2,"",
        "type"=>"replacement","value"=>"|"),
    array("'practice'","practice",-2,"",
        "type"=>"replacement",
        "value"=>"<a href='./contest_arena.php?id=#{0}'>practice</a>"),
    array("co.creator_id",  "username",     -1, "",""),
    array("us.username",  "Creator",     100, "",
            "type"=>"replacement",
            "value"=>"<a class='userLink' href='./user.php?u=#{8}'>#{9}</a>")
);
// $conditionPC = "WHERE co.estado = 'FINALIZED'".
//     " AND co.id_usuario = us.id_usuario ".
//     "ORDER BY 10 DESC";

$conditionNE = "WHERE co.is_published = 1 
        AND TIMESTAMPDIFF(SECOND,now(),ADDTIME(fecha,total_time)) < 0 ".
        "ORDER BY fecha DESC"; 

include_once 'table2.php';
$tablePC = new RCTable(conecDb(),$tablesPC,$columnsPC,$conditionNE);
//$tablePC->setTitle("Concursos Pasados");
$tablePastContest = $tablePC->getTable();

$content = '<div>Running Contests</div>'.$runningContestsTable->getTable().'<br/>'.
            '<div>Upcoming Contests</div>'.$upcomingContestsTable->getTable()."</br>".
            '<div>Finished Contests</div>'.$tablePC->getTable();

showPage("Completed HuaHContests", false, $content, "");
?>
