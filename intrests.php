<?php

require_once "databaseconn.php";


if($_SERVER['REQUEST_METHOD'] === 'POST' ) {
 
    $username = $_SERVER['QUERY_STRING'];
    $gender = $_POST['gender'];
    $preferredagerange = $_POST['agerange'];
    $hobby1 = $_POST['hobby1'];
    $hobby2 = $_POST['hobby2'];
    $hobby3 = $_POST['hobby3'];
    $hobby4 = $_POST['hobby4'];
    $about = $_POST['about'];
    $followers = 0;
    $following = 0;
    $likes = 0;

    // echo '<pre>';
    // echo print_r($_SERVER).'<br>';
    // echo '</pre>';
    // exit; 

    $selectAccount = $pdo->prepare("SELECT * FROM users where username = :username");
    $selectAccount->bindValue(':username', $username);
    $selectAccount->execute();
    $results = $selectAccount->fetch();


    $detailsAccount = $pdo->prepare("INSERT INTO details(userid, username, gender,
                                      preferredagerange, hobby1, hobby2, hobby3, hobby4, about, followers, following, likes)
    VALUES(:userid, :username, :gender, :preferredagerange, :hobby1, :hobby2, :hobby3, :hobby4, :about, :followers, :following, :likes)");
    $detailsAccount->bindValue(':userid', $results['userid']);
    $detailsAccount->bindValue(':username', $username);
    $detailsAccount->bindValue(':gender', $gender);
    $detailsAccount->bindValue(':preferredagerange', $preferredagerange);
    $detailsAccount->bindValue(':hobby1', $hobby1);
    $detailsAccount->bindValue(':hobby2', $hobby2);
    $detailsAccount->bindValue(':hobby3', $hobby3);
    $detailsAccount->bindValue(':hobby4', $hobby4);
    $detailsAccount->bindValue(':about', $about);
    $detailsAccount->bindValue(':followers', $followers);
    $detailsAccount->bindValue(':following', $following);
    $detailsAccount->bindValue(':likes', $likes);
    $detailsAccount->execute();
    header("Location: settings.php?username=$username");
    
    
}

?>

<!DOCTYPE html>
<html>
  <head>
<title>Intrests</title>
<link rel="stylesheet" href="/findr_demo/bootstrap-5.0.2-dist/css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="/findr_demo/bootstrap-5.0.2-dist/css/bootstrap.min.css">
<link rel="stylesheet" href="/findr_demo/intrests.css">
  </head>
  <body>
    <nav>
      
      <ul class="topnav">
          <li class="topnav-left"><a href="/findr_demo/index.php">Home</a></li>
          <li><a href="/findr_demo/login.php">Login</a></li>
        <li><a href="/findr_demo/Signup.php">Signup</a></li>
        <li><a href="/findr_demo/about.php">About</a></li>
        <li><a href="/findr_demo/support.php">Support</a></li>
        <li><a href="/findr_demo/FAQs.php">FAQs</a></li>
          
          
      </ul>
  </nav>
  <form method="post" action="" enctype="application/x-www-form-urlencoded">
  <div class="container">
    <h3>I am a?</h3><br>
    <div class="radio-tile-group">
      <div class="input-container">
        <select name="gender" required>
          <option value=""></option>
          <option value="male">Male</option>
          <option value="female">Female</option>
        </select>
        </div>

    </div>

  </div>

  <div class="container">
    <h3>Enter your age range</h3><br>
    <div class="radio-tile-group">
    <select name="agerange" required>
          <option value=""></option>
          <option value="18-25">18-25</option>
          <option value="26-35">26-35</option>
          <option value="36-50">36-50</option>
          <option value="51-60">51-60</option>
          <option value="61+">61+</option>
        </select>
    </div>

  </div>

 <div class="container">
        <div class="input-data">
                    
    <div class="underline"></div>
    <label for="1." >Enter your hobbies</label>
        </div>
        </br>
        <div class="input-data">
    <input type="text" id="hobbie1" name="hobby1" required>
    <div class="underline"></div>
    <label for="1." >1.</label>
        </div>
        </br>
        <div class="input-data">
    <input type="text" id="hobbie2" name="hobby2" required>
    <div class="underline"></div>
    <label for="2.">2.</label>
        </div>
        </br>
        <div class="input-data">
    <input type="text" id="hobbie3" name="hobby3" required>
    <div class="underline"></div>
    <label for="3.">3.</label>
        </div>
        </br>
        <div class="input-data">
    <input type="text" id="hobbie4" name="hobby4" required>
    <div class="underline"></div>
    <label for="4.">4.</label>
        </div>


</div>
<div class="container">
<div>
  <h2>Tell us a little more about you.</h2>
        <textarea placeholder="Tell us about yourself" name="about" class="about-textarea"></textarea>
        <button class="btn btn-primary login-styling" type="submit">Submit</button>
</div>
</div>

  </form>

  </body>
</html>