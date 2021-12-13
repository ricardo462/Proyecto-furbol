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
                <th>Fecha</th>
                <th>Goles local</th>
                <th>Goles visita</th>
                <th>Goles local 1er tiempo</th>
                <th>Goles visita 1er tiempo</th>
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

            $name=$_GET['theName'];

            $stmt = $pdo->prepare("SELECT date, homeGoals, awayGoals, homeGoalsHalfTime, awayGoalsHalfTime
                                FROM games
                                WHERE leagueID IN (
                                    SELECT id
                                    FROM leagues
                                    WHERE name =:name
                                )");

            $stmt->execute([$name]);
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