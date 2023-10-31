<?php

require_once "databaseconn.php"; 


if($_SERVER['REQUEST_METHOD'] === 'GET' || $_SERVER['REQUEST_METHOD'] === 'POST' ) {

    $username = $_GET['username'];
    $from = '';

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

    

    if((stripos($_SERVER['QUERY_STRING'],"&from") !== false)){
        $from = $_GET['from'] ?? null;
        if($from === ''){
            echo "<h1>Error: Missing value.</h1>";
            exit;
        } else {
            $checkUserExistsAgain = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $checkUserExistsAgain->bindValue(":username", $from);
    $checkUserExistsAgain->execute();
    $checkUserAgain = $checkUserExistsAgain->fetchAll(PDO::FETCH_ASSOC);

    if(count($checkUserAgain) === 0){
        echo "<h1>User \"$from\" doesn't exist";
        exit;
    }
        }
    }

    //  echo '<pre>';
    // echo print_r($_SERVER).'<br>';
    // echo '</pre>';
    // exit; 
    
    $str='';
    if(stripos($_SERVER['QUERY_STRING'], "&from") !== false)
    {
        $startindex = strpos($_SERVER['QUERY_STRING'], "=") +1; //return the position of the first "="
        $endindex = strpos($_SERVER['QUERY_STRING'], "&"); //return the position of the first "&"
        //together they both have the start and end positions of the username
    
        
        while($startindex < $endindex){
            $str .= $_SERVER['QUERY_STRING'][$startindex];
            $startindex++;
        }
    
        $username = $str;
    }

    $notificationid = 0;
    $hasNotifications = true;
    
    $notificationid = rand(1,4);
    
    $pickNotification = $pdo->prepare("SELECT * FROM notifications where notificationid = :notificationid");
    $pickNotification->bindValue(':notificationid', $notificationid);
    $pickNotification->execute();
    $randNo = $pickNotification->fetch();

    $selectAccount = $pdo->prepare("SELECT * FROM users where username = :username");
    $selectAccount->bindValue(':username', $username);
    $selectAccount->execute();
    $results = $selectAccount->fetchAll(PDO::FETCH_ASSOC);

    $detailsAccount = $pdo->prepare("SELECT * FROM details where username = :username");
    $detailsAccount->bindValue(':username', $username);
    $detailsAccount->execute();
    $details = $detailsAccount->fetch();
   
    $usernameCount = 0;
    $passwordsCount = 0;

    
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
    <link rel="stylesheet" href="/findr_demo/userProfile.css">
    <title>User Profile</title>
</head>
<body>
    <div class="container">
        <div class="row">
            <?php foreach($results as $n => $result): ?>
            <div class="col-2">
                <h2 class="username"><a href="/findr_demo/userProfile.php?username=<?php if(stripos($_SERVER['QUERY_STRING'], "&from")){if(stripos($_SERVER['QUERY_STRING'],"&from") !== 0){
    echo $from; }} else { echo $result['username']; } ?>"><?php if(stripos($_SERVER['QUERY_STRING'], "&from")){if(stripos($_SERVER['QUERY_STRING'],"&from") !== 0){
        echo $from; }} else { echo $result['username']; } ?></a></h2>
            </div>
            <div>
                <img src="/findr_demo/images/userProfileLogo.svg" class="logoProfile">
            </div>
            <nav>
                <ul>
                    <li><a href="/findr_demo/login.php">Log out</a></li>
                    <li><a href="/findr_demo/Settings.php?username=<?php if(stripos($_SERVER['QUERY_STRING'], "&from")){if(stripos($_SERVER['QUERY_STRING'],"&from") !== 0){
    echo $from;} } else { echo $result['username']; } ?>">Settings</a></li>
                    <li><a href="/findr_demo/chat.php?username=<?php if(stripos($_SERVER['QUERY_STRING'], "&from")){if(stripos($_SERVER['QUERY_STRING'],"&from") !== 0){
    echo "$username&from=$from"; }} else { echo $result['username']; } ?>">Chat</a></li>
                    <li><a href="#" id="notifications">Notifications</a></li>
                    <li><a href="/findr_demo/matches.php?username=<?php if(stripos($_SERVER['QUERY_STRING'], "&from")){if(stripos($_SERVER['QUERY_STRING'],"&from") !== 0){
    echo $from;} } else { echo $result['username']; } ?>">Matches</a></li>
                    <li><a href="/findr_demo/Dashboard.php?username=<?php if(stripos($_SERVER['QUERY_STRING'], "&from")){if(stripos($_SERVER['QUERY_STRING'],"&from") !== 0){
    echo $from;} } else { echo $result['username']; } ?>">Dashboard</a></li>
                    <li><a href="#" id="trigram">&#9776;</a></li>
                </ul>
            </nav>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="container">
    <?php foreach($results as $n => $result): ?>
        <div class="row">
            <div class="col-3 mainDiv">
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
                <div class="row">
                    <div class="col-12">
                        <div class="row" height="700px">
                            <div class="col-12">
                                <img src="<?php echo $result['image']; ?>" style=" position: relative; width: 418px;height: 627px;box-shadow: 8px 8px 8px black;margin-top: 24px;left: 20%;border-radius: 12px;display: block;">
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="row btmRow">
                    <div class="col-6">
                        <a class="btn btn-outline-primary centerBtn" type="submit">Follow</a>
                    </div>
                    <div class="col-6">
                        <a class="btn btn-outline-primary centerBtn" type="submit" href="/findr_demo/chat.php?username=<?php echo $username; ?><?php if(stripos($_SERVER['QUERY_STRING'], "&from")){if(stripos($_SERVER['QUERY_STRING'],"&from") !== 0){
    echo "&from=$from"; }} ?>">Chat</a>
                    </div>
                </div>
            </div>
            <div class="col-8" id="navigation">
                <div class="lowerBox">
                    <div class="row">
                        <div class="col-2" id="none">
                        </div>
                        <div class="col-5 ageStyle" id="age">
                            <h2><i>Age</i></h2>
                            <hr class="verticalRule">
                        </div>
                        <div class="col-5 residenceStyle" id="residence">
                            <h2><i>Residence</i></h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3" id="none2">
                            <div class="row">
                                <div class="col-3 floatFirstName">
                                    <h1><?php echo $result['firstname']; ?></h1>
                                </div>
                            </div>
                        </div>
                        <div class="col-4 ageNumber" id="ageNo">
                            <h2><?php echo $result['age']; ?></h2>
                        </div>
                        <div class="col-5 residenceNumber" id="residenceNo">
                            <h2><?php echo $result['city']; ?>, <?php echo $result['residentcountry']; ?></h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6" id="none3">
                            <div class="col-3 floatSecondName">
                                <h1><i><?php echo $result['lastname']; ?></i></h1>
                            </div>
                        </div>
                        <div class="col-6 distance" id="distance">
                            <h2><?php echo rand(1, 100); ?> Miles Away</h2>
                        </div>
                    </div>
                    <div class="row pushRow">
                        <div class="col-3"></div>
                        <div class="col-3 userFollowers">
                            <h3>Followers</h3>
                            <hr class="verticalRule">
                        </div>
                        
                        <div class="col-3 userLikes">
                            <h3>Likes</h3>
                            <hr class="verticalRule">
                        </div>
                        <div class="col-3 userFollowing">
                            <h3>Following</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3"></div>
                        <div class="col-3 userFollowersNo">
                            <h3><i><?php echo $details['followers']; ?></i></h3>
                        </div>
                        <div class="col-3 userLikesNo">
                            <h3><i><?php echo $details['likes']; ?></i></h3>
                        </div>
                        <div class="col-3 userFollowingNo">
                            <h3><i><?php echo $details['following']; ?></i></h3>
                        </div>
                    </div>
                    <div class="row pushRow">
                        <div class="col-1"></div>
                        <div class="col-11 about">
                            <h1><i>About</i></h1>
                        </div>
                    </div>
                    <div class="row pushRow2">
                        <div class="col-1">
                        </div>
                        <div class="col-10 aboutDesc">
                            <p>
                            <?php echo $details['about']; ?></
                            </p>
                        </div>
                        <div class="col-1"></div>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div id="note" class="main-div ">
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
    <?php endforeach; ?>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-1"></div>
            <div class="col-10">
                <div class="hobby">
                    <div class="row hobby-push">
                        <div class="col-12">
                            <h1>Hobbies</h1>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            <div class="hobbytext">
                                <h3><?php echo $details['hobby1']; ?></h3>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="hobbytext">
                                <h3><?php echo $details['hobby2']; ?></h3>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="hobbytext">
                                <h3><?php echo $details['hobby3']; ?></h3>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="hobbytext">
                                <h3><?php echo $details['hobby4']; ?></h3>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-1"></div>
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

    
        let collection = changeable.length;
        var i,x = 0;
        
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