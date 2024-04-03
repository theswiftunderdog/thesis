<?php
session_start();

if(isset($_SESSION['user'])) {
    header('Location: admin/adminportal.php');
    exit();
}

$error_message = '';

if($_POST) {
    include('Connection/Connection.php');

    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if Admin
    $query = 'SELECT * FROM customer WHERE email=:email AND password=:password';
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    if($stmt->rowCount() > 0) {
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $user = $stmt->fetchAll()[0];
        $_SESSION['user'] = $user;
        
        header('Location: customer/customerportal.php');
        exit();
    }

    // Check if Super Admin
    $query = 'SELECT * FROM users WHERE email=:email AND password=:password';
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    if($stmt->rowCount() > 0) {
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $user = $stmt->fetchAll()[0];
        $_SESSION['user'] = $user;
        
        header('Location: admin/adminportal.php');
        exit();
    }




    $error_message = 'Please make sure that email and password are correct.';
}
?>

<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/login.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <title>Admin Login</title>
    <link rel="icon" href="../lance/image/Icon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css"/>
</head>
<body>

<body id="loginbody">
    <?php if(!empty($error_message)) { ?>
        <div id="errorMessage">
            <strong>ERROR:</strong> <p><?= $error_message ?></p>
        </div>
    <?php } ?>
<div class="container">
    <div class="loginheader">
         <img src="image/Untitled.png" width="451px" height="125px" alt="Tentian-Logo"><br>
         <p data-aos="fade-right" data-aos-duration="1000"> Admin Login Page</p>
    </div>

    <div class="loginbody" data-aos="fade-up" data-aos-duration="1000">
    <form action="login.php" method="POST">
        <div class="loginInputsContainer" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="400">
            <label for="">EMAIL</label>
            <input placeholder="-insert email address here-" name="email" type="text">
        </div>
        <div class="loginInputsContainer" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="300">
            <label for="">PASSWORD</label>
            <input placeholder="-insert password here-" name="password" type="password">
        </div>
        <div class="loginbutton" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
            <button>Login</button>
        </div>
        <div id="portalreturn" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="100">
            <a href="../tentian_v22/index.php">Return to home</a>
        </div>
    </form>
    </div>
</div>   

<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>

</body>
</html>