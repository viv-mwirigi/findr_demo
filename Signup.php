<?php

require_once "databaseconn.php";

if($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username= $_POST['username'];
    $email= $_POST['email'];
    $passwords= $_POST['passwords'];
    $age= $_POST['age'];
    $firstname= ucfirst($_POST['firstname']);
    $lastname= ucfirst($_POST['lastname']);
    $residentcountry= $_POST['residentcountry'];
    $city= $_POST['city'];

    

    $statement = $pdo->prepare("INSERT INTO users(username, email, passwords, age, firstname, lastname, residentcountry, city) 
                            VALUES(:username, :email, :passwords, :age, :firstname, :lastname, :residentcountry, :city)");
    $statement->bindValue(':username', $username);
    $statement->bindValue(':email', $email);
    $statement->bindValue(':passwords', $passwords);
    $statement->bindValue(':age', $age);
    $statement->bindValue(':firstname', $firstname);
    $statement->bindValue(':lastname', $lastname);
    $statement->bindValue(':residentcountry', $residentcountry);
    $statement->bindValue(':city', $city);

    $checkAccounts = $pdo->prepare("SELECT * FROM users");
    $checkAccounts->execute();
    $results = $checkAccounts->fetchAll();
    $usernameCount = 0;
    $emailCount = 0;

    // echo '<pre>';
    // echo print_r($results);
    // echo '</pre>';

    foreach($results as $i => $result){
        if($result['username'] === $username) {
            $usernameCount = 1;
            
        }

        if($result['email'] === $email) {
            $emailCount = 1;
            
        }
    }
    
    if($usernameCount === 1) {
        $errors[] = 'Username already exists.';
    } 

    if($emailCount === 1) {
        $errors[] = 'Email already exists';
    }

   
   
    if($usernameCount === 0 && $emailCount === 0)
    {
        $statement->execute();
        header("Location: intrests.php?$username");
    }

    
}




?>

<!DOCTYPE html>
<html>

<head>
    <title>
        Sign-up
    </title>
    <link rel="stylesheet" href="/findr_demo/bootstrap-5.0.2-dist/css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="/findr_demo/bootstrap-5.0.2-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/findr_demo/signup.css">
    
</head>

<body>
    <div class="logo"></div>
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

    <?php if($errors): ?>
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

    <div class="container">
        <form method="post" action="signup.php">
        <div class="input-data">
                    <input type="text" id="username" name="username" required>
                    <div class="underline"></div>
                    <label for="Username" >Enter your username</label>
                </div>
                </br>
                <div class="input-data">
                    <input type="text" id="email" name="email" required>
                    <div class="underline"></div>
                    <label for="email">Email</label>
                </div>
                </br>
                <div class="input-data">
                    <input type="password" id="password" name="passwords" required>
                    <div class="underline"></div>
                    <label for="password">Password</label>
                </div>
                </br>
                <div class="input-data">
                    <input type="password" id="cpassword" required>
                    <div class="underline"></div>
                    <label for="cpassword">Confirm your password</label>
                </div>
                </br>
                <div class="input-data">
                    <input type="text" id="age" name="age" required>
                    <div class="underline"></div>
                    <label for="age">Enter your age</label>
                </div>
                </br>
                <div class="input-data">
                    <input type="text" id="fname" name="firstname" required>
                    <div class="underline"></div>
                    <label for="First name">First name</label>
                </div>
                </br>
                <div class="input-data">
                    <input type="text" id="lname" name="lastname" required>
                    <div class="underline"></div>
                    <label for="Last name">Last name</label>
                </div>
                </br>
                <div class="input-data">
                    <input type="text" id="cresidence" name="residentcountry" required>
                    <div class="underline"></div>
                    <label for="Country of residence">Country of residence</label>
                </div>
                </br>
                <div class="input-data">
                    <input type="text" id="city" name="city" required>
                    <div class="underline"></div>
                    <label for="City">City</label>
                </div>
                <br><br>
                <button class="btn btn-primary login-styling" type="submit">Sign up</button>
        </form>
                
  <div class="signuplink">
    Already a member? <a href="/findr_demo/login.php">Log in</a>
  </div>
        
    </div>
</body>

</html>