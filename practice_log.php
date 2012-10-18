
<?php
include_once 'conexion.php';
include_once 'table.php';
include_once 'container.php';

$columns = array(
        array("id_log",  "ID",     10, ""),
        array("text",   "w00t", 400, ""),
        array("extra_text",   "w00t", 400, ""),
        array("fecha",   "Top", 100,  "")
);

$condition =
        " ORDER BY 1 DESC ";
$tables = "`log`";
$table = new RCTable(conecDb(),$tables,10,$columns,$condition);
$table->setTitle("Practice Log");

showPage("Practice Log", false, $table->getTable(), " ");
?>
