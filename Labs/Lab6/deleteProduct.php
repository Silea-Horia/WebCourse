<?php
    header("Access-Control-Allow-Origin: http://localhost:4200");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit;
}

    echo "lol";
    $conn = new mysqli("localhost", "root", "", "ecommerce");


    if ($conn->connect_error) {
        die("Connection error: " . $conn->connect_error);
    }

    $id = $_GET["Id"];

    $sql = "DELETE FROM Product WHERE Id = '" . $id . "';";

    $conn->query($sql);

    $conn->close();
?>