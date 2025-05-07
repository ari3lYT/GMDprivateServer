<?php
include "../../incl/lib/connection.php";
include_once "../../config/security.php";
require "../../incl/lib/generatePass.php";
require_once "../../incl/lib/exploitPatch.php";
include_once "../../incl/lib/defuse-crypto.phar";
require_once "../../incl/lib/Captcha.php";


use Defuse\Crypto\KeyProtectedByPassword;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;
$userName = ExploitPatch::remove($_POST["userName"]);
$oldpass = $_POST["oldpassword"];
$newpass = $_POST["newpassword"];
$salt = "";
if($userName != "" AND $newpass != "" AND $oldpass != ""){
    if(!Captcha::validateCaptcha())
        exit("Invalid captcha response");
$pass = GeneratePass::isValidUsrname($userName, $oldpass);
if ($pass == 1) {
	//creating pass hash
	$passhash = password_hash($newpass, PASSWORD_DEFAULT);
	$query = $db->prepare("UPDATE accounts SET password=:password, salt=:salt WHERE userName=:userName");	
	$query->execute([':password' => $passhash, ':userName' => $userName, ':salt' => $salt]);
	GeneratePass::assignGJP2($accid, $pass);
	echo "Password changed. <a href='..'>Go back to tools</a>";
	//decrypting save
	$query = $db->prepare("SELECT accountID FROM accounts WHERE userName=:userName");	
	$query->execute([':userName' => $userName]);
	$accountID = $query->fetchColumn();
	$saveData = file_get_contents("../../data/accounts/$accountID");
	if(file_exists("../../data/accounts/keys/$accountID")){
		$protected_key_encoded = file_get_contents("../../data/accounts/keys/$accountID");
		if($protected_key_encoded != ""){
			$protected_key = KeyProtectedByPassword::loadFromAsciiSafeString($protected_key_encoded);
			$user_key = $protected_key->unlockKey($oldpass);
			try {
				$saveData = Crypto::decrypt($saveData, $user_key);
			} catch (Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException $ex) {
				exit("Unable to update save data encryption");	
			}
			file_put_contents("../../data/accounts/$accountID",$saveData);
			file_put_contents("../../data/accounts/keys/$accountID","");
		}
	}
}else{
	echo "Invalid old password or nonexistent account. <a href='changePassword.php'>Try again</a>";

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
	<title>ChangePassword</title>
</head>
<body class="bg-dark text-white">
	<div class="container-fluid">
		<div class="container position-absolute top-0 start-50 translate-middle-x">
			<h1>Сменить пароль</h1>
			<form class="form-group" action="changePassword.php" method="post">
				<div class="mb-3">
					<label class="form-label">Никнейм:</label>
					<input type="text" class="form-control bg-dark text-white" name="userName">
				</div>
				<div class="mb-3">
					<label class="form-label">Старый пароль:</label>
					<input type="password" name="oldpassword" class="form-control bg-dark text-white">
				</div>
				<div class="mb-3">
					<label class="form-label">Новый пароль:</label>
					<input type="password" name="newpassword" maxlength=20 class="form-control bg-dark text-white">
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