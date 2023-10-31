<?php

require_once "databaseconn.php";



 $username = $_GET['username']; //who we liked

 $getUser = $pdo->prepare("SELECT * FROM users where username = :username");
 $getUser->bindValue(":username", $username);
 $getUser->execute();
 $result = $getUser->fetch();

 $getMatchDetails = $pdo->prepare("SELECT * FROM details where username = :username");
 $getMatchDetails->bindValue(":username", $username);
 $getMatchDetails->execute();
 $fileResults = $getMatchDetails->fetch();

 if(!empty($fileResults['matcheslocation'])){
 //access the file and store usernames into an array
//add username to file but first open it
$handleMine = fopen($fileResults['matcheslocation'], "a");
$users [] = file($fileResults['matcheslocation']);
$storeResults = [];

$index = 0;
foreach($users as $user => $n){
    $countUsers = count($n);
    while($index < $countUsers){
        //pick user data and store
        $selectUserData = $pdo->prepare("SELECT * FROM users where username = :username");
        $selectUserData->bindValue(":username", trim($users[0][$index], $charlist=PHP_EOL));
        $selectUserData->execute();
        $selectResults = $selectUserData->fetch();
        $storeResults[$index] = $selectResults;

        $index++;
    }
 }



// echo '<pre>';
//     echo print_r($users).'<br>';
//     echo '</pre>';
//     exit; 




    // echo '<pre>';
    // echo print_r($storeResults).'<br>';
    // echo '</pre>';
    // exit; 
}
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/findr_demo/bootstrap-5.0.2-dist/css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="/findr_demo/bootstrap-5.0.2-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/findr_demo/matches.css">
    <title>Matches</title>
</head>
<body>
<div class="container">
        <div class="row">
            <div>
                <h2 class="username"><a href="/findr_demo/userProfile.php?username=<?php echo $result['username']; ?>"><?php echo $result['firstname']; ?></a></h2>
            </div>
            <div>
                <img src="/findr_demo/images/userProfileLogo.svg" class="logoProfile">
            </div>
            <nav>
                <ul>
                    <li><a href="/findr_demo/login.php">Log out</a></li>
                    <li><a href="/findr_demo/Settings.php?username=<?php echo $result['username']; ?>">Settings</a></li>
                    <li><a href="/findr_demo/chat.php?username=<?php echo $result['username']; ?>">Chat</a></li>
                    <li><a href="/findr_demo/matches.php?username=<?php echo $result['username']; ?>">Matches</a></li>
                    <li><a href="/findr_demo/Dashboard.php?username=<?php echo $result['username']; ?>">Dashboard</a></li>
                    <li><a href="#" id="trigram"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                      </svg></a></li>
                </ul>
            </nav>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <?php if(!empty($storeResults)): ?>
            <?php foreach($storeResults as $sr => $r): ?>
            <div class="col-6">
                <div class="row">
                    <div class="col-12">
                        <div class="user-box">
                            <img src="<?php echo $r['image']; ?>" width="300px" height="500px" style="display: flex; border-radius: 12px;">
                            <div class="user-info">
                                <h5>Name: <?php echo $r['firstname']; ?> <?php echo $r['lastname']; ?></h5>
                                <p>Age: <?php echo $r['age']; ?></p>
                                <p>Residence: <?php echo $r['residentcountry']; ?>, <?php echo $r['city']; ?></p>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
            <?php if(empty($storeResults)): ?>
            <h1 style="text-align:center;">You have no matches.</h1>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>