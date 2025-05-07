<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Наши модеры</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>
<body class="text-light bg-dark">
    <div class="container-fluid">
<h1>Наши модеры</h1>
<table class="table text-light text-center table-bordered fs-5">
<tr class="bg-secondary">
    <th>Модератор</th>
    <th>Количество действий</th>
    <th>Количество оцененных уровней</th>
    <th>Последний раз онлайн</th>
</tr>
<?php
//error_reporting(0);
include "../incl/lib/connection.php";
require "../incl/lib/mainLib.php";


$gs = new mainLib();
$accounts = implode(",",$gs->getAccountsWithPermission("toolModactions"));
if($accounts == ""){
	exit("Error: No accounts with the 'toolModactions' permission have been found");
}
$query = $db->prepare("SELECT accounts.accountID, accounts.userName, users.lastPlayed FROM accounts INNER JOIN users ON users.extID = accounts.accountID WHERE accountID IN ($accounts) ORDER BY users.stars DESC");
$query->execute();
$result = $query->fetchAll();
foreach($result as &$mod){
	$time = date("d/m/Y G:i:s", $mod['lastPlayed']);
	//TODO: optimize the count queries
	$query = $db->prepare("SELECT count(*) FROM modactions WHERE account = :id");
	$query->execute([':id' => $mod["accountID"]]);
	$actionscount = $query->fetchColumn();
	$query = $db->prepare("SELECT count(*) FROM modactions WHERE account = :id AND type = '1'");
	$query->execute([':id' => $mod["accountID"]]);
	$lvlcount = $query->fetchColumn();
	echo "<tr>
            <td class=\"bg-info text-dark fw-bold\">${mod["userName"]}</td>
            <td>${actionscount}</td>
            <td>${lvlcount}</td>
            <td>${time}</td>
        </tr>";
}
?>
</table>
        </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
  </body>
</html>