<!doctype html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<title>Song Add</title>
</head>
<body class="bg-dark text-white">
<div class="container-fluid">
    <div class="container position-absolute top-0 start-50 translate-middle-x">
<?php
//error_reporting(0);
include "../incl/lib/connection.php";
require_once "../incl/lib/exploitPatch.php";
require_once "../incl/lib/mainLib.php";
require_once "../incl/lib/Captcha.php";
$gs = new mainLib();


if(!empty($_POST['songlink'])){
	if(!Captcha::validateCaptcha())
		exit("Invalid captcha response");

	$result = $gs->songReupload($_POST['songlink']);
	if($result == "-4"){
		echo "Этот URL-адрес не указывает на действительный аудиофайл.";
	}elseif($result == "-3")
		echo "Эта песня уже существует в нашей базе данных.";
	elseif($result == "-2")
		echo "Ссылка для скачивания не является допустимым URL.";
	else
		echo "<h1>Трек успешно загружен!<b> ID: ${result}</b></h1>";

}else{
	echo '<h2>Добавить свой трек</h2>
<h5><i>Загружаются ссылки формата: <a>https://example.com/music.mp3</a><br>или DropBox: <a>https://dl.dropboxusercontent.com/s/music.mp3</a></i></h5>
		<form class="form-group" action="songAdd.php" method="post">
		<div class="mb-3">
            <label class="form-label">Ссылка:</label>
            <input type="text" class="form-control bg-dark text-white" name="songlink">
        </div>';
	Captcha::displayCaptcha();
	echo '<input type="submit" class="btn btn-warning" value="Add Song">
			</form>
		</div>
	</div>	
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>';
}
?>