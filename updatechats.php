<?php

require_once "databaseconn.php";

$sender = $_GET['sender'];
$recipient = $_GET['recipient'];
$addThis = $_POST['sendtext'];

if(empty($addThis)){
    header("Location: chat.php?username=$recipient&from=$sender");
}

//update the username if it is changed
$updateSender = $pdo->prepare("SELECT username FROM users where username = :sender");
$updateSender->bindValue(":sender", $sender);
$updateSender->execute();
$tempSenderNameHolder = $updateSender->fetch();
$sender = $tempSenderNameHolder['username'];
// echo '<pre>';
// echo print_r($sender);
// echo '</pre>';

//update the recipients username if it is changed
$updateRecipient = $pdo->prepare("SELECT username FROM users where username = :recipient");
$updateRecipient->bindValue(":recipient", $recipient);
$updateRecipient->execute();
$tempRecipientNameHolder = $updateRecipient->fetch();
$recipient = $tempRecipientNameHolder['username'];
// echo '<pre>';
// echo print_r($recipient);
// echo '</pre>';
// exit;

$mychatsession = $pdo->prepare("SELECT * FROM chatsessions WHERE sender = :me AND recipient = :them");
$mychatsession->bindValue(":me", $sender);
$mychatsession->bindValue(":them", $recipient);
$mychatsession->execute();
$mychatdetails = $mychatsession->fetch();

$theirchatsession = $pdo->prepare("SELECT * FROM chatsessions WHERE sender = :them AND recipient = :me");
$theirchatsession->bindValue(":them", $recipient);
$theirchatsession->bindValue(":me", $sender);
$theirchatsession->execute();
$theirchatdetails = $theirchatsession->fetch();


$handleMine = fopen($mychatdetails['chatlocation'], "a");
fwrite($handleMine,"Me: ".$addThis.PHP_EOL);

$handleTheirs = fopen($theirchatdetails['chatlocation'], "a");
fwrite($handleTheirs,$addThis.PHP_EOL);

// echo '<pre>';
// echo print_r($_POST);
// echo '</pre>';
// exit;

header("Location: chat.php?username=$recipient&from=$sender");


?>