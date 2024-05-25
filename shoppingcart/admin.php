<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once('functions.php');
$conn = connect_db();

if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = hash('sha256', $_POST['password']);

    $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['admin'] = $email;
        if (basename($_SERVER['SCRIPT_NAME']) == 'index.php') {
            header('Location: ./shoppingcart/dashboard.php');
        } else {
            header('Location: ./dashboard.php');
        }
        exit();
    } else {
        $error = "Invalid credentials.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-content{
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-container {
            margin-right: 3rem;
            margin-left: 1rem;
            margin-top: 1rem;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            width: 25%;
            background-color: #f9f9f9;
        }

        .login-container h2 {
            font-size: 1.8rem;
            margin-top: 1rem;
            margin-left: 1rem;
        }

        .error-message {
        color: red;
        margin-bottom: 1rem;
        }

        .login-form {
            display: flex;
            flex-direction: column;
            gap: 0.1rem;
            margin-left: 1rem;
        }

        .login-container input {
            padding: 0.4rem;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            width: calc(100% - 1rem);
            margin-bottom: 1rem;
        }

        .login-button {
            padding: 8px 16px;
            background-color: white;
            border: 1.2px solid #333;
            border-radius: 8px;
            cursor: pointer;
            align-self: flex-start;
        }

        .login-button:hover {
            background-color: #333;
            color: white;
            transition: ease-in-out 0.2s;
        }
    </style>
</head>
<body>
    <div class="admin-content">
        <div class="login-container">
            <h2>Admin Login</h2>
            <?php if (isset($error)) : ?>
                <p class="error-message"><?php echo $error; ?></p>
            <?php endif; ?>
            <form class="login-form" method="POST">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button class="login-button" type="submit">Login</button>
            </form>
        </div>
    </div>
    
</body>
</html>