<?php
    $conn = new mysqli("localhost", "root", "", "ecommerce");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT p.Name Name
            FROM Product p INNER JOIN Category g ON p.CategoryId = g.Id
            WHERE g.Name = \"" . $_GET["Category"] . "\"";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>" . $row['Name'] . "</li>";
        }
        echo "</ul>";
    }

    $conn->close();
?>