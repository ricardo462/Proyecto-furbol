<!DOCTYPE html>
<html>
    <head>
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    </head>
<body>
    <?php
        echo "<table>";
        echo "<tr>
                <th>Header Columna 1(s)</th>
                <th>Header Columna 2(s)</th>
                <th>Header Columna 3(s)</th>
                <th>Header Columna 4(s)</th>
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
           $stmt = $pdo->prepare('SELECT *
                                  FROM jugadores_malditos
                                  WHERE name=:nombreJugador');
           $stmt->execute(['nombreJugador' => $nombre]);
           $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

           foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
               echo $v;
           }
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    ?>
</body>
</html>
