sudo su postgres
createuser cc3201 -s
createdb -O cc3201 cc3201
exit
psql
CREATE SCHEMA <furbol>;
ALTER USER cc3201 SET search_path TO furbol, public;


CREATE TABLE leagues(
    id SMALLINT,
    name VARCHAR(255),
    PRIMARY KEY(id)
);

CREATE TABLE teams(
    id INT,
    name VARCHAR(255),
    PRIMARY KEY(id)
);


CREATE TABLE players(
    id INT,
    name VARCHAR(255),
    PRIMARY KEY (id)
); 


CREATE TABLE games(
    id INT,
    leagueId INT,
    season SMALLINT,
    date TIMESTAMP,
    homeGoals INT,
    awayGoals INT,
    homeGoalsHalfTime INT,
    awayGoalsHalfTime INT,
    PRIMARY KEY (id),
    FOREIGN KEY (leagueId) REFERENCES leagues(id)
); 

CREATE TABLE players_stats_on_game(
    playerId INT,
    gameId INT,
    subsituteIn INT,
    time INT,
    subsituteOut INT,
    goals INT,
    ownGoals INT,
    shots INT,
    assists INT,
    keyPasses INT,
    position VARCHAR(5),
    positionOrder INT,
    yellowCard INT,
    redCard INT,
    PRIMARY KEY (gameId, playerId),
    FOREIGN KEY (playerId) REFERENCES players(id),
    FOREIGN KEY (gameId) REFERENCES games(id)
);

CREATE TABLE team_stats_on_game(
    teamId INT,
    gameId INT,
    shotsOnTarget INT,
    shots INT,
    goals INT,
    LOCATION VARCHAR(1),
    deep INT,
    ppda REAL,
    fouls INT,
    corners INT,
    yellowCard INT,
    redCard INT,
    result VARCHAR(1),
    PRIMARY KEY (gameId, teamId),
    FOREIGN KEY (teamId) REFERENCES teams(id),
    FOREIGN KEY (gameId) REFERENCES games(id)
);

CREATE TABLE shot(
    shotId INT,
    playerId INT,
    assistsId INT,
    gameId INT,
    minute INT,
    positionX REAL,
    positionY REAL,
    shotType VARCHAR(15),
    shotResult VARCHAR(15),
    lastAction VARCHAR(15),
    sistuation VARCHAR(20),    
    PRIMARY KEY (shotId, gameId, playerId, assistsId),
    FOREIGN KEY (gameId) REFERENCES games(id),
    FOREIGN KEY (playerId) REFERENCES players(id),
    FOREIGN KEY (assistsId) REFERENCES players(id)
);

CREATE TABLE plays_for(
    playerId INT,
    teamId INT,
    PRIMARY KEY (playerId, teamId),
    FOREIGN KEY (playerId) REFERENCES players(id),
    FOREIGN KEY (teamId) REFERENCES teams(id)
);

--drop owned by cc3201 cascade; eliminar todo
