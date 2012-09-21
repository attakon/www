<?php
include_once ("utils/DBUtils.php");

function DAOLeagueCompetitor_getLeagueId($leagueCompetitorId){ 
   $query = "SELECT competitor.id_temporada FROM competidor WHERE competidor.id_competitor = '".$leagueCompetitorId."'";
   $leagueId = getRow($query);
   return $leagueId;
}

?>
