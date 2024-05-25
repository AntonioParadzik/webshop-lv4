<?php
session_start();
include('functions.php');

if (!isset($_SESSION['admin'])) {
    header('Location: admin.php');
    exit();
}

$products = getProducts();
$orders = getOrders();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <header>
    <h1>Admin Dashboard</h1>
    <nav>
        <ul>
            <li><a href="dashboard.php">Home</a></li>
            <li><a href="dashboard.php?page=products">Products</a></li>
            <li><a href="dashboard.php?page=orders">Orders</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>   
    </nav>
    </header>

    <div class="content">
        <?php if (isset($_GET['page']) && $_GET['page'] == 'products') : ?>
            <h2>Products</h2>
            <a href="add_product.php">Add New Product</a>
            <?php if (!empty($products)) : ?>
                <table>
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product) : ?>
                            <tr>
                                <td><img src="../imgs/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" width="50"></td>
                                <td><?php echo $product['name']; ?></td>
                                <td><?php echo $product['code']; ?></td>
                                <td><?php echo $product['price']; ?> $</td>
                                <td><?php echo $product['quantity']; ?></td>
                                <td class="actions">
                                    <a href="edit_product.php?id=<?php echo $product['id']; ?>">Edit</a>
                                    <a href="delete_product.php?id=<?php echo $product['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else : ?>
                <p>No products found.</p>
            <?php endif; ?>
        <?php elseif (isset($_GET['page']) && $_GET['page'] == 'orders') : ?>
            <h2>Orders</h2>
            <?php if (!empty($orders)) : ?>
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>City</th>
                            <th>ZIP</th>
                            <th>Phone</th>
                            <th>Total Price</th>
                            <th>Date</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order) : ?>
                            <tr>
                                <td><?php echo $order['id']; ?></td>
                                <td><?php echo $order['name']; ?></td>
                                <td><?php echo $order['address']; ?></td>
                                <td><?php echo $order['city']; ?></td>
                                <td><?php echo $order['zip']; ?></td>
                                <td><?php echo $order['phone']; ?></td>
                                <td><?php echo $order['total_price']; ?> $</td>
                                <td><?php echo $order['created_at']; ?></td>
                                <td><a href="dashboard.php?page=order_details&id=<?php echo $order['id']; ?>">View Details</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else : ?>
                <p>No orders found.</p>
            <?php endif; ?>
        <?php elseif (isset($_GET['page']) && $_GET['page'] == 'order_details' && isset($_GET['id'])) : ?>
            <?php 
                $order_id = $_GET['id'];
                $order_items = getOrderItems($order_id);
            ?>
            <h2>Order Details (Order ID: <?php echo $order_id; ?>)</h2>
            <?php if (!empty($order_items)) : ?>
                <table>
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order_items as $item) : ?>
                            <tr>
                                <td><?php echo $item['name']; ?></td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td><?php echo $item['price']; ?> $</td>
                                <td><?php echo $item['price'] * $item['quantity']; ?> $</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <a href="dashboard.php?page=orders" class="back-link">Back to Orders</a>
            <?php else : ?>
                <p>No items found for this order.</p>
                <a href="dashboard.php?page=orders" class="back-link">Back to Orders</a>
            <?php endif; ?>
        <?php else : ?>
            <h2>Welcome to the Admin Dashboard</h2>
            <p>Select an option from the menu.</p>
        <?php endif; ?>
    </div>
</body>
</html>
