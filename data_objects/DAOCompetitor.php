<?php
include_once ("utils/DBUtils.php");

function DAOLeagueCompetitor_getLeagueId($leagueCompetitorId){ 
   $query = "SELECT competitor.league_id FROM competidor WHERE competidor.id_competitor = '".$leagueCompetitorId."'";
   $leagueId = getRow($query);
   return $leagueId;
}

function DAOCompetitor_getContestProblemsForUser($competitorId, $contestId){ 
   $query = "SELECT cp.problem_id, cp.name, cd.solved, cd.intentos_fallidos, ccp.points, cps.statement, cp.example_cases FROM 
   	campaigndetalle cd join campaign ca using(id_campaign)
   	JOIN co_problem cp on(cd.id_problema = cp.problem_id)
   	JOIN co_contest_problems ccp on(ccp.problem_id=cp.problem_id AND ccp.contest_id=".$contestId.")
      JOIN co_problem_statement cps on(ccp.problem_id = cps.problem_id AND cps.language_id = ccp.problem_language_id) 
   	 WHERE ca.id_usuario = '".$competitorId."' AND ca.contest_id = '".$contestId."'";
   $data = getRowsInArray($query);
   return $data;
}

?>
