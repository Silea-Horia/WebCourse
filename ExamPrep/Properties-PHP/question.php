<?php
	$con = new mysqli("localhost", "root", "", "properties");

	if ($con->connect_error) {
		die("Could not connect: " . $con->connect_error);
	}

	$name = $_POST['name'];

	$stmt = $con->prepare("select secretQuestion, secretAnswer from User where name = ?");
	$stmt->bind_param("s", $name);
	$stmt->execute();

	$result = $stmt->get_result();
	if ($row = $result->fetch_assoc()) {
		$secretQuestion = $row["secretQuestion"];
		$secretAnswer = $row["secretAnswer"];
	} else {
		die("weird error");
	}

	$stmt->close();
	$con->close();
?>

<!DOCTYPE html>
<html>
<head>
	<title>Secret Question</title>
</head>
<body>
	<form action='check_answer.php' method='post'>
		<label for='answer'>
			<?php
				echo $secretQuestion;
			?>
		</label>
		<br>
		<input type='text' name='answer' placeholder='Answer' required/>
		<input type='hidden' name='name' value='<?php echo $name; ?>'/>
		<input type='hidden' name='secretAnswer' value='<?php echo $secretAnswer; ?>'/>
		<input type='submit' value='Submit Answer'/>
 	</form>
</body>
</html>