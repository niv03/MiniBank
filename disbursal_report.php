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


$query = "SELECT creation_date, SUM(loan_amount) AS total_disbursal FROM loans GROUP BY creation_date";
$result = mysqli_query($conn, $query);


$report = array();
while ($row = mysqli_fetch_assoc($result)) {
    $date = $row['creation_date'];
    $disbursal = $row['total_disbursal'];
    $report[$date] = $disbursal;
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>Disbursal Report</title>
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
    <h2>Disbursal Report</h2>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Total Disbursal</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($report as $date => $disbursal) {
                echo "<tr>";
                echo "<td>" . $date . "</td>";
                echo "<td>" . $disbursal . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

 
    <br>
    <a href="admin_dashboard.php">Back to Dashboard</a>
</body>
</html>
