<?php
    session_start();

    if (!isset($_SESSION['name'])) {
        header('Location: index.php');
    }

    $con = new mysqli("localhost", "root", "", "customerfeedback");

	if ($con->connect_error) {
		die("Could not connect: " . $con->connect_error);
	}

	$result_set = [];

    if (isset($_GET['getall'])) {
        $stmt = $con->prepare('select * from Feedback');
        $stmt->execute();

        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
			$result_set[] = $row;
		}

		$stmt->close();
    }

    if (isset($_POST['submit'])) {
        $content = $_POST['content'];

        $blocked_words = [];

        $stmt = $con->prepare('select pattern from BlockedWords');
        $stmt->execute();

        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
			$blocked_words[] = $row['pattern'];
		}

		$stmt->close();

        $count = 0;

        foreach ($blocked_words as $pattern) {
            if (strpos($content, $pattern) + 1) {
                $count += 1;
            }
        }
        
        if ($count <= 3 && (!isset($_SESSION['blocked']) || $_SESSION['blocked'] == 0)) {
            $stmt = $con->prepare('insert into Feedback (customerId, message, timestamp) values (?, ?, ?)');
            $today = "2025-06-19";
            $stmt->bind_param("iss", $_SESSION['id'], $content, $today);
        
            $stmt->execute();

    		$stmt->close();
        } else {
            if ((isset($_SESSION['blocked']) || $_SESSION['blocked'] == 0)) {
                if (isset($_SESSION['flags'])) {
                    $_SESSION['flags'] += 1;
                } else {
                    $_SESSION['flags'] = 1;
                }
            }
            $_SESSION['content'] = $content;
            header('Location: error.php');
        }
    }
    $con->close();
?>

<html>
<head>
	<title>Home</title>
</head>
<body>
    <?php
        if (isset($_SESSION['flags'] )) {
            echo "<p>Flags: " . $_SESSION['flags'] . "</p>";

            if ($_SESSION['flags'] > 2) {
                echo "<p>You exceeded the flag limit!</p>";
                $_SESSION['blocked'] = 1;
            }
        }
    ?>
	<form action="home.php" method="GET">
		<input type="submit" value="Get All" name="getall"/>
	</form>
    <table>
        <thead>
			<td>Id</td>
			<td>CustomerId</td>
			<td>Message</td>
			<td>Timestamp</td>
		</thead>
		<tbody>
			<?php foreach ($result_set as $feedback):?>
				<tr 
                    <?php if ($feedback['customerId'] == $_SESSION['id'])
                        echo 'style="color: red;"'
                    ?>
                >
	                <td><?php echo $feedback['id']; ?></td>
	                <td><?php echo $feedback['customerId']; ?></td>
	                <td><?php echo $feedback['message']; ?></td>
	                <td><?php echo $feedback['timestamp']; ?></td>
	            </tr>
            <?php endforeach; ?>
		</tbody>
    </table>
    <form action="home.php" method="POST">
        <input type="text"  name="content"/>
		<input type="submit" value="Submit" name="submit"/>
	</form>
</body>
</html>