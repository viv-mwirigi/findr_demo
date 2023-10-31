<?php

$pdo = new PDO("mysql:host=localhost;port=3306;dbname=findr", 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



$username = $_POST['username'] ?? null;
$imagePath = '';
echo '<pre>';
echo print_r($_POST);
echo '</pre>';
if(!$username){
    header('Location: admin.php');
    exit;
}

$statement = $pdo->prepare("SELECT * FROM users WHERE username=:username");
$statement->bindValue(':username', $username);
$statement->execute();
$result = $statement->fetch();

unlink(dirname($result['image']));

$statement = $pdo->prepare("DELETE FROM users WHERE username=:username");
$statement->bindValue(':username', $username);
$statement->execute();
header('Location: admin.php');