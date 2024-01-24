<?php

require_once 'db_connection.php';


function executeQuery($sql)
{
    global $conn;
    $result = $conn->query($sql);
    if (!$result) {
        die("Query failed: " . $conn->error);
    }
    return $result;
}


function fetchData($sql)
{
    $result = executeQuery($sql);
    $data = $result->fetch_all(MYSQLI_ASSOC);
    return $data;
}


function insertData($sql)
{
    global $conn;
    $result = executeQuery($sql);
    return $conn->insert_id;
}
?>
