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


$query = "SELECT creation_date, SUM(deposit_amount) AS total_collection FROM deposits GROUP BY creation_date";
$result = mysqli_query($conn, $query);


$report = array();
while ($row = mysqli_fetch_assoc($result)) {
    $date = $row['creation_date'];
    $collection = $row['total_collection'];
    $report[$date] = $collection;
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>Collection Report</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Collection Report</h2>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Total Collection</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($report as $date => $collection) {
                echo "<tr>";
                echo "<td>" . $date . "</td>";
                echo "<td>" . $collection . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    
    <br>
    <a href="admin_dashboard.php">Back to Dashboard</a>
</body>
</html>
