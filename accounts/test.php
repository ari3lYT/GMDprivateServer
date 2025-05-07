<?php
include "../incl/lib/connection.php";
require "../incl/lib/generatePass.php";
require_once "../incl/lib/exploitPatch.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userName = ExploitPatch::remove($_POST["userName"] ?? '');
    $newpass = $_POST["newpassword"] ?? '';

    if ($userName === '' || $newpass === '') {
        $message = "❌ Укажите и имя пользователя, и новый пароль.";
    } else {
        $query = $db->prepare("SELECT accountID FROM accounts WHERE userName = :userName");
        $query->execute([':userName' => $userName]);

        if ($query->rowCount() == 0) {
            $message = "❌ Пользователь с таким именем не найден.";
        } else {
            $accid = $query->fetchColumn();
            $passhash = password_hash($newpass, PASSWORD_DEFAULT);

            $query = $db->prepare("UPDATE accounts SET password = :passhash WHERE accountID = :id");
            $query->execute([':passhash' => $passhash, ':id' => $accid]);

            GeneratePass::assignGJP2($accid, $newpass);

            $message = "✅ Пароль успешно изменён для пользователя <b>$userName</b>!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Смена пароля</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body class="bg-dark text-white">
<div class="container py-5">
    <h1 class="mb-4">Смена пароля</h1>
    <?php if ($message): ?>
        <div class="alert alert-info"><?= $message ?></div>
    <?php endif; ?>
    <form method="post" class="bg-secondary p-4 rounded">
        <div class="mb-3">
            <label for="userName" class="form-label">Имя пользователя:</label>
            <input type="text" name="userName" id="userName" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="newpassword" class="form-label">Новый пароль:</label>
            <input type="password" name="newpassword" id="newpassword" class="form-control" maxlength="32" required>
        </div>
        <button type="submit" class="btn btn-warning">Изменить пароль</button>
    </form>
</div>
</body>
</html>
        