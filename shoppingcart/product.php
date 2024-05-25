<?php

include_once('functions.php');

if (!isset($_GET['id'])) {
    header('Location: home.php');
    exit();
}

$conn = connect_db();
$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM products WHERE id='$id'");
$product = mysqli_fetch_assoc($result);
mysqli_close($conn);
?>

<h2><?php echo $product['name']; ?></h2>
<div class="product-detail">
    <div class="product-general">
        <img src="imgs/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
        <p>$<?php echo $product['price']; ?></p>
    </div>
    
    <form method="post" action="./shoppingcart/cart.php">
        <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" id="quantity" value="1" min="1">
        <button type="submit">Add to Cart</button>
    </form>
</div>
