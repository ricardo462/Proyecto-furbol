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
                <th>Resultado del tiro</th>
                <th>Última Acción</th>
                <th>Situación</th>
                <th>Número de tiros</th>
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
            $xInicial=$_GET['xInicial'];
            $yInicial=$_GET['yInicial'];
            $xFinal=$_GET['xFinal'];
            $yFinal=$_GET['yFinal'];
            $dir = [$xFinal, $xInicial, $yFinal,  $yInicial];
           
    
            if ($xInicial != NULL) {
                $stmt = $pdo->prepare('SELECT shotresult, lastaction, sistuation, count(*) as cuenta
                FROM shot 
                WHERE positionx < :xFinal
                AND positionx > :xInicial
                AND positiony < :yFinal
                AND positiony > :yInicial
                GROUP BY shotResult, lastaction, sistuation
                ORDER BY shotResult, lastaction, sistuation');
                $stmt->execute($dir);
            }
            else {
                $stmt = $pdo->prepare('SELECT shotresult, lastaction, sistuation, count(*) as cuenta
                                        FROM shot
                                        GROUP BY shotResult, lastaction, sistuation');
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