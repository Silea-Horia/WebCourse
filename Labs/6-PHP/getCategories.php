<?php
    header("Access-Control-Allow-Origin: http://localhost:4200");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit;
    }
    
    $conn = new mysqli("localhost", "root", "", "ecommerce");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT Name FROM Category";
    $result = $conn->query($sql);
    $categories = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row['Name'];
        }
    }

    header("Content-Type: application/json"); 
    echo json_encode([
        "categories" => $categories
    ]);

    $conn->close();
?>