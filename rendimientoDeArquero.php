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
                <th>Nombre del jugador</th>
                <th>Tiempo jugado</th>
                <th>Autogoles</th>
                <th>Tiros</th>
                <th>Asistencias</th>
                <th>Pases Clave</th>
                <th>Tarjetas Amarillas</th>
                <th>Tarjetas Rojas</th>
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

           $posicion= $_GET['posicion'];
           $nombre=$_GET['nombreJugador'];
           if($posicion == 'arquero'){
                 $stmt = $pdo->prepare("SELECT name, sum(time) as Tiempo_jugado, 
                                  sum(goals) as Goles, 
                                  sum(owngoals) as Autogoles, 
                                  sum(shots) as Tiros, 
                                  sum(assists) as Asistencias, 
                                  sum(keypasses) as Pases_claves, 
                                  sum(Tarjetas_amarillas) as Tarjetas_amarillas, 
                                  sum(Tarjetas_rojas) as Tarjetas_rojas
                                  
                                  FROM  d_stats
                                  WHERE position = 'GK'
                                  AND name =:nombreJugador 
                                  GROUP BY name");
           }
           elseif($posicion=='medioCampo'){
               $stmt = $pdo ->prepare("SELECT name, sum(time) as Tiempo_jugado, 
                                   sum(goals) as Goles, 
                                   sum(owngoals) as Autogoles, 
                                   sum(shots) as Tiros, 
                                   sum(assists) as Asistencias, 
                                   sum(keypasses) as Pases_claves, 
                                   sum(Tarjetas_amarillas) as Tarjetas_amarillas, 
                                   sum(Tarjetas_rojas) as Tarjetas_rojas

                                   FROM  d_stats
                                   WHERE (position = 'AMC'
                                   OR position = 'AML'
                                   OR position = 'AMR'
                                   OR position = 'DMC'
                                   OR position = 'DML'
                                   OR position = 'DMR'
                                   OR position = 'MC'
                                   OR position = 'ML'
                                   OR position = 'MR')
                                   AND name =:nombreJugador
                                   GROUP BY name");
           }
           elseif($posicion=='defensa'){
                 $stmt = $pdo ->prepare("SELECT name, sum(time) as Tiempo_jugado, 
                                   sum(goals) as Goles, 
                                   sum(owngoals) as Autogoles,
                                   sum(shots) as Tiros, 
                                   sum(assists) as Asistencias, 
                                   sum(keypasses) as Pases_claves, 
                                   sum(Tarjetas_amarillas) as Tarjetas_amarillas, 
                                   sum(Tarjetas_rojas) as Tarjetas_rojas
                                   FROM  d_stats
                                   WHERE (position = 'DC'
                                   OR position = 'DL'
                                   OR position = 'DR')
                                   AND name =:nombreJugador
                                   GROUP BY name");
           }
           elseif($posicion=='delantero'){
                 $stmt = $pdo ->prepare("SELECT name, sum(time) as Tiempo_jugado, 
                                   sum(goals) as Goles, 
                                   sum(owngoals) as Autogoles,
                                   sum(shots) as Tiros, 
                                   sum(assists) as Asistencias, 
                                   sum(keypasses) as Pases_claves, 
                                   sum(Tarjetas_amarillas) as Tarjetas_amarillas, 
                                   sum(Tarjetas_rojas) as Tarjetas_rojas
                                   FROM  d_stats
                                   WHERE (position = 'FW'
                                   OR position = 'FWL'
                                   OR position = 'FWR')
                                   AND name =:nombreJugador
                                   GROUP BY name");
           }
           else{
               $stmt = $pdo ->prepare("SELECT name, sum(time) as Tiempo_jugado, 
                                       sum(goals) as Goles, 
                                       sum(owngoals) as Autogoles,
                                       sum(shots) as Tiros, 
                                       sum(assists) as Asistencias, 
                                       sum(keypasses) as Pases_claves,
                                       sum(Tarjetas_amarillas) as Tarjetas_amarillas, 
                                       sum(Tarjetas_rojas) as Tarjetas_rojas
                                       FROM  d_stats
                                       WHERE (position = 'DC'
                                       OR position = 'DL'
                                       OR position = 'DR')
                                       AND name =:nombreJugador
                                       GROUP BY name");
           }
            if ($nombre != NULL) {
                $stmt->execute(['nombreJugador' => $nombre]);
            }
            else {
                $stmt->execute(['nombreJugador' => 'Claudio Bravo']);
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
