<?php
session_start();


if ($_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit();
}


$host = "localhost";
$username = "bank_admin";
$password = "admin123";
$database = "bank_management";


$conn = mysqli_connect($host, $username, $password, $database);


if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_customer'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $aadhar = $_POST['aadhar'];
    $pan = $_POST['pan'];

    $query = "INSERT INTO customers (name, email, phone, address, aadhar, pan) VALUES ('$name', '$email', '$phone', '$address', '$aadhar', '$pan')";
    if (mysqli_query($conn, $query)) {
        echo "Customer added successfully!";
    } else {
        echo "Error adding customer: " . mysqli_error($conn);
    }
}


if (isset($_POST['edit_customer'])) {
    $customer_id = $_POST['customer_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $aadhar = $_POST['aadhar'];
    $pan = $_POST['pan'];

    $query = "UPDATE customers SET name = '$name', email = '$email', phone = '$phone', address = '$address', aadhar = '$aadhar', pan = '$pan' WHERE id = $customer_id";
    mysqli_query($conn, $query);
    echo "Customer updated successfully!";
}


if (isset($_GET['delete_customer'])) {
    $customer_id = $_GET['delete_customer'];

    
    $query = "DELETE FROM loans WHERE customer_id = $customer_id";
    mysqli_query($conn, $query);

    
    $query = "DELETE FROM deposits WHERE customer_id = $customer_id";
    mysqli_query($conn, $query);

    
    $query = "DELETE FROM customers WHERE id = $customer_id";
    mysqli_query($conn, $query);

    echo "Customer deleted successfully!";
}

if (isset($_POST['create_loan'])) {
    $customer_id = $_POST['customer_id'];
    $interest_rate = $_POST['interest_rate'];
    $loan_amount = $_POST['loan_amount'];
    $loan_type = $_POST['loan_type'];
    $creation_date = $_POST['creation_date'];

    $query = "INSERT INTO loans (customer_id, interest_rate, loan_amount, loan_type, creation_date) VALUES ('$customer_id', '$interest_rate', '$loan_amount', '$loan_type', '$creation_date')";
    mysqli_query($conn, $query);
    echo "Loan created successfully!";
}


if (isset($_POST['create_deposit'])) {
    $customer_id = $_POST['customer_id'];
    $interest_rate = $_POST['interest_rate'];
    $deposit_amount = $_POST['deposit_amount'];
    $deposit_type = $_POST['deposit_type'];
    $creation_date = $_POST['creation_date'];

    $query = "INSERT INTO deposits (customer_id, interest_rate, deposit_amount, deposit_type, creation_date) VALUES ('$customer_id', '$interest_rate', '$deposit_amount', '$deposit_type', '$creation_date')";
    mysqli_query($conn, $query);
    echo "Deposit created successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
</head>
<body>
    <h2>Admin Dashboard</h2>

  
    <h3>Add Customer</h3>
    <form method="POST" action="admin_dashboard.php">
        <label for="name">Name:</label>
        <input type="text" name="name" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" required><br>

        <label for="phone">Phone:</label>
        <input type="text" name="phone" required><br>

        <label for="address">Address:</label>
        <input type="text" name="address" required><br>

        <label for="aadhar">Aadhar:</label>
        <input type="text" name="aadhar" required><br>

        <label for="pan">Pan:</label>
        <input type="text" name="pan" required><br>

        <button type="submit" name="add_customer">Add Customer</button>
    </form>

 
    <h3>Customer List</h3>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Aadhar</th>
                <th>Pan</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT * FROM customers";
            $result = mysqli_query($conn, $query);

            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>".$row['name']."</td>";
                echo "<td>".$row['email']."</td>";
                echo "<td>".$row['phone']."</td>";
                echo "<td>".$row['address']."</td>";
                echo "<td>".$row['aadhar']."</td>";
                echo "<td>".$row['pan']."</td>";
                echo "<td><a href='edit_customer.php?customer_id=".$row['id']."'>Edit</a> | <a href='admin_dashboard.php?delete_customer=".$row['id']."' onclick=\"return confirm('Are you sure you want to delete this customer?');\">Delete</a></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <h3>Create Loan</h3>
    <form method="POST" action="admin_dashboard.php">
        <label for="customer_id">Customer:</label>
        <select name="customer_id" required>
            <?php
            $query = "SELECT * FROM customers";
            $result = mysqli_query($conn, $query);

            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='".$row['id']."'>".$row['name']."</option>";
            }
            ?>
        </select><br>

        <label for="interest_rate">Interest Rate:</label>
        <input type="number" name="interest_rate" required><br>

        <label for="loan_amount">Loan Amount:</label>
        <input type="number" name="loan_amount" required><br>

        <label for="loan_type">Loan Type:</label>
        <select name="loan_type" required>
            <option value="daily">Daily</option>
            <option value="monthly">Monthly</option>
            <option value="annually">Annually</option>
        </select><br>

        <label for="creation_date">Creation Date:</label>
        <input type="date" name="creation_date" required><br>

        <button type="submit" name="create_loan">Create Loan</button>
    </form>


    <h3>Create Deposit</h3>
    <form method="POST" action="admin_dashboard.php">
        <label for="customer_id">Customer:</label>
        <select name="customer_id" required>
            <?php
            $query = "SELECT * FROM customers";
            $result = mysqli_query($conn, $query);

            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='".$row['id']."'>".$row['name']."</option>";
            }
            ?>
        </select><br>

        <label for="interest_rate">Interest Rate:</label>
        <input type="number" name="interest_rate" required><br>

        <label for="deposit_amount">Deposit Amount:</label>
        <input type="number" name="deposit_amount" required><br>

        <label for="deposit_type">Deposit Type:</label>
        <select name="deposit_type" required>
            <option value="daily">Daily</option>
            <option value="monthly">Monthly</option>
            <option value="annually">Annually</option>
        </select><br>

        <label for="creation_date">Creation Date:</label>
        <input type="date" name="creation_date" required><br>

        <button type="submit" name="create_deposit">Create Deposit</button>
    </form>

 
    <h3>Reports</h3>
    <ul>
        <li><a href="collection_report.php">Datewise Collection Report</a></li>
        <li><a href="disbursal_report.php">Datewise Disbursal Report</a></li>
    </ul>

  
    <a href="login.php">Logout</a>
</body>
</html>


