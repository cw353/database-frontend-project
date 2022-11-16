<?php header("X-Clacks-Overhead: GNU Terry Pratchett"); ?>

<?php
	require_once 'helperFunctions.php';
	require_once 'tables.php';
	require_once 'operators.php';

	$mysqli = new mysqli('localhost', 'root', 'root', 'project');
	$tablename = $_GET['table'];
	$table = $tables[$tablename];

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Add New Record</title>
	</head>

	<body>
		<form method="" action="">
			<input type="hidden" name="table" value="<?php echo sanitizeHtml($tablename); ?>">
			<?php
				echo getModifiableTable($table, $mysqli);
			?>
			<button type="submit">Insert Record</button>
		</form>


	</body>
</html>

<?php
	$mysqli && $mysqli->close();
?>
