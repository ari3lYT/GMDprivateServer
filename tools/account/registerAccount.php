<?php
include "../../config/security.php";
include "../../incl/lib/connection.php";
require "../../incl/lib/exploitPatch.php";
require "../../incl/lib/generatePass.php";
require_once "../../incl/lib/Captcha.php";


if(!isset($preactivateAccounts)){
	$preactivateAccounts = true;
}

// here begins the checks
if(!empty($_POST["username"]) AND !empty($_POST["email"]) AND !empty($_POST["repeatemail"]) AND !empty($_POST["password"]) AND !empty($_POST["repeatpassword"])){
	if(!Captcha::validateCaptcha())
		exit("Invalid captcha response");
	// catching all the input
	$username = ExploitPatch::remove($_POST["username"]);
	$password = ExploitPatch::remove($_POST["password"]);
	$repeat_password = ExploitPatch::remove($_POST["repeatpassword"]);
	$email = ExploitPatch::remove($_POST["email"]);
	$repeat_email = ExploitPatch::remove($_POST["repeatemail"]);
	if(strlen($username) < 3){
		// choose a longer username
		echo '<body style="background-color:grey;">Username should be more than 3 characters.<br><br><form action="registerAccount.php" method="post">Username: <input type="text" name="username" maxlength=15><br>Password: <input type="password" name="password" maxlength=20><br>Repeat Password: <input type="password" name="repeatpassword" maxlength=20><br>Email: <input type="email" name="email" maxlength=50><br>Repeat Email: <input type="email" name="repeatemail" maxlength=50><br><input type="submit" value="Register"></form></body>';
	}elseif(strlen($password) < 6){
		// just why did you want to give a short password? do you wanna be hacked?
		echo '<body style="background-color:grey;">Password should be more than 6 characters.<br><br><form action="registerAccount.php" method="post">Username: <input type="text" name="username" maxlength=15><br>Password: <input type="password" name="password" maxlength=20><br>Repeat Password: <input type="password" name="repeatpassword" maxlength=20><br>Email: <input type="email" name="email" maxlength=50><br>Repeat Email: <input type="email" name="repeatemail" maxlength=50><br><input type="submit" value="Register"></form></body>';
	}else{
		// this checks if there is another account with the same username as your input
		$query = $db->prepare("SELECT count(*) FROM accounts WHERE userName LIKE :userName");
		$query->execute([':userName' => $username]);
		$registred_users = $query->fetchColumn();
		if($registred_users > 0){
			// why did you want to make a new account with the same username as someone else's
			echo '<body style="background-color:grey;">Username already taken.<br><br><form action="registerAccount.php" method="post">Username: <input type="text" name="username" maxlength=15><br>Password: <input type="password" name="password" maxlength=20><br>Repeat Password: <input type="password" name="repeatpassword" maxlength=20><br>Email: <input type="email" name="email" maxlength=50><br>Repeat Email: <input type="email" name="repeatemail" maxlength=50><br><input type="submit" value="Register"></form></body>';
		}else{
			if($password != $repeat_password){
				// this is when the passwords do not match
				echo '<body style="background-color:grey;">Passwords do not match.<br><br><form action="registerAccount.php" method="post">Username: <input type="text" name="username" maxlength=15><br>Password: <input type="password" name="password" maxlength=20><br>Repeat Password: <input type="password" name="repeatpassword" maxlength=20><br>Email: <input type="email" name="email" maxlength=50><br>Repeat Email: <input type="email" name="repeatemail" maxlength=50><br><input type="submit" value="Register"></form></body>';
			}elseif($email != $repeat_email){
				// this is when the emails dont match
				echo '<body style="background-color:grey;">Emails do not match.<br><br><form action="registerAccount.php" method="post">Username: <input type="text" name="username" maxlength=15><br>Password: <input type="password" name="password" maxlength=20><br>Repeat Password: <input type="password" name="repeatpassword" maxlength=20><br>Email: <input type="email" name="email" maxlength=50><br>Repeat Email: <input type="email" name="repeatemail" maxlength=50><br><input type="submit" value="Register"></form></body>';
			}else{
				// hashing your password and registering your account
				$hashpass = password_hash($password, PASSWORD_DEFAULT);
				$query2 = $db->prepare("INSERT INTO accounts (userName, password, email, registerDate, isActive, gjp2)
				VALUES (:userName, :password, :email, :time, :isActive, :gjp2)");
				$query2->execute([':userName' => $username, ':password' => $hashpass, ':email' => $email,':time' => time(), ':isActive' => $preactivateAccounts ? 1 : 0, ':gjp2' => GeneratePass::GJP2hash($password)]);
				// there you go, you are registered.
				$activationInfo = $preactivateAccounts ? "No e-mail verification required, you can login." : "<a href='activateAccount.php'>Click here to activate it.</a>";
				echo "<body style='background-color:grey;'>Account registred. ${activationInfo}</body>"; // <a href='..'>Go back to tools</a>
			}
		}
	}
}else{
	// this is given when we dont have an input
	echo <<<DIV
<!doctype html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<title>Registration</title>
</head>
<body class="bg-dark text-white">
	<div class="container-fluid">
		<div class="container position-absolute top-0 start-50 translate-middle-x">
			<h1>Регистрация</h1>
			<form class="form-group" action="registerAccount.php" method="post">
				<div class="mb-3">
					<label class="form-label">Никнейм:</label>
					<input type="text" class="form-control bg-dark text-white" name="username" maxlength=15>
				</div>
				<div class="mb-3">
					<label class="form-label">Пароль:</label>
					<input type="password" name="password" maxlength=20 class="form-control bg-dark text-white">
				</div>
				<div class="mb-3">
					<label class="form-label">Повторите пароль:</label>
					<input type="password" name="repeatpassword" maxlength=20 class="form-control bg-dark text-white">
				</div>
				<div class="mb-3">
					<label class="form-label">Email: </label>
					<input type="email" name="email" maxlength=50 class="form-control bg-dark text-white">
				</div>
				<div class="mb-3">
					<label class="form-label">Повторите Email: </label>
					<input type="email" name="repeatemail" maxlength=50 class="form-control bg-dark text-white">
				</div>
DIV;
Captcha::displayCaptcha();
echo <<<DEDV
<input type="submit" class="btn btn-warning" value="Register">
			</form>
		</div>
	</div>	
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
DEDV;
}
?>
