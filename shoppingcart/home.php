<?php
$conn = connect_db();
$result = mysqli_query($conn, "SELECT * FROM products WHERE quantity > 0 ORDER BY id DESC LIMIT 4 ");
?>
<h2>New Arrivals</h2>
<div class="products">
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <div class="product">
            <img src="imgs/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
            <div class="product-details">
                <h3><?php echo $row['name']; ?></h3>
                <p>$<?php echo $row['price']; ?></p>
                <button><a href="index.php?page=product&id=<?php echo $row['id']; ?>">View Product</a></button>
            </div>
        </div>
    <?php } ?>
</div>
<?php mysqli_close($conn); ?>