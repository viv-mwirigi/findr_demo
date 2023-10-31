<?php

require_once "databaseconn.php";


// echo '<pre>';
//     echo print_r($_GET).'<br>';
//     echo '</pre>';
//     exit; 
$username = $_GET['username'];
$followers = $_GET['followers'];
$following = $_GET['following'];
$to = $_GET['to'];
$keep = $_GET['keep'];

$editFollowers = $pdo->prepare("UPDATE details SET followers = :followers WHERE username = :username");
$editFollowers->bindValue(':username', $username);
$editFollowers->bindValue(':followers', $followers);
$editFollowers->execute();

$editFollowing = $pdo->prepare("UPDATE details SET following = :following WHERE username = :keep");
$editFollowing->bindValue(':following', $following);
$editFollowing->bindValue(':keep', $keep);
$editFollowing->execute();

header("Location: Dashboard.php?username=$keep&move=next&returnThis=$to");    
    
?>