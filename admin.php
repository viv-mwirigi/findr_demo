<?php 

$pdo = new PDO('mysql:host=localhost;port=3306;dbname=findr', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$statement  =$pdo->prepare('SELECT * FROM users');
$statement->execute();
$users = $statement->fetchAll(PDO::FETCH_ASSOC);


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
    <title>Findr Admin Panel</title>
</head>
<body>
    <div class="container">
        <ul>
            <a href="admincreate.php"><li>Create Account</li></a>
            <a href="adminupdate.php"><li>Update Account</li></a>
        </ul>
    </div>
    <div class="container">
        <div class="push-in">
        <h1>Admin Panel</h1>
        <p>
            <a href="admincreate.php" class="btn btn-success">Create Account</a>
            <a href="report.php" class="btn btn-success">Generate CSV users Report</a>
            <a href="detailsreport.php" class="btn btn-success">Generate CSV details Report</a>
            <a href="chatsreport.php" class="btn btn-success">Generate CSV chats Report</a>
        </p>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                
                <th scope="col">Image</th>
                
                <th scope="col">Username</th>
                <th scope="col">First name</th>
                <th scope="col">Last name</th>
                <th scope="col">Email</th>
                <th scope="col">Age</th>
                <th scope="col">Country</th>
                <th scope="col">City</th>
                <th scope="col">Action</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($users as $i => $user): ?>
                <tr>
                <th scope="row"><?php echo $i + 1 ?></th>
                <td>
                    <img src="<?php echo $user['image'] ?>" width="60px" height="100px" id="storedImage">
                </td>
                <td><?php echo $user['username'] ?></td>
                <td><?php echo $user['firstname'] ?></td>
                <td><?php echo $user['lastname'] ?></td>
                <td><?php echo $user['email'] ?></td>
                <td><?php echo $user['age'] ?></td>
                <td><?php echo $user['residentcountry'] ?></td>
                <td><?php echo $user['city'] ?></td>
                <td>

                    <a href="adminupdate.php?username=<?php echo $user['username'] ?>" class="btn btn-outline-primary">Edit</a>
                    <form method="post" action="admindelete.php" style="display: inline-block">
                        <input type="hidden" name="username" value="<?php echo $user['username'] ?>">
                        <button type="submit" class="btn btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
           <?php endforeach; ?>
        </tbody>  
    </table>
        </div>
   
    </div>

    <script src="/findr_demo/bootstrap-5.0.2-dist/js/bootstrap.min.js"></script>
</body>
</html>