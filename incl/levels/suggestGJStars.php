<?php
//error_reporting(0);
chdir(dirname(__FILE__));
include "../lib/connection.php";
require_once "../lib/GJPCheck.php";
require_once "../lib/exploitPatch.php";
require_once "../lib/mainLib.php";
$gs = new mainLib();
$gjp2check = isset($_POST['gjp2']) ? $_POST['gjp2'] : $_POST['gjp'];
$gjp = ExploitPatch::remove($gjp2check);
$stars = ExploitPatch::remove($_POST["stars"]);
$feature = ExploitPatch::remove($_POST["feature"]);
$levelID = ExploitPatch::remove($_POST["levelID"]);
$accountID = GJPCheck::getAccountIDOrDie();
$difficulty = $gs->getDiffFromStars($stars);

if($gs->checkPermission($accountID, "actionRateStars")){
	$gs->rateLevel($accountID, $levelID, $stars, $difficulty["diff"], $difficulty["auto"], $difficulty["demon"]);
	$gs->featureLevel($accountID, $levelID, $feature);
	$gs->verifyCoinsLevel($accountID, $levelID, 1);

	$mname = $gs->getAccountName($accountID);
	$diffr = $gs->getDifficulty($difficulty["diff"], $difficulty["auto"], $difficulty["demon"]);
	$txt = <<<Div
Модератор $mname оценил(а) уровень $levelID!
Сложность {$diffr}
$feature
Div;
	send_msg($txt);

	echo 1;
}else if($gs->checkPermission($accountID, "actionSuggestRating")){
	$gs->suggestLevel($accountID, $levelID, $difficulty["diff"], $stars, $feature, $difficulty["auto"], $difficulty["demon"]);
	echo 1;
}else{
	echo -2;
}

function send_msg($text)
{
//    $log_chat = '-1001739846266';
    $log_chat = '5994110433';
    $BOT_TOKEN = '6140588959:AAHp6i443SelKFBuWzYsjgqryRb4H-ePHto';
    $base_url = "https://api.telegram.org/bot{$BOT_TOKEN}/";
    $params = [
        'chat_id' => $log_chat,
        'parse_mode' => 'Markdown',
        'text' => $text,
    ];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $base_url . 'sendMessage');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = json_decode(curl_exec($ch), true);
    curl_close($ch);
}
?>
