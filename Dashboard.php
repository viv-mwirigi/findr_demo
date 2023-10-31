<?php

require_once "databaseconn.php";


$username = $_GET['username'];

if($username === ''){
    echo "<h1>Username not provided</h1>";
    exit;
}

$checkUserExists = $pdo->prepare("SELECT * FROM users WHERE username = :username");
$checkUserExists->bindValue(":username", $username);
$checkUserExists->execute();
$checkUser = $checkUserExists->fetchAll(PDO::FETCH_ASSOC);

if(count($checkUser) === 0){
    echo "<h1>User \"$username\" doesn't exist";
    exit;
}


$notificationid = 0;
$hasNotifications = true;

$notificationid = rand(1,4);

$randomUserDisplay = $pdo->prepare("SELECT * FROM users");
    $randomUserDisplay->execute();
    $randomuser = $randomUserDisplay->fetchAll(PDO::FETCH_ASSOC);

$usersGender = $pdo->prepare("SELECT gender FROM details WHERE username = :username");
$usersGender->bindValue(":username", $username);
$usersGender->execute();
$usersG = $usersGender->fetch();






$storedDetails = [];
$show = [];

 foreach($randomuser as $i => $random) {
    $rando = $random['username'];
    $genderCheck = $pdo->prepare("SELECT * FROM details where username = :username");
    $genderCheck->bindValue(":username", $rando);
$genderCheck->execute();
$gender = $genderCheck->fetch();

// echo '<pre>';
// echo print_r($gender).'<br>';
// echo '</pre>';
// exit;
   
    if((!(strcasecmp($random['username'], $username) === 0)) && !(strcasecmp($usersG['gender'],$gender['gender']) === 0)) {

        //it should be stored
        $storedDetails[] = $random;
    }
 }



$ret = 0;
if(strpos($_SERVER['QUERY_STRING'],"&")){

    if($_GET['returnThis'] >= count($storedDetails)) {
        $ret = 0;
    } else {
        $ret = $_GET['returnThis'];
    }

    if($_GET['move'] === "next") { 
        $show[] = $storedDetails[$ret];
        
     } else {
        $show[] = $storedDetails[0];
     }
}else {
    $show[] = $storedDetails[0];
 }






 



    $selectAccount = $pdo->prepare("SELECT * FROM users where username = :username");
    $selectAccount->bindValue(':username', $username);
    $selectAccount->execute();
    $result = $selectAccount->fetch();

$pickNotification = $pdo->prepare("SELECT * FROM notifications where notificationid = :notificationid");
$pickNotification->bindValue(':notificationid', $notificationid);
$pickNotification->execute();
$randNo = $pickNotification->fetch();

$mydetailsAccount = $pdo->prepare("SELECT * FROM details where username = :username");
    $mydetailsAccount->bindValue(':username', $username);
    $mydetailsAccount->execute();
    $mydetails = $mydetailsAccount->fetch();
    

    $detailsAccount = $pdo->prepare("SELECT * FROM details where username = :username");
    $detailsAccount->bindValue(':username', $show[0]['username']);
    $detailsAccount->execute();
    $details = $detailsAccount->fetch();

    

/**
 * while user is not current user
 *  take user id or username and assign to storage variable
 *  repeat till done use foreach
 * after the loop
 * iterate over the values in storage variable
 * display the image and details associated with this result
 * 
 * repeat if needed
 */





   
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/findr_demo/bootstrap-5.0.2-dist/css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="/findr_demo/bootstrap-5.0.2-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/findr_demo/Dashboard.css">
    <style>
.notification_bar_full {
    position: relative;
    top: 0;
    left: 0;
    margin: 0;
    width: 0px;
    height: 100vh;
    background: white;
    transition: 2s;
}
    </style>

    <title>Dashboard</title>
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
                    <li><a href="/findr_demo/Settings.php?username=<?php echo $mydetails['username']; ?>">Settings</a></li>
                    <li><a href="/findr_demo/chat.php?username=<?php echo $mydetails['username']; ?>">Chat</a></li>
                    <li><a href="#" id="notifications">Notifications</a></li>
                    <li><a href="/findr_demo/matches.php?username=<?php echo $mydetails['username']; ?>">Matches</a></li>
                    <li><a href="/findr_demo/Dashboard.php?username=<?php echo $mydetails['username']; ?>">Dashboard</a></li>
                    <li><a href="#" id="trigram"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                      </svg></a></li>
                </ul>
            </nav>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-3">
                <span id="cardPopup" style="display: none; position: absolute; float: right; top: 45px; right: 6px;">
                    <div class="card" style="width: 22rem;">
                        <ul class="list-group list-group-flush">
                            <div class="row card-header">
                                <div class="col-8">
                                    <h3 class="exceptP">Notifications</h3>
                                </div>
                                <div class="col-3">
                                    <a href="#" class="exceptLink" id="collapsible">See all</a>
                                </div>
                                <div class="col-1">
                                    <a href="#" id="closeCard">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="black" class="bi bi-x-lg" viewBox="0 0 16 16">
                                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                          </svg>
                                      </a>
                                </div>
                            </div>
                            <?php if($hasNotifications): ?>
                          <h6 class="list-group-item"><?php 
                                    echo $randNo['name'];
                            ?><?php 
                                    
                            if($notificationid ==1){
                                echo "<a class=\"btn btn-info\" href=\"/findr_demo/Settings.php?username=".$result['username']."\">My account settings</a>";
                            } elseif($notificationid == 2) {
                                echo "<a class=\"btn btn-success\" href=\"#\" class=\"btn btn-info\">Rate us</a>";
                            } elseif($notificationid == 3){
                                echo "<a class=\"btn btn-primary\" href=\"/findr_demo/userProfile.php?username=".$result['username']."\">View followers</a>";
                            } elseif($notificationid == 4) {
                                echo "<a class=\"btn btn-danger\" href=\"/findr_demo/settings.php?username=".$result['username']."\">Password alert</a>";
                            }
                            ?><?php endif; ?>
                            <?php if($hasNotifications == false){
                            echo '<p class="card-text" style="color: grey; position: relative; margin-top:14px; text-align:center;">
                            You have no notifications.
                          </p>';
                        } ?></h6>

                        </ul>
                      </div>
                </span>
            </div>
            <!-- only if two people like each other should they be allowed to see the profile -->
            <div class="col-6">
                <div class="backImg">
                    <img src="<?php echo $show[0]['image'] ?>" style="width: 350px;height: 600px;background-repeat: no-repeat;background-size: cover;display: block;margin: auto;margin-top: 14px;border-radius: 12px;">
                    <div class="FullName" id="fullnames">
                        <h1><?php echo $show[0]['firstname'] ?> <?php echo $show[0]['lastname'] ?></h1>
                    </div>
                    <div class="age">
                        <h2><?php echo $show[0]['age'] ?> years old</h2>
                    </div>
                    <div class="location">
                        <h3><?php echo $show[0]['city'] ?>, <?php echo $show[0]['residentcountry'] ?>. <?php echo rand(1, 100); ?> Mi</h3>
                    </div>
                    
                    <div class="iconBg">
                        <div class="row">
                            <div class="col-6 cancelimg" id="cancel">
                                <a class="cancel">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="106px" height="70px" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                                        <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>
                                      </svg>
                                </a>
                            </div>
                            <div class="col-6 likeimg" id="like">
                                <a class="like" type="input" value="0" >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="106px" height="70px" fill="currentColor" class="bi bi-suit-heart-fill" viewBox="0 0 16 16">
                                        <path d="M4 1c2.21 0 4 1.755 4 3.92C8 2.755 9.79 1 12 1s4 1.755 4 3.92c0 3.263-3.234 4.414-7.608 9.608a.513.513 0 0 1-.784 0C3.234 9.334 0 8.183 0 4.92 0 2.755 1.79 1 4 1z"/>
                                      </svg>
                                </a>
                            </div>
                        </div>
                    </div> 
                </div>
                <div class="row inputBtns">
                    <div class="col-3"></div>
                    <div class="col-3">
                        <a class="btn btn-outline-primary centerBtn" type="submit" value="Follow" id="follow">Follow</a>
                    </div>
                    <div class="col-3">
                        <a class="btn btn-outline-primary centerBtn" type="submit" href="/findr_demo/chat.php?username=<?php echo $show[0]['username'] ?>&from=<?php echo $mydetails['username']; ?>">Chat</a>
                    </div>
                    <div class="col-3"></div>
                </div>
            </div>
            
            <div class="col-3" >
                <div id="note" class="main-div">
                    <div class="card-header" id="changeable" style="display: none;">
                        <div class="row">
                            <div class="col-10">
                                <h2>Notifications</h2>
                            </div>
                            <div class="col-2">
                                <a href="#" id="closeLargerCard">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="black" class="bi bi-x-lg" viewBox="0 0 16 16">
                                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                      </svg>
                                  </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="right-bar" id="changeable" style="display:none;">
                    
                        <div class="row">
                        <?php if($hasNotifications): ?>
                            <div class="card card-positioning" style="width: 21rem;">
                                <div class="card-body">
                                  <h5 class="card-title">
                                    <?php 
                                    echo $randNo['name'];
                                    if($notificationid ==1){
                                        echo '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="blue" class="bi bi-info-circle" viewBox="0 0 16 16">
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                        <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                                      </svg>';
                                    } elseif($notificationid == 2) {
                                        echo '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="gold" class="bi bi-stars" viewBox="0 0 16 16">
                                        <path d="M7.657 6.247c.11-.33.576-.33.686 0l.645 1.937a2.89 2.89 0 0 0 1.829 1.828l1.936.645c.33.11.33.576 0 .686l-1.937.645a2.89 2.89 0 0 0-1.828 1.829l-.645 1.936a.361.361 0 0 1-.686 0l-.645-1.937a2.89 2.89 0 0 0-1.828-1.828l-1.937-.645a.361.361 0 0 1 0-.686l1.937-.645a2.89 2.89 0 0 0 1.828-1.828l.645-1.937zM3.794 1.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387A1.734 1.734 0 0 0 4.593 5.69l-.387 1.162a.217.217 0 0 1-.412 0L3.407 5.69A1.734 1.734 0 0 0 2.31 4.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387A1.734 1.734 0 0 0 3.407 2.31l.387-1.162zM10.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732L9.1 2.137a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L10.863.1z"/>
                                      </svg>';
                                    } elseif($notificationid == 3){
                                        echo '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="green" class="bi bi-bell-fill" viewBox="0 0 16 16">
                                        <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zm.995-14.901a1 1 0 1 0-1.99 0A5.002 5.002 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901z"/>
                                      </svg>';
                                    } elseif($notificationid == 4) {
                                        echo '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="red" class="bi bi-exclamation-circle-fill" viewBox="0 0 16 16">
                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
                                      </svg>';
                                    }
                                    ?></h5>
                                  <p class="card-text">
                                    <?php echo $randNo['description']; ?>
                                  </p>
                                  <?php 
                                    
                                    if($notificationid ==1){
                                        echo "<a class=\"btn btn-info\" href=\"/findr_demo/Settings.php?username=".$result['username']."\">My account settings</a>";
                                    } elseif($notificationid == 2) {
                                        echo "<a class=\"btn btn-success\" href=\"#\" class=\"btn btn-info\">Rate us</a>";
                                    } elseif($notificationid == 3){
                                        echo "<a class=\"btn btn-primary\" href=\"/findr_demo/userProfile.php?username=".$result['username']."\">View followers</a>";
                                    } elseif($notificationid == 4) {
                                        echo "<a class=\"btn btn-danger\" href=\"/findr_demo/settings.php?username=".$result['username']."\">Password alert</a>";
                                    }
                                    ?>
                                  
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if($hasNotifications == false){
                            echo '<p class="card-text" style="color: grey; position: relative; margin-top:14px; text-align:center;">
                            You have no notifications.
                          </p>';
                        } ?>
                          


                        <div class="row padding-insurer2"></div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    <script>

        var notificationLink = document.getElementById("notifications");
        var coll = document.getElementById("collapsible");
        var notify = document.getElementById("note");
        var spanContent = document.getElementById("cardPopup");
        var closer = document.getElementById("closeCard");
        var closeBig = document.getElementById("closeLargerCard");
        const changeable = document.querySelectorAll("#changeable");
        var likeClick = document.getElementById("like");
        var cancelClick = document.getElementById("cancel");
        var followClick = document.getElementById("follow");
        var fullnames = document.getElementById("fullnames");
    

        let collection = changeable.length;
        var i,x = 0;
        var likes = <?php echo $details['likes'] ?>;
        let followers = <?php echo $details['followers'] ?>;
        let following = <?php echo $mydetails['following'] ?>;
        var jsUsername = "<?php echo $show[0]['username']; ?>";
        var jsKeep = "<?php echo $_GET['username']; ?>";
        var counter = 1;
        var to = <?php echo $ret ?>;
        
        
        cancelClick.addEventListener("click", function() {
            followers+=1;
            console.log(followers);
            window.location.assign("http://localhost/findr_demo/cancelupdater.php?username="+jsUsername+"&to="+to+"&keep="+jsKeep);
        });

        followClick.addEventListener("click", function() {
            followers+=1;
            following+=1;
            console.log(followers);
            window.location.assign("http://localhost/findr_demo/followersupdater.php?username="+jsUsername+"&followers="+followers+"&following="+following+"&to="+to+"&keep="+jsKeep);
        });

        likeClick.addEventListener("click", function(e) {
            likes+=1;
            console.log(likes);
           
           
           window.location.assign("http://localhost/findr_demo/likesupdater.php?username="+jsUsername+"&likes="+likes+"&to="+to+"&keep="+jsKeep);
        });
        
        closer.addEventListener("click", function() {
            spanContent.style.display = "none";
        });

        closeBig.addEventListener("click", function() {
            notify.style.display = "none";
            changeable.forEach(changed => {
                changed.style.display = "none";
            });
        });


        notificationLink.addEventListener("click", function () {
            notify.style.display = "none";
            changeable.forEach(changed => {
                changed.style.display = "none";
            });
                
            if(spanContent.style.display === "none") {
                spanContent.style.display = "block";
            } else {
                spanContent.style.display = "none";
            }
        });

x=0;

        

            coll.addEventListener("click", function()
            {
                
                spanContent.style.display = "none";
                notify.className += "notification_bar_full ";

                if(notify.style.display === "block") {
                    notify.style.display = "none";
                    changeable.forEach(changed => {
                changed.style.display = "none";
            })
                } else {
                    
                    notify.style.display = "block";
                    notify.style.width = "100%";
                    changeable.forEach(changed => {
                changed.style.display = "block";
            })
                    notify.style.height ="" + window.outerHeight + "px";
                }
            });

    </script>

    <script src="/findr_demo//bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>