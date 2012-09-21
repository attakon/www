<?php

include_once ("utils/DBUtils.php");


function DAOPLeague_registerLeague($leagueName){
    $insert = "INSERT INTO co_league(nombre)
    VALUES ('".$leagueName."')";
    runQuery($insert);
}

?>

