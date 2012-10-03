<?php
include_once ("utils/DBUtils.php");

function DAOLeagueCompetitor_getLeagueId($leagueCompetitorId){ 
   $query = "SELECT competitor.id_temporada FROM competidor WHERE competidor.id_competitor = '".$leagueCompetitorId."'";
   $leagueId = getRow($query);
   return $leagueId;
}

function DAOCompetitor_getContestProblemsForUser($competitorId, $contestId){ 
   $query = "SELECT cp.problem_id, cp.name, cd.solved, cd.intentos_fallidos, ccp.points FROM 
   	campaigndetalle cd join campaign ca using(id_campaign)
   	JOIN co_problem cp on(cd.id_problema = cp.problem_id)
   	JOIN co_contest_problems ccp on(ccp.problem_id=cp.problem_id AND ccp.contest_id=".$contestId.")
   	 WHERE ca.id_usuario = '".$competitorId."' AND ca.id_concurso = '".$contestId."'";
   $data = getRowsInArray($query);
   return $data;
}

?>
