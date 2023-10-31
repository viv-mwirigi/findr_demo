<?php

require_once "databaseconn.php";

$username = $_GET['username'];
$accessChat = $_GET['access'];

header("Location: chat.php?username=$accessChat&from=$username");