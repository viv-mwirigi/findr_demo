<?php

require_once "databaseconn.php";

if($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username= $_POST['username'];
    $passwords= $_POST['passwords'];


    $checkAccounts = $pdo->prepare("SELECT * FROM users where username = :username");
    $checkAccounts->bindValue(':username', $username);
    $checkAccounts->execute();
    $results = $checkAccounts->fetchAll();
    $usernameCount = 0;
    $passwordsCount = 0;

    // echo '<pre>';
    // echo print_r($results);
    // echo '</pre>';

    foreach($results as $i => $result){
        if($result['username'] === $username && $result['passwords'] === $passwords) {
            $usernameCount = 1;
            $passwordsCount = 1;
        } elseif ($result['username'] === $username && $result['passwords'] != $passwords) {
          $usernameCount = 1;
          $passwordsCount = 0;
        } elseif($result['username'] != $username && $result['passwords'] != $passwords) {
            $usernameCount = 0;
            $passwordsCount = 0;
        }
    }
    
    if($usernameCount == 0) {
        $errors[] = 'Username doesn\'t exist.';
    } 

    if($passwordsCount == 0) {
        $errors[] = 'Incorrect password.';
    }

   
   
    if($usernameCount === 1 && $passwordsCount === 1)
    {
        header("Location: settings.php?username=$username");
    }

    
}




?>

<!DOCTYPE html>
<html>
  <head>
    <title>Login</title>
    <link rel="stylesheet" href="/findr_demo/bootstrap-5.0.2-dist/css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="/findr_demo/bootstrap-5.0.2-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/findr_demo/login.css">
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

  <?php if($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
  <?php if($errors ): ?>
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
    <?php endif; ?>

  <div class="container">
  <form method="post" action="">
    <div class="input-data">
    <input type="text" name="username" required>
    <div class="underline"></div>
    <label> Username</label>

    </div>
    <br>
    <div class="input-data">
    <input type="password" name="passwords" required>
    <div class="underline"></div>
    <label> Password</label>

    </div>
    </br>
    <button class="btn btn-primary login-styling" type="submit">Login</button><br><br>
  
  </form>


  <div class="remember">
  <div class="signuplink">
    Not a member? <a href="/findr_demo/Signup.php">Sign-up</a>
  </div>
  

  </div>
  </body>
</html>