<?php
session_start();
include('functions.php');


if (!isset($_SESSION['admin'])) {
    header('Location: admin.php');
    exit();
}

$id = $_GET['id'];
deleteProduct($id);
header('Location: dashboard.php?page=products');
exit();
?>