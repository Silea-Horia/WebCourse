<?php
    $conn = new mysqli("localhost", "root", "", "ecommerce");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT Name FROM Category";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<button onClick=\"setCategory('" . $row['Name'] . "')\">" . $row['Name'] . "</button>";
        }
        echo "</ul>";
    }

    $conn->close();
?>