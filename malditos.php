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
                <th>Auto goles</th>
                <th>Tarjetas amarillas</th>
                <th>Tarjetas rojas</th>
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
           $nombre=$_GET['nombreMaldito'];
           
    
            if ($nombre != NULL) {
                $stmt = $pdo->prepare('SELECT *
                                  FROM jugadores_malditos
                                  WHERE name=:nombre');

                $stmt->execute([$nombre]);
            }
            else {
                $stmt = $pdo->prepare('SELECT *
                                       FROM jugadores_malditos');
                $stmt->execute();
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
