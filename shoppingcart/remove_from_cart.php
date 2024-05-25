<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    if (isset($_SESSION["shopping_cart"][$id])) {
        unset($_SESSION["shopping_cart"][$id]);
    }
}
header('Location: ./cart.php');
?>
