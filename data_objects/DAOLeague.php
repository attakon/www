<?php

include_once ("utils/DBUtils.php");


function DAOPLeague_registerLeague($leagueName){
    $insert = "INSERT INTO co_league(nombre)
    VALUES ('".$leagueName."')";
    runQuery($insert);
}

function DAOLeague_delLeague($leagueId){
    $query = "DELETE FROM temporada WHERE id_temporada='".$leagueId."'";
    runQuery($query);
}

function DAOLeague_getLeague($leagueId){
    $query = "SELECT id_temporada, nombre FROM temporada WHERE id_temporada='".$leagueId."'";
    return getWholeRow($query);
}
?>

