<?php
session_start();


$conn = mysqli_connect("localhost", "bank_admin", "admin123", "bank_management");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['collect_payment'])) {
    $customer_name = $_POST['customer_name'];
    $phone_number = $_POST['phone_number'];
    $amount_collected = $_POST['amount_collected'];

    
    $query = "UPDATE loans 
              SET payment_status = 'Paid', remaining_balance = remaining_balance - $amount_collected 
              WHERE customer_id IN (
                  SELECT id 
                  FROM customers 
                  WHERE name = '$customer_name' OR phone = '$phone_number'
              )";
    mysqli_query($conn, $query);

    echo "Payment collected successfully!";
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Staff Dashboard</title>
</head>
<body>
    <h2>Staff Dashboard</h2>

    <h3>Collect Payment</h3>
    <form method="POST" action="staff_dashboard.php">
        <label for="customer_name">Customer Name:</label>
        <input type="text" name="customer_name" required><br>

        <label for="phone_number">Phone Number:</label>
        <input type="text" name="phone_number" required><br>

        <label for="amount_collected">Amount Collected:</label>
        <input type="number" name="amount_collected" required><br>

        <button type="submit" name="collect_payment">Collect Payment</button>
    </form>


    <a href="login.php">Logout</a>
</body>
</html>
