<?php

require_once "databaseconn.php";

$temp = $_SERVER['QUERY_STRING'];
    $checker = '';
    $username = $_POST['username'];
    $firstname = ucfirst($_POST['firstname']);
    $lastname = ucfirst($_POST['lastname']);
    $age = $_POST['age'];
    $city = ucfirst($_POST['city']);
    $residentcountry = ucfirst(dotChecker($_POST['residentcountry']));

    // echo '<pre>';
    // echo print_r($_POST).'<br>';
    // echo '</pre>';
    // exit; 

    function dotChecker($check) {
        $ans = strpos($check, ".");
        if($ans == false){
            return $check;
        } else {
            return $checker = strtoupper($check);
        }
    }

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $checkAccounts = $pdo->prepare("SELECT * FROM users");
    $checkAccounts->execute();
    $confirmResults = $checkAccounts->fetchAll(PDO::FETCH_ASSOC);
    $usernameCount = 0;

    $deleteImage = $pdo->prepare("SELECT * FROM users where username = :username");
    $deleteImage->bindValue(':username', $username);
    $deleteImage->execute();
    $imgResults = $deleteImage->fetchAll(PDO::FETCH_ASSOC);

    foreach($confirmResults as $n => $confirmResult ) {
        if ($confirmResult['username'] === $_POST['username'] && $_POST['username'] != $temp){
            $usernameCount = 1;
        }
    }
    

    if(!is_dir('dbimages')) {
        mkdir('dbimages');
    }

    

        $image = $_FILES['image'] ?? null;
        $imagePath = '';
        if(!empty($image['name'])){

            foreach($imgResults as $n => $imgResult ) {
                unlink($imgResult['image']);
                rmdir(dirname($imgResult['image']));
            }

            $imagePath = 'dbimages/'.randomString(8).'/'.$image['name'];
            mkdir(dirname($imagePath));

            move_uploaded_file($image['tmp_name'], $imagePath);

            if($usernameCount == 0) {
                $selectAccount = $pdo->prepare("UPDATE users SET firstname = :firstname, lastname = :lastname,
                                             username = :username, age = :age, city = :city, 
                                             residentcountry = :residentcountry, image = :image
                                             WHERE username = '$temp'");
                $selectAccount->bindValue(':firstname', $firstname);
                $selectAccount->bindValue(':lastname', $lastname);
                $selectAccount->bindValue(':username', $username);
                $selectAccount->bindValue(':age', $age);
                $selectAccount->bindValue(':city', $city);
                $selectAccount->bindValue(':residentcountry', $residentcountry);
                $selectAccount->bindValue(':image', $imagePath);
                $selectAccount->execute();
                header("Location: Settings.php?username=$username"); //new username
            }  elseif ($usernameCount == 1) {
                $errors[] = 'Username already exists please choose another.';
                header("Location: Settings.php?username=$temp"); //use previous username
            }
        } else {

            $checkAccounts = $pdo->prepare("SELECT * FROM users");
    $checkAccounts->execute();
    $confirmResults = $checkAccounts->fetchAll(PDO::FETCH_ASSOC);
    $usernameCount = 0;

    foreach($confirmResults as $n => $confirmResult ) {
        if ($confirmResult['username'] === $_POST['username'] && $_POST['username'] != $temp){
            $usernameCount = 1;
        }
    }


            if($usernameCount == 0) {
                $selectAccount = $pdo->prepare("UPDATE users SET firstname = :firstname, lastname = :lastname,
                                             username = :username, age = :age, city = :city,
                                             residentcountry = :residentcountry
                                             WHERE username = '$temp'");
                $selectAccount->bindValue(':firstname', $firstname);
                $selectAccount->bindValue(':lastname', $lastname);
                $selectAccount->bindValue(':username', $username);
                $selectAccount->bindValue(':age', $age);
                $selectAccount->bindValue(':city', $city);
                $selectAccount->bindValue(':residentcountry', $residentcountry);
                $selectAccount->execute();
                header("Location: Settings.php?username=$username"); //new username
            }  elseif ($usernameCount == 1) {
                $errors[] = 'Username already exists please choose another.';
                header("Location: Settings.php?username=$temp"); //use previous username
            }
        } 
}

function randomString($n) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $str = '';
    for($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $str .= $characters[$index];
    }
    return $str;
}

?>