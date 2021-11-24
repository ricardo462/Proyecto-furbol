-- Obtener tiros que terminaron en goal
SELECT *
FROM shot
WHERE shotResult = 'Goal';


-- Obtener tiros que no terminaron en goal
SELECT *
FROM shot
--WHERE shotResult = 'SavedShot' OR shotResult = 'MissedShot' OR shotResult = 'MissedShots';
WHERE shotResult <> 'Goal' OR shotResult <> 'OwnGoal'


-- Ligas con más goles
SELECT leagueid, avg(goles) as cuenta
FROM( SELECT leagueid,(homeGoals + awayGoals) as goles
FROM games) foo
GROUP BY leagueid;

SELECT name, promedio_goles, suma_goles
FROM leagues, (SELECT leagueid, avg(goles) as promedio_goles, sum(goles) as suma_goles
FROM( SELECT leagueid,(homeGoals + awayGoals) as goles
FROM games) foo
GROUP BY leagueid) goles_por_liga
WHERE id = leagueid
ORDER BY promedio_goles DESC;

--Índice de converisón de una liga
SELECT gameid, sum(goals) as suma_goles, sum(shots) as suma_tiros
FROM team_stats_on_game
GROUP BY gameid
LIMIT 5;

SELECT name, sum(suma_goles) as suma_goles, sum(suma_tiros) as suma_tiros, sum(suma_goles)/sum(suma_tiros) as indice
FROM games, leagues, (SELECT gameid, sum(goals) as suma_goles, sum(shots) as suma_tiros
FROM team_stats_on_game
GROUP BY gameid) goles_por_partido
WHERE games.id = goles_por_partido.gameid
AND leagues.id = games.leagueid
GROUP BY leagues.id;

-- nota: hacer una vista que contenga la suma de los goles que sirva tanto para este apartado y el anterior.