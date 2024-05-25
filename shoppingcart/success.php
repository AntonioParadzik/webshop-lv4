<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once('functions.php');

$order_id = $_GET['order_id'];
$conn = connect_db();
$query = "SELECT * FROM orders WHERE id='$order_id'";
$result = mysqli_query($conn, $query);
$order = mysqli_fetch_assoc($result);

$query = "SELECT oi.*, p.name, p.price FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id='$order_id'";
$result = mysqli_query($conn, $query);
$order_items = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Success</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .order-confirmation {
            max-width: 800px;
            margin: 0 auto;
            }

            .order-title {
            font-size: 2rem;
            margin-bottom: 1rem;
            }

            .order-message {
            font-size: 1.2rem;
            margin-bottom: 1rem;
            }

            .order-details-title,
            .order-items-title {
            font-size: 1.5rem;
            margin-top: 1.5rem;
            margin-bottom: 1rem;
            }

            .order-detail {
            font-size: 1rem;
            margin: 0.5rem 0;
            }

            .order-items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            margin-bottom: 1.5rem;
            }

            .order-items-table, 
            .order-items-table th, 
            .order-items-table td {
            border: 1px solid #ddd;
            padding: 8px;
            }

            .order-items-table th {
            background-color: #f2f2f2;
            font-size: 1rem;
            }

            .order-items-table td {
            font-size: 1rem;
            }

            .back-link {
                display: inline-block;
                margin-top: 1rem;
                padding: 8px 16px;
                background-color: white;
                border: 1.2px solid #333;
                border-radius: 8px;
                text-decoration: none;
                color: #333;
                font-weight: bold;
            }

            .back-link:hover {
                background-color: #333;
                color: white;
            }
    </style>
</head>
<body>
    <div class="order-confirmation">
        <h2 class="order-title">Order Successful</h2>
        <p class="order-message">Thank you for your order. Your order ID is: <?php echo $order_id; ?></p>
        <h3 class="order-details-title">Order Details</h3>
        <p class="order-detail"><strong>Name:</strong> <?php echo $order['name']; ?></p>
        <p class="order-detail"><strong>Address:</strong> <?php echo $order['address']; ?></p>
        <p class="order-detail"><strong>City:</strong> <?php echo $order['city']; ?></p>
        <p class="order-detail"><strong>Zip:</strong> <?php echo $order['zip']; ?></p>
        <p class="order-detail"><strong>Phone:</strong> <?php echo $order['phone']; ?></p>
        <h3 class="order-items-title">Order Items</h3>
        <table class="order-items-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order_items as $item) : ?>
                    <tr>
                        <td><?php echo $item['name']; ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td><?php echo $item['price']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a class="back-link" href="../index.php">Go back to Home</a>
    </div>
</body>
</html>
