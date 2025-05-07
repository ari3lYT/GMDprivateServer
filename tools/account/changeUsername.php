<?php
include "../../incl/lib/connection.php";
require "../../incl/lib/generatePass.php";
require_once "../../incl/lib/exploitPatch.php";
require_once "../../incl/lib/Captcha.php";


//here im getting all the data
$userName = ExploitPatch::remove($_POST["userName"]);
$newusr = ExploitPatch::remove($_POST["newusr"]);
$password = ExploitPatch::remove($_POST["password"]);
if($userName != "" AND $newusr != "" AND $password != ""){
    if(!Captcha::validateCaptcha())
        exit("Invalid captcha response");
	$pass = GeneratePass::isValidUsrname($userName, $password);
	if ($pass == 1) {
		if(strlen($newusr) > 20)
			exit("Username too long - 20 characters max. <a href='changeUsername.php'>Try again</a>");
		$query = $db->prepare("UPDATE accounts SET username=:newusr WHERE userName=:userName");	
		$query->execute([':newusr' => $newusr, ':userName' => $userName]);
		if($query->rowCount()==0){
			echo "Invalid password or nonexistant account. <a href='changeUsername.php'>Try again</a>";
		}else{
			echo "Username changed. <a href='..'>Go back to tools</a>";
		}
	}else{
		echo "Invalid password or nonexistant account. <a href='changeUsername.php'>Try again</a>";
	}
}else{
	echo <<<DIV
<!doctype html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<title>ChangeName</title>
</head>
<body class="bg-dark text-white">
	<div class="container-fluid">
		<div class="container position-absolute top-0 start-50 translate-middle-x">
			<h1>Сменить никнейм</h1>
			<form class="form-group" action="changeUsername.php" method="post">
				<div class="mb-3">
					<label class="form-label">Старый никнейм:</label>
					<input type="text" name="userName" class="form-control bg-dark text-white">
				</div>
				
				<div class="mb-3">
					<label class="form-label">Новый никнейм:</label>
					<input type="text" name="newusr" class="form-control bg-dark text-white">
				</div>
				
				<div class="mb-3">
					<label class="form-label">Пароль</label>
					<input type="password" name="password" class="form-control bg-dark text-white">
				</div>
DIV;
    Captcha::displayCaptcha();
echo <<<DEVA
				<input type="submit" class="btn btn-warning" value="Change">
			</form>
		</div>
	</div>	
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
DEVA;
}
?>