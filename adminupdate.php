<?php

$pdo = new PDO("mysql:host=localhost;port=3306;dbname=findr", 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$user = '';
$errors = [];

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$age = $_POST['age'];
$city = $_POST['city'];
$residentcountry = $_POST['residentcountry'];
$passwords = $_POST['passwords'];
$email = $_POST['email'];


        if(!is_dir("images")) {
            mkdir('images');
        }

        if(empty($errors)) {

       
        $image = $_FILES['image'] ?? null;
        $imagePath = '';
        if(!empty($image['name'])) {
            $imagePath = 'dbimages/'.randomString(8).'/'.$image['name'];

            mkdir(dirname($imagePath));
            move_uploaded_file($image['tmp_name'], $imagePath);
        }





        $updateStatement = $pdo->prepare("UPDATE users SET username = :username, image = :image, 
        passwords = :passwords, age = :age, firstname = :firstname, lastname = :lastname, email = :email
                                            , city = :city, residentcountry = :residentcountry  WHERE username = :username");
           $updateStatement->bindValue(':username', $username);
           $updateStatement->bindValue(':image', $imagePath);
           $updateStatement->bindValue(':passwords', $passwords);
           $updateStatement->bindValue(':age', $age);
           $updateStatement->bindValue(':firstname', $firstname);
           $updateStatement->bindValue(':lastname', $lastname);
           $updateStatement->bindValue(':email', $email);
           $updateStatement->bindValue(':city', $city);
           $updateStatement->bindValue(':residentcountry', $residentcountry);
        $updateStatement->execute();
        $user = $updateStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    $pullStatement = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $pullStatement->bindValue(':username', $username);
    $pullStatement->execute();
    $user = $pullStatement->fetch(PDO::FETCH_ASSOC);

//    echo '<pre>';
//     echo print_r($user);
//     echo '</pre>';
//     exit; 

}

function randomString($n) {

    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $str = '';
    for($i =0; $i < $n; $i++) {
        $index = rand(0, strlen($characters)-1);
        $str .= $characters[$index];
    }

    return $str;

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
    <link rel="stylesheet" href="admin.css">
    <title>Findr Admin Update</title>
</head>
<body>
<div class="container">
        <ul>
            <a href="admin.php"><li>Admin Panel</li></a>
            <a href="admincreate.php"><li>Create Account</li></a>
        </ul>
    </div>
    <div class="container">
        <div class="push-in">
 
        <h1>
            Update Account: 
        </h1>
        <?php if($errors): ?>
        <div class="alert alert-danger">
            <?php foreach($errors as $error): ?>
                <div><?php echo $error; ?></div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <p>
            <a href="admincreate.php" class="btn btn-success">Create Account</a>
            <a href="admin.php" class="btn btn-info">View Accounts</a>
        </p>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Profile image</label>
                <input type="file" class="form-control" name="image">
            </div>
            <div class="form-group">
                <label>Username</label>
                <input type="text" class="form-control" name="username" value="">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input class="form-control" type="password" name="passwords" value="">
            </div>
            <div class="age">
                <label>Age</label>
                <input class="form-control" type="text"  name="age" value="">
            </div>
          <div class="form-group">
                <label>First name</label>
                <input class="form-control" name="firstname" type="text" value=""></input>
            </div>
            <div class="form-group">
                <label>Last name</label>
                <input class="form-control" type="text" name="lastname" value="">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input class="form-control" type="email" name="email" value="">
            </div>
            <div class="form-group">
                <label>City</label>
                <input class="form-control" type="text"  name="city" value="">
            </div>
            <div class="form-group">
                <label>Country</label>
                <input class="form-control" type="text"  name="residentcountry" value="">
            </div>
            <br>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        </div>
    </div>


    
   

    <script src="/findr_demo/bootstrap-5.0.2-dist/js/bootstrap.min.js"></script>
</body>
</html>