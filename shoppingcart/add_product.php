<?php
session_start();
include('functions.php');

if (!isset($_SESSION['admin'])) {
    header('Location: admin.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $code = $_POST['code'];
    $price = floatval($_POST['price']);
    $image = $_POST['image'];
    $quantity = intval($_POST['quantity']);

    addProduct($name, $code, $price, $image, $quantity);
    
    
    header('Location: dashboard.php?page=products');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="add-product-form">
        <h2>Add Product</h2>
        <form method="post" action="add_product.php">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            <br>
            <label for="code">Code:</label>
            <input type="text" id="code" name="code" required>
            <br>
            <label for="price">Price:</label>
            <input type="text" id="price" name="price" required>
            <br>
            <label for="image">Image URL:</label>
            <input type="text" id="image" name="image" required>
            <br>
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" required>
            <br>
            <button type="submit">Add Product</button>
        </form>
    </div>
    <a class="back" href="dashboard.php?page=products">Back to Products</a>
</body>
</html>
