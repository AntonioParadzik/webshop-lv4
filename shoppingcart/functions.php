<?php

function connect_db() {
    $conn = mysqli_connect("localhost", "root", "", "shoppingcart");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    return $conn;
}

function encrypt_password($password) {
    return hash('sha256', $password);
}

function getProducts() {
    $conn = connect_db();
    $sql = "SELECT * FROM products";
    $result = mysqli_query($conn, $sql);
    $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $products;
}

function getProductById($id) {
    $conn = connect_db();
    $sql = "SELECT * FROM products WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $product = mysqli_fetch_assoc($result);
    mysqli_close($conn);
    return $product;
}

function addProduct($name, $code, $price, $image, $quantity) {
    $conn = connect_db();
    $sql = "INSERT INTO products (name, code, price, image, quantity) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ssdsi', $name, $code, $price, $image, $quantity);
    mysqli_stmt_execute($stmt);
    mysqli_close($conn);
}


function updateProduct($id, $name, $code, $price, $image, $quantity) {
    $conn = connect_db();
    $sql = "UPDATE products SET name = ?, code = ?, price = ?, image = ?, quantity = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ssdsii', $name, $code, $price, $image, $quantity, $id);
    mysqli_stmt_execute($stmt);
    mysqli_close($conn);
}


// Brisanje proizvoda
function deleteProduct($id) {
    $conn = connect_db();
    $sql1 = "DELETE FROM products WHERE id = ?";
    $sql2 = "DELETE FROM order_items WHERE product_id = ?";
    $stmt2 = mysqli_prepare($conn, $sql2);
    mysqli_stmt_bind_param($stmt2, 'i', $id);
    mysqli_stmt_execute($stmt2);
    $stmt1 = mysqli_prepare($conn, $sql1);
    mysqli_stmt_bind_param($stmt1, 'i', $id);
    mysqli_stmt_execute($stmt1);
    mysqli_close($conn);
}

function getOrders() {
    $conn = connect_db();
    $sql = "SELECT * FROM orders ORDER BY created_at DESC";
    $result = mysqli_query($conn, $sql);
    $orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $orders;
}

function getOrderItems($order_id) {
    $conn = connect_db();
    $sql = "SELECT order_items.*, products.name, products.price 
            FROM order_items 
            JOIN products ON order_items.product_id = products.id 
            WHERE order_items.order_id = '$order_id'";
    $result = mysqli_query($conn, $sql);
    $order_items = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $order_items;
}

function header_template() {
    echo '<header>
            <h1>Welcome to our Shop</h1>
            <nav>
                <ul>
                    <li><a href="index.php?page=home">Home</a></li>
                    <li><a href="index.php?page=products">Products</a></li>
                    <li><a href="index.php?page=admin">Admin</a></li>
                </ul>
            </nav>';
    if (!empty($_SESSION["shopping_cart"])) {
        $cart_count = count(array_keys($_SESSION["shopping_cart"]));
        echo "<button class='cart_div'><a href='./shoppingcart/cart.php'>Cart<span>$cart_count</span></a></button>";
    }
    else{
        echo "<button class='cart_div'><a href='./shoppingcart/cart.php'>Cart<span>0</span></a></button>";
    }
    echo '</header>';
}

?>