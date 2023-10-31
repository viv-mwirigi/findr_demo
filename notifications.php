<?php

require_once "databaseconn.php";
//  echo '<pre>';
//     echo print_r($_SERVER).'<br>';
//     echo '</pre>';
//     exit; 

if($_SERVER['REQUEST_METHOD'] === 'POST' ) {

    $username = $_SERVER['QUERY_STRING'];
    $followernotif = $_POST['followernotif'] ?? NULL;
    $likesnotif = $_POST['likesnotif'] ?? NULL;


    $editAccount = $pdo->prepare("UPDATE users SET followernotificationsenabled = :followernotif, 
                                    likesnotificationsenabled = :likesnotif where username = :username");
    $editAccount->bindValue(':username', $username);
    $editAccount->bindValue(':followernotif', $followernotif);
    $editAccount->bindValue(':likesnotif', $likesnotif);
    $editAccount->execute();
    header("Location: settings.php?username=$username");
    
}

?>