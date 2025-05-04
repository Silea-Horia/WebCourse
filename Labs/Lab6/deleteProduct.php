<?php
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