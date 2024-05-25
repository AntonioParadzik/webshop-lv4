<?php
session_start();
include('functions.php');

if (!isset($_SESSION['admin'])) {
    header('Location: admin.php');
    exit();
}

$id = $_GET['id'];
$product = getProductById($id);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $code = $_POST['code'];
    $price = floatval($_POST['price']);
    $image = $_POST['image'];
    $quantity = intval($_POST['quantity']);

    updateProduct($id, $name, $code, $price, $image, $quantity);
    
    
    header('Location: dashboard.php?page=products');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="edit-product-form">
        <h2>Edit Product</h2>
        <form action="edit_product.php?id=<?php echo $id; ?>" method="post">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo $product['name']; ?>" required>
            <br>
            <label for="code">Code:</label>
            <input type="text" id="code" name="code" value="<?php echo $product['code']; ?>" required>
            <br>
            <label for="price">Price:</label>
            <input type="number" id="price" name="price" step="0.01" value="<?php echo $product['price']; ?>" required>
            <br>
            <label for="image">Image URL:</label>
            <input type="text" id="image" name="image" value="<?php echo $product['image']; ?>" required>
            <br>
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" value="<?php echo $product['quantity']; ?>" required>
            <br>
            <button type="submit">Update Product</button>
        </form>
    </div>
    
    <a class="back" href="dashboard.php?page=products">Back to Products</a>
</body>
</html>
