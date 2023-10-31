<?php

require_once "databaseconn.php";


// echo '<pre>';
//     echo print_r($_GET).'<br>';
//     echo '</pre>';
//     exit; 
 $username = $_GET['username']; //who we liked
 $likes = $_GET['likes'];
 $returnThis = $_GET['to'];
 $keep = $_GET['keep'];
 $returnThis += 1;
/**
 * steps that you should check
 * check if an entry exists for the liked users(i.e carol) match file
 * location in the details db
 * if it doesn't create it and add username
 * else open the file and add the username
 */

$checkMatches = $pdo->prepare("SELECT * FROM details WHERE username = :username");
$checkMatches->bindValue(":username", $username);
$checkMatches->execute();
$check = $checkMatches->fetch();




//create the matches folder if it doesn't exist
if(!is_dir('matches')){
    mkdir("matches");
}

if(empty($check['matcheslocation'])){
    //entry doesn't exist
 //create the matches file
 $matchFile = fopen("matches.txt","w");

//make the liked users chat folder
$likedUsersFile = 'matches/'.$username;
if(!is_dir($likedUsersFile)){
    //if dir doesn't exist it also means that the matches file doesn't exist
    mkdir($likedUsersFile,0777,true);
    //move the matches file into the senders folder
    copy("matches.txt", $likedUsersFile.'/matches.txt');
    //insert into details db
    $insertMatch = $pdo->prepare("UPDATE details SET matcheslocation = :filelocation where username = :username");
    $insertMatch->bindValue(":filelocation", $likedUsersFile.'/matches.txt');
    $insertMatch->bindValue(":username", $username);
    $insertMatch->execute();

    $selectMatchFile = $pdo->prepare("SELECT * from details where username = :username ");
    $selectMatchFile->bindValue(":username", $username);
    $selectMatchFile->execute();
    $results = $selectMatchFile->fetch();

    //add username to file but first open it
    $handleMine = fopen($results['matcheslocation'], "a");
    fwrite($handleMine,$keep.PHP_EOL);
} 
} else {
    //access chat location
    $selectMatchFile = $pdo->prepare("SELECT * from details where username = :username ");
    $selectMatchFile->bindValue(":username", $username);
    $selectMatchFile->execute();
    $results = $selectMatchFile->fetch();

    //add username to file but first open it
    $handleMine = fopen($results['matcheslocation'], "a");
    $users [] = file($results['matcheslocation']);

// 
$index = 0;
foreach($users as $user => $n){
    $countUsers = count($n);
    while($index < $countUsers){
        if(strcasecmp($users[0][$index], $keep.PHP_EOL) !== 0){
            //username is not in file so add it
            fwrite($handleMine,$keep.PHP_EOL);
        }
        $index++;
    }
    
}
    
}


$editLikes = $pdo->prepare("UPDATE details SET likes = :likes where username = :username");
$editLikes->bindValue(':username', $username);
$editLikes->bindValue(':likes', $likes);
$editLikes->execute();

header("Location: Dashboard.php?username=$keep&move=next&returnThis=$returnThis");    
    
?>