<?php

function getDbConnection() {
    $dbHost = 'localhost';
    $dbName = 'your_database_name';
    $dbUser = 'db_user';
    $dbPass = 'db_password';

    $connection = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    return $connection;
}




if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestBody = json_decode(file_get_contents('php://input'), true);
    $action = $_GET['action'];

    if ($action === 'login') {
        $mobileNumber = $requestBody['mobile_number'];
        $password = $requestBody['password'];

        
        $connection = getDbConnection();
        $stmt = $connection->prepare("SELECT id FROM users WHERE mobile_number = ? AND password = ?");
        $stmt->bind_param("ss", $mobileNumber, $password);
        $stmt->execute();
        $stmt->bind_result($userId);
        $stmt->fetch();
        $stmt->close();

        if ($userId) {
            session_start();
            $_SESSION['user_id'] = $userId;

            echo json_encode(['message' => 'Login successful']);
        } else {
            http_response_code(401);
            echo json_encode(['message' => 'Invalid credentials']);
        }
    } elseif ($action === 'register') {
        $mobileNumber = $requestBody['mobile_number'];
        $password = $requestBody['password'];
        $connection = getDbConnection();
        $stmt = $connection->prepare("SELECT id FROM users WHERE mobile_number = ?");
        $stmt->bind_param("s", $mobileNumber);
        $stmt->execute();
        $stmt->bind_result($userId);
        $stmt->fetch();
        $stmt->close();

        if ($userId) {
            http_response_code(400);
            echo json_encode(['message' => 'Mobile number already registered']);
        } else {
            $stmt = $connection->prepare("INSERT INTO users (mobile_number, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $mobileNumber, $password);
            $stmt->execute();
            $stmt->close();

            echo json_encode(['message' => 'Registration successful']);
        }
    } elseif ($action === 'forgot_password') {
        $mobileNumber = $requestBody['mobile_number'];
        $newPassword = $requestBody['new_password'];
        $connection = getDbConnection();
        $stmt = $connection->prepare("UPDATE users SET password = ? WHERE mobile_number = ?");
        $stmt->bind_param("ss", $newPassword, $mobileNumber);
        $stmt->execute();
        $stmt->close();

        echo json_encode(['message' => 'Password updated successfully']);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'];

    if ($action === 'get_items') {
        $connection = getDbConnection();
        $result = $connection->query("SELECT * FROM items");
        $items = $result->fetch_all(MYSQLI_ASSOC);
        $connection->close();

        echo json_encode($items);
    } elseif ($action === 'get_item') {
        $itemId = $_GET['id'];
        $connection = getDbConnection();
        $stmt = $connection->prepare("SELECT * FROM items WHERE id = ?");
        $stmt->bind_param("i", $itemId);
        $stmt->execute();
        $result = $stmt->get_result();
        $item = $result->fetch_assoc();
        $stmt->close();
        $connection->close();

        echo json_encode($item);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestBody = json_decode(file_get_contents('php://input'), true);
    $action = $_GET['action'];

    if ($action === 'add_item') {
        $itemName = $requestBody['name'];

        $connection = getDbConnection();
        $stmt = $connection->prepare("INSERT INTO items (name) VALUES (?)");
        $stmt->bind_param("s", $itemName);
        $stmt->execute();
        $stmt->close();
        $connection->close();

        echo json_encode(['message' => 'Item added successfully']);
    } elseif ($action === 'edit_item') {
        $itemId = $_GET['id'];
        $itemName = $requestBody['name'];

        $connection = getDbConnection();
        $stmt = $connection->prepare("UPDATE items SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $itemName, $itemId);
        $stmt->execute();
        $stmt->close();
        $connection->close();

        echo json_encode(['message' => 'Item updated successfully']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $action = $_GET['action'];
    $itemId = $_GET['id'];

    if ($action === 'delete_item') {
        $connection = getDbConnection();
        $stmt = $connection->prepare("DELETE FROM items WHERE id = ?");
        $stmt->bind_param("i", $itemId);
        $stmt->execute();
        $stmt->close();
        $connection->close();

        echo json_encode(['message' => 'Item deleted successfully']);
    }
}
?>
