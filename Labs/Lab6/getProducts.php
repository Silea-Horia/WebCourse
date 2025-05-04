<?php
    $conn = establishConnection();

    $category = $_GET["Category"];

    $allProducts = getAllProductsByCategory($conn, $category);

    $page = $_GET["Page"];

    define("productsPerPage", 4);

    $paginatedProducts = getPaginatedProducts($allProducts, $page);

    $totalPages = getTotalPages($allProducts);

    header("Content-Type: application/json"); 
    echo json_encode([
        "products" => $paginatedProducts,
        "totalPages" => $totalPages
    ]);
    
    $conn->close();

    function establishConnection() {
        $connection = new mysqli("localhost", "root", "", "ecommerce");

        if ($connection->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        return $connection;
    }

    function getAllProductsByCategory($conn, $category) {
        $sql = "SELECT p.Id Id, p.Name Name, p.Price Price, g.Name Category
            FROM Product p INNER JOIN Category g ON p.CategoryId = g.Id
            WHERE g.Name = \"" . $category . "\"";

        $result = $conn->query($sql);

        $products = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $products[] = [
                    "id" => $row["Id"],
                    "name" => $row["Name"],
                    "price" => $row["Price"],
                    "category" => $row["Category"]
                ];
            }
        }

        return $products;
    }

    function getPaginatedProducts($products, $page) {
        $numberOfProducts = count($products);

        $numberOfPages = ceil($numberOfProducts / productsPerPage);

        $currentProducts = array();

        $startIndex = ($page - 1) * productsPerPage;
        $endIndex = $page * productsPerPage;

        if ($endIndex > $numberOfProducts) {
            $endIndex = $numberOfProducts;
        }

        for ($i = $startIndex; $i < $endIndex; $i++) {
            $currentProducts[] = $products[$i];
        }

        return $currentProducts;
    }

    function getTotalPages($products) {
        $numberOfProducts = count($products);

        return ceil($numberOfProducts / productsPerPage);
    }
?>