<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    
    if ($phone == 'admin' && $password == 'admin123') {
        $_SESSION['user_id'] = 1;
        $_SESSION['role'] = 'Admin';
        header("Location: admin_dashboard.php");
        exit();
    } elseif ($phone == 'staff' && $password == 'staff123') {
        $_SESSION['user_id'] = 2;
        $_SESSION['role'] = 'Staff';
        header("Location: staff_dashboard.php");
        exit();
    } elseif ($phone == 'customer' && $password == 'customer123') {
        $_SESSION['user_id'] = 3;
        $_SESSION['role'] = 'Customer';
        header("Location: customer_dashboard.php");
        exit();
    } else {
        echo 'Invalid login credentials';
    }
}
?>
