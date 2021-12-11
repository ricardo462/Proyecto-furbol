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
FROM  leagues, conversion_en_partidos
WHERE leagues.id = conversion_en_partidos.leagueid
GROUP BY leagues.id;

-- nota: hacer una vista que contenga la suma de los goles que sirva tanto para este apartado y el anterior.
--juegos chuecos
CREATE VIEW conversion_en_partidos
AS
SELECT games.id as gameid, games.leagueid, sum(suma_goles) as suma_goles, sum(suma_tiros) as suma_tiros, sum(suma_goles)/sum(suma_tiros) as indice
FROM games,  (SELECT gameid, sum(goals) as suma_goles, sum(shots) as suma_tiros
FROM team_stats_on_game
GROUP BY gameid) goles_por_partido
WHERE games.id = goles_por_partido.gameid
GROUP BY games.id;



----- 07-12-21

-- Jugadores más quesos
SELECT * 
FROM(SELECT name, sum(owngoals) as owngoal, sum(yellowcard) as yellowcards, sum(redcard) as redcard
FROM (SELECT name, owngoals, yellowcard, redcard
FROM players_stats_on_game, players
WHERE playerid = id) FOO
GROUP BY name) consulta
WHERE name = 'Arturo Vidal';

-- Esta es la potencial vista
CREATE VIEW jugadores_malditos AS
SELECT name, sum(owngoals) as owngoal, sum(yellowcard) as yellowcards, sum(redcard) as redcard
FROM (SELECT name, owngoals, yellowcard, redcard
FROM players_stats_on_game, players
WHERE playerid = id) FOO
GROUP BY name
ORDER BY redcard desc;

--Consulta:
SELECT * 
FROM jugadores_malditos
WHERE name LIKE '%Arturo%';


-- jugador chueco

SELECT name, season, sum(goals), sum(shots)
FROM players_stats_on_game, players, games
WHERE playerid = players.id 
AND gameid = games.id
GROUP BY name, season
LIMIT 10;

-- equipo chueco
SELECT name, season, sum(goals) as goles, sum(shots) as tiros
FROM team_stats_on_game, teams, games
WHERE teamid = teams.id 
AND gameid = games.id
GROUP BY name, season
ORDER BY name
LIMIT 10;

-- \d+ games
SELECT * 
FROM games
LIMIT 1;


-- 10-12-21

--Estadísticas por posición

SELECT name, position, sum(time) as time, sum(goals) as goals, sum(owngoals) as owngoals, sum(shots) as shots, sum(assists) as assists, sum(keypasses) as keypasses, sum(yellowcard) as Tarjetas_amarillas, sum(redcard) as Tarjetas_rojas
FROM (SELECT * 
FROM players_stats_on_game, players
WHERE playerid = id) stats
GROUP BY name, position; --Esto tiene que ser una vista


SELECT position
FROM players_stats_on_game
GROUP BY position;


--Mediocampistas 9:
--AMC
--AML
--AMR
--DMC
--DML
--DMR
--MC
--ML
--MR


--Defensas 3:
--DC
--DR
--DL

--Delantero 3:
--FW
--FWL
--FWR

--Arquero 1:
--GK

--Suplente 1
--Sub

-- Consulta para arquero
SELECT name, sum(time) as Tiempo_jugado, 
sum(goals) as Goles, 
sum(owngoals) as Autogoles, 
sum(shots) as Tiros, 
sum(assists) as Asistencias, 
sum(keypasses) as Pases_claves, 
sum(Tarjetas_amarillas) as Tarjetas_amarillas, 
sum(Tarjetas_rojas) as Tarjetas_rojas

FROM  d_stats
WHERE position = 'GK'
GROUP BY name;



--Consulta para Defensa
SELECT name, sum(time) as Tiempo_jugado, 
sum(goals) as Goles, 
sum(owngoals) as Autogoles, 
sum(shots) as Tiros, 
sum(assists) as Asistencias, 
sum(keypasses) as Pases_claves, 
sum(Tarjetas_amarillas) as Tarjetas_amarillas, 
sum(Tarjetas_rojas) as Tarjetas_rojas

FROM d_stats
WHERE position ='DC'
OR position = 'DR'
OR position = 'DL'
GROUP BY name;


--Consulta para Mediocampista
SELECT name, sum(time) as Tiempo_jugado, 
sum(goals) as Goles, 
sum(owngoals) as Autogoles, 
sum(shots) as Tiros, 
sum(assists) as Asistencias, 
sum(keypasses) as Pases_claves, 
sum(Tarjetas_amarillas) as Tarjetas_amarillas, 
sum(Tarjetas_rojas) as Tarjetas_rojas

FROM  d_stats
WHERE position = 'AMC'
OR position = 'AML'
OR position = 'AMR'
OR position = 'DMC'
OR position = 'DML'
OR position = 'DMR'
OR position = 'MC'
OR position = 'ML'
OR position = 'MR'
GROUP BY name;


--Consulta para delantero
SELECT name, sum(time) as Tiempo_jugado, 
sum(goals) as Goles, 
sum(owngoals) as Autogoles, 
sum(shots) as Tiros, 
sum(assists) as Asistencias, 
sum(keypasses) as Pases_claves, 
sum(Tarjetas_amarillas) as Tarjetas_amarillas, 
sum(Tarjetas_rojas) as Tarjetas_rojas

FROM d_stats
WHERE position = 'FW'
OR position = 'FWL'
OR position = 'FWR'
GROUP BY name;


--Consulta para suplente

SELECT name, sum(time) as Tiempo_jugado, 
sum(goals) as Goles, 
sum(owngoals) as Autogoles, 
sum(shots) as Tiros, 
sum(assists) as Asistencias, 
sum(keypasses) as Pases_claves, 
sum(Tarjetas_amarillas) as Tarjetas_amarillas, 
sum(Tarjetas_rojas) as Tarjetas_rojas

FROM  d_stats
WHERE position = 'Sub'
GROUP BY name
ORDER BY Goles desc;

-- Vista para las estats por posición

CREATE VIEW d_stats
AS
(SELECT name, position, sum(time) as time, sum(goals) as goals, sum(owngoals) as owngoals, sum(shots) as shots, sum(assists) as assists, sum(keypasses) as keypasses, sum(yellowcard) as Tarjetas_amarillas, sum(redcard) as Tarjetas_rojas
FROM (SELECT * 
FROM players_stats_on_game, players
WHERE playerid = id) stats
GROUP BY name, position)


--Filtrado de goles por posición
-- Los valores los da el ususario
SELECT shotresult, lastaction, sistuation, count(*) as cuenta
FROM shot 
WHERE positionx < 0.5
AND positionx > 0
AND positiony < 1
AND positiony > 0.6
GROUP BY shotResult, lastaction, sistuation
ORDER BY shotResult, lastaction, sistuation;


-- Con eso terminaríamos las consultas


