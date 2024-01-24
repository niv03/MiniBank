<?php
session_start();


$conn = mysqli_connect("localhost", "bank_admin", "admin123", "bank_management");


if (isset($_SESSION['customer_id'])) {
    $customer_id = $_SESSION['customer_id'];

    
    $query = "SELECT * FROM customers WHERE id = $customer_id";
    $result = mysqli_query($conn, $query);
    $customer = mysqli_fetch_assoc($result);

    
    $query = "SELECT * FROM loans WHERE customer_id = $customer_id";
    $loanResult = mysqli_query($conn, $query);

    
    $query = "SELECT * FROM deposits WHERE customer_id = $customer_id";
    $depositResult = mysqli_query($conn, $query);
} else {
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $phone = $_POST['phone'];

        
        $query = "SELECT * FROM customers WHERE name = '$name' AND phone = '$phone' LIMIT 1";
        $result = mysqli_query($conn, $query);
        $customer = mysqli_fetch_assoc($result);

        if ($customer) {
            
            $_SESSION['customer_id'] = $customer['id'];

            
            $query = "SELECT * FROM loans WHERE customer_id = {$customer['id']}";
            $loanResult = mysqli_query($conn, $query);

            
            $query = "SELECT * FROM deposits WHERE customer_id = {$customer['id']}";
            $depositResult = mysqli_query($conn, $query);
        } else {
            
            $error = "Invalid login credentials. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Dashboard</title>
</head>
<body>
    <?php if (isset($_SESSION['customer_id'])) { ?>
        <h2>Welcome, <?php echo $customer['name']; ?>!</h2>

        <h3>Loan Details</h3>
        <?php if (mysqli_num_rows($loanResult) > 0) { ?>
            <table>
                <thead>
                    <tr>
                        <th>Loan ID</th>
                        <th>Interest Rate</th>
                        <th>Loan Amount</th>
                        <th>Loan Type</th>
                        <th>Creation Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($loan = mysqli_fetch_assoc($loanResult)) { ?>
                        <tr>
                            <td><?php echo $loan['id']; ?></td>
                            <td><?php echo $loan['interest_rate']; ?></td>
                            <td><?php echo $loan['loan_amount']; ?></td>
                            <td><?php echo $loan['loan_type']; ?></td>
                            <td><?php echo $loan['creation_date']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>No loans found.</p>
        <?php } ?>

        <h3>Deposit Details</h3>
        <?php if (mysqli_num_rows($depositResult) > 0) { ?>
            <table>
                <thead>
                    <tr>
                        <th>Deposit ID</th>
                        <th>Interest Rate</th>
                        <th>Deposit Amount</th>
                        <th>Deposit Type</th>
                        <th>Creation Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($deposit = mysqli_fetch_assoc($depositResult)) { ?>
                        <tr>
                            <td><?php echo $deposit['id']; ?></td>
                            <td><?php echo $deposit['interest_rate']; ?></td>
                            <td><?php echo $deposit['deposit_amount']; ?></td>
                            <td><?php echo $deposit['deposit_type']; ?></td>
                            <td><?php echo $deposit['creation_date']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>No deposits found.</p>
        <?php } ?>
        <a href="login.php">Logout</a>
    <?php } else { ?>
        <h2>Customer Login</h2>
        <?php if (isset($error)) { ?>
            <p><?php echo $error; ?></p>
        <?php } ?>
        <form method="POST" action="customer_dashboard.php">
            <label for="name">Name:</label>
            <input type="text" name="name" required><br>

            <label for="phone">Phone Number:</label>
            <input type="text" name="phone" required><br>

            <button type="submit" name="login">Login</button>
        </form>
        
    <?php } ?>

</body>
</html>
