<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('location: ../login.php');
    exit; 
}

$user = $_SESSION['user'];

include('../Connection/Connection.php');

function generateNextSalesId($conn) {
    $maxSalesIdQuery = $conn->query("SELECT MAX(sales_id) AS max_sales_id FROM sales");
    $maxSalesIdResult = $maxSalesIdQuery->fetch(PDO::FETCH_ASSOC);
    $maxSalesId = $maxSalesIdResult['max_sales_id'];

    $currentNumber = intval(substr($maxSalesId, 6));

    $nextNumber = $currentNumber + 1;
    return 'Walkin' . $nextNumber;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $salesName = $_POST['salesName'];
    $quantity = $_POST['quantity'];
    $salesDate = date('Y-m-d'); 
    $status = 'Paid';


    $prices = [
        '500ml Water Bottle' => 10.00,
        'New Slim Gallon' => 150.00,
        'New Round Gallon' => 150.00,
        'Slim Gallon Refill' => 25.00,
        'Round Gallon Refill' => 25.00
    ];
    $price = $prices[$salesName] * $quantity;

    $nextSalesId = generateNextSalesId($conn);

    $stmt = $conn->prepare("INSERT INTO sales (sales_id, sales_name, quantity, price, sales_date, status) 
        VALUES (:sales_id, :sales_name, :quantity, :price, :sales_date, :status)");

    $stmt->bindParam(':sales_id', $nextSalesId);
    $stmt->bindParam(':sales_name', $salesName);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->bindParam(':price', $price); 
    $stmt->bindParam(':sales_date', $salesDate);
    $stmt->bindParam(':status', $status);

    if ($stmt->execute()) {
        header('location: admin_totalsales.php');
        exit;
    } else {
        echo "Error: " . $stmt->errorInfo()[2];
    }
}
?>



<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../admin/files/admin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&family=Roboto+Condensed&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&family=Titillium+Web&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css"/>
    <title>Add Inventory</title>
    <link rel="icon" href="../image/Icon.png" type="image/x-icon">    
</head>
<body>
    <?php include ('../admin/adminsidebar.php')?>

    <div class="main-content">
        <div class="form-group" data-aos="zoom-in" data-aos-duration="1000">
            <div class="header-font">
                <h1>Add Walk-in Sales</h1>
            </div>
            <form id="productForm" method="POST" enctype="multipart/form-data" action="#">
            <div class="form-row">
    <label for="salesId">Sales ID:</label>
    <div class="input-container">
        <input type="text" id="salesId" name="salesId" value="<?php echo generateNextSalesId($conn); ?>" disabled>
    </div>
</div>

                <div class="form-row">
                    <label for="salesName">Sales Name:</label>
                    <div class="input-container">
                        <select id="salesName" name="salesName" required>
                            <option value="">Select Sales Name</option>
                            <option value="500ml Water Bottle">500ml Water Bottle</option>
                            <option value="New Slim Gallon">New Slim Gallon</option>
                            <option value="New Round Gallon">New Round Gallon</option>
                            <option value="Slim Gallon Refill">Slim Gallon Refill</option>
                            <option value="Round Gallon Refill">Round Gallon Refill</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <label for="quantity">Quantity:</label>
                    <div class="input-container">
                        <input type="number" id="quantity" name="quantity" required>
                    </div>
                </div>
                <div class="form-row">
                    <label for="price">Price:</label>
                    <div class="input-container">
                        <input type="number" id="price" name="price" required disabled>
                    </div>
                </div>
                
                <div>
                    <button type="submit" name="add" id="addProductButton">Add Sales</button>
                </div>
            </form>
        </div>
    </div>

    <script src="../admin/files/adminscript.js"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>

    AOS.init();

    document.getElementById('quantity').addEventListener('input', updatePrice);
    document.getElementById('salesName').addEventListener('change', updatePrice);

    function updatePrice() {
        var salesName = document.getElementById('salesName').value;
        var quantity = document.getElementById('quantity').value;
        var prices = {
            '500ml Water Bottle': 10.00,
            'New Slim Gallon': 150.00,
            'New Round Gallon': 150.00,
            'Slim Gallon Refill': 25.00,
            'Round Gallon Refill': 25.00
        };
        var price = prices[salesName] * quantity;
        document.getElementById('price').value = price.toFixed(2);
    }
    </script>
</body>
</html>
