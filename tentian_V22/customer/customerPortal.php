<?php
    // Start the session.
    session_start();
    if (!isset($_SESSION['user'])) {
        header('location: ../login.php');
        exit;
    }

    $user = $_SESSION['user'];


?>

<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../CSS/navstyle.css">
    <title>Customer Portal</title>
    <link rel="icon" href="../image/Icon.png" type="image/x-icon">
</head>
<body>
    <?php include('../customer/customerSidebar.php') ?>

    <div class="welcome-message">
        <h3>Welcome back, <?php echo $user['full_name']; ?>!</h3>
    </div>
    
  

    <script src="../customer/files/customerscript.js"></script>
</body>
</html>
