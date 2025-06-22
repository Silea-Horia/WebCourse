<?php
    session_start();

    $eligible = 0;

    if (!isset($_SESSION['name'])) {
        header('Location: index.php');
    }

    $con = new mysqli("localhost", "root", "", "customerfeedback");

	if ($con->connect_error) {
		die("Could not connect: " . $con->connect_error);
	}

	$blocked_words = [];

    $stmt = $con->prepare('select pattern from BlockedWords');
    $stmt->execute();

    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $blocked_words[] = $row['pattern'];
    }

    $stmt->close();

    if (isset($_POST['remove'])) {
        
        $content = $_SESSION['content'];

        foreach ($blocked_words as $word) {
            if (strpos($content, $word)) {
                $content = substr_replace($content, "", strpos($content, $word), strlen($word));
            }
        }
        
        $_SESSION['content'] = $content;

        $eligible = 1;
    }

    if (isset($_POST['save'])) {
        $content = $_POST['content'];

        $count = 0;
        foreach ($blocked_words as $pattern) {
            if (strpos($content, $pattern)) {
                $count += 1;
            }
        }

        if ($count <= 3) {
            $stmt = $con->prepare('insert into Feedback (customerId, message, timestamp) values (?, ?, ?)');
            $today = "2025-06-19";
            $stmt->bind_param("iss", $_SESSION['id'], $content, $today);
        
            $stmt->execute();

    		$stmt->close();
            header('Location: home.php');
        } else {
            $_SESSION['content'] = $content;
            header('Location: error.php');
        }

    }

    if (isset($_POST['back'])) {
        header('Location: home.php');
    }

    $con->close();

?>

<html>
<head>
	<title>Error</title>
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
    <p>
        <?php 
            $text = "";
            $tokens = explode(" ", $_SESSION['content']);
            foreach ($tokens as $word) {
                $bad = 0;
                foreach ($blocked_words as $pattern) {
                    if ($word == $pattern) {
                        $bad = 1;
                    }
                }
                if ($bad == 1) {
                    $text = $text . '<a style="color:red;">' . $word . ' </a>';
                } else {
                    $text = $text  . $word . ' ';
                }
            }
            echo $text;
        ?>
    </p>
    <form action="error.php" method="POST">
        <input type="hidden"  name="content" value="<?php echo $_SESSION['content'];?>"/>
		<?php
            if ($eligible == 1 && (isset($_SESSION['blocked']) && $_SESSION['blocked'] == 0)) {
                echo '<input type="submit" value="Save" name="save"/>';
            }
        ?>
	</form>
    <form action="error.php" method="POST">
		<input type="submit" value="Remove Bad Words" name="remove"/>
	</form>
    <form action="error.php" method="POST">
		<input type="submit" value="Cancel" name="back"/>
	</form>
</body>
</html>