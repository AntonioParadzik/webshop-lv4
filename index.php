<?php
include('./shoppingcart/functions.php');
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php header_template(); ?>
    <main>
        <?php
        // Set default page to home if not specified
        $page = isset($_GET['page']) ? $_GET['page'] : 'home';
        
        // Sanitize page variable to prevent directory traversal attacks
        $page = basename($page);
        
        // Include the requested page
        $page_file = "shoppingcart/$page.php";
        if (file_exists($page_file)) {
            include($page_file);
        } else {
            echo "Page not found.";
        }
        ?>
    </main>
</body>
</html>
