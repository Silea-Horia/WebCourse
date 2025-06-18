<?php
	if ($_POST['answer'] != $_POST['secretAnswer']) {
		header('Location: index.html');
	}

	session_start();

	$_SESSION['user'] = $_POST['name'];

	header('Location: home.php');
?>