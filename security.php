<?php

require_once "databaseconn.php";
//  echo '<pre>';
//     echo print_r($_SERVER).'<br>';
//     echo '</pre>';
//     exit; 

if($_SERVER['REQUEST_METHOD'] === 'POST' ) {

    $username = $_SERVER['QUERY_STRING'];
    $passwords = $_POST['passwords'];


    $editAccount = $pdo->prepare("UPDATE users SET passwords = :passwords where username = :username");
    $editAccount->bindValue(':username', $username);
    $editAccount->bindValue(':passwords', $passwords);
    $editAccount->execute();
    header("Location: settings.php?username=$username");
    
}

?>