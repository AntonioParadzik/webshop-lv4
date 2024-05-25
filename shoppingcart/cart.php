<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once('functions.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id']) && isset($_POST['quantity']) && !isset($_POST['action'])) {
    $id = $_POST['id'];
    $quantity = $_POST['quantity'];

    $conn = connect_db();
    $result = mysqli_query($conn, "SELECT * FROM products WHERE id='$id'");
    if ($result) {
        $product = mysqli_fetch_assoc($result);
        if ($product) {
            // Provjeri postoji li proizvod već u košarici
            if (isset($_SESSION["shopping_cart"][$id])) {
            
                $_SESSION["shopping_cart"][$id]['quantity'] += $quantity;
            } else {
                $_SESSION["shopping_cart"][$id] = array(
                    'id' => $id,
                    'name' => $product['name'],
                    'code' => $product['code'],
                    'price' => $product['price'],
                    'quantity' => $quantity,
                    'image' => $product['image']
                );
            }
        } else {
            echo "Product with ID $id not found.";
        }
    } else {
        echo "Error: " . mysqli_error($conn);
    }
    mysqli_close($conn);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'decrement_quantity') {
    $id = $_POST['id'];
    if (isset($_SESSION["shopping_cart"][$id])) {
        if ($_SESSION["shopping_cart"][$id]['quantity'] > 1) {
            $_SESSION["shopping_cart"][$id]['quantity'] -= 1;
        } else {
            unset($_SESSION["shopping_cart"][$id]);
        }
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'increment_quantity') {
    $id = $_POST['id'];
    if (isset($_SESSION["shopping_cart"][$id])) {
        $_SESSION["shopping_cart"][$id]['quantity'] += 1;
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'remove_from_cart') {
    $id = $_POST['id'];
    if (isset($_SESSION["shopping_cart"][$id])) {
        unset($_SESSION["shopping_cart"][$id]);
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name']) && isset($_POST['address']) && !isset($_POST['action'])) {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $zip = $_POST['zip'];
    $phone = $_POST['phone'];

    $total_price = 0;
    $order_items = array();
    if (isset($_SESSION["shopping_cart"])) {
        $conn = connect_db();
        foreach ($_SESSION["shopping_cart"] as $id => $product) {
            $result = mysqli_query($conn, "SELECT * FROM products WHERE id='$id'");
            if ($result) {
                $product_data = mysqli_fetch_assoc($result);
                if ($product_data && $product['quantity'] <= $product_data['quantity']) {
                    $total_price += $product['price'] * $product['quantity'];
                    $order_items[] = array('id' => $id, 'quantity' => $product['quantity']);
                } else {
                    echo "<h1>Requested quantity is not available.</h1>";
                    echo "<a style='color: black; font-size:18px' href='./cart.php'>Go back to Cart</a>";

                    mysqli_close($conn);
                    exit();
                }
            } else {
                echo "Error: " . mysqli_error($conn);
                mysqli_close($conn);
                exit();
            }
        }

        $query = "INSERT INTO orders (name, address, city, zip, phone, total_price) VALUES ('$name', '$address', '$city', '$zip', '$phone', '$total_price')";
        if (mysqli_query($conn, $query)) {
            $order_id = mysqli_insert_id($conn);
            foreach ($order_items as $item) {
                $product_id = $item['id'];
                $quantity = $item['quantity'];
                $query = "INSERT INTO order_items (order_id, product_id, quantity) VALUES ('$order_id', '$product_id', '$quantity')";
                mysqli_query($conn, $query);

                $query = "UPDATE products SET quantity = quantity - $quantity WHERE id='$product_id'";
                mysqli_query($conn, $query);
            }
            $_SESSION["shopping_cart"] = array();
            mysqli_close($conn);
            header('Location: success.php?order_id=' . $order_id);
            exit();
        } else {
            echo "Error: " . $query . "<br>" . mysqli_error($conn);
            mysqli_close($conn);
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart and Checkout</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <header>
        <h1>Welcome to our Shop</h1>
        <nav>
            <ul>
                <li><a href="../index.php?page=home">Home</a></li>
                <li><a href="../index.php?page=products">Products</a></li>
                <li><a href="../index.php?page=admin">Admin</a></li>
            </ul>
        </nav>
        <?php
       if (!empty($_SESSION["shopping_cart"])) {
        $cart_count = count(array_keys($_SESSION["shopping_cart"]));
        echo "<button class='cart_div'><a href=''>Cart<span>$cart_count</span></a></button>";
        }
        else{
            echo "<button class='cart_div'><a href=''>Cart<span>0</span></a></button>";
        }
        
        ?>
    </header>
    <h2>Shopping Cart</h2>
    <div class="cart-checkout">
        <div class="cart">
            <?php
            if (!empty($_SESSION["shopping_cart"])) {
                $total_price = 0;
            ?>
            <table>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total Price</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($_SESSION["shopping_cart"] as $id => $product) { ?>
                <tr>
                    <td><img src="../imgs/<?php echo $product['image']; ?>" width="50" height="40" /></td>
                    <td><?php echo $product['name']; ?></td>
                    <td><?php echo $product['quantity']; ?></td>
                    <td><?php echo "$" . $product['price']; ?></td>
                    <td><?php echo "$" . $product['price'] * $product['quantity']; ?></td>
                    <td>
                        <div class="quantity-change">
                            <form method="post" action="">
                                <input type="hidden" name="id" value="<?php echo $id; ?>">
                                <input type="hidden" name="action" value="decrement_quantity">
                                <button type="submit">-</button>
                            </form>
                            <form method="post" action="">
                                <input type="hidden" name="id" value="<?php echo $id; ?>">
                                <input type="hidden" name="action" value="increment_quantity">
                                <button type="submit">+</button>
                            </form>
                        </div>
                        <form method="post" action="">
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                            <input type="hidden" name="action" value="remove_from_cart">
                            <button type="submit">Remove</button>
                        </form>
                    </td>
                </tr>
                <?php
                    $total_price += ($product['price'] * $product['quantity']);
                }
                ?>
                <tr>
                    <td colspan="5" align="right">
                        <strong>TOTAL: <?php echo "$" . $total_price; ?></strong>
                    </td>
                </tr>
            </table>
            <?php
            } else {
                echo "<h3>Your cart is empty!</h3>";
            }
            ?>
        </div>
        <div class="checkout">
            <h2>Checkout</h2>
            <form method="post" action="">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
                <br>
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" required>
                <br>
                <label for="city">City:</label>
                <input type="text" id="city" name="city" required>
                <br>
                <label for="zip">ZIP Code:</label>
                <input type="text" id="zip" name="zip" required>
                <br>
                <label for="phone">Phone:</label>
                <input type="text" id="phone" name="phone" required>
                <br>
                <button type="submit">Place Order</button>
            </form>
        </div>
    </div>
   
</body>
<a class="add-products" href="../index.php">Add more products</a>
</html>
