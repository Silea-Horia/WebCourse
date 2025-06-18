<?php

class DBUtils {
	private $host = 'localhost';
	private $db   = 'ecommerce';
	private $user = 'root';
	private $pass = '';
	private $conn = null;
	
	public function __construct () {
		$this->conn = new mysqli($this->host, $this->db, $this->user, $this->pass);

		if ($this->conn->connect_error) {
			echo json_encode(["success" => false, "error" => "Connection failed: " . $this->conn->connect_error]);
			exit;
		}
	}

	public function selectAllCategories() {
    	$sql = "SELECT Name FROM Category";
		$result = $this->conn->query($sql);
		$categories = array();

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$categories[] = $row['Name'];
			}
		}

		return $categories;
    }

    public function selectProductsForCategory($category) {
    	$sql = "SELECT p.Id Id, p.Name Name, p.Price Price, g.Name Category
            FROM Product p INNER JOIN Category g ON p.CategoryId = g.Id
            WHERE g.Name = \"" . $category . "\"";

        $result = $this->conn->query($sql);

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

	public function deleteProduct($id) {
		$sql = "DELETE FROM Product WHERE Id = '" . $id . "';";

    	$this->conn->query($sql);
	}

	public function updateProduct($id, $name, $price, $category) {
		$stmt = $this->conn->prepare("SELECT Id FROM Category WHERE Name = ?");
		$stmt->bind_param("s", $category);
		$stmt->execute();
		$result = $stmt->get_result();
		if ($row = $result->fetch_assoc()) {
			$categoryId = $row["Id"];
		} else {
			return false;
		}
		$stmt->close();

		$stmt = $conn->prepare("UPDATE Product SET Name = ?, Price = ?, CategoryId = ? WHERE Id = ?");
		$stmt->bind_param("siii", $name, $price, $categoryId, $id);
		if ($stmt->execute()) {
			return true;
		} else {
			return false;
		}
	}

}
?>

