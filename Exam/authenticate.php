<?php
    $con = new mysqli("localhost", "root", "", "customerfeedback");

	if ($con->connect_error) {
		die("Could not connect: " . $con->connect_error);
	}

	$name = $_POST['name'];
	$email = $_POST['email'];

	$stmt = $con->prepare("select id from customer where name = ? and email = ?");
	$stmt->bind_param("ss", $name, $email);
	$stmt->execute();

	$result = $stmt->get_result();
	if ($row = $result->fetch_assoc()) {
        $stmt->close();
        $con->close();

		$id = $row["id"];

        session_start();

        $_SESSION['id'] = $id;
        $_SESSION['name'] = $name;
        $_SESSION['email'] = $email;
        $_SESSION['flags'] = 0;
        $_SESSION['blocked'] = 0;

        header('Location: home.php');
	} else {
		header('Location: index.php');
	}
	$con->close();
?>