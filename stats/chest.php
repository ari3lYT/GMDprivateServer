<?php
//error_reporting(0);
include "../incl/lib/connection.php";


$lmt = 5;
$query = $db->prepare("SELECT * FROM users ORDER BY users.chest1count DESC LIMIT $lmt");
$query->execute();
$result = $query->fetchAll();
$color = array(
    'primary',
    'success',
    'danger',
    'warning',
    'info'
);
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CHEST</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>
<body class="text-light bg-dark">
    <div class="container-fluid position-relative">
        <center>
            <h2>Сундуки</h2>
            <div class="table-responsive-xl">
            <table class="table text-white table-bordered border-warning table-responsive text-center fs-5">
                <thead class="bg-secondary">
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col">Имя</th>
                    <th scope="col">Малые сундуки</th>
                    <th scope="col">Большие сундуки</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                for ($i=0; $i<$lmt; $i++) {
                    $clr = $color[array_rand(range(0, count($color)), $lmt)[$i]];
                    $name = $result[$i]['userName'];
                    $small = $result[$i]['chest1count'];
                    $big = $result[$i]['chest2count'];
                    $TXT = <<<DIV
                    <tr class="bg-$clr">
                      <th scope="row" class="bg-secondary">$i</th>
                      <td>$name</td>
                      <td>$small</td>
                      <td>$big</td>
                    </tr>
DIV;
                    echo $TXT;
                }
                ?>
                </tbody>
            </table>
                </div>
        </center>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
  </body>
</html>
