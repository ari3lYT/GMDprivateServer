<?php
//send_msg('hi');
chdir(dirname(__FILE__));
include "../lib/connection.php";
require_once "../lib/GJPCheck.php";
require_once "../lib/exploitPatch.php";
require_once "../lib/mainLib.php";
$gs = new mainLib();
$gjp2check = isset($_POST['gjp2']) ? $_POST['gjp2'] : $_POST['gjp'];
$gjp = ExploitPatch::remove($gjp2check);
$stars = ExploitPatch::remove($_POST["stars"]);
$levelID = ExploitPatch::remove($_POST["levelID"]);
$accountID = GJPCheck::getAccountIDOrDie();
$permState = $gs->checkPermission($accountID, "actionRateStars");
if($permState){
	$difficulty = $gs->getDiffFromStars($stars);
	$gs->rateLevel($accountID, $levelID, 0, $difficulty["diff"], $difficulty["auto"], $difficulty["demon"]);
//    $mname = $gs->getAccountName($accountID);
//    $txt = <<<Div
//Модератор $mname оценил(а) уровень $levelID!
//Сложность {$difficulty["diff"]}
//{$difficulty["demon"]}
//{$difficulty["auto"]}
//Div;
//    $txt = $mname


}
echo 1;

function send_msg($text)
{
    $log_chat = '-1001739846266';
//    $log_chat = '5994110433';
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