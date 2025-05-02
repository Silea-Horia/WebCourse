<?php 
    $conn = new mysqli("localhost", "root", "", "ecommerce");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $name = $_POST["pname"];
    $price = $_POST["price"];
    $category = $_POST["category"]; 

    $sql = "INSERT INTO Product VALUES (NULL, '" . $name . "', " . $price . ", ( SELECT Id FROM Category WHERE Name = '" . $category . "'));";

    $result = $conn->query($sql);
?>
