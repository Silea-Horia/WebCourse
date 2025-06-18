<?php 
    header("Access-Control-Allow-Origin: http://localhost:4200");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit;
    }   
    
    header('Content-Type: application/json');

    $conn = new mysqli("localhost", "root", "", "ecommerce");

    if ($conn->connect_error) {
        echo json_encode(["success" => false, "error" => "Connection failed: " . $conn->connect_error]);
        exit;
    }

    $name = $_POST["pname"];
    $price = $_POST["price"];
    $category = $_POST["category"]; 

    // validation
    if (empty($name) || empty($price) || empty($category) || !is_numeric($price)) {
        echo json_encode(["success" => false, "error" => "Invalid input"]);
        exit;
    } 

    $stmt = $conn->prepare("SELECT Id FROM Category WHERE Name = ?");
    $stmt->bind_param("s", $category);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $categoryId = $row["Id"];
    } else {
        echo json_encode(['success' => false, 'error' => 'Category not found']);
        $stmt->close();
        $conn->close();
        exit;
    }
    $stmt->close();

    $stmt = $conn->prepare("INSERT INTO Product (Name, Price, CategoryId) VALUES (?, ?, ?)");
    $stmt->bind_param("sii", $name, $price, $categoryId);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error adding product: ' . $conn->error]);
    }

    $stmt->close();
?>
