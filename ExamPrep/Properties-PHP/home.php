<?php 
	session_start();
	if (!isset($_SESSION['user'])) {
		header('Location: index.html');
	}

	if (!isset($_SESSION['dict'])) {
		$_SESSION['dict'] = [];
	}

	$con = new mysqli("localhost", "root", "", "properties");

	if ($con->connect_error) {
		die('Could not connect: ' . $con->connect_error);
	}

	$result_set = [];

	if(isset($_GET['search_key'])) {
		$stmt = $con->prepare('select * from Property where description like ?');
		$search_regex = "%" . $_GET['search_key'] . "%";
		$stmt->bind_param("s", $search_regex);
		$stmt->execute();

		$result = $stmt->get_result();

		$dict = $_SESSION['dict'];

		while ($row = $result->fetch_assoc()) {
			$result_set[] = $row;
			if (array_key_exists($row['id'], $dict)) {
				$dict[$row['id']] += 1;
			} else {
				$dict[$row['id']] = 0;
			}
		}

		$_SESSION['dict'] = $dict;

		$stmt->close();
	}

	$most_pop = ['id' => 0, 'address' => 'none', 'description' => 'none'];

	if (isset($_GET['popular']) && isset($_SESSION['dict'])) {
		$id = 0;
		$max_val = 0;
		$dict = $_SESSION['dict'];

		foreach ($dict as $key => $value) {
			if ($value > $max_val) {
				$id = $key;
				$max_val = $value;
			}
		}

		$stmt = $con->prepare('select * from Property where id = ?');
		$stmt->bind_param('i', $id);
		$stmt->execute();

		$result = $stmt->get_result();

		if ($row = $result->fetch_assoc()) {
			$most_pop = $row;
		}

		$_SESSION['most_pop'] = $most_pop;

		$stmt->close();
	}

	$message = '';

	if (isset($_GET['delete'])) {
		$id = $_GET['delete'];

		$stmt = $con->prepare('select * from Property p inner join UserToProperties utp on p.id = utp.idProperty inner join User u on u.id = utp.idUser where p.id = ?');
		$stmt->bind_param('i', $id);
		$stmt->execute();

		$result = $stmt->get_result();

		$has = false;
		$count = 0;
		while ($row = $result->fetch_assoc()) {
			if ($row['name'] == $_SESSION['user']) {
				$has = true;
			}
			$count += 1;
		}

		$stmt->close();

		if ($has) {
	        if ($count == 1) {
	            $stmt = $con->prepare('DELETE FROM Property WHERE id = ?');
	        } else {
	            $stmt = $con->prepare('DELETE FROM UserToProperties WHERE idProperty = ?');
	        }

	        $stmt->bind_param('i', $id);
	        if ($stmt->execute()) {
	            $message = "Property deleted successfully.";
	        } else {
	            $message = "Error deleting property.";
	        }

	        $stmt->close();
    	} else {
	        $message = "You are not authorized to delete this property.";
    	}
	}

	$con->close();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
</head>
<body>
	<form action="home.php" method="get">
		<input type="text" name="search_key" placeholder="<?php echo isset($_GET['search_key']) ? $_GET['search_key'] : 'Search'; ?>" />
		<input type="submit" value="Search"/>
	</form>
	<?php if ($message): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>
	<table>
		<thead>
			<td>Id</td>
			<td>Address</td>
			<td>Desctiption</td>
			<td>Delete</td>
		</thead>
		<tbody>
			<?php foreach ($result_set as $property):?>
				<tr>
	                <td><?php echo $property['id']; ?></td>
	                <td><?php echo $property['address']; ?></td>
	                <td><?php echo $property['description']; ?></td>
	                <td>
	                	<form action="home.php" method="get">
							<input type='hidden' name='delete' value='<?php echo $property['id']; ?>'/>
	                		<input type="submit" value="delete"/>
	                	</form>
	            	</td>
	            </tr>
            <?php endforeach; ?>
		</tbody>
	</table>
	<form action="home.php" method="get">
		<input type="submit" value="Most Popular" name="popular"/>
	</form>
	<?php if (isset($_SESSION['most_pop']) && $_SESSION['most_pop']['id'] != 0): ?>
		<?php $property = $_SESSION['most_pop']; ?>
		<p>
			Most Popular Property
		</p>
		<table>
			<thead>
				<td>Id</td>
				<td>Address</td>
				<td>Desctiption</td>
			</thead>
			<tr>
		        <td><?php echo $property['id']; ?></td>
		        <td><?php echo $property['address']; ?></td>
		        <td><?php echo $property['description']; ?></td>
		    </tr>
		</table>
	<?php endif; ?>
</body>
</html>