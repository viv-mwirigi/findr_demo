<?php

require_once "databaseconn.php";

$username = $_GET['username'] ?? null; //recipient
$recipient = $username;
$mychatlocation = '';
$theirchatlocation = '';
$mydetails = $_GET['from'] ?? null; //sender
$sender = $mydetails;
$dbMatchFound = false;
$sendersChatFileContents = [];
$activeUsernames = [];
$activeUserDetails = [];
$activeUserDetailsChecker = false;
$stopProcessing = true;
$numberOfUserRecordsReturned = 0;
$numberOfActiveArraysReturned = 0;


//when username isn't provided
if($username == ''){
    echo '<h2>Username is not provided.</h2>';
    exit;
}

//create a query to store user images 
$selectUserDetails = $pdo->prepare("SELECT * FROM users WHERE username = :username");
$selectUserDetails->bindValue(":username", $username);
$selectUserDetails->execute();
$selectUserDetailResults = $selectUserDetails->fetch();


$checkForUsername = $selectUserDetailResults['username'] ?? null;
//   echo '<pre>';
//      echo print_r($selectUserDetailResults);
//      echo '</pre>';
//      exit;
//check if user exists using records gotten from $_GET['username']
if(strcasecmp($checkForUsername,$username) !== 0){
    //user doesn't exist
    echo '<h2>User: '.$username.' doesn\'t exist</h2>';
    exit;
}

//create a query to check if users exist in db
$checkDB = $pdo->prepare("SELECT * FROM chatsessions");
$checkDB->execute();
$checkDBResults = $checkDB->fetchAll(PDO::FETCH_ASSOC);

//loop through every entry looking for a match
foreach($checkDBResults as $db => $checkDBResult){
    if(strcasecmp($checkDBResult['sender'], $sender) == 0 && strcasecmp($checkDBResult['recipient'],$recipient) == 0){
        $dbMatchFound = true; //entry exists
        break;
    }
}

//since we're using details gathered from query parameters we need to ensure that
//we can handle three scenarios, i.e;
//      -the request comes from a url with 2 entries (username and from)
//      -the request comer from a url with 1 entry (username)
//      -the request comes from a url with no entry

//if request comes from a url with 1 entry(username)
/**
 * check if the user has any active chat sessions in the db (use sender)
 * if tthey do then
 *      -select their recipient and link their username to their profile
 *      -by default the first active session to be found should be displayed including their chats
 *      -if there are recipients then store their usernames in an array and iterate over it while
 *       making requests for their image
 * if they don't
 *      then display nothing
 */

 if(!stripos($_SERVER['REQUEST_URI'],"&from")){ //contains only one entry
    //check for active sessions
    //in this scenario the sender will be $recipient variable
    $checkDBActiveSessions = $pdo->prepare("SELECT * FROM chatsessions WHERE sender = :sender");
    $checkDBActiveSessions->bindValue(":sender", $recipient);
    $checkDBActiveSessions->execute();
    $checkDBActiveSession = $checkDBActiveSessions->fetchAll(PDO::FETCH_ASSOC);

    //we need to count all the arrays that have been returned
    $numberOfActiveArraysReturned = count($checkDBActiveSession);
    

    //store the results in a variable
    if($numberOfActiveArraysReturned == 0){
        //set a flag to stop select from occurring
        $stopProcessing = true;
    } else {
        $activeUsernames[] = $checkDBActiveSession;
        $stopProcessing = false;
    }
    //   echo '<pre>';
    //  echo print_r($activeUsernames);
    //  echo '</pre>';
    
 if(!$stopProcessing){$x = 0;
    while($x < $numberOfActiveArraysReturned){
        
      

        $selectActiveUserDetails = $pdo->prepare("SELECT username, image FROM users WHERE username = :username");
        $selectActiveUserDetails->bindValue(":username", $checkDBActiveSession[$x]['recipient']);//check for bugs
        $selectActiveUserDetails->execute();
        $selectActiveUserDetail = $selectActiveUserDetails->fetchAll(PDO::FETCH_ASSOC);

// echo '<pre>';
//      echo print_r($x);
//      echo '</pre>';

//    echo '<pre>';
//      echo print_r($checkDBActiveSession[$x]['recipient']);
//      echo '</pre>';

//           echo '<pre>';
//      echo print_r($selectActiveUserDetail);
//      echo '</pre>';

        //store the image and username associated with the image
        $activeUserDetails[$x] = $selectActiveUserDetail;
        $x++;
    }
    $activeUserDetailsChecker = true;
//        echo '<pre>';
//      echo print_r($activeUserDetails);
//      echo '</pre>';
//     exit;
}
 }

 if(stripos($_SERVER['REQUEST_URI'],"&from")){

    if($sender == ''){
        echo '<h2>The value for sender is not provided.</h2>';
        exit;
    }

    $selectUserDetails = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $selectUserDetails->bindValue(":username", $sender);
    $selectUserDetails->execute();
    $selectUserDetailResultsForFrom = $selectUserDetails->fetch();
    
    $checkForUsername = $selectUserDetailResultsForFrom['username'] ?? null;
    //   echo '<pre>';
    //      echo print_r($selectUserDetailResults);
    //      echo '</pre>';
    //      exit;
    //check if user exists using records gotten from $_GET['username']
    if(strcasecmp($checkForUsername,$sender) !== 0){
        //user doesn't exist
        echo '<h2>User: '.$sender.' doesn\'t exist</h2>';
        exit;
    }

if($dbMatchFound){
    //since entry exists then select the location stored in the database
    //access the file and display the records

    //select the chat location and store it
    $selectSenderAndRecipientDetails = $pdo->prepare("SELECT chatlocation FROM chatsessions WHERE sender = :sender and recipient = :recipient");
    $selectSenderAndRecipientDetails->bindValue(":sender", $sender);
    $selectSenderAndRecipientDetails->bindValue(":recipient", $recipient);
    $selectSenderAndRecipientDetails->execute();
    $sendersChatFileLocation = $selectSenderAndRecipientDetails->fetch();

    $checkDBActiveSessions = $pdo->prepare("SELECT * FROM chatsessions WHERE sender = :sender");
    $checkDBActiveSessions->bindValue(":sender", $sender);
    $checkDBActiveSessions->execute();
    $checkDBActiveSession = $checkDBActiveSessions->fetchAll(PDO::FETCH_ASSOC);

   
     //we need to count all the arrays that have been returned
     $numberOfActiveArraysReturned = count($checkDBActiveSession);

   

     //store the results in a variable
     if($numberOfActiveArraysReturned == 0){
         //set a flag to stop select from occurring
         $stopProcessing = true;
     } else {
         $activeUsernames[] = $checkDBActiveSession;
         $stopProcessing = false;
     }
    //  echo '<pre>';
    //  echo print_r($activeUsernames);
    //  echo '</pre>';
     if(!$stopProcessing){
        $x = 0;
        foreach($checkDBActiveSession as $checkActive => $ca){
    //create a query to store user images 
$selectChatUserDetails = $pdo->prepare("SELECT username, image FROM users WHERE username = :username");
$selectChatUserDetails->bindValue(":username", $ca['recipient']);//check for bugs
$selectChatUserDetails->execute();
$selectChatUserDetailResults = $selectChatUserDetails->fetchAll(PDO::FETCH_ASSOC);

//store the array of info
        //store the image and username associated with the image
        $activeUserDetails[$x] = $selectChatUserDetailResults;
        $x++;
    }
    //  echo '<pre>';
    // echo print_r($activeUserDetails);
    // echo '</pre>';
    $activeUserDetailsChecker = true;
    
}
    

    $sendersChatFileContents[] = file($sendersChatFileLocation['chatlocation']);
    //  echo '<pre>';
    // echo print_r($sendersChatFileContents);
    // echo '</pre>';
    // exit;
    //go and display the data in the array it should be simple
} else {
    //it means the match wasn't found and we should create the data in the DB

    //create the chat file
    $chatFile = fopen("chats.txt","w");

    //create the chats folder if it doesn't exist
    if(!is_dir('chats')){
        mkdir("chats");
    }

    //make the senders chat folder
    $sendersChatFile = 'chats/'.$sender.'/'.$recipient;
    if(!is_dir($sendersChatFile)){
        mkdir($sendersChatFile,0777,true);
    }
    //move the chat file into the senders folder
    copy("chats.txt", $sendersChatFile.'/chats.txt');

    //make the recipients chat folder if it doesn't exist
    $recipientsChatFile = 'chats/'.$recipient.'/'.$sender;
    if(!is_dir($recipientsChatFile)){
        mkdir($recipientsChatFile, 0777,true);
    }
    //move the chat file into the recipients folder
    copy("chats.txt",$recipientsChatFile.'/chats.txt');

    //finally insert the entries into the table

    //insert senders entry
    $insertSendersDetails = $pdo->prepare("INSERT INTO chatsessions VALUES(:sender, :recipient, :chatlocation)");
    $insertSendersDetails->bindValue(":sender", $sender);
    $insertSendersDetails->bindValue(":recipient", $recipient);
    $insertSendersDetails->bindValue(":chatlocation", $sendersChatFile.'/chats.txt');
    $insertSendersDetails->execute();

    //insert recipients entry
    $insertRecipientsDetails = $pdo->prepare("INSERT INTO chatsessions VALUES(:recipient, :sender, :chatlocation)");
    $insertRecipientsDetails->bindValue(":recipient", $recipient);
    $insertRecipientsDetails->bindValue(":sender", $sender);
    $insertRecipientsDetails->bindValue(":chatlocation", $recipientsChatFile.'/chats.txt');
    $insertRecipientsDetails->execute();
    header("Location: chat.php?username=$recipient&from=$sender"); //dirty workaround
}

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
    <link rel="stylesheet" href="/findr_demo/chat.css">
    <title>Inbox</title>
</head>
<body>
   <div class="container">
        <div class="row main-bar">
            <div class="col-2">
                <a href="/findr_demo/Dashboard.php?username=<?php if(!stripos($_SERVER['REQUEST_URI'],"&from")) { echo $recipient;} 
                else{echo $sender; }?>">
                    <div class="back-left">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                          </svg>
                    </div>
                </a>
            </div>
            <div class="col-10 site-title">
                <div>
                    <h1>FindrChat</h1>
                </div>
            </div>
        </div>

        <div class="row context-bar">
            <div class="col-3 context-bar-deviation">
                <div class="row">
                    <div class="col-12">
                        <div class="message-title">
                            <h1>Messages</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- you might have to change this whole area when someone clicks a user -->
            <!-- where username is only entry -->
            <?php if(!stripos($_SERVER['REQUEST_URI'],"&from")): ?>
        <div class="col-1">
            <?php if(!stripos($_SERVER['REQUEST_URI'],"&from")): ?>
                <img src="<?php if(!stripos($_SERVER['REQUEST_URI'],"&from")) { echo null;} 
                else{echo $selectUserDetailResults['image']; }?>" style="background-size: cover;background-repeat: no-repeat;background-color: grey;width: 60px;height: 60px;border-radius: 50%;margin: 1.5px;">
            </div>
            <div class="col-8">
                <a href="/findr_demo/userProfile.php?username=<?php if(!stripos($_SERVER['REQUEST_URI'],"&from")) { echo $recipient;} 
                        else{echo $selectUserDetailResults['username']; }?>&from=<?php echo $recipient; ?>">
                    <div class="active-chat-username">
                        <div>
                            <h3><?php if(!stripos($_SERVER['REQUEST_URI'],"&from")) { echo ucfirst($recipient)."'s Inbox";} 
                            else{echo $selectUserDetailResults['username']; }?></h3>
                        </div>
                    </div>
                    <div class="active-chat-status">
                        <div>
                            <p style="<?php if(!stripos($_SERVER['REQUEST_URI'],"&from")){ echo "Color:black;";}?>"><?php if(!stripos($_SERVER['REQUEST_URI'],"&from")){ echo "Select a chat to display";} ?></p>
                        </div>
                    </div>
                </a>
                
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
<!-- context area -->
        <!-- Where username and from are provided 2 entries -->
        <?php if(stripos($_SERVER['REQUEST_URI'],"&from")): ?>
        <div class="col-1">
            <?php if(stripos($_SERVER['REQUEST_URI'],"&from")): ?>
                <img src="<?php if(stripos($_SERVER['REQUEST_URI'],"&from")) { echo $selectUserDetailResults['image'];} 
                else{echo $null; }?>" style="background-size: cover;background-repeat: no-repeat;background-color: grey;width: 60px;height: 60px;border-radius: 50%;margin: 1.5px;">
            </div>
            <div class="col-8">
                <a href="/findr_demo/userProfile.php?username=<?php if(stripos($_SERVER['REQUEST_URI'],"&from")) { echo $selectUserDetailResults['username'];} 
                        else{echo "User Not Found"; }?>&from=<?php echo $sender; ?>">
                    <div class="active-chat-username">
                        <div>
                            <h3><?php if(stripos($_SERVER['REQUEST_URI'],"&from")) { echo $selectUserDetailResults['username'];} 
                            else{echo "No such user"; }?></h3>
                        </div>
                    </div>
                    <div class="active-chat-status">
                        <div>
                            <p>Active</p>
                        </div>
                    </div>
                </a>
                
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <div class="row main-div">
            <div class="col-3 inbox-column">
                <!-- create one for a scenario where the username is only entry provided -->
                <?php if(!stripos($_SERVER['REQUEST_URI'],"&from")): ?>
                    <?php if($activeUserDetailsChecker): ?>
                        <?php foreach($activeUserDetails as $activeUserDetail => $aud): ?>
                <div class="row spaced-out">
                    <div class="col-3">
                        <img src="<?php echo $aud[0]['image']; ?>" style=" background-color: grey;background-size: contain;background-repeat: no-repeat;width: 60px;height: 60px;border-radius: 50%;margin: 2px;" class="inbox-profile-picture">
                    </div>
                    <div class="col-9">
                        <a href="/findr_demo/accessSelectedChat.php?username=<?php echo $recipient; ?>&access=<?php echo $aud[0]['username']; ?>">
                            <div class="inbox-chat-username">
                                <div>
                                    <h5><?php echo $aud[0]['username']; ?></h5>
                                </div>
                            </div>
                            <div class="inbox-chat-status">
                                <div>
                                    <p>Active</p>
                                </div>
                            </div>
                        </a> 
                    </div>
                </div>
                <?php endforeach;?>
                <?php endif;?>
                <?php endif;?>




                <!-- another for username and from entry 2 entries-->
                <?php if(stripos($_SERVER['REQUEST_URI'],"&from")): ?>
                    <?php if($activeUserDetailsChecker): ?>
                        <?php foreach($activeUserDetails as $activeUserDetail => $aud): ?>
                <div class="row spaced-out">
                    <div class="col-3">
                        <img src="<?php echo $aud[0]['image']; ?>" style=" background-color: grey;background-size: contain;background-repeat: no-repeat;width: 60px;height: 60px;border-radius: 50%;margin: 2px;" class="inbox-profile-picture">
                    </div>
                    <div class="col-9">
                        <a href="/findr_demo/accessSelectedChat.php?username=<?php echo $sender; ?>&access=<?php echo $aud[0]['username']; ?>">
                            <div class="inbox-chat-username">
                                <div>
                                    <h5><?php echo $aud[0]['username']; ?></h5>
                                </div>
                            </div>
                            <div class="inbox-chat-status">
                                <div>
                                    <p>Active</p>
                                </div>
                            </div>
                        </a> 
                    </div>
                </div>
                <?php endforeach;?>
                <?php endif;?>
                <?php endif;?>


                <!-- for when user has no active chats and username is provided -->
                <?php if($numberOfActiveArraysReturned == 0): ?>
                    <?php if(!stripos($_SERVER['REQUEST_URI'],"&from")): ?>
                <div class="row spaced-out">
                    <div class="col-12">
                            <div class="inbox-chat-username" style="margin:15px;text-align:justify;">
                                <div>
                                    <h5>It seems you have no chats. Go back to <a href="/findr_demo/Dashboard.php?username=<?php echo $recipient; ?>" style="color:cyan;">Dashboard</a> and start a chat with someone.</h5>
                                </div>
                            </div> 
                    </div>
                </div>
                <?php endif; ?>
                <?php endif; ?>

                <!-- User has no active chats and 2 entries are provided  -->
                <?php if($numberOfActiveArraysReturned == 0): ?>
                    <?php if(stripos($_SERVER['REQUEST_URI'],"&from")): ?>
                <div class="row spaced-out">
                    <div class="col-12">
                            <div class="inbox-chat-username">
                                <div>
                                    <h6>It seems you have no chats. Go back to <a href="/findr_demo/Dashboard.php?username=<?php echo $sender; ?>">Dashboard</a> and start a chat with someone.</h6>
                                </div>
                            </div> 
                    </div>
                </div>
                <?php endif; ?>
                <?php endif; ?>

               

                <div class="row spaced-out">
                    <div class="col-12">
                        <div class="encryption-text">
                            <div>
                                <p style="color: black">We use secure <small style="color:#51ed2a;">end-to-end</small> encryption.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row padding-insurer">
                    <div class="col-12">
                        <div class="inbox-chat-username">
                            <div>
                                <h3></h3>
                            </div>
                        </div>
                        <div class="inbox-chat-status">
                        </div>
                    </div>
                </div>

                
            </div>
            <div class="col-9 chat-column">
                <div class="text-box" id="fun-slide">
                    <div class="row">
                        <div class="col-2 emojis">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-emoji-laughing" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                <path d="M12.331 9.5a1 1 0 0 1 0 1A4.998 4.998 0 0 1 8 13a4.998 4.998 0 0 1-4.33-2.5A1 1 0 0 1 4.535 9h6.93a1 1 0 0 1 .866.5zM7 6.5c0 .828-.448 0-1 0s-1 .828-1 0S5.448 5 6 5s1 .672 1 1.5zm4 0c0 .828-.448 0-1 0s-1 .828-1 0S9.448 5 10 5s1 .672 1 1.5z"/>
                              </svg>
                        </div>
                        <div class="col-10 text-holder"></div>
                            <form action="updatechats.php?sender=<?php echo $sender; ?>&recipient=<?php echo $recipient; ?>" method="post">
                            <div class="row">
                                <div class="col-3">
                                <textarea type="text" placeholder="Type your message" name="sendtext" class="text-box-holder"></textarea>
                                </div>
                                <div class="col-9">
                                <button type="submit" class="btn btn-primary send-button">Send</button>
                                </div>
                            </div> 
                        </form> 
                        
                    </div>
                </div>
                <div class="row info-chat-bar">
                    <div class="col-12">
                        <div class="info-text">
                            <h6>Your communication is securely encrypted.</h6>
                            <p>Click <a href="#" style="text-decoration: none; color: blue; padding: 1px;">here</a> to learn more.</p>
                        </div>
                    </div>
                </div>

<!-- username is only entry provided -->
<?php if(!stripos($_SERVER['REQUEST_URI'],"&from")): ?>
               
<?php endif; ?>

<?php if(stripos($_SERVER['REQUEST_URI'],"&from")): ?>
    <?php $fileIndex = 0; ?>
    <?php foreach($sendersChatFileContents as $d => $scfc): ?>
            <?php $countTexts =count($scfc); ?>
            <?php while($fileIndex < $countTexts): ?>
                <div class="row">
                    <div class="col-12">
                        <div class="<?php if(stripos($sendersChatFileContents[0][$fileIndex],"Me:") !== false){ echo "message-mine";}else{echo "message-theirs";} ?>">
                            <p><?php echo $sendersChatFileContents[0][$fileIndex]; $fileIndex++;?></p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
    <?php endforeach; ?>
<?php endif; ?>

                
                <div class="row padding-insurer2"></div>
            </div>
        </div>
   </div>
</body>
</html>