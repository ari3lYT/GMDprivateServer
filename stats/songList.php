<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Song List</title>
</head>
<body class="bg-dark text-white">
<div class="container-fluid">
    <div class="container position-absolute top-0 start-50 translate-middle-x">
        <h2>Все треки:</h2>
        <form class="form-group" action="songList.php" method="post">
            <div class="mb-3">
                <label class="form-label">Поиск: </label>
                <input type="text" class="form-control bg-dark text-white" name="name">
            </div>
            <div class="mb-3">
                <select class="form-select" name="type">
                    <option value="1">Название трека</option>
                    <option value="2">Автор трека</option>
                </select>
            </div>
            <input type="submit" class="btn btn-warning" value="Search">
        </form><br />
        <div class="container border border-1 border-white">
        <table class="table table-dark table-hover">
            <thead>
            <tr>
                <th>ID</th>
                <th>Song Name</th>
                <th>Song Author</th>
                <th>Size</th>
            </tr>
</thead>
        <tbody>
            <?php
            include "../incl/lib/connection.php";
            require "../incl/lib/exploitPatch.php";
            if (isset($_POST['type']) == true) {
                $type = ExploitPatch::number($_POST['type']);
            } else {
                $type = 2;
            }
            switch ($type) {
                case 1:
                    $searchType = "name";
                    break;
                case 2:
                    $searchType = "authorName";
                    break;
                default:
                    $searchType = "name";
                    break;
            }
            if (isset($_POST['name']) == true) {
                $name = ExploitPatch::remove($_POST['name']);
            } else {
                $name = 'Ari3l';
            }
            $query = $db->prepare("SELECT ID,name,authorName,size FROM songs WHERE " . $searchType . " LIKE CONCAT('%', :name, '%') ORDER BY ID DESC LIMIT 5000");
            $query->execute([':name' => $name]);
            $result = $query->fetchAll();
            foreach ($result as &$song) {
                echo "<tr><td>" . $song["ID"] . "</td><td>" . htmlspecialchars($song["name"], ENT_QUOTES) . "</td><td>" . $song['authorName'] . "</td><td>" . $song['size'] . "mb</td></tr>";
            }
            ?>
        </tbody>
        </table>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
  </body>
</html>