<!DOCTYPE html>
<html>
    <head>
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <!-- Compiled and minified CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

        <!-- Compiled and minified JavaScript -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    </head>
    <body style="background-color: #eceff1">
    <?php

        echo "<div class='row'>";
        echo "<div class='col s2'></div>";
        echo "<div class='col s8'>";
        echo "<div class='card'>";
        echo "<table>";
        echo "<tr>
                <th>Nombre</th>
                <th>Cantidad de tiros</th>
                <th>Cantidad de goles</th>
                <th>Temporada</th>
              </tr>";

        class TableRows extends RecursiveIteratorIterator {
            function __construct($it) {
                parent::__construct($it, self::LEAVES_ONLY);
            }
            function current() {
                return "<td>" . parent::current(). "</td>";
            }
            function beginChildren() {
                echo "<tr>";
            }
            function endChildren() {
                echo "</tr>" . "\n";
            }
        }

        try {
           $pdo = new PDO('pgsql:
                           host=localhost;
                           port=5432;
                           dbname=cc3201;
                           user=webuser;
                           password=garfield420');

            $scope=$_GET['queryScope'];
            $since=$_GET['from'];
            $until=$_GET['until'];
            $name=$_GET['nameQuery'];

            if($name != NULL) {
                if ($scope == "league") {
                    $stmt = $pdo->prepare("SELECT name, sum(suma_goles) as suma_goles, sum(suma_tiros) as suma_tiros
                        FROM  leagues, conversion_en_partidos
                        WHERE leagues.id = conversion_en_partidos.leagueid
                        AND season <=:until
                        AND season >=:since
                        AND name =:name
                        GROUP BY leagues.id");
                    $stmt->execute([$until, $since, $name]);
                }
                elseif ($scope == "player") {
                    $stmt = $pdo->prepare("SELECT name, sum(goals) as goles, sum(shots) as tiros, season
                        FROM players_stats_on_game, players, games
                        WHERE playerid = players.id 
                        AND gameid = games.id
                        AND season <=:until
                        AND season >=:since
                        AND name =:name
                        GROUP BY name, season");
                    $stmt->execute([$until, $since, $name]);
                }
                else {
                    $stmt = $pdo->prepare("SELECT name, sum(goals) as goles, sum(shots) as tiros, season
                        FROM team_stats_on_game, teams, games
                        WHERE teamid = teams.id 
                        AND gameid = games.id
                        AND season <:until
                        AND season >:since
                        AND name =:name
                        GROUP BY name, season
                        ORDER BY name");
                    $stmt->execute([$until, $since, $name]);
                }
            }
            else {
                if ($scope == "league") {
                    $stmt = $pdo->prepare("SELECT name, sum(suma_goles) as suma_goles, sum(suma_tiros) as suma_tiros
                        FROM  leagues, conversion_en_partidos
                        WHERE leagues.id = conversion_en_partidos.leagueid
                        AND season <=:until
                        AND season >=:since
                        GROUP BY leagues.id");
                    $stmt->execute([$until, $since]);
                }
                elseif ($scope == "player") {
                    $stmt = $pdo->prepare("SELECT name, sum(goals) as goles, sum(shots) as tiros, season
                        FROM players_stats_on_game, players, games
                        WHERE playerid = players.id 
                        AND gameid = games.id
                        AND season <=:until
                        AND season >=:since
                        GROUP BY name, season");
                        $stmt->execute([$until, $since]);
                }
                else {
                    $stmt = $pdo->prepare("SELECT name, sum(goals) as goles, sum(shots) as tiros, season
                        FROM team_stats_on_game, teams, games
                        WHERE teamid = teams.id 
                        AND gameid = games.id
                        AND season <=:until
                        AND season >=:since
                        GROUP BY name, season
                        ORDER BY name");
                    $stmt->execute([$until, $since]);
                }
            }
            
           $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

           foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
               echo $v;
           }
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    echo '<div class="col s2"></div>';
    echo "</table>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    ?>
</body>
</html>