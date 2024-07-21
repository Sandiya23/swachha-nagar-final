<?php
session_start();

// Hard-coded admin credentials
$admin_username = 'admin';
$admin_password = 'password';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate credentials
    if ($username === $admin_username && $password === $admin_password) {
        $_SESSION['loggedin'] = true;
        header('Location: manage_schedule.php');
        exit();
    } else {
        $error_message = 'Invalid username or password.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background-color: #f0f0f0;
    }

    .login-container {
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .login-container h1 {
        margin-bottom: 20px;
    }

    .login-container input[type="text"],
    .login-container input[type="password"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .login-container button {
        width: 100%;
        padding: 10px;
        background-color: #009600;
        border: none;
        border-radius: 4px;
        color: white;
        font-size: 16px;
        cursor: pointer;
    }

    .login-container button:hover {
        background-color: #007f00;
    }

    .error-message {
        color: red;
        margin-bottom: 10px;
    }
    </style>
</head>

<body>
    <div class="login-container">
        <h1>Login</h1>
        <?php if (isset($error_message)) { ?>
        <div class="error-message"><?php echo $error_message; ?></div>
        <?php } ?>
        <form action="login.php" method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>

</html>