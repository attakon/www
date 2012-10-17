<?php

include_once ("utils/DBUtils.php");


function DAOPLeague_registerLeague($leagueName){
    $insert = "INSERT INTO co_league(nombre)
    VALUES ('".$leagueName."')";
    runQuery($insert);
}

function DAOLeague_delLeague($leagueId){
    $query = "DELETE FROM co_league WHERE league_id='".$leagueId."'";
    runQuery($query);
}

function DAOLeague_getLeague($leagueId){
    $query = "SELECT league_id, nombre FROM co_league WHERE league_id='".$leagueId."'";
    return getWholeRow($query);
}
?>

