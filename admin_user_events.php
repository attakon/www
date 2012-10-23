<?php
session_start();
include_once 'utils/ValidateAdmin.php';


// RUNNING CONTESTS
$tables="user_events ue JOIN usuario u on (ue.user_id=u.id_usuario) 
    JOIN user_event_types uet on(ue.user_event_type_id = uet.user_event_type_id)";
// TIME
// <div id="timer_"/>
// <script type="text/javascript">window.onload = CreateTimer("timer1", 30);</script>

$columnsNE = array(
        array("ue.user_event_id",  "user_event_id",     -2, ""),
        array("u.username",  "user_id",     -2, ""),
        array("uet.event_name",  "Event",     -2, ""),
        array("ue.event_date",  "date",     -2, ""),
);

$conditionNE = "ORDER BY 1 DESC"; 

include_once 'table2.php';
include_once 'conexion.php';
$userEventsTable = new RCTable(conecDb(),$tables,$columnsNE,$conditionNE);
$userEventsTable->setTitle("User Events");
$userEventsTable->setTableAtr("width='400'");


$content = '<div>All User Events</div>'.$userEventsTable->getTable().'<br/>';

include_once ('container.php');
showPage("Completed HuaHContests", false, $content, "");
?>
