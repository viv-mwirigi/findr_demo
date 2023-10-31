<?php

require_once "databaseconn.php";



if($_SERVER['REQUEST_METHOD'] === 'GET' || $_SERVER['REQUEST_METHOD'] === 'POST' ) {
    // echo '<pre>';
    // echo print_r($_SERVER);
    // echo '</pre>';
    // exit;

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


    $selectAccount = $pdo->prepare("SELECT * FROM users where username = :username");
    $selectAccount->bindValue(':username', $username);
    $selectAccount->execute();
    $results = $selectAccount->fetchAll(PDO::FETCH_ASSOC);
    $usernameCount = 0;
    $passwordsCount = 0;

    // echo '<pre>';
    // echo print_r($_FILES).'<br>';
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
    <link rel="stylesheet" href="/findr_demo/settings.css">
    <title>Settings</title>
</head>
<body>
    <div class="container">
        <?php foreach($results as $i => $result): ?>
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
        <?php endforeach; ?>
    </div>
                                        <?php if(!empty($errors)): ?>
                                            <div class="row">
                                                <div class="col-4"></div>
                                                <div class="col-4">
                                                    <div class="alert alert-danger">
                                                        <?php foreach($errors as $error): ?>
                                                            <div><?php echo $error; ?></div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                                <div class="col-4"></div>
                                            </div>  
                                        <?php endif; ?>
                                    

<!-- Account info, Security, Blocked Accounts, My Profile, Notifications, Deactivate Account -->
    <div class="container">
        <?php foreach($results as $i => $result): ?>
        <div class="row main-div">
            <div class="col-3 right-column">
                <div class="list-selection">
                    <ul>
                        <div id="profile-box" class="profile-box-shaper">
                            <a href="" id="profile"><li>My Profile</li></a>
                        </div>
                        <div class="line-separator-1"></div>
                        
                        <div id="account-box" class="">
                            <a href="" id="account"><li>Account Info</li></a>
                        </div>
                        <div class="line-separator-2"></div>

                        <div id="notification-box" class="">
                            <a href="" id="notification"><li>Notifications</li></a>
                        </div>
                        <div class="line-separator-3"></div>

                        <div id="security-box" class="">
                            <a href="" id="security"><li>Security</li></a>
                        </div>
                        <div class="line-separator-4"></div>

                        <!-- <div id="blocked-box" class="">
                            <a href="" id="blocked"><li>Blocked Accounts</li></a>
                        </div>
                        <div class="line-separator-5"></div> -->
                        <div id="deactivate-box" class="">
                            <a href="" id="deactivate"><li>Deactivate Account</li></a>
                        </div>
                    </ul>
                </div>

                <div class="row padding-insurer2"></div>
            </div>
            <div class="col-9">
                <!--Profile section-->
                <div id="profile-bar" class="row profile-bar" style="display: flex;">
                    <div class="row">
                        <div class="col-4">
                            <div class="profile-img">
                                <img src="<?php 
                                if($result['image'] != null) {
                                    echo $result['image'];
                                } else {
                                    echo '/findr_demo/images/nullbg.png';
                                }
                                ?>" style="background-repeat: no-repeat; position: relative; border-radius: 20%; margin: 6px; background-size: cover; width: 356.33px; height: 356.33px;">
                            </div>
                        </div>
                        <div class="col-8 details">
                            <div class="row">
                                <div class="col-6">
                                    <div>
                                        <label>First name: </label>
                                        <h3><?php echo $result['firstname']; ?></h3>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div>
                                        <label>Last name: </label>
                                        <h3><?php echo $result['lastname']; ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div>
                                        <div>
                                            <label>Username:</label>
                                            <h3>@<?php echo $result['username']; ?></h3>
                                        </div>
                                    </div>
                                    <div>
                                        <label>Age: </label>
                                        <h3><?php echo $result['age']; ?> Years old</h3>
                                    </div>
                                    <div>
                                        <label>City: </label>
                                        <h3><?php echo $result['city']; ?></h3>
                                    </div>
                                    <div>
                                        <label>Country: </label>
                                        <h3><?php echo $result['residentcountry']; ?></h3>
                                    </div>
                                </div> 
                            </div>      
                        </div>
                    </div>
                    
                    
                </div>
                

                <!--Account section-->
                <div id="account-bar" class="row account-bar" style="display: none;">
                    <div class="col-12 details">
                        <div class="row">
                            <div class="row">
                                <div class="account-title">
                                    <h2>Account information</h2>
                                </div>
                            </div>
                            <form method="POST" action="editinfo.php?<?php echo $result['username']; ?>" enctype="multipart/form-data">
                                <div class="row">
                                <div class="form-group">
                                    <label>Change profile image</label>
                                    <input type="file" class="form-control" name="image">
                                </div>
                                <div class="col-6">
                                    <div>
                                        <p>Edit your First name:</p>
                                        <input type="text" placeholder="Enter First name" name="firstname" value="<?php echo $result['firstname']; ?>">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div>
                                        <p>Edit your Last name:</p>
                                        <input type="text" placeholder="Enter Last name" name="lastname" value="<?php echo $result['lastname']; ?>">
                                    </div>
                                </div>
                                </div>
                                <div class="row">
                                <div class="col-6">
                                    <div>
                                        <p>Edit your Username:</p>
                                        <input type="text" placeholder="Enter new Username" name="username" value="<?php echo $result['username']; ?>">
                                    </div>
                                   
                                </div>
                                <div class="col-6">
                                    <div>
                                        <p>Edit your age:</p>
                                        <input type="text" placeholder="Enter current age" name="age" value="<?php echo $result['age']; ?>">
                                    </div>
                                </div>
                                </div>
                                <div class="col-6">
                                <div>
                                    <p>Edit your city:</p>
                                    <input type="text" placeholder="Enter city of residence" name="city" value="<?php echo $result['city']; ?>">
                                </div>
                                </div>
                                <div class="col-6">
                                <div>
                                    <p>Edit your country:</p>
                                    <input type="text" placeholder="Enter country of residence" name="residentcountry" value="<?php echo $result['residentcountry']; ?>">
                                </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 notification-buttons">
                                        <a href="Settings.php?username=<?php echo $result['username']; ?>" class="btn btn-danger">Cancel</a>
                                        <button type="submit" class="btn btn-primary">Save Changes</button>       
                                </div>
                                </div>
                            </form>
                            
                        </div>
                    </div>
                </div>

                 <!--Notification section-->
                <div id="notification-bar" class="row notification-bar" style="display: none;">
                    <div class="col-12 details">
                        <div class="row">
                            <div class="col-12">
                                <div class="notification-title">
                                    <h2>Notifications</h2>
                                </div>
                            </div>
                        </div>
                        <form method="post" action="notifications.php?<?php echo $result['username']; ?>" enctype="application/x-www-form-urlencoded">
                        <div class="row">
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-sm-1">
                                        <input type="checkbox" name="followernotif" 
                                        <?php 
                                            if($result['followernotificationsenabled'] === 'on') {
                                                echo 'checked="true"';
                                            } else {
                                                echo ' ';
                                            }
                                        ?>>
                                    </div>
                                    <div class="col-sm-11">
                                        <p>Receive notifications for new followers?</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-sm-1">
                                        <input type="checkbox" name="likesnotif" <?php 
                                            if($result['likesnotificationsenabled'] === 'on') {
                                                echo 'checked="true"';
                                            } else {
                                                echo ' ';
                                            }
                                        ?>>
                                    </div>
                                    <div class="col-sm-11">
                                        <p>Receive notifications for likes?</p>
                                    </div>
                                </div>
                            </div> 
                        </div>
                        <div class="row">
                                <div class="col-12 notification-buttons">
                                    <button type="submit" class="btn btn-danger">Update</button>
                                </div>
                                </div>
                    </div>  
                        </form>
                        
                </div>

                <!--Security section-->
                <div id="security-bar" class="row security-bar" style="display: none;">
                    <div class="col-12 details">
                        <div class="row">
                            <div class="col-12">
                                <div class="security-title">
                                    <h2>Security</h2>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <form method="post" action="security.php?<?php echo $result['username']; ?>">
                            <div class="col-6">
                                <div>
                                    <p>Change your password:</p>
                                    <input type="password" placeholder="Enter new password" value="<?php echo $result['passwords']; ?>" name="passwords">
                                </div>
                            </div>
                            <div class="col-6">
                                <div>
                                    <p>Confirm your password:</p>
                                    <input type="password" placeholder="Reenter new password" value="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 notification-buttons">
                                <a href="Settings.php?username=<?php echo $result['username']; ?>" class="btn btn-danger">Cancel</a>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </div>
                            </form>
                            
                    </div>
                </div>

                <!--Deactivate section-->
                <div id="deactivate-bar" class="row deactivate-bar" style="display: none;">
                    <div class="col-12 details">
                        <div class="row">
                            <div class="col-12">
                                <div class="notification-title">
                                    <h2>Deactivate my account</h2>
                                </div>
                            </div>
                        </div>
                        <form method="post" action="delete.php?<?php echo $result['username']; ?>">
                        <div class="row">
                            <div>
                                <h5>We are sorry to see you leave :(</h5>
                            </div>
                        </div>
                        <div class="row">
                            
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-2">
                                        <input type="radio" name="delete" value="deleteall" required>
                                    </div>
                                    <div class="col-10">
                                        <p>Delete all of my information. It was nice knowing you ;)</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 notification-buttons">
                                <a href="Settings.php?username=<?php echo $result['username']; ?>" class="btn btn-danger">Cancel</a>
                                <button type="submit" class="btn btn-primary">Delete my account</button>
                            </div>
                        </div>
                        </form>
                        
                        
                        
                    </div>  
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>


    <script>
        let profile = document.getElementById("profile");
        let account = document.getElementById("account");
        let notification = document.getElementById("notification");
        let security = document.getElementById("security");
        let deactivate = document.getElementById("deactivate");

        let profile_box = document.getElementById("profile-box");
        let account_box = document.getElementById("account-box");
        let notification_box = document.getElementById("notification-box");
        let security_box = document.getElementById("security-box");
        let deactivate_box = document.getElementById("deactivate-box");

        let profile_bar =  document.getElementById("profile-bar");
        let account_bar =  document.getElementById("account-bar");
        let notification_bar =  document.getElementById("notification-bar");
        let security_bar =  document.getElementById("security-bar");
        let deactivate_bar =  document.getElementById("deactivate-bar");


        profile.addEventListener("click", function (e) {
            account_box.className = "";
            notification_box.className = "";
            security_box.className = "";
            deactivate_box.className = "";

            account_bar.style.display = "none";
            notification_bar.style.display = "none";
            security_bar.style.display = "none";
            deactivate_bar.style.display = "none";
            profile_bar.style.display = "flex";
            

            profile_box.className = "profile-box-shaper";
            e.preventDefault();
        });

        account.addEventListener("click", function (e) {
            profile_box.className = "";
            notification_box.className = "";
            security_box.className = "";
            deactivate_box.className = "";

            profile_bar.style.display = "none";
            notification_bar.style.display = "none";
            security_bar.style.display = "none";
            deactivate_bar.style.display = "none";
            account_bar.style.display = "flex";
            
            account_box.className = "account-box-shaper";
            e.preventDefault();
        });

        notification.addEventListener("click", function (e) {
            account_box.className = "";
            profile_box.className = "";
            security_box.className = "";
            deactivate_box.className = "";

            profile_bar.style.display = "none";
            account_bar.style.display = "none";
            security_bar.style.display = "none";
            deactivate_bar.style.display = "none";
            notification_bar.style.display = "flex";

            notification_box.className = "notification-box-shaper";
            e.preventDefault();
        });

        security.addEventListener("click", function (e) {
            account_box.className = "";
            notification_box.className = "";
            profile_box.className = "";
            deactivate_box.className = "";

            profile_bar.style.display = "none";
            account_bar.style.display = "none";
            notification_bar.style.display = "none";
            deactivate_bar.style.display = "none";
            security_bar.style.display = "flex";
            
            security_box.className = "security-box-shaper";
            e.preventDefault();
        });

        

        deactivate.addEventListener("click", function (e) {
            account_box.className = "";
            notification_box.className = "";
            security_box.className = "";
            profile_box.className = "";

            profile_bar.style.display = "none";
            account_bar.style.display = "none";
            notification_bar.style.display = "none";
            security_bar.style.display = "none";
            deactivate_bar.style.display = "flex";
            
            deactivate_box.className = "deactivate-box-shaper";
            e.preventDefault();
        });

    </script>
</body>
</html>