<?php

require_once "databaseconn.php";
//  echo '<pre>';
//     echo print_r($_POST).'<br>';
//     echo '</pre>';
//     exit; 

if($_SERVER['REQUEST_METHOD'] === 'POST' ) {

    $username = $_SERVER['QUERY_STRING'];
    // $passwords = $_POST['passwords'];

    // if($_POST['delete'] === 'keepsome') {
    //     $deleteAccount = $pdo->prepare("UPDATE users SET username = null, email = null, 
    //                                     passwords = null, image = null, 
    //                                     followernotificationsenabled = null, 
    //                                     likesnotificationsenabled = null  
    //                                     where username = :username");
    //     $deleteAccount->bindValue(':username', $username);
    //     $deleteAccount->execute();

    //     $deleteAccount2 = $pdo->prepare("UPDATE details SET username = null  
    //                                     where username = :username");
    //     $deleteAccount2->bindValue(':username', $username);
    //     $deleteAccount2->execute();
    //     header("Location: signup.php");
    // }
    // else
    if($_POST['delete'] === 'deleteall') {
        $deleteAccount = $pdo->prepare("DELETE FROM users where username = :username");
        $deleteAccount->bindValue(':username', $username);
        $deleteAccount->execute();

        $deleteAccount2 = $pdo->prepare("DELETE FROM details where username = :username");
        $deleteAccount2->bindValue(':username', $username);
        $deleteAccount2->execute();
        header("Location: signup.php");
    }   
}

?>