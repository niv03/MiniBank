<?php
session_start();


$conn = mysqli_connect("localhost", "bank_admin", "admin123", "bank_management");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    
    if ($phone === '1234567890' && $password === 'password123') {
        $_SESSION['role'] = 'Admin';
        header("Location: admin_dashboard.php");
        
    }
    
    
    if ($phone === '987654321' && $password === 'password123') {
        $_SESSION['role'] = 'Staff';
        header("Location: staff_dashboard.php");
        
    }

    
    if ($phone === '12345' && $password === 'password123') {
        $_SESSION['role'] = 'Customer';
        header("Location: customer_dashboard.php");
        


    } else {
        echo 'Invalid credentials. Please try again.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <form method="POST" action="login.php">
        <label for="phone">Phone Number:</label>
        <input type="text" name="phone" required><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required><br>

        <button type="submit" name="login">Login</button>
    </form>
</body>
</html>
